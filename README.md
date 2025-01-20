# EPP Client Library for Laravel

This is a PSR-compliant EPP (Extensible Provisioning Protocol) client library for Laravel applications.

## Requirements

- PHP 8.2 or higher
- Laravel 10.x or 11.x
- ext-xml PHP extension

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
$xml = <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
  <command>
    <check>
      <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
        <domain:name>example.com</domain:name>
      </domain:check>
    </check>
    <clTRID>ABC-12345</clTRID>
  </command>
</epp>
XML;

$response = EPP::request($xml);
```

## Development

### Testing

```bash
# Run all tests
composer test

# Run tests with coverage report
composer test:coverage
```

### Code Style

The package follows PSR-12 coding standards. You can check and fix the code style using:

```bash
# Check code style
composer cs

# Fix code style issues
composer format
```

### Static Analysis

```bash
composer analyse
```

## License

This package is open-sourced software licensed under the MIT license.

## Credits

- Author: Jacques MBABAZI
- Email: mbabazijacques@gmail.com

## Contributing

Thank you for considering contributing to the EPP Client Library! Please feel free to submit a PR.
