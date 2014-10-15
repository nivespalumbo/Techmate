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

class ApiController extends API {

    public function __construct($request) {
        parent::__construct($request);
    }

    /**
     * Example of an Endpoint
     */
    protected function example() {
        if ($this->method == 'GET') {
            return "Hello world";
        } else {
            return "Only accepts GET requests";
        }
    }
     
    protected function magazine() {
        if ($this->method == 'GET') {
            if(array_count_values($this->args) > 0){
                return MagazineApiController::get($this->args);
            }
            return MagazineApiController::get();
        } else {
            return "Only accepts GET requests";
        }
    }
}
