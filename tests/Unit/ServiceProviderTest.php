<?php

namespace Tests\Unit;

use DreamHun\EPP\Client;
use DreamHun\EPP\Facades\EPP;
use function Pest\Laravel\app;
use Orchestra\Testbench\TestCase;
use DreamHun\EPP\Providers\EPPServiceProvider;

uses(\Tests\TestCase::class)->in('Unit');

beforeEach(function () {
    $this->defineEnvironment($this->app);
});

protected function getPackageProviders($app)
{
    return [EPPServiceProvider::class];
}

protected function getPackageAliases($app)
{
    return [
        'EPP' => EPP::class,
    ];
}

protected function defineEnvironment($app)
{
    // Define the test configuration
    $app['config']->set('epp', [
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
    ]);
}

test('config is loaded', function () {
    expect(config('epp'))->not->toBeNull()
        ->and(config('epp.host'))->toBe('epp.example.com');
});

test('client is singleton', function () {
    $client1 = app(Client::class);
    $client2 = app(Client::class);

    expect($client1)
        ->toBeInstanceOf(Client::class)
        ->and($client1)->toBe($client2);
});

test('facade works', function () {
    expect(EPP::ping())->toBeFalse();
});
