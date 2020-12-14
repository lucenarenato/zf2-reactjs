<?php
namespace Todo\Model;

 use Zend\Db\TableGateway\TableGateway;

 class TodoTable
 {
     protected $tableGateway;

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
     }

     public function getTodo($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('id' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;
     }

     public function saveTodo(Todo $todo)
     {
         $data = array(
             'name' => $todo->name,
         );

         $id = (int) $todo->id;
         if ($id == 0) {
             $this->tableGateway->insert($data);
         } else {
             if ($this->getTodo($id)) {
                 $this->tableGateway->update($data, array('id' => $id));
             } else {
                 throw new \Exception('Todo id does not exist');
             }
         }
     }

     public function deleteTodo($id)
     {
         $this->tableGateway->delete(array('id' => (int) $id));
     }
 }