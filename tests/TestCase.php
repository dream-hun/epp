<?php

namespace Tests;

use DreamHun\EPP\Providers\EPPServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            EPPServiceProvider::class,
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
}
