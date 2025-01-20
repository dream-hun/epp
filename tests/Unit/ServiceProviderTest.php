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
