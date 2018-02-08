<?php

namespace DarkS\ACI;


use DarkS\ACI\Entities\Response;
use DarkS\ACI\Entities\ValidationError;

class AciError {}

class AciRemoteError extends AciError {
    public $code;
    public $message;
    public $response;

    public function __construct(Response $response)
    {
        $this->code = $response->result->code;
        $this->message = $response->result->description;
        $this->response = $response;
    }
}

class MalformedRequestError extends AciError {
    public $errors;

    public function __construct(ValidationError $error)
    {
    }
}