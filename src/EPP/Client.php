<?php

declare(strict_types=1);

namespace DreamHun\EPP;

use Exception;
use Illuminate\Support\Facades\Config;

/**
 * A simple client class for the Extensible Provisioning Protocol (EPP)
 */
class Client
{
    private $socket;
    private array $config;

    /**
     * Create a new EPP Client instance
     */
    public function __construct()
    {
        $this->config = Config::get('epp');
    }

    /**
     * Establishes a connection to the server
     *
     * @throws Exception On connection errors
     * @return string The server <greeting>
     */
    public function connect(): string
    {
        $host = $this->config['host'];
        $port = $this->config['port'];
        $timeout = $this->config['timeout'];
        $ssl = $this->config['ssl'];
        
        $context = null;
        if ($ssl) {
            $context = stream_context_create([
                'ssl' => $this->config['ssl_options']
            ]);
        }

        $target = sprintf('%s://%s:%d', ($ssl === true ? 'tls' : 'tcp'), $host, $port);
        
        if (is_resource($context)) {
            $result = stream_socket_client($target, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $context);
        } else {
            $result = stream_socket_client($target, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
        }

        if ($result === false) {
            throw new Exception("Error connecting to $target: $errstr (code $errno)");
        }

        $this->socket = $result;

        if (!stream_set_timeout($this->socket, $timeout)) {
            throw new Exception("Failed to set timeout on socket: $errstr (code $errno)");
        }

        if (!stream_set_blocking($this->socket, 0)) {
            throw new Exception("Failed to set blocking on socket: $errstr (code $errno)");
        }

        return $this->getFrame();
    }

    /**
     * Login to the EPP server
     *
     * @throws Exception On login errors
     * @return string The server response
     */
    public function login(): string
    {
        $username = $this->config['username'];
        $password = $this->config['password'];

        $xml = sprintf(
            '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
            <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
                <command>
                    <login>
                        <clID>%s</clID>
                        <pw>%s</pw>
                        <options>
                            <version>1.0</version>
                            <lang>en</lang>
                        </options>
                        <svcs>
                            <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
                            <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
                            <objURI>urn:ietf:params:xml:ns:host-1.0</objURI>
                        </svcs>
                    </login>
                </command>
            </epp>',
            htmlspecialchars($username),
            htmlspecialchars($password)
        );

        return $this->request($xml);
    }

    /**
     * Get an EPP frame from the server.
     *
     * @throws Exception On frame errors
     * @return string The XML frame from the server
     */
    public function getFrame(): string
    {
        return Protocol::getFrame($this->socket);
    }

    /**
     * Send an XML frame to the server.
     *
     * @param string $xml The XML data to send
     * @throws Exception When it doesn't complete the write to the socket
     * @return bool The result of the write operation
     */
    public function sendFrame(string $xml): bool
    {
        return Protocol::sendFrame($this->socket, $xml);
    }

    /**
     * A wrapper around sendFrame() and getFrame()
     *
     * @param string $xml The frame to send to the server
     * @throws Exception When it doesn't complete the write to the socket
     * @return string The frame returned by the server
     */
    public function request(string $xml): string
    {
        $res = $this->sendFrame($xml);
        return $this->getFrame();
    }

    /**
     * Close the connection.
     *
     * This method closes the connection to the server. Note that the
     * EPP specification indicates that clients should send a <logout>
     * command before ending the session.
     *
     * @return bool The result of the close operation
     */
    public function disconnect(): bool
    {
        return @fclose($this->socket);
    }

    /**
     * Ping the connection to check that it's up
     *
     * @return bool
     */
    public function ping(): bool
    {
        return (!is_resource($this->socket) || feof($this->socket) ? false : true);
    }
}
```

```php
<?php

namespace DreamHun\EPP\Facades;

use Illuminate\Support\Facades\Facade;
use DreamHun\EPP\Client;

class EPP extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }
}
