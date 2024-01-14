<?php 
namespace cbedu\inc\lib\CBEDUCustomFunctions;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CBEDUCustomFunctions {
    private $prefix;
    private $required_taxonomies = [
        'cbedu_session_years',
        'cbedu_examinations',
        'cbedu_boards',
        'cbedu_department_group'
    ];

    public function __construct($prefix) {
        $this->prefix = $prefix;
        add_action('save_post', array($this, 'check_taxonomy_requirements'), 10, 3);
        add_action('admin_notices', array($this, 'display_admin_notice'));
    }

    public function check_taxonomy_requirements($post_id, $post, $update) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || $post->post_type != $this->prefix . 'results') return;

        $missing_taxonomies = [];
        foreach ($this->required_taxonomies as $taxonomy) {
            $terms = wp_get_post_terms($post_id, $taxonomy);
            if (empty($terms) || is_wp_error($terms)) {
                $missing_taxonomies[] = $taxonomy;
            }
        }

        if (!empty($missing_taxonomies)) {
            remove_action('save_post', array($this, 'check_taxonomy_requirements'));

            wp_update_post([
                'ID'          => $post_id,
                'post_status' => 'draft',
            ]);

            add_action('save_post', array($this, 'check_taxonomy_requirements'), 10, 3);

            add_filter('redirect_post_location', function ($location) use ($missing_taxonomies) {
                return add_query_arg('cbedu_taxonomy_errors', implode(',', $missing_taxonomies), $location);
            });
        }
    }

    public function display_admin_notice() {
        if (!empty($_GET['cbedu_taxonomy_errors'])) {
            $taxonomy_slugs = explode(',', sanitize_text_field($_GET['cbedu_taxonomy_errors']));
            $taxonomy_names = [];
    
            foreach ($taxonomy_slugs as $slug) {
                $taxonomy_obj = get_taxonomy($slug);
                $taxonomy_names[] = $taxonomy_obj ? $taxonomy_obj->labels->singular_name : $slug;
            }

            // Prepare the list of taxonomy names for display
            $taxonomies_list = esc_html(implode(', ', $taxonomy_names));
        
            // Use sprintf for formatting and make the string translatable
            $error_message = sprintf(
                __('Error: You must select at least one term in each of the following taxonomies before this result can be published:<b> %s.</b>', 'edu-results'),
                $taxonomies_list
            );
        
            echo '<div class="error"><p>' . $error_message . '</p></div>';
        }
            
    }
}