# EPP Client Library for Laravel

This is a PSR-compliant EPP (Extensible Provisioning Protocol) client library for Laravel applications.

## Installation

```bash
composer require dream-hun/epp
```

The package will automatically register its service provider and facade.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=epp-config
```

This will create a `config/epp.php` file in your application. Update your `.env` file with your EPP server credentials:

```env
EPP_HOST=epp.example.com
EPP_PORT=700
EPP_USERNAME=your-username
EPP_PASSWORD=your-password
EPP_SSL=true

# SSL Configuration (optional)
EPP_SSL_VERIFY_PEER=true
EPP_SSL_VERIFY_PEER_NAME=true
EPP_SSL_ALLOW_SELF_SIGNED=false
EPP_SSL_CAFILE=/path/to/ca.crt
EPP_SSL_LOCAL_CERT=/path/to/client.crt
EPP_SSL_LOCAL_PK=/path/to/client.key
EPP_SSL_PASSPHRASE=your-passphrase
```

## Usage

You can use the EPP facade to interact with the EPP server:

```php
use DreamHun\EPP\Facades\EPP;

// Connect to the EPP server
$greeting = EPP::connect();

// Login to the server
$response = EPP::login();

// Check domain availability
$response = EPP::request('
    <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
        <command>
            <check>
                <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                    <domain:name>example.com</domain:name>
                </domain:check>
            </check>
        </command>
    </epp>
');

// Close the connection
EPP::disconnect();
```

Or you can inject the Client class using dependency injection:

```php
use DreamHun\EPP\Client;

class DomainController extends Controller
{
    public function check(Client $epp)
    {
        $epp->connect();
        $epp->login();
        
        $response = $epp->request('... EPP XML ...');
        
        $epp->disconnect();
        
        return $response;
    }
}
```

## Features

- Full EPP protocol support
- Laravel integration with config file and facade
- PSR-4 autoloading compliant
- Modern PHP 8.1+ syntax
- Extensible architecture
- SSL/TLS support with custom certificates

## Testing

```bash
composer test
```

## License

MIT License
