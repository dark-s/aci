<?php

namespace DarkS\ACI\Entities;


abstract class Card {
    public $cvv = '';
    public $expiryMonth;
    public $expiryYear;
    public $holder = '';
    public $number;
}