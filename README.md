# SFCC/Demandware PHP SDK

This SDK provides simple access to the Demandware API. 
It currently handles the following requests

- Create Customer records
- Update Customer records
- Retreive Customer record by ID
- Searching Customers in a list


## Contents

- [Getting started](#getting-started)
- [Integrating with Laravel](#integrating-with-laravel)
- [Contributing](#contributing)

## Getting started

Install the SDK into your project using Composer.

```bash
composer config repositories.demandware-sdk git git@github.com:arkade-digital/demandware-sdk.git
composer require arkade/demandware-sdk
```

## Integrating with Laravel

This package ships with a Laravel specific service provider which allows you to set your credentials from your configuration file and environment.

### Registering the provider

Add the following to the `providers` array in your `config/app.php` file.

```php
Arkade\Demandware\LaravelServiceProvider::class
```

### Adding config keys

In your `config/services.php` file, add the following to the array.

```php
'demandware'=> [
        'version' => env('DW_API_VERSION'),
        'siteName' => env('DW_SITE_NAME'),
        'clientId' => env('DW_CLIENT_ID'),
        'clientSecret' => env('DW_CLIENT_SECRET'),
        'authUrl' => env('DW_AUTH_URL', 'http://account.demandware.com/dw/oauth2/access_token'),
        'endpoint' => env('DW_ENDPOINT'),
    ]
```

### Adding environment keys

In your `.env` file, add the following keys.

```ini
DW_API_VERSION=
DW_SITE_NAME=
DW_CLIENT_ID=
DW_CLIENT_SECRET=
DW_ENDPOINT=
```

### Resolving a client

To resolve a client, you simply pull it from the service container. This can be done in a few ways.

#### Dependency Injection

```php
use Arkade\Demandware\Client;

public function yourControllerMethod(Client $client) {
    // Call methods on $client
}
```

#### Using the `app()` helper

```php
use Arkade\Demandware\Client;

public function anyMethod() {
    $client = app(Client::class);
    // Call methods on $client
}
```

### Available methods

```
$client->customers()->list();

$client->customers()->getFromId(Int $id);

$client->customers()->create(Array $customerData)

$client->customers()->search(
            [
                'text_query' => [
                    'fields' => ['first_name'],
                    'search_phrase' => 'joe'
                ]
            ]

$client->customers()->update( '00000001',
            [
                'credentials' => [
                    'login' => 'hello@world.com'
                ],
                "phone_mobile" => '0412345678'
            ]

        ))
```

Refer to the Demandware documentation for further information
https://documentation.demandware.com/DOC1/topic/com.demandware.dochelp/OCAPI/17.8/data/Resources/CustomerLists.html




## Contributing

If you wish to contribute to this library, please submit a pull request and assign to a member of Capcom for review.

All public methods should be accompanied with unit tests.

### Testing

Coming soon