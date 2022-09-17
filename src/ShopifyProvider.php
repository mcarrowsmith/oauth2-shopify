<?php

namespace McArrowsmithPackages\Oauth2Shopify;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class ShopifyProvider extends AbstractProvider
{
    protected $shop;

    public function getBaseAuthorizationUrl(): string
    {
        return "{$this->rootShopDomain()}/admin/oauth/authorize";
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return "{$this->rootShopDomain()}/admin/oauth/access_token";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return "{$this->rootShopDomain()}/admin/shop.json";
    }

    public function getDefaultScopes(): array
    {
        return [
            'read_content',
            'read_customers',
            'read_inventory',
            'read_orders',
            'read_products',
            'read_product_listings'
        ];
    }

    public function getAuthorizationHeaders($token = null): array
    {
        return ['X-Shopify-Access-Token' => $token->getToken()];
    }

    public function rootShopDomain(): string
    {
        return "https://{$this->shop}";
    }

    /**
     * @param $data
     *
     * @throws IdentityProviderException
     *
     * @return array|string
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (array_key_exists('errors', $data)) {
            throw new IdentityProviderException($data['errors'], 0, $data);
        }

        return $data;
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ShopifyStore($response);
    }
}
