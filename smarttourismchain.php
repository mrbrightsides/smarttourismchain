<?php
/**
 * Plugin Name: SmartTourismChain
 * Plugin URI: https://smartourism.elpeef.com/
 * Description: Plugin open-source untuk transaksi wisata digital berbasis smart contract (versi free).
 * Version: 1.0.0
 * Author: Elpeef Dev Team
 * Author URI: https://elpeef.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: smarttourismchain
 * Requires at least: 5.5
 * Tested up to: 6.8
 * Requires PHP: 7.4
 */
 
require_once plugin_dir_path(__FILE__) . 'includes/template-loader.php';
// === Default Booking Contract Address ===
function stc_set_default_booking_address() {
    if (get_option('stc_booking_contract_address') === false) {
        update_option('stc_booking_contract_address', '0xB74955A1Da43d5C4737770E41a8e92F6492E2898');
    }
}
register_activation_hook(__FILE__, 'stc_set_default_booking_address');
add_filter('template_include', 'stc_booking_custom_template');

function stc_booking_custom_template($template) {
    if (is_singular('stc_booking')) {
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/single-stc_booking.php';

        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }

    return $template;
}


// === Enqueue JS (ethers + QR + main hybrid) ===
function stc_enqueue_assets() {
    if (is_page('simulasi')) {
        wp_enqueue_script(
            'ethers-js',
            'https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.umd.min.js',
            array(),
            null,
            true
        );

        wp_enqueue_script(
            'qrcode',
            'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js',
            array(),
            '1.0.0',
            true
        );


        wp_enqueue_script(
            'stc-booking',
            plugins_url('js/stc-booking.js', __FILE__),
            array('jquery', 'ethers-js'),
            '1.1',
            true
        );

        wp_enqueue_script(
            'stc_ajax',
            plugin_dir_url(__FILE__) . 'js/stc_ajax.js',
            array('jquery'),
            null,
            true
        );
        
        wp_enqueue_script(
            'stc-generate-token',
            plugin_dir_url(__FILE__) . 'js/generate-token.js',
            array('ethers-js'),
            filemtime(plugin_dir_path(__FILE__) . 'js/generate-token.js'),
            true
        );
        
        wp_localize_script('stc-booking', 'stc_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'contract_address' => get_option('stc_contract_address'),
            'contract_abi' => get_option('stc_contract_abi'),
            'booking_contract_address' => get_option('stc_booking_contract_address'),
            'booking_contract_abi' => get_option('stc_booking_contract_abi'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'stc_enqueue_assets');

// === Register Booking Post Type ===
function stc_register_post_type() {
    register_post_type('stc_booking', array(
        'labels' => array(
    'name' => __( 'Booking STC', 'smarttourismchain' ),
    'singular_name' => __( 'Booking', 'smarttourismchain' ),
),
        'public' => true,
        //'publicly_queryable' => false,
        'has_archive' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'supports' => array('title', 'custom-fields'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'capabilities' => array(
            'create_posts' => 'do_not_allow',
        ),
    ));
}
add_action('init', 'stc_register_post_type');

// === Shortcode untuk Menampilkan Form ===
function stc_booking_form() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'views/form-booking.php';
    return ob_get_clean();
}
add_shortcode('smartwisata_booking', 'stc_booking_form');

// === Admin Menu: Pengaturan dan Daftar Booking ===
function stc_add_admin_menu() {
    add_menu_page(
     __( 'SmartTourism Settings', 'smarttourismchain' ),
    __( 'SmartTourismChain', 'smarttourismchain' ),
    'manage_options',
    'smarttourismchain',
    'stc_settings_page',
    'dashicons-admin-generic'
);

    add_submenu_page(
    'smarttourismchain',
     __( 'Daftar Booking', 'smarttourismchain' ),
    __( 'Daftar Booking', 'smarttourismchain' ),
    'manage_options',
    'stc-booking-list',
    'stc_booking_list_page'
);
}
add_action('admin_menu', 'stc_add_admin_menu');

function stc_booking_list_page() {
    $bookings = get_posts(array(
        'post_type' => 'stc_booking',
        'numberposts' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    echo '<div class="wrap"><h1>' . esc_html__( 'Daftar Booking', 'smarttourismchain' ) . '</h1><table class="wp-list-table widefat fixed striped">';
echo '<thead><tr>';
echo '<th>' . esc_html__( 'Judul', 'smarttourismchain' ) . '</th>';
echo '<th>' . esc_html__( 'Tanggal', 'smarttourismchain' ) . '</th>';
echo '<th>' . esc_html__( 'TxHash', 'smarttourismchain' ) . '</th>';
echo '</tr></thead><tbody>';


    foreach ($bookings as $booking) {
        $tx = get_post_meta($booking->ID, 'txhash', true);
        echo '<tr>';
        echo '<td>' . esc_html($booking->post_title) . '</td>';
        echo '<td>' . esc_html(get_the_date('', $booking)) . '</td>';
        echo '<td>' . ($tx ? '<code>' . esc_html($tx) . '</code>' : '-') . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}

// === Pengaturan Kontrak di Admin Panel ===
function stc_register_settings() {
    register_setting('stc_settings_group', 'stc_contract_address', 'sanitize_text_field');
    register_setting('stc_settings_group', 'stc_contract_abi', 'sanitize_textarea_field');
    register_setting('stc_settings_group', 'stc_booking_contract_address', 'sanitize_text_field');
    register_setting('stc_settings_group', 'stc_booking_contract_abi', 'sanitize_textarea_field');
}
add_action('admin_init', 'stc_register_settings');
require_once plugin_dir_path(__FILE__) . 'includes/generate-token-form.php';
add_action('admin_init', 'stc_set_booking_default_fallback');
function stc_set_booking_default_fallback() {
    $key = 'stc_booking_contract_address';
    if (get_option($key) === '') {
        update_option($key, '0xB74955A1Da43d5C4737770E41a8e92F6492E2898');
    }
}


function stc_settings_page() { ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Pengaturan SmartTourismChain', 'smarttourismchain' ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('stc_settings_group'); ?>
            <?php do_settings_sections('stc_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Token Contract Address', 'smarttourismchain' ); ?></th>
                    <td>
                        <input type="text" name="stc_contract_address" value="<?php echo esc_attr(get_option('stc_contract_address')); ?>" style="width: 100%;" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Token Contract ABI (Template Preview)', 'smarttourismchain' ); ?></th>
                    <td>
                        <textarea readonly rows="10" style="width: 100%; background-color: #f8f8f8;"><?php
                            echo esc_textarea(file_get_contents(plugin_dir_path(__FILE__) . 'abi/erc20.json'));
                        ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Token Contract ABI (Custom, Optional)', 'smarttourismchain' ); ?></th>
                    <p>
    <?php esc_html_e( '🔧 Gunakan shortcode [stc_generate_token] di halaman manapun untuk membuat token ERC-20 Anda secara instan. Setelah token berhasil dibuat, copy alamat kontraknya ke kolom Token Contract Address di bawah.', 'smarttourismchain' ); ?>
</p>
                    <td>
                        <textarea name="stc_contract_abi" rows="10" style="width: 100%;" placeholder="<?php esc_attr_e( 'Kolom ini tidak boleh kosong. Gunakan ABI di atas atau ABI versi Anda sendiri.', 'smarttourismchain' ); ?>"
><?php
                            echo esc_textarea(get_option('stc_contract_abi'));
                        ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( '🛡️ Default STC Contract: biarkan terisi default jika ingin menggunakan kontrak resmi dari SmartTourismChain.', 'smarttourismchain' ); ?></th>
                    <td>
                        <input type="text" name="stc_booking_contract_address" value="<?php echo esc_attr(get_option('stc_booking_contract_address')); ?>" style="width: 100%;" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Booking Contract ABI (Template Preview)', 'smarttourismchain' ); ?></th>
                    <td>
                        <textarea readonly rows="10" style="width: 100%; background-color: #f8f8f8;"><?php
                            echo esc_textarea(file_get_contents(plugin_dir_path(__FILE__) . 'abi/booking.json'));
                        ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Booking Contract ABI (Custom, Optional)', 'smarttourismchain' ); ?></th>
                    <td>
                        <textarea name="stc_booking_contract_abi" rows="10" style="width: 100%;" placeholder="<?php esc_attr_e( 'Kolom ini tidak boleh kosong. Gunakan ABI di atas atau ABI versi Anda sendiri.', 'smarttourismchain' ); ?>"
><?php
                            echo esc_textarea(get_option('stc_booking_contract_abi'));
                        ?></textarea>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php }

// === Include handler untuk AJAX Booking ===
$ajax_handler_path = plugin_dir_path(__FILE__) . 'includes/ajax_handler.php';
if (file_exists($ajax_handler_path)) {
    require_once $ajax_handler_path;
}

$ajax_file = plugin_dir_path(__FILE__) . 'includes/stc_ajax.php';
if (file_exists($ajax_file)) {
    require_once $ajax_file;
}

// === Load Translation ===
function stc_load_textdomain() {
    load_plugin_textdomain( 'smarttourismchain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'stc_load_textdomain' );