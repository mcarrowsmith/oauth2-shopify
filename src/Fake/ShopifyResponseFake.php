<?php

namespace Fake;

use JsonSerializable;

class ShopifyResponseFake implements JsonSerializable
{
    public const ID = 12345678981;

    public const NAME = 'example-oauth2-dev';

    public const EMAIL = 'store@example.com';

    public const DOMAIN = 'example.myshopify.com';

    public const MYSHOPIFY_DOMAIN = 'store.example.com';

    public const ADDRESS_1 = 'Wembley Stadium';

    public const CITY = 'London';

    public const POSTCODE = 'HA9 0WS';

    public const COUNTRY_CODE = 'GB';

    public function requestData(): array
    {
        return [
            'shop' => [
                'id'                                   => self::ID,
                'name'                                 => self::NAME,
                'email'                                => self::EMAIL,
                'domain'                               => self::DOMAIN,
                'province'                             => 'England',
                'country'                              => self::COUNTRY_CODE,
                'address1'                             => self::ADDRESS_1,
                'zip'                                  => self::POSTCODE,
                'city'                                 => self::CITY,
                'source'                               => null,
                'phone'                                => '',
                'latitude'                             => 53.3404579,
                'longitude'                            => -2.1273138,
                'primary_locale'                       => 'en',
                'address2'                             => null,
                'created_at'                           => '2022-09-17T11:02:28+01:00',
                'updated_at'                           => '2022-09-17T11:25:05+01:00',
                'country_code'                         => self::COUNTRY_CODE,
                'country_name'                         => 'United Kingdom',
                'currency'                             => 'GBP',
                'customer_email'                       => self::EMAIL,
                'timezone'                             => '(GMT+00:00) Europe/London',
                'iana_timezone'                        => 'Europe/London',
                'shop_owner'                           => 'mcarrowsmith-oauth2-dev Admin',
                'money_format'                         => '£{{amount}}',
                'money_with_currency_format'           => '£{{amount}} GBP',
                'weight_unit'                          => 'lb',
                'province_code'                        => 'ENG',
                'taxes_included'                       => true,
                'auto_configure_tax_inclusivity'       => null,
                'tax_shipping'                         => null,
                'county_taxes'                         => true,
                'plan_display_name'                    => 'Development',
                'plan_name'                            => 'affiliate',
                'has_discounts'                        => false,
                'has_gift_cards'                       => false,
                'myshopify_domain'                     => self::MYSHOPIFY_DOMAIN,
                'google_apps_domain'                   => null,
                'google_apps_login_enabled'            => null,
                'money_in_emails_format'               => '£{{amount}}',
                'money_with_currency_in_emails_format' => '£{{amount}} GBP',
                'eligible_for_payments'                => true,
                'requires_extra_payments_agreement'    => false,
                'password_enabled'                     => true,
                'has_storefront'                       => true,
                'eligible_for_card_reader_giveaway'    => false,
                'finances'                             => true,
                'primary_location_id'                  => 12345678987,
                'cookie_consent_level'                 => 'implicit',
                'visitor_tracking_consent_preference'  => 'allow_all',
                'checkout_api_supported'               => false,
                'multi_location_enabled'               => true,
                'setup_required'                       => false,
                'pre_launch_enabled'                   => false,
                'enabled_presentment_currencies'       => [
                    0 => 'GBP',
                ],
            ]
        ];
    }

    /**
     * @return false|mixed|string
     */
    public function jsonSerialize()
    {
        return json_encode($this->requestData(), JSON_PRETTY_PRINT);
    }
}
