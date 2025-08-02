<?php
/**
 * Plugin Name: SmartWisataChain
 * Plugin URI: https://smartourism.elpeef.com/
 * Description: Plugin open-source untuk transaksi wisata digital berbasis smart contract (versi free).
 * Version: 1.0
 * Author: Elpeef Dev Team
 * Author URI: https://elpeef.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: smartwisatachain
 * Requires at least: 5.5
 * Tested up to: 6.5
 * Requires PHP: 7.4
 */

// === Enqueue JS (ethers + QR + main) ===
function swc_enqueue_scripts() {
    wp_enqueue_script(
        'ethers-js',
        'https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.umd.min.js',
        array(),
        null,
        true
    );

    wp_enqueue_script(
  'qrcode-js',
  'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js',
  array(),
  null,
  true
);


    wp_enqueue_script(
    'swc-main',
    plugin_dir_url(__FILE__) . 'swc-main.js?ver=' . time(), // cache buster
    array('ethers-js'),
    null,
    true
);


    wp_localize_script('swc-main', 'swc_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'contract_address' => get_option('swc_contract_address'),
    'contract_abi' => get_option('swc_contract_abi'),
    'booking_contract_address' => get_option('swc_booking_contract_address'),
    'booking_contract_abi' => get_option('swc_booking_contract_abi'),
));

}
add_action('wp_enqueue_scripts', 'swc_enqueue_scripts');

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

// === Shortcode Booking Form ===
function swc_booking_form() {
    ob_start(); ?>
    <form id="swc-booking-form">
        <label>Nama:</label><br>
        <input type="text" id="nama" name="nama" required>
        <label>Hotel:</label><br>
        <input type="text" id="hotel" name="hotel" required><br><br>

        <label>Tanggal:</label><br>
        <input type="date" name="tanggal" required><br><br>

        <label>Metode Pembayaran:</label><br>
        <input type="radio" name="payment" value="offchain" checked> Off-Chain
        <input type="radio" name="payment" value="onchain"> On-Chain (Blockchain)<br><br>

        <button type="submit">Kirim</button>
    </form>
    <div id="swc-result"></div>
    <?php
    return ob_get_clean();
}
add_shortcode('smartwisata_booking', 'swc_booking_form');

// === Off-Chain Booking Handler ===
function swc_handle_offchain() {
    $nama = sanitize_text_field($_POST['nama'] ?? '');
    $tanggal = sanitize_text_field($_POST['tanggal'] ?? '');

    if (!$nama || !$tanggal) {
        wp_send_json_error('Data tidak lengkap.');
        return;
    }

    $post_id = wp_insert_post(array(
        'post_title' => "$nama - $tanggal",
        'post_type' => 'swc_booking',
        'post_status' => 'publish',
    ));

    if ($post_id) {
        wp_send_json_success('Booking disimpan secara off-chain.');
    } else {
        wp_send_json_error('Gagal menyimpan booking.');
    }
}
add_action('wp_ajax_swc_offchain', 'swc_handle_offchain');
add_action('wp_ajax_nopriv_swc_offchain', 'swc_handle_offchain');

// === On-Chain Booking Handler ===
function swc_handle_onchain() {
    $nama = sanitize_text_field($_POST['nama'] ?? '');
    $tanggal = sanitize_text_field($_POST['tanggal'] ?? '');
    $txhash = sanitize_text_field($_POST['txhash'] ?? '');

    if (!$nama || !$tanggal || !$txhash) {
        wp_send_json_error('Data tidak lengkap untuk on-chain.');
        return;
    }

    $post_id = wp_insert_post(array(
        'post_title' => "$nama - $tanggal",
        'post_type' => 'swc_booking',
        'post_status' => 'publish',
        'meta_input' => array('txhash' => $txhash),
    ));

    if ($post_id) {
        wp_send_json_success(array(
            'message' => 'Booking on-chain dicatat.',
            'txhash' => $txhash,
            'post_id' => $post_id,
        ));
    } else {
        wp_send_json_error('Gagal menyimpan booking.');
    }
}
add_action('wp_ajax_swc_onchain', 'swc_handle_onchain');
add_action('wp_ajax_nopriv_swc_onchain', 'swc_handle_onchain');

// === Admin Menu for Smart Contract Settings ===
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

function swc_register_settings() {
    register_setting('swc_settings_group', 'swc_contract_address', 'sanitize_text_field');
    register_setting('swc_settings_group', 'swc_contract_abi', 'sanitize_textarea_field');
    register_setting('swc_settings_group', 'swc_booking_contract_address', 'sanitize_text_field');
    register_setting('swc_settings_group', 'swc_booking_contract_abi', 'sanitize_textarea_field');


}
add_action('admin_init', 'swc_register_settings');

// === Admin Settings Page HTML ===
function swc_settings_page() { ?>
    <div class="wrap">
        <h1>Pengaturan SmartWisataChain</h1>
        <form method="post" action="options.php">
            <?php settings_fields('swc_settings_group'); ?>
            <?php do_settings_sections('swc_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Token Contract Address</th>
                    <td><input type="text" name="swc_contract_address" value="<?php echo esc_attr(get_option('swc_contract_address')); ?>" style="width: 100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Token Contract ABI (JSON)</th>
                    <td><textarea name="swc_contract_abi" rows="10" style="width: 100%;"><?php echo esc_textarea(get_option('swc_contract_abi')); ?></textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Booking Contract Address</th>
                    <td><input type="text" name="swc_booking_contract_address" value="<?php echo esc_attr(get_option('swc_booking_contract_address')); ?>" style="width: 100%;" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Booking Contract ABI (JSON)</th>
                    <td><textarea name="swc_booking_contract_abi" rows="10" style="width: 100%;"><?php echo esc_textarea(get_option('swc_booking_contract_abi')); ?></textarea></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php }