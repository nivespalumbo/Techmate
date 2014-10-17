<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiController
 *
 * @author Nives
 */
require_once 'API.php';
require_once 'controllers/MagazineApiController.php';
require_once 'controllers/ArticleApiController.php';

class ApiController extends API {

    public function __construct($request) {
        parent::__construct($request);
    }
     
    protected function magazine() {
        if ($this->method == 'GET') {
            if(array_count_values($this->args) > 0){
                return MagazineApiController::get($this->args);
            }
            return MagazineApiController::get();
        } else if($this->method == 'POST') {
            $request_body = file_get_contents('php://input');
            if($request_body != NULL) {
                $data = json_decode($request_body);
                return MagazineApiController::save($data);
            } else {
                throw new Exception("Wrong Request payload");
            }
        } else if($this->method == 'PUT') {
            return "Gestire PUT";
        } else {
            return "Gestire DELETE";
        }
    }
    
    protected function article() {
        return "Gestire ArticleController";
    }
}
