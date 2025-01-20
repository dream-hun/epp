<?php

use DreamHun\EPP\Client;
use DreamHun\EPP\Facades\EPP;

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

test('config is loaded', function () {
    expect(config('epp'))->not->toBeNull()
        ->and(config('epp.host'))->toBe('epp.example.com');
});

test('client is singleton', function () {
    $client1 = resolve(Client::class);
    $client2 = resolve(Client::class);

    expect($client1)
        ->toBeInstanceOf(Client::class)
        ->and($client1)->toBe($client2);
});

test('facade works', function () {
    expect(EPP::ping())->toBeFalse();
});
