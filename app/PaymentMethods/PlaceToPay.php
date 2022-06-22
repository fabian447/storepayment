<?php

namespace App\PaymentMethods;

class PlaceToPay
{
    private $login;
    private $secretKey;
    private $baseUrl;

    function __construct($login = "", $secretKey = "", $baseUrl = "")
    {
        $this->login = $login;
        $this->secretKey = $secretKey;
        $this->baseUrl = $baseUrl;
    }

    public function generateTranKey($payment)
    {
        $secretKey = $this->secretKey;
        $tranKey = base64_encode(sha1($payment->nonce.$payment->seed.$secretKey, true));

        return $tranKey;
    }

    public function generatePaymentUrl($payment)
    {
        $nonceEncoded = base64_encode($payment->nonce);
        $tranKey = $this->generateTranKey($payment);

        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl.'/api/session',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "locale": "es_CO",
                "auth": {
                    "login": "' . $this->login . '",
                    "tranKey": "' . $tranKey . '",
                    "nonce": "' . $nonceEncoded . '",
                    "seed": "' . $payment->seed . '"
                },
                "payment": {
                    "reference": "'. $payment->session_id .'",
                    "description": "Prueba",
                    "amount": {
                    "currency": "USD",
                    "total": 100
                    },
                    "allowPartial": false
                },
                "expiration": "2022-12-30T00:00:00-05:00",
                "returnUrl": "' .route ('payments.process', ['id' => $payment->id]).'",
                "ipAddress": "127.0.0.1",
                "userAgent": "PlacetoPay Sandbox"
            }',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($response);
    }

    public function getPaymentInformation($payment)
    {
        $nonceEncoded = base64_encode($payment->nonce);
        $tranKey = $this->generateTranKey($payment);

        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl.'/api/session/'.$payment->request_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "auth": {
                "login": "' . $this->login .'",
                "tranKey": "' . $tranKey . '",
                "nonce": "' . $nonceEncoded . '",
                "seed": "' . $payment->seed . '"
                }
            }',
            CURLOPT_HTTPHEADER => [
              'Content-Type: application/json'
            ],
          ]);
          
          $response = curl_exec($curl);
          curl_close($curl);

          return json_decode($response);
    }

}