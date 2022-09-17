<?php

namespace McArrowsmithPackages\Oauth2Shopify;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class ShopifyStore implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;
    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId(): int
    {
        return $this->getResponseData('shop.id');
    }

    public function getName(): string
    {
        return $this->getResponseData('shop.name');
    }

    public function getEmail(): string
    {
        return $this->getResponseData('shop.email');
    }

    public function getDomain(): string
    {
        return $this->getResponseData('shop.domain');
    }

    public function getShopifyDomain(): string
    {
        return $this->getResponseData('shop.myshopify_domain');
    }

    public function getCountryCode(): string
    {
        return $this->getResponseData('shop.country_code');
    }

    public function toArray(): array
    {
        return $this->getResponseData();
    }

    /**
     * @return array|mixed
     */
    private function getResponseData(string $key = 'shop')
    {
        return $this->getValueByKey($this->response, $key);
    }
}
