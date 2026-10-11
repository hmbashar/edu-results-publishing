<?php

namespace CBEDU\Frontend\Helpers;

use CBEDU\Inc\Icons as SharedIcons;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Frontend icon helper — proxies to the shared icon set.
 */
class Icons
{
    /**
     * Render a named icon as inline SVG.
     *
     * @param string $name Icon key.
     * @param array  $args Optional class and size.
     * @return string
     */
    public static function get($name, $args = array())
    {
        return SharedIcons::get($name, $args);
    }
}
