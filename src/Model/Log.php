<?php


namespace App\Model;

class Log
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}