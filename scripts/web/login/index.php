<?php

include __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->safeLoad();

session_start();

$provider = new \McArrowsmithPackages\Oauth2Shopify\ShopifyProvider([
    'shop'         => $_ENV['SHOPIFY_SHOP'],
    'clientId'     => $_ENV['SHOPIFY_CLIENT_ID'],
    'clientSecret' => $_ENV['SHOPIFY_CLIENT_SECRET'],
    'redirectUri'  => $_ENV['SHOPIFY_REDIRECT']
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {
    $options = [
        'scope' => ['read_orders','write_orders'] // overwrite default scopes
    ];

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authUrl = $provider->getAuthorizationUrl($options);

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: '. $authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }

    exit('Invalid state');
} else {
    try {
        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo sprintf("Access Token: %s", $accessToken->getToken()) . "<br>";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        /** @var \McArrowsmithPackages\Oauth2Shopify\ShopifyStore $resourceOwner */
        echo sprintf("Hello %s", $resourceOwner->getName()) . "<br>";
        echo sprintf("Store %s", $resourceOwner->getDomain()) . "<br>";

        echo "<pre>";
        var_export($resourceOwner->toArray());
        echo "</pre>";

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request = $provider->getAuthenticatedRequest(
            'GET',
            "{$provider->rootShopDomain()}/admin/api/2022-07/orders.json?status=any",
            $accessToken,
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]
        );

        /** @var $request \Psr\Http\Message\RequestInterface */
        echo "<pre>";
        var_export($provider->getResponse($request)->getBody()->getContents());
        echo "</pre>";

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        // Failed to get the access token or user details.
        exit($e->getMessage());
    }
}
