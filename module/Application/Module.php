<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\AuthenticationService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();

        $sharedEvents        = $e->getApplication()->getEventManager()->getSharedManager();
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
            $result = $e->getResult();
            if ($result instanceof \Zend\View\Model\ViewModel) {
                $result->setTerminal($e->getRequest()->isXmlHttpRequest());
            }
        });
        /**
         * Log any Uncaught Exceptions, including all Exceptions in the stack
         */
        $sharedManager = $e->getApplication()->getEventManager()->getSharedManager();
        $sm = $e->getApplication()->getServiceManager();
        $sharedManager->attach('Zend\Mvc\Application', 'dispatch.error',
            function($e) use ($sm) {
                if ($e->getParam('exception')){
                    $ex = $e->getParam('exception');
                    do {
                        $sm->get('Logger')->crit(
                            sprintf(
                               "%s:%d %s (%d) [%s]\n", 
                                $ex->getFile(), 
                                $ex->getLine(), 
                                $ex->getMessage(), 
                                $ex->getCode(), 
                                get_class($ex)
                            )
                        );
                    }
                    while($ex = $ex->getPrevious());
                }
            }
        );

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function bootstrapSession($e)
    {
        $session = $e->getApplication()
                     ->getServiceManager()
                     ->get('Zend\Session\SessionManager');
        try{
            $session->start();
        } 
        catch (\RuntimeException $ex) { 
            // THIS EXCEPTION IS THROWN ON EVERY REQUEST
                $sm = $e->getApplication()->getServiceManager();
                $sm->get('Logger')->crit(
                    sprintf(
                       "%s:%d %s (%d) [%s]\n", 
                        $ex->getFile(), 
                        $ex->getLine(), 
                        $ex->getMessage(), 
                        $ex->getCode(), 
                        get_class($ex)
                    )
                );
        
        }

        $sm = $e->getApplication()->getServiceManager();
        $request        = $sm->get('Request');
         
        $container = new Container('initialized');
        if (!isset($container->init)) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $request        = $serviceManager->get('Request');

            $session->regenerateId(true);
            $container->init          = 1;
            $container->remoteAddr    = $request->getServer()->get('REMOTE_ADDR');
            $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');

            $config = $serviceManager->get('Config');
            if (!isset($config['session'])) {
                return;
            }

            $sessionConfig = $config['session'];
            if (isset($sessionConfig['validators'])) {
                $chain = $session->getValidatorChain();

                foreach ($sessionConfig['validators'] as $validator) {
                    switch ($validator) {
                        case 'Zend\Session\Validator\HttpUserAgent':
                            $validator = new $validator($container->httpUserAgent);
                            break;
                        case 'Zend\Session\Validator\RemoteAddr':
                            $validator  = new $validator($container->remoteAddr);
                            break;
                        default:
                            $validator = new $validator();
                    }

                    $chain->attach('session.validate', array($validator, 'isValid'));
                }
            }
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    // Register TableGateway
    public function getServiceConfig()
     {
         return array(
             // hier stehen die models, die im controller aufgerufen werden
             // erst der name, wie er im Aufruf verwendet wird, dann die Klasse
             'invokables' => array(
                    //'ajax_model' => '\Application\Model\AjaxModel',                    
                    'indexController' => '\Application\Controller\IndexController',   
                    'apiController' => '\Application\Controller\ApiController',
             ),
             'factories' => array(    
                
                'mail.transport' => function($sm){
                    $config = $sm->get('config');
                    $transport = new \Zend\Mail\Transport\Smtp();
                    $transport->setOptions(new \Zend\Mail\Transport\SmtpOptions($config['mail']['transport']['options']));
                    return $transport;
                },
                'Zend\Session\SessionManager' => function ($sm) {
                    $config = $sm->get('config');
                    if (isset($config['session'])) {
                        $session = $config['session'];

                        $sessionConfig = null;
                        
                        if (isset($session['config'])) {
                            $class                  = isset($session['config']['class'])  ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                            $options                = isset($session['config']['options']) ? $session['config']['options'] : array();
                            $sessionConfig          = new $class();
			    $options['save_path']   = getcwd() . '/data/session';       //kann auch für test mit live-db bleiben
                            
                            $sessionConfig->setOptions($options);
                        }

                        $sessionStorage = null;
                        if (isset($session['storage'])) {
                            $class = $session['storage'];
                            $sessionStorage = new $class();
                        }

//                          $sessionSaveHandler = null;
                        if (isset($session['save_handler'])) {
                            // class should be fetched from service manager since it will require constructor arguments
                            $sessionSaveHandler = $sm->get($session['save_handler']);
                            $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);
                        } else {
                            $sessionManager = new SessionManager($sessionConfig, $sessionStorage); // nur für session in DB: $sessionSaveHandler);
                        }
                    } else {
                        $sessionManager = new SessionManager();
                    }
                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },
                'Logger' => function($sm){
                    $logger = new \Zend\Log\Logger;
                    $writer = new \Zend\Log\Writer\Stream('./data/log/'.date('Y-m-d').'-error.log');
                    $logger->addWriter($writer);
                    return $logger;
                }
             )                         
         );
     }   
}
