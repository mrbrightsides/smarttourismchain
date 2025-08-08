add_filter('template_include', 'stcdrk_booking_template_override');

function stcdrk_booking_template_override($template) {
    if (is_singular('stcdrk_booking')) {
        $custom_template = plugin_dir_path(__FILE__) . 'templates/single-stcdrk_booking.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}
