<?php

/**
 * The PHP-Scoper configuration.
 *
 * @package WooCommerce\PayPalCommerce
 */
declare (strict_types=1);
namespace WooCommerce\PayPalCommerce;

use Isolated\Symfony\Component\Finder\Finder;
$finders = array(Finder::create()->files()->ignoreVCS(\true)->ignoreDotFiles(\false)->exclude(array('.github', '.ddev', '.idea', '.psalm', 'tests'))->in('.'));
return array(
    'prefix' => 'WooCommerce\PayPalCommerce\Vendor',
    'finders' => $finders,
    'patchers' => array(),
    'exclude-files' => array('vendor/symfony/polyfill-php80/Resources/stubs/Stringable.php'),
    // list<string>.
    'exclude-namespaces' => array('/^(?!Psr).*/'),
    // list<string|regex>.
    'exclude-constants' => array(),
    // list<string|regex>.
    'exclude-classes' => array(),
    // list<string|regex>.
    'exclude-functions' => array(),
    // list<string|regex>.
    'expose-global-constants' => \false,
    // bool.
    'expose-global-classes' => \false,
    // bool.
    'expose-global-functions' => \false,
    // bool.
    'expose-namespaces' => array(),
    // list<string|regex>.
    'expose-constants' => array(),
    // list<string|regex>.
    'expose-classes' => array(),
    // list<string|regex>.
    'expose-functions' => array(),
);
