<?php

namespace App\Providers;

use CommerceGuys\Addressing\Formatter\DefaultFormatter;
use CommerceGuys\Addressing\Formatter\FormatterInterface;
use CommerceGuys\Addressing\Formatter\PostalLabelFormatter;
use CommerceGuys\Addressing\Formatter\PostalLabelFormatterInterface;
use CommerceGuys\Addressing\Repository\AddressFormatRepositoryInterface;
use CommerceGuys\Addressing\Repository\CountryRepositoryInterface;
use CommerceGuys\Addressing\Repository\SubdivisionRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use CommerceGuys\Addressing\Repository\AddressFormatRepository;
use CommerceGuys\Addressing\Repository\CountryRepository;
use CommerceGuys\Addressing\Repository\SubdivisionRepository;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;
use libphonenumber\PhoneNumberUtil;

class AddressingProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('country_code', function($attribute, $value) {
            $repository = resolve(CountryRepositoryInterface::class);
            return array_key_exists($value, $repository->getList());
        });

        \Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            $country_code_attr = str_replace('phone_number','country_code',$attribute);
            $country_code = array_get($validator->getData(), $country_code_attr, 'NL');
            $util = resolve(PhoneNumberUtil::class);
            $phone_number = $util->parse($value, $country_code);
            return $util->isValidNumber($phone_number);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Phones
        $this->app->singleton(PhoneNumberUtil::class, function() {
            return PhoneNumberUtil::getInstance();
        });

        $this->app->singleton(PhoneNumberOfflineGeocoder::class, function() {
            return PhoneNumberOfflineGeocoder::getInstance();
        });

        // Addressing
        $this->app->singleton(AddressFormatRepository::class);
        $this->app->singleton(CountryRepository::class);
        $this->app->singleton(SubdivisionRepository::class);

        $this->app->bind(AddressFormatRepositoryInterface::class, AddressFormatRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(SubdivisionRepositoryInterface::class, SubdivisionRepository::class);

        $this->app->bind(DefaultFormatter::class);
        $this->app->bind(PostalLabelFormatter::class);

        $this->app->bind(FormatterInterface::class, DefaultFormatter::class);
        $this->app->bind(PostalLabelFormatterInterface::class, PostalLabelFormatter::class);
    }
}
