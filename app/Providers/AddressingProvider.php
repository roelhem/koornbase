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

class AddressingProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

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
