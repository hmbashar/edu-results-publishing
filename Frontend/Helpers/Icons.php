<?php

namespace CBEDU\Frontend\Helpers;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom premium SVG icons for the EDU Results frontend.
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
        $class = isset($args['class']) ? $args['class'] : 'cbedu-icon';
        $size  = isset($args['size']) ? (int) $args['size'] : 20;

        $paths = self::paths();
        if (!isset($paths[$name])) {
            return '';
        }

        return sprintf(
            '<svg class="%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">%3$s</svg>',
            esc_attr($class),
            $size,
            $paths[$name]
        );
    }

    /**
     * Custom education marksheet icon set (duotone strokes + soft fills).
     *
     * @return array<string,string>
     */
    private static function paths()
    {
        return array(
            'student' => '<path d="M3.2 9.1L12 4.4l8.8 4.7-8.8 4.8L3.2 9.1z" fill="currentColor" opacity=".18"/><path d="M3.2 9.1L12 4.4l8.8 4.7-8.8 4.8L3.2 9.1z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M6.4 11.2v4.6c0 1.9 2.4 3.4 5.6 3.4s5.6-1.5 5.6-3.4v-4.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M19.8 10v5.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="19.8" cy="16.4" r="1.35" fill="currentColor"/>',
            'document' => '<rect x="5.2" y="3.4" width="13.6" height="17.2" rx="2.2" fill="currentColor" opacity=".14"/><path d="M8.2 3.8h6.4L18.4 8v11.4a1.6 1.6 0 0 1-1.6 1.6H8.2A1.6 1.6 0 0 1 6.6 19.4V5.4A1.6 1.6 0 0 1 8.2 3.8z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M14.4 3.9V8h4" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M9.2 12.2h5.8M9.2 15.4h4.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><circle cx="16.4" cy="15.4" r="1.1" fill="currentColor"/>',
            'search' => '<circle cx="10.6" cy="10.6" r="6.1" fill="currentColor" opacity=".14"/><circle cx="10.6" cy="10.6" r="6.1" stroke="currentColor" stroke-width="1.6"/><path d="M15.2 15.2L20.2 20.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M8.2 10.6h4.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M10.6 8.2v4.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
            'books' => '<path d="M4 5h4.4v14.4H5.1A1.1 1.1 0 0 1 4 18.3V5z" fill="currentColor" opacity=".2"/><path d="M8.4 5H13v14.4H9.5A1.1 1.1 0 0 1 8.4 18.3V5z" fill="currentColor" opacity=".12"/><path d="M4 5h4.4v14.4H5.1A1.1 1.1 0 0 1 4 18.3V5zM8.4 5H13v14.4H9.5A1.1 1.1 0 0 1 8.4 18.3V5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M13.2 6.3l6.2-1.7v13.4l-6.2 2V6.3z" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>',
            'structure' => '<rect x="8.6" y="3.2" width="6.8" height="4.6" rx="1.2" fill="currentColor" opacity=".18" stroke="currentColor" stroke-width="1.5"/><rect x="2.8" y="16.2" width="6.8" height="4.6" rx="1.2" fill="currentColor" opacity=".18" stroke="currentColor" stroke-width="1.5"/><rect x="14.4" y="16.2" width="6.8" height="4.6" rx="1.2" fill="currentColor" opacity=".18" stroke="currentColor" stroke-width="1.5"/><path d="M12 7.8v3.2M6.2 13.8h11.6M6.2 13.8v2.4M17.8 13.8v2.4M12 11V13.8" stroke="currentColor" stroke-width="1.55" stroke-linecap="round"/>',
            'print' => '<rect x="7" y="3.6" width="10" height="5.2" rx="1.2" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.5"/><path d="M5.4 10h13.2a1.8 1.8 0 0 1 1.8 1.8v5.4H3.6v-5.4A1.8 1.8 0 0 1 5.4 10z" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><rect x="7.2" y="16.4" width="9.6" height="4" rx="1" fill="currentColor" opacity=".18" stroke="currentColor" stroke-width="1.5"/><circle cx="16.6" cy="13" r="1" fill="currentColor"/>',
            'exam' => '<path d="M7.4 3.6h9.2a1.8 1.8 0 0 1 1.8 1.8v14.4l-3-1.7-2.9 1.8-2.9-1.8-3.2 1.7V5.4A1.8 1.8 0 0 1 7.4 3.6z" fill="currentColor" opacity=".14" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M9.2 9h5.8M9.2 12.2h5.8M9.2 15.4h3.6" stroke="currentColor" stroke-width="1.55" stroke-linecap="round"/><circle cx="15.8" cy="15.4" r="1.05" fill="currentColor"/>',
            'calendar' => '<rect x="3.6" y="5" width="16.8" height="15.2" rx="2.2" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5"/><path d="M3.6 9.4h16.8M8 3.6v3.2M16 3.6v3.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="7.2" y="12.2" width="3" height="3" rx="0.7" fill="currentColor"/><rect x="10.8" y="12.2" width="3" height="3" rx="0.7" fill="currentColor" opacity=".55"/><rect x="14.4" y="12.2" width="3" height="3" rx="0.7" fill="currentColor" opacity=".3"/>',
            'board' => '<rect x="3.6" y="4.6" width="16.8" height="11.6" rx="1.8" fill="currentColor" opacity=".14" stroke="currentColor" stroke-width="1.5"/><path d="M8 19.4h8M12 16.2v3.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M6.8 8.2h5M6.8 11.2h9.2" stroke="currentColor" stroke-width="1.55" stroke-linecap="round"/>',
            'group' => '<circle cx="8" cy="8.2" r="2.7" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.5"/><circle cx="16" cy="8.2" r="2.7" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.5"/><path d="M3.4 18.6c.5-3 2.6-4.5 4.6-4.5s4.1 1.5 4.6 4.5M11.4 18.6c.5-3 2.6-4.5 4.6-4.5s4.1 1.5 4.6 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
            'ticket' => '<path d="M3.8 8.4A1.8 1.8 0 0 1 5.6 6.6h12.8A1.8 1.8 0 0 1 20.2 8.4v2a2 2 0 0 0 0 3.2v2a1.8 1.8 0 0 1-1.8 1.8H5.6A1.8 1.8 0 0 1 3.8 15.6v-2a2 2 0 0 0 0-3.2v-2z" fill="currentColor" opacity=".14" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M10 7v1.8M10 11v1.8M10 15v1.8" stroke="currentColor" stroke-width="1.55" stroke-linecap="round" stroke-dasharray="0.1 2.1"/><circle cx="15.6" cy="12" r="1.1" fill="currentColor"/>',
            'hash' => '<path d="M9.1 4.2L7.6 19.8M16.4 4.2L14.9 19.8M4.4 9h15.2M3.8 15h15.2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="12" cy="12" r="2.1" fill="currentColor" opacity=".16"/>',
            'arrow' => '<path d="M4.8 12h12.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M13 7l5.8 5L13 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="5.2" cy="12" r="1.1" fill="currentColor"/>',
            'user' => '<circle cx="12" cy="8" r="3.4" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.5"/><path d="M4.8 19.4c.9-3.6 3.4-5.3 7.2-5.3s6.3 1.7 7.2 5.3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><path d="M9.6 19.4h4.8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
            'chart' => '<path d="M4 19.4h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="5.6" y="11" width="3.2" height="8.4" rx="1" fill="currentColor" opacity=".2" stroke="currentColor" stroke-width="1.4"/><rect x="10.4" y="6.4" width="3.2" height="13" rx="1" fill="currentColor" opacity=".28" stroke="currentColor" stroke-width="1.4"/><rect x="15.2" y="13.2" width="3.2" height="6.2" rx="1" fill="currentColor" opacity=".18" stroke="currentColor" stroke-width="1.4"/>',
            'alert' => '<path d="M12 3.8L21 19.2H3L12 3.8z" fill="currentColor" opacity=".14" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M12 9.6v3.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="16.4" r="1.05" fill="currentColor"/>',
            'empty' => '<rect x="3.8" y="5.2" width="16.4" height="13.6" rx="2.2" fill="currentColor" opacity=".1" stroke="currentColor" stroke-width="1.5"/><path d="M7.6 10h8.8M7.6 13.4h5.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="16.4" cy="13.6" r="3.4" fill="#fff" stroke="currentColor" stroke-width="1.5"/><path d="M15 13.6h2.8M16.4 12.2v2.8" stroke="currentColor" stroke-width="1.45" stroke-linecap="round"/>',
            'check' => '<circle cx="12" cy="12" r="8.4" fill="currentColor" opacity=".14" stroke="currentColor" stroke-width="1.5"/><path d="M8 12.2l2.6 2.6L16.2 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        );
    }
}
