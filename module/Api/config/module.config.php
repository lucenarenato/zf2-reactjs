<?php
/**
 * Created by Alpha-Hydro.
 * @link http://www.alpha-hydro.com
 * @author Vladimir Mikhaylov <admin@alpha-hydro.com>
 * @copyright Copyright (c) 2017, Alpha-Hydro
 *
 */

namespace Api;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'api' => [
                //'type'    => Segment::class,
                'type'    => 'Literal',
                'options' => [
                    'route' => '/api',
                    'defaults' => [
                        //'controller' => Controller\ApiController::class,
                        'controller' => 'Api\Controller\Api',
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'factories' => array(
            'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Api\Controller\Api' => 'Api\Controller\ApiController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        // 'template_map' => array(
        //     'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        //     'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
        //     'error/404'               => __DIR__ . '/../view/error/404.phtml',
        //     'error/index'             => __DIR__ . '/../view/error/index.phtml',
        // ),
        // 'template_path_stack' => array(
        //     __DIR__ . '/../view',
        // ),
    ),
    // Placeholder for console routes
    // 'console' => array(
    //     'router' => array(
    //         'routes' => array(
    //         ),
    //     ),
    // ),
    /*'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',

        /*'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],*/
    //],
];