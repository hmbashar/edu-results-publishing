<?php

namespace CBEDU\Admin\Helpers;

class Helpers
{
    private $prefix = CBEDU_PREFIX;
    private $required_taxonomies = [
        'cbedu_session_years',
        'cbedu_examinations',
        'cbedu_boards',
        'cbedu_department_group'
    ];

    public function __construct()
    {

        add_action('save_post', array($this, 'check_taxonomy_requirements'), 10, 3);
        add_action('admin_notices', array($this, 'display_admin_notice'));
        // Change placeholder
        add_filter('enter_title_here', array($this, 'changeTitlePlaceholder'));
        //add custom description afeter title for the result post type
        add_action('edit_form_after_title', array($this, 'add_custom_description_after_title'));
    }

    //auto save if taxonomy not selected
    public function check_taxonomy_requirements($post_id, $post, $update)
    {

        // 1) Safety checks
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }

        if (! $post || $post->post_type !== $this->prefix . 'results') {
            return;
        }

        if (! current_user_can('edit_post', $post_id)) {
            return;
        }

        // 2) ONLY run when user is trying to publish
        //    (Publish button / publish action)
        $new_status      = isset($_POST['post_status']) ? sanitize_text_field($_POST['post_status']) : '';
        $original_status = isset($_POST['original_post_status']) ? sanitize_text_field($_POST['original_post_status']) : '';

        // If not trying to publish right now, do nothing (covers autosave, "Save Draft", updates while draft, etc.)
        if ($new_status !== 'publish') {
            return;
        }

        // Optional: if it's already published and user is just updating it, skip
        // (remove this block if you ALSO want to enforce taxonomies on updates to published posts)
        if ($original_status === 'publish') {
            return;
        }

        // 3) Validate required taxonomies
        $missing_taxonomies = [];

        foreach ($this->required_taxonomies as $taxonomy) {
            $terms = wp_get_post_terms($post_id, $taxonomy, ['fields' => 'ids']);
            if (empty($terms) || is_wp_error($terms)) {
                $missing_taxonomies[] = $taxonomy;
            }
        }

        if (empty($missing_taxonomies)) {
            return;
        }

        // 4) Force back to draft + show notice
        remove_action('save_post', [$this, 'check_taxonomy_requirements'], 10);

        wp_update_post([
            'ID'          => $post_id,
            'post_status' => 'draft',
        ]);

        add_action('save_post', [$this, 'check_taxonomy_requirements'], 10, 3);

        add_filter('redirect_post_location', function ($location) use ($missing_taxonomies) {
            return add_query_arg('cbedu_taxonomy_errors', implode(',', $missing_taxonomies), $location);
        });
    }


    public function display_admin_notice()
    {
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

    public function changeTitlePlaceholder($title)
    {
        $screen = get_current_screen();
        if ($screen->post_type == $this->prefix . 'results') {
            $title = __('Student Name', 'edu-results');
        } elseif ($screen->post_type == $this->prefix . 'students') {
            $title = __('Enter Student Name', 'edu-results');
        } elseif ($screen->post_type == $this->prefix . 'subjects') {
            $title = __('Enter Subject Name', 'edu-results'); // Placeholder for Subjects post type
        }
        return $title;
    }

    public function add_custom_description_after_title()
    {
        $screen = get_current_screen();
        if ($screen->post_type == $this->prefix . 'results') {
            echo '<div style="margin-top: 10px; font-style: italic;font-weight:bold">';
            echo '<p>' . __('The name will be automatically shown based on the student\'s registration number after publish the post.', 'edu-results') . '</p>';
            echo '</div>';
        }
    }
}
