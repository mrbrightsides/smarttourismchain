
<?php
/**
 * Plugin Name: SmartWisataChain
 * Plugin URI: https://smartourism.elpeef.com/
 * Description: Plugin open-source untuk transaksi wisata digital berbasis smart contract (versi free).
 * Version: 1.1
 * Author: Elpeef Dev Team
 * Author URI: https://elpeef.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: smartwisatachain
 * Requires at least: 5.5
 * Tested up to: 6.5
 * Requires PHP: 7.4
 */

// === Enqueue JS (ethers + QR + main hybrid) ===
function swc_enqueue_assets() {
    if (is_page('simulasi')) {
        wp_enqueue_script(
            'ethers-js',
            'https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.umd.min.js',
            array(),
            null,
            true
        );

        wp_enqueue_script(
            'qrcode-generator',
            'https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js',
            array(),
            null,
            true
        );

        wp_enqueue_script(
            'swc-booking',
            plugins_url('js/swc-booking.js', __FILE__),
            array('jquery', 'ethers-js'),
            '1.1',
            true
        );

        wp_enqueue_script(
            'swc_ajax',
            plugin_dir_url(__FILE__) . 'js/swc_ajax.js',
            array('jquery'),
            null,
            true
        );
        
        wp_enqueue_script(
            'swc-generate-token',
            plugin_dir_url(__FILE__) . 'js/generate-token.js',
            array('ethers-js'),
            filemtime(plugin_dir_path(__FILE__) . 'js/generate-token.js'),
            true
        );
        
        function swc_enqueue_admin_scripts($hook) {
            if ($hook === 'toplevel_page_smartwisatachain') {
                wp_enqueue_script('fill-template-abi', plugin_dir_url(__FILE__) . 'js/fill-template-abi.js', array(), '1.0', true);
            }
        }
        add_action('admin_enqueue_scripts', 'swc_enqueue_admin_scripts');

        wp_localize_script('swc-booking', 'swc_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'contract_address' => get_option('swc_contract_address'),
            'contract_abi' => get_option('swc_contract_abi'),
            'booking_contract_address' => get_option('swc_booking_contract_address'),
            'booking_contract_abi' => get_option('swc_booking_contract_abi'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'swc_enqueue_assets');

// === Register Booking Post Type ===
function swc_register_post_type() {
    register_post_type('swc_booking', array(
        'labels' => array(
            'name' => __('Booking SWC'),
            'singular_name' => __('Booking'),
        ),
        'public' => true,
        'has_archive' => false,
        'show_ui' => true,
        'supports' => array('title'),
        'capability_type' => 'post',
    ));
}
add_action('init', 'swc_register_post_type');

// === Shortcode untuk Menampilkan Form ===
function swc_booking_form() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'views/form-booking.php';
    return ob_get_clean();
}
add_shortcode('smartwisata_booking', 'swc_booking_form');

// === Admin Menu: Pengaturan dan Daftar Booking ===
function swc_add_admin_menu() {
    add_menu_page(
        'SmartWisata Settings',
        'SmartWisataChain',
        'manage_options',
        'smartwisatachain',
        'swc_settings_page',
        'dashicons-admin-generic'
    );

    add_submenu_page(
        'smartwisatachain',
        'Daftar Booking',
        'Daftar Booking',
        'manage_options',
        'swc-booking-list',
        'swc_booking_list_page'
    );
}
add_action('admin_menu', 'swc_add_admin_menu');

function swc_booking_list_page() {
    $bookings = get_posts(array(
        'post_type' => 'swc_booking',
        'numberposts' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    echo '<div class="wrap"><h1>Daftar Booking</h1><table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>Judul</th><th>Tanggal</th><th>TxHash</th></tr></thead><tbody>';

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
function swc_register_settings() {
    register_setting('swc_settings_group', 'swc_contract_address', 'sanitize_text_field');
    register_setting('swc_settings_group', 'swc_contract_abi', 'sanitize_textarea_field');
    register_setting('swc_settings_group', 'swc_booking_contract_address', 'sanitize_text_field');
    register_setting('swc_settings_group', 'swc_booking_contract_abi', 'sanitize_textarea_field');
}
add_action('admin_init', 'swc_register_settings');
require_once plugin_dir_path(__FILE__) . 'includes/generate-token-form.php';

function swc_settings_page() { ?>
    <div class="wrap">
        <h1>Pengaturan SmartWisataChain</h1>
        <form method="post" action="options.php">
            <?php settings_fields('swc_settings_group'); ?>
            <?php do_settings_sections('swc_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Token Contract Address</th>
                    <td>
                        <input type="text" name="swc_contract_address" value="<?php echo esc_attr(get_option('swc_contract_address')); ?>" style="width: 100%;" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Token Contract ABI (Template Preview)</th>
                    <td>
                        <textarea readonly rows="10" style="width: 100%; background-color: #f8f8f8;"><?php
                            echo esc_textarea(file_get_contents(plugin_dir_path(__FILE__) . 'abi/erc20.json'));
                        ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Token Contract ABI (Custom, Optional)</th>
                    <td>
                        <textarea name="swc_contract_abi" rows="10" style="width: 100%;" placeholder="Kolom ini tidak boleh kosong. Gunakan ABI di atas atau ABI Anda sendiri."><?php
                            echo esc_textarea(get_option('swc_contract_abi'));
                        ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Booking Contract Address</th>
                    <td>
                        <input type="text" name="swc_booking_contract_address" value="<?php echo esc_attr(get_option('swc_booking_contract_address')); ?>" style="width: 100%;" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Booking Contract ABI (Template Preview)</th>
                    <td>
                        <textarea readonly rows="10" style="width: 100%; background-color: #f8f8f8;"><?php
                            echo esc_textarea(file_get_contents(plugin_dir_path(__FILE__) . 'abi/booking.json'));
                        ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Booking Contract ABI (Custom, Optional)</th>
                    <td>
                        <textarea name="swc_booking_contract_abi" rows="10" style="width: 100%;" placeholder="Kolom ini tidak boleh kosong. Gunakan ABI di atas atau ABI Anda sendiri."><?php
                            echo esc_textarea(get_option('swc_booking_contract_abi'));
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

$ajax_file = plugin_dir_path(__FILE__) . 'includes/swc_ajax.php';
if (file_exists($ajax_file)) {
    require_once $ajax_file;
}
