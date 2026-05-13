<?php

namespace App\Services;

use SimpleXMLElement;

class ArcaPadronService
{
    protected ArcaWsaaService $wsaa;

    public function __construct(ArcaWsaaService $wsaa)
    {
        $this->wsaa = $wsaa;
    }

    public function consultarCuit(string $cuit): SimpleXMLElement
    {
        // 1️⃣ Login WSAA (token + sign)
        $login = $this->wsaa->login('ws_sr_padron_a5');

        $token = (string) $login->credentials->token;
        $sign  = (string) $login->credentials->sign;

        // 2️⃣ XML request al padrón
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
                  xmlns:a5="http://a5.soap.ws.server.puc.sr">
   <soapenv:Header/>
   <soapenv:Body>
      <a5:getPersona>
         <token>{$token}</token>
         <sign>{$sign}</sign>
         <cuitRepresentada>{$cuit}</cuitRepresentada>
      </a5:getPersona>
   </soapenv:Body>
</soapenv:Envelope>
XML;

        // 3️⃣ Endpoint ARCA PADRÓN
        $url = env('ARCA_ENV') === 'produccion'
            ? 'https://aws.afip.gov.ar/sr-padron/webservices/personaServiceA5'
            : 'https://awshomo.afip.gov.ar/sr-padron/webservices/personaServiceA5';

        // 4️⃣ cURL SOAP
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: text/xml; charset=utf-8',
                'SOAPAction: ""',
            ],
            CURLOPT_POSTFIELDS     => $xml,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new \Exception('Error cURL: ' . curl_error($ch));
        }

        curl_close($ch);

        // 5️⃣ Parsear XML
        return simplexml_load_string($response);
    }
}
