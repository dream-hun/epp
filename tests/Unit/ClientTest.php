<?php

namespace Tests\Unit;

use DreamHun\EPP\Client;
use DreamHun\EPP\Providers\EPPServiceProvider;
use Orchestra\Testbench\TestCase;

class ClientTest extends TestCase
{
    private Client $client;

    protected function getPackageProviders($app)
    {
        return [EPPServiceProvider::class];
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = $this->app->make(Client::class);
    }

    public function testPingReturnsFalseWhenNotConnected(): void
    {
        $this->assertFalse($this->client->ping());
    }
}
