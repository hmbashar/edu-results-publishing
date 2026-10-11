<?php

namespace CBEDU\Inc;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shared custom SVG icons for admin and frontend.
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
     * Icon path markup.
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
            'settings' => '<circle cx="12" cy="12" r="3.2" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.5"/><path d="M12 3.6v2.2M12 18.2v2.2M3.6 12h2.2M18.2 12h2.2M6.1 6.1l1.6 1.6M16.3 16.3l1.6 1.6M17.9 6.1l-1.6 1.6M7.7 16.3l-1.6 1.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
            'download' => '<path d="M12 3.8v10.2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M8.2 10.6L12 14.4l3.8-3.8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.2 17.2v1.6A1.8 1.8 0 0 0 6 20.6h12a1.8 1.8 0 0 0 1.8-1.8v-1.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><rect x="5" y="17" width="14" height="3.8" rx="1.2" fill="currentColor" opacity=".12"/>',
            'upload' => '<path d="M12 15.8V5.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M8.2 9.2L12 5.4l3.8 3.8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.2 17.2v1.6A1.8 1.8 0 0 0 6 20.6h12a1.8 1.8 0 0 0 1.8-1.8v-1.6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><rect x="5" y="17" width="14" height="3.8" rx="1.2" fill="currentColor" opacity=".12"/>',
            'image' => '<rect x="3.6" y="4.6" width="16.8" height="14.8" rx="2" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5"/><circle cx="9" cy="9.4" r="1.8" fill="currentColor" opacity=".35" stroke="currentColor" stroke-width="1.4"/><path d="M4.4 17.2l4.6-4.2 3.2 2.8 3.4-3.6 4 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
            'building' => '<path d="M4.4 20.2V7.4L12 3.6l7.6 3.8v12.8" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M4.4 20.2h15.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><rect x="9.4" y="14.2" width="5.2" height="6" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.4"/><path d="M8 9.4h2M14 9.4h2M8 12.2h2M14 12.2h2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>',
            'phone' => '<path d="M7.2 4.4h3.2l1.4 3.4-2 1.4a11.2 11.2 0 0 0 5.2 5.2l1.4-2 3.4 1.4v3.2a1.8 1.8 0 0 1-1.8 1.8A14.4 14.4 0 0 1 5.4 6.2 1.8 1.8 0 0 1 7.2 4.4z" fill="currentColor" opacity=".14" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>',
            'email' => '<rect x="3.4" y="5.4" width="17.2" height="13.2" rx="2" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5"/><path d="M4.2 7.2L12 13l7.8-5.8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
            'globe' => '<circle cx="12" cy="12" r="8.2" fill="currentColor" opacity=".1" stroke="currentColor" stroke-width="1.5"/><path d="M3.8 12h16.4M12 3.8c2.4 2.6 3.6 5.2 3.6 8.2S14.4 17.6 12 20.2C9.6 17.6 8.4 15 8.4 12S9.6 6.4 12 3.8z" stroke="currentColor" stroke-width="1.45"/>',
            'map' => '<path d="M9.2 4.4L3.8 6.4v13.2l5.4-2 5.6 2 5.4-2V4.4l-5.4 2-5.6-2z" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M9.2 4.4v13.2M14.8 6.4v13.2" stroke="currentColor" stroke-width="1.4"/>',
            'family' => '<circle cx="8" cy="7.6" r="2.4" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.45"/><circle cx="16" cy="7.6" r="2.4" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.45"/><circle cx="12" cy="13.8" r="2" fill="currentColor" opacity=".2" stroke="currentColor" stroke-width="1.4"/><path d="M3.8 19.4c.5-2.8 2.4-4.2 4.2-4.2M16 15.2c1.8 0 3.7 1.4 4.2 4.2M9.4 19.4c.3-1.8 1.4-2.8 2.6-2.8s2.3 1 2.6 2.8" stroke="currentColor" stroke-width="1.45" stroke-linecap="round"/>',
            'idcard' => '<rect x="3.4" y="6" width="17.2" height="12" rx="2" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5"/><circle cx="9" cy="11.2" r="2.1" fill="currentColor" opacity=".25" stroke="currentColor" stroke-width="1.4"/><path d="M13.4 10h4.2M13.4 13.2h3.2M5.8 15.6c.5-1.6 1.8-2.4 3.2-2.4s2.7.8 3.2 2.4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>',
            'save' => '<path d="M5.2 4.2h11.2L18.8 6.6v13.2H5.2V4.2z" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M8 4.4v4.8h6.4V4.4M8 20v-6.2h8V20" stroke="currentColor" stroke-width="1.45" stroke-linejoin="round"/><circle cx="12" cy="15.6" r="1.1" fill="currentColor"/>',
            'trash' => '<path d="M5 7.2h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M9.2 7.2V5.4h5.6v1.8" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M7.2 7.2l.8 11.2h8l.8-11.2" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M10.2 11v4.8M13.8 11v4.8" stroke="currentColor" stroke-width="1.45" stroke-linecap="round"/>',
            'plus' => '<circle cx="12" cy="12" r="8.4" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5"/><path d="M12 8v8M8 12h8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>',
            'help' => '<circle cx="12" cy="12" r="8.4" fill="currentColor" opacity=".12" stroke="currentColor" stroke-width="1.5"/><path d="M9.4 9.2a2.6 2.6 0 0 1 5 1.2c0 1.8-2.5 2.4-2.5 2.4" stroke="currentColor" stroke-width="1.55" stroke-linecap="round"/><circle cx="12" cy="16.4" r="1" fill="currentColor"/>',
            'cloud' => '<path d="M7.2 17.6A4.2 4.2 0 0 1 7.2 9.2 5.2 5.2 0 0 1 17 10.4 3.8 3.8 0 0 1 17.4 17.6H7.2z" fill="currentColor" opacity=".14" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M12 14.2v5M12 14.2l-2.2 2.2M12 14.2l2.2 2.2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>',
            'layers' => '<path d="M12 3.6L3.8 8.2 12 12.8l8.2-4.6L12 3.6z" fill="currentColor" opacity=".16" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/><path d="M3.8 12.4L12 17l8.2-4.6M3.8 16.2L12 20.8l8.2-4.6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>',
        );
    }
}
