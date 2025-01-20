<?php

namespace Tests\Unit;

use DreamHun\EPP\Client;
use DreamHun\EPP\Facades\EPP;
use Orchestra\Testbench\TestCase;
use DreamHun\EPP\Providers\EPPServiceProvider;

class ServiceProviderTest extends TestCase
{
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

    public function testConfigIsLoaded()
    {
        $this->assertNotNull(config('epp'));
        $this->assertEquals('epp.example.com', config('epp.host'));
    }

    public function testClientIsSingleton()
    {
        $client1 = $this->app->make(Client::class);
        $client2 = $this->app->make(Client::class);

        $this->assertInstanceOf(Client::class, $client1);
        $this->assertSame($client1, $client2);
    }

    public function testFacadeWorks()
    {
        $this->assertTrue(EPP::ping() === false);
    }
}
