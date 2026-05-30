<?php

$wsdl = null;
$url  = 'http://localhost/sistem_manajemen_tugas/web/soap/tugas';

$client = new SoapClient($wsdl, [
    'location' => $url,
    'uri'      => $url,
    'trace'    => true,
]);

try {
    // Test getTugasByMatkul dengan matkul_id = 1
    $result = $client->getPengumpulanByTugas(1);
    echo '<pre>';
    print_r($result);
    echo '</pre>';
} catch (SoapFault $e) {
    echo 'Error: ' . $e->getMessage();
}