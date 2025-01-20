<?php

namespace Tests\Unit;

use DreamHun\EPP\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client();
    }

    public function testPingReturnsFalseWhenNotConnected(): void
    {
        $this->assertFalse($this->client->ping());
    }
}
