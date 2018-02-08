<?php

namespace DarkS\ACI\Entities;


abstract class Payment {
    public $amount;
    public $card;
    public $createRegistration;
    public $currency;
    public $descriptor = '';
    public $merchantInvoiceId = '';
    public $merchantTransactionId = '';
    public $paymentBrand = '';
    public $paymentType = '';
}