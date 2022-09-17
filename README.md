[<img src="https://mcarrowsmith.co.uk/assets/images/mcarrowsmith-consulting-social-card.png" />](https://mcarrowsmith.co.uk)

# OAuth2 Shopify

PHP OAuth 2.0 provider for Shopify extending [PHP League's OAuth Client](https://github.com/thephpleague/oauth2-client). 

## Installation

You can install the package via composer:

```bash
composer require mcarrowsmith/oauth2-shopify
```

## Usage

Make sure you have configured and app via your Shopify Partner account.

```php
$provider = new \McArrowsmithPackages\Oauth2Shopify\ShopifyProvider([
    'shop'         => '{example-shopify-store}.myshopify.com',
    'clientId'     => '{shopify-add-id}',
    'clientSecret' => '{shopify-app-secret}',
    'redirectUri'  => 'https://{example-ngrok-subdomain}.ngrok.io/login'
]);
```

See [web/login/index.php](web/login/index.php) for full workflow example.

## Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/mcarrowsmith/.github/blob/main/CONTRIBUTING.md) for details.
