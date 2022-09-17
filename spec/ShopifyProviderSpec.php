<?php

namespace spec\McArrowsmithPackages\Oauth2Shopify;

use Fake\ShopifyResponseFake;
use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use McArrowsmithPackages\Oauth2Shopify\ShopifyProvider;
use McArrowsmithPackages\Oauth2Shopify\ShopifyStore;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;

class ShopifyProviderSpec extends ObjectBehavior
{
    const SHOPIFY_DOMAIN = 'example.shopify.com';
    const CLIENT_ID = 'fake_client_id';
    const CLIENT_SECRET = 'fake_client_secret';
    const ACCESS_TOKEN = 'fake_access_token';

    function let(ClientInterface $http)
    {
        $this->beConstructedWith(
            [
                'clientId'     => self::CLIENT_ID,
                'clientSecret' => self::CLIENT_SECRET,
                'shop'         => self::SHOPIFY_DOMAIN
            ],
            [
                'httpClient'   => $http
            ]
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ShopifyProvider::class);
        $this->shouldBeAnInstanceOf(AbstractProvider::class);
    }

    function it_has_base_authorization_url()
    {
        $this->getBaseAuthorizationUrl()->shouldBe('https://' . self::SHOPIFY_DOMAIN . '/admin/oauth/authorize');
    }

    function it_has_authorisation_url()
    {
        $this->getAuthorizationUrl()->shouldHaveParsedUrl([
            'client_id'       => self::CLIENT_ID,
            'approval_prompt' => 'auto',
            'response_type'   => 'code',
            'host'            => self::SHOPIFY_DOMAIN,
            'path'            => '/admin/oauth/authorize'
        ]);

        $this->getState()->shouldNotBeNull();
    }

    function it_has_base_access_token_url()
    {
        $this->getBaseAccessTokenUrl([])->shouldBe('https://' . self::SHOPIFY_DOMAIN . '/admin/oauth/access_token');
    }

    function it_has_resource_owner_details_url(AccessToken $token)
    {
        $this->getResourceOwnerDetailsUrl($token)
            ->shouldBe('https://' . self::SHOPIFY_DOMAIN . '/admin/shop.json');
    }

    function it_has_default_scopes()
    {
        $this->getDefaultScopes()->shouldBe([
            'read_content',
            'read_customers',
            'read_inventory',
            'read_orders',
            'read_products',
            'read_product_listings'
        ]);
    }

    function it_has_authorization_headers(AccessToken $token)
    {
        $token->getToken()->willReturn(self::ACCESS_TOKEN);

        $this->getAuthorizationHeaders($token)->shouldBe([
            'X-Shopify-Access-Token' => self::ACCESS_TOKEN
        ]);
    }

    function it_can_throw_exception_on_invalid_response(
        ResponseInterface $response,
        ClientInterface $http
    ) {
        $response->getBody()->willReturn(json_encode(['errors' => 'Invalid token']));
        $response->getHeader('content-type')->willReturn('application/json');

        $http->send(Argument::any())->willReturn($response);

        $this->shouldThrow(IdentityProviderException::class)
            ->duringGetAccessToken('authorization_code', ['code' => 'fake_auth_code']);
    }

    function it_has_resource_owner(
        AccessToken $token,
        ResponseInterface $response,
        ClientInterface $http
    ) {
        $fixture = new ShopifyResponseFake();

        $response->getHeader('content-type')->willReturn('application/json');
        $response->getBody()->willReturn($fixture->jsonSerialize());

        $http->send(Argument::any())->willReturn($response);

        $this->getResourceOwner($token)->shouldBeLike(new ShopifyStore($fixture->requestData()));
    }

    public function getMatchers(): array
    {
        return [
            'haveParsedUrl' => function ($subject, $key) {
                $uri = parse_url($subject);
                parse_str($uri['query'], $query);

                $params = $uri + $query;

                foreach ($key as $index => $value) {
                    if(!array_key_exists($index, $params)) {
                        throw new FailureException(sprintf('Missing parameter key "%s"', $index));
                    }
                }

                foreach ($key as $index => $value) {
                    if ($params[$index] !== $value) {
                        throw new FailureException(
                            sprintf(
                                'Parameter value "%s" does not equal "%s"',
                                $index,
                                $value
                            )
                        );
                    }
                }

                return true;
            }
        ];
    }
}
