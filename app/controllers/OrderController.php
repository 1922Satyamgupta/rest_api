
  
<?php

use Phalcon\Mvc\Controller;

class OrdersController extends Controller
{
    public function indexAction()
    {
        $data = $this->mongo->demo->order->find();
        $this->view->orders = $data;
    }
}