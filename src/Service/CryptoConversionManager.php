<?php

namespace App\Service;

use App\Repository\CryptoRepository;

class CryptoConversionManager 
{
    public function cryptoToEuro($qty, $crypto_type)
    {
        $url = 'https://sandbox-api.coinmarketcap.com/v2/tools/price-conversion';
        $parameters = [
            'amount' => $qty,
            'symbol' => $crypto_type,
            'convert' => 'EUR'
        ];
        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c'
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL

        $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
        CURLOPT_URL => $request,            // set the request URL
        CURLOPT_HTTPHEADER => $headers,     // set the headers 
        CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        $json = json_decode($response, true);
        curl_close($curl); // Close request;

        return $json['data'][$crypto_type]['quote']['EUR']['price'];
    }

}