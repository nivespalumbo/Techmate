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
require_once 'controllers/MagazineController.php';
require_once 'controllers/ArticleController.php';

class ApiController extends API {

    public function __construct($request) {
        parent::__construct($request);
    }
     
    protected function magazine() {
        switch ($this->method) {
            case 'GET':
                switch ($this->verb) {
                    case 'all':
                        return MagazineController::getAll();
                    case 'publish' :
                        return MagazineController::publish($this->args[0]);
                    default :
//                        if(array_count_values($this->args) > 0) {
//                            return MagazineController::get($this->args[0]);
//                        }
                        return MagazineController::get();
                }
                break;
            case 'POST':
                $request_body = file_get_contents('php://input');
                if($request_body != NULL) {
                    $data = json_decode($request_body);
                    return MagazineController::save($data);
                } else {
                    throw new Exception("Wrong Request payload");
                }
                break;
            case 'PUT':
                return "Gestire PUT magazine";
            case 'DELETE':
                if(array_count_values($this->args) > 0) {
                    return MagazineController::delete($this->args[0]);
                }
                throw new Exception("Undefined id");
            default:
                break;
        }
    }
    
    protected function article() {
        switch ($this->method) {
            case 'GET':
                if(array_count_values($this->args) > 0)
                    return ArticleController::get($this->args[0]);
                throw new Exception("Missing magazine id");
            case 'POST':
                $request_body = file_get_contents('php://input');
                if($request_body != NULL) {
                    $data = json_decode($request_body);
                    return ArticleController::save($data);
                } else {
                    throw new Exception("Wrong Request payload");
                }
                break;
            case 'PUT':
                return "Gestire PUT article";
            case 'DELETE':
                if(array_count_values($this->args) == 2) {
                    return ArticleController::delete($this->args[0], $this->args[1]);
                }
                throw new Exception("Missing parameters");
            default:
                break;
        }
    }
}
