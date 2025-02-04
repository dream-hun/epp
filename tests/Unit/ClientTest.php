<?php

use DreamHun\EPP\Client;
use function Pest\Laravel\app;

beforeEach(function () {
    config([
        'epp' => [
            'host' => 'epp.example.com',
            'port' => 700,
            'timeout' => 1,
            'ssl' => true,
            'username' => 'test-user',
            'password' => 'test-pass',
            'ssl_options' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ],
    ]);
});

test('client ping returns false when not connected', function () {
    $client = resolve(Client::class);
    expect($client->ping())->toBeFalse();
});
