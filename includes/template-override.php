add_filter('template_include', 'stc_booking_template_override');

function stc_booking_template_override($template) {
    if (is_singular('stc_booking')) {
        $custom_template = plugin_dir_path(__FILE__) . 'templates/single-stc_booking.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}
