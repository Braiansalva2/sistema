<?php

namespace App\Services;

class ArcaWsaaService
{
    private string $wsaaUrl;

public function __construct()
{
    $this->wsaaUrl = env('ARCA_ENV') === 'produccion'
        ? 'https://wsaa.afip.gov.ar/ws/services/LoginCms'
        : 'https://wsaahomo.afip.gov.ar/ws/services/LoginCms';
}

    public function login(): array
    {
        // 1️ Generar TRA
        $tra = $this->crearTRA();
        file_put_contents(storage_path('app/arca/tra.xml'), $tra);

        // 2️ Firmar TRA
        $cms = $this->firmarTRA();

        // 3️ Enviar a WSAA
        $response = $this->enviarLoginCms($cms);

        // 4️ Parsear respuesta
        return $this->parsearRespuesta($response);
    }

   private function crearTRA(): string
{
    $now = new \DateTime('now', new \DateTimeZone('America/Argentina/Buenos_Aires'));

    $gen = clone $now;
    $gen->modify('-10 minutes');

    $exp = clone $now;
    $exp->modify('+10 minutes');

    $uniqueId = time();

    return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<loginTicketRequest version="1.0">
  <header>
    <uniqueId>{$uniqueId}</uniqueId>
    <generationTime>{$gen->format('Y-m-d\TH:i:s')}</generationTime>
    <expirationTime>{$exp->format('Y-m-d\TH:i:s')}</expirationTime>
  </header>
  <service>wsfe</service>
</loginTicketRequest>
XML;
}



    private function firmarTRA(): string
{
    $traPath = storage_path('app/arca/tra.xml');
    $cmsPath = storage_path('app/arca/tra.cms');

    $cmd = sprintf(
        '"%s" smime -sign -signer "%s" -inkey "%s" -outform DER -nodetach -binary -in "%s" -out "%s"',
        'C:\Program Files\OpenSSL-Win64\bin\openssl.exe',
        base_path(env('ARCA_CERT')),
        base_path(env('ARCA_KEY')),
        $traPath,
        $cmsPath
    );

    exec($cmd, $output, $code);

    if ($code !== 0 || !file_exists($cmsPath)) {
        throw new \Exception('Error firmando TRA (openssl)');
    }

    $cms = file_get_contents($cmsPath);

    return base64_encode($cms);
}


private function enviarLoginCms(string $cms): string
{
    // FORZAR a PHP a NO validar CA (Windows fix)
    ini_set('openssl.cafile', '');
    ini_set('curl.cainfo', '');

    $client = new \SoapClient(null, [
        'location' => $this->wsaaUrl,
        'uri' => 'http://wsaa.view.sua.dvadac.desein.afip.gov',
        'trace' => 1,
        'exceptions' => true,
        'soap_version' => SOAP_1_1,
        'stream_context' => stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ]),
    ]);

    $response = $client->__soapCall('loginCms', [$cms]);

    return (string) $response;
}





 private function parsearRespuesta(string $xml): array
{
    file_put_contents(storage_path('app/arca/wsaa_response.xml'), $xml);

    // Cargamos el SOAP
    $soap = simplexml_load_string($xml);
    if (!$soap) {
        throw new \Exception('Respuesta SOAP inválida');
    }

    // Navegamos al Body
    $body = $soap->children('http://schemas.xmlsoap.org/soap/envelope/')->Body;

    // Obtenemos loginCmsReturn
    $loginCmsReturn = (string) $body->children()->loginCmsReturn;

    if (!$loginCmsReturn) {
        throw new \Exception('loginCmsReturn vacío');
    }

    // AFIP devuelve XML escapado → lo des-escapamos
    $loginCmsReturn = html_entity_decode($loginCmsReturn);

    // Ahora sí es XML real
    $ticket = simplexml_load_string($loginCmsReturn);

    if (!$ticket || !isset($ticket->credentials->token)) {
        throw new \Exception('WSAA no devolvió token');
    }

    return [
        'token' => (string) $ticket->credentials->token,
        'sign'  => (string) $ticket->credentials->sign,
    ];
}


}
