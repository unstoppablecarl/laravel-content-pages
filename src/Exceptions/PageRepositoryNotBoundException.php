<?php


namespace UnstoppableCarl\Pages\Exceptions;


use Exception;
use UnstoppableCarl\Pages\Contracts\PageRepository;

class PageRepositoryNotBoundException extends Exception {

    public function __construct($message = "", $code = 0, Exception $previous = null) {

        if(!$message){
            $message = 'An implementation must be bound to the ' . PageRepository::class . ' contract';
        }

        parent::__construct($message, $code, $previous);
    }
}
