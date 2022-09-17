<?php

namespace spec\McArrowsmithPackages\Oauth2Shopify;

use Fake\ShopifyResponseFake;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use McArrowsmithPackages\Oauth2Shopify\ShopifyStore;
use PhpSpec\ObjectBehavior;

class ShopifyStoreSpec extends ObjectBehavior
{
    private ShopifyResponseFake $fixture;

    function let()
    {
        $this->fixture = new ShopifyResponseFake;

        $this->beConstructedWith($this->fixture->requestData());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ShopifyStore::class);
        $this->shouldImplement(ResourceOwnerInterface::class);
    }

    function it_has_properties()
    {
        $this->getId()->shouldBe(ShopifyResponseFake::ID);
        $this->getName()->shouldBe(ShopifyResponseFake::NAME);
        $this->getEmail()->shouldBe(ShopifyResponseFake::EMAIL);
        $this->getDomain()->shouldBe(ShopifyResponseFake::DOMAIN);
        $this->getShopifyDomain()->shouldBe(ShopifyResponseFake::MYSHOPIFY_DOMAIN);
        $this->getCountryCode()->shouldBe(ShopifyResponseFake::COUNTRY_CODE);
    }

    function it_has_data()
    {
        $this->toArray()->shouldBe($this->fixture->requestData()['shop']);
    }
}
