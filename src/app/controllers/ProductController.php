<?php 

use Phalcon\Mvc\Controller;

 class ProductController extends Controller {
     public function indexAction() {
        $url = "http://192.168.2.54:8080/api/product/list";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $res = json_decode($response, true);
        $this->view->products = $res;

     }
     public function viewAction() {
        $collection = $this->mongo->demo->order;
        $pname = $this->request->get('p_name');
        $pprice = $this->request->get('p_price');
        $cname = $this->request->get('name');
        $contact = $this->request->get('contact');
        $address = $this->request->get('address');
        $quantity = $this->request->get('quantity');
        $result = $collection->insertOne(['p_name' => $pname, 'p_price' => $pprice, 'cust_name' => $cname, 'contact' => $contact, 'address' => $address, 'quantity' => $quantity] );
        $this->response->redirect('/product/index');
     }
     public function orderPlacedAction() {
        $id = $this->request->get('id');
        $collection = $this->mongo->demo->products;
        $result = $collection->findOne(["_id" => new \MongoDB\BSON\ObjectID($id)]);
        $this->view->result = $result;

     }
 }