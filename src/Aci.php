<?php

namespace DarkS\ACI;


use DarkS\ACI\AciRemoteError;
use DarkS\ACI\MalformedRequestError;
use DarkS\ACI\Entities\Authentication;
use DarkS\ACI\Entities\Payment;
use DarkS\ACI\Entities\Response;
use DarkS\ACI\Entities\Payload;
use GuzzleHttp\Client;

const successfulTransactionCodeRule = "/^(000\.000\.|000\.100\.1|000\.[36])/";
const maybeSuccessfulTransactionCodeRule = "/^(000\.400\.0|000\.400\.100)/";

class Aci {
    public $apiUrl;
    public $authentication;

    public function __construct($apiUrl, Authentication $authentication)
    {
        $this->apiUrl = $apiUrl;
        $this->authentication = $authentication;
    }

    public function createPayment(Payment $payment) {
        try {
            $response = self::post($this->apiUrl, $this->authentication, 'payments', $payment);

            if (preg_match(successfulTransactionCodeRule, $response['result']['code']) || preg_match(maybeSuccessfulTransactionCodeRule, $response['result']['code'])) {
                return $response;
            }
        } catch (\Exception $exception) {
            throw new \Error($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }

    public function capturePayment($id, Payment $payment) {
        try {
            $response = self::post($this->apiUrl, $this->authentication, 'payments/' . $id, $payment);

            if (preg_match(successfulTransactionCodeRule, $response['result']['code']) || preg_match(maybeSuccessfulTransactionCodeRule, $response['result']['code'])) {
                return $response;
            }
        } catch (\Exception $exception) {
            throw new \Error($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }

    public function createRegistrationPayment($registrationId, Payment $payment) {
        try {
            $response = self::post($this->apiUrl, $this->authentication, 'registrations/' . $registrationId . '/payments', $payment);

            if (preg_match(successfulTransactionCodeRule, $response['result']['code']) || preg_match(maybeSuccessfulTransactionCodeRule, $response['result']['code'])) {
                return $response;
            }
        } catch (\Exception $exception) {
            throw new \Error($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }

    public function reversePayment($paymentId) {
        try {
            $response = self::post($this->apiUrl, $this->authentication, 'payments/' . $paymentId, [
                'paymentType' => 'RV'
            ]);


            if (preg_match(successfulTransactionCodeRule, $response['result']['code']) || preg_match(maybeSuccessfulTransactionCodeRule, $response['result']['code'])) {
                return $response;
            }
        } catch (\Exception $exception) {
            throw new \Error($exception->getMessage(), $exception->getCode(), $exception->getPrevious());
        }
    }

    static function post($apiUrl, Authentication $authentication, $resource, $payload) {
        $client = new Client();

        $payload['authentication'] = $authentication;
        $options = [
            'form_params' => $payload,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'user-agent' => 'aci'
            ]
        ];

        $result = $client->request('POST', $apiUrl . $resource, $options);

        return $result->getBody()->getContents();
    }
}