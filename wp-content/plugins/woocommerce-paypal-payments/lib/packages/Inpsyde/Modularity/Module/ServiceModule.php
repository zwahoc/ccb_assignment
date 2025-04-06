<?php

declare (strict_types=1);
namespace WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module;

interface ServiceModule extends \WooCommerce\PayPalCommerce\Vendor\Inpsyde\Modularity\Module\Module
{
    /**
     * Return application services' factories.
     *
     * Array keys will be services' IDs in the container, array values are callback that
     * accepts a PSR-11 container as parameter and return an instance of the service.
     * Services are "cached", so the given factory is called once the first time `get()` is called
     * in the container, and on subsequent `get()` the same instance is returned again and again.
     *
     * @return array<string, callable(\WooCommerce\PayPalCommerce\Vendor\Psr\Container\ContainerInterface $container):mixed>
     */
    public function services(): array;
}
