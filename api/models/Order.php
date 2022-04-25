<?php

namespace Api\Models;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use Phalcon\Di\Injectable;

class Order extends Injectable
{
    function placeorder()
    {
        if ($this->request->isPost()) {
            $token = $this->request->getQuery('token');
            $key = 'example_key';
            $decodedtoken = JWT::decode($token, new Key($key, 'HS256'));
           
            $data = array(
                "customer_Id" => $decodedtoken->id,
                "customer_Name" => $this->request->getPost('customer_name'),
                "product_Name" => $this->request->getPost('product_name'),
                "product_Quantity" => $this->request->getPost('quantity'),
                "status" => "unpaid"
            );

            $this->mongo->demo->order->insertOne($data);
        }
    }

    function updateorder()
    {
        if ($this->request->isPut()) {
            $token = $this->request->getQuery('token');
            $key = 'example_key';
            $decodedtoken = JWT::decode($token, new Key($key, 'HS256'));
            $updated_status = $this->request->getPut('status');
            $id = $this->request->getPut('id');

            $this->mongo->demo->order->updateOne(["_id" => new \MongoDB\BSON\ObjectID($id)], ['$set' => ['status'=> $updated_status]]);
        }
    }
}