# Responsys PHP SDK

This SDK provides simple access to the Responsys REST API. 
It currently handles the following requests

- Retrieve profile list recipients
- Merge profile list recipients
- Merge profile extension recipients

## Contents

- [Getting started](#getting-started)
- [Integrating with Laravel](#integrating-with-laravel)
- [Contributing](#contributing)

## Getting started

Install the SDK into your project using Composer.

```bash
composer config repositories.responsys-sdk git git@github.com:arkade-digital/responsys-sdk.git
composer require arkade/responsys-sdk
```

## Integrating with Laravel

This package ships with a Laravel specific service provider which allows you to set your credentials from your configuration file and environment.

### Registering the provider

Add the following to the `providers` array in your `config/app.php` file.

```php
Arkade\Responsys\LaravelServiceProvider::class
```

### Adding config keys

In your `config/services.php` file, add the following to the array.

```php
'responsys' => [
    'username'      => env('RESPONSYS_USERNAME'),
    'password'   => env('RESPONSYS_PASSWORD'),
    'list'   => env('RESPONSYS_LIST'),
]
```

### Adding environment keys

In your `.env` file, add the following keys.

```ini
RESPONSYS_USERNAME=
RESPONSYS_PASSWORD=
RESPONSYS_LIST=
```

### Resolving a client

To resolve a client, you simply pull it from the service container. This can be done in a few ways.

#### Dependency Injection

```php
use Arkade\Responsys\Client;

public function yourControllerMethod(Client $client) {
    // Call methods on $client
}
```

#### Using the `app()` helper

```php
use Arkade\Responsys\Client;

public function anyMethod() {
    $client = app(Client::class, ['username' => '', 'password' => '']);
    // Call methods on $client
}
```

### Available methods

```
$client->mergeProfileListRecipients(array $recipients);

$client->mergeProfileExtensionRecipients($profileExtensionTable, array $recipients);
```

Refer to the Responsys REST API documentation for further information
https://docs.oracle.com/cloud/latest/marketingcs_gs/OMCED/index.html

## Webhooks

WIP

## Contributing

If you wish to contribute to this library, please submit a pull request and assign to a member of Capcom for review.

All public methods should be accompanied with unit tests.

### Testing

```bash
./vendor/bin/phpunit
```