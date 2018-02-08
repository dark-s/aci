<?php

namespace DarkS\ACI\Entities;


abstract class ValidationError {
    public $keyword;
    public $schemaPath;
    public $params;
    public $message;
}