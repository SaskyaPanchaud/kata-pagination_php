<?php

require_once __DIR__ . '/../vendor/autoload.php';

include("./include/secrets.php");

# client permet d'envoyer des requetes a API
$client = Discogs\ClientFactory::factory([
    'headers' => [
        'User-Agent' => 'kata/0.0 +http://www.kata.com',
        'Authorization' => "Discogs token={$access_token}",
    ]
]);

?>
