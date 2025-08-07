<?php
// Block direct access
if (!defined('ABSPATH')) {
    exit;
}

// === Logging only, tidak menyimpan ke post_type ===
error_log("✅ stc_ajax.php loaded");

// === OFFCHAIN LOGGING (simulasi atau uji coba) ===
add_action('wp_ajax_stc_handle_offchain_booking', 'stc_handle_offchain_booking');
add_action('wp_ajax_nopriv_stc_handle_offchain_booking', 'stc_handle_offchain_booking');

function stc_handle_offchain_booking() {
    $nama    = sanitize_text_field($_POST['nama']);
    $hotel   = sanitize_text_field($_POST['hotel']);
    $tanggal = sanitize_text_field($_POST['tanggal']);
    $booking_id = 'OFF-' . time();

    $log = [
        'booking_id' => $booking_id,
        'nama'       => $nama,
        'hotel'      => $hotel,
        'tanggal'    => $tanggal,
        'mode'       => 'offchain',
        'timestamp'  => current_time('mysql'),
    ];

    error_log("📘 OFFCHAIN Booking:\n" . print_r($log, true));

    wp_send_json_success(array(
        'message'     => 'Reservasi off-chain berhasil (log-only).',
        'booking_id'  => $booking_id,
        'nama'        => $nama,
        'hotel'       => $hotel,
        'tanggal'     => $tanggal,
        'mode'        => 'offchain'
    ));
}

// === ONCHAIN LOGGING ===
add_action('wp_ajax_stc_record_onchain', 'stc_record_onchain');
add_action('wp_ajax_nopriv_stc_record_onchain', 'stc_record_onchain');

function stc_record_onchain() {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    $booking_id = sanitize_text_field($_POST['booking_id']);
    $nama       = sanitize_text_field($_POST['nama']);
    $hotel      = sanitize_text_field($_POST['hotel']);
    $tanggal    = sanitize_text_field($_POST['tanggal']);
    $tx_hash    = sanitize_text_field($_POST['tx_hash']);
    $timestamp  = current_time('mysql');

    // Logging ke file (optional)
    $log_dir = plugin_dir_path(__FILE__) . '/../logs/';
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $log_file = $log_dir . 'onchain-log.txt';
    $log_data = date('Y-m-d H:i:s') . " - ID: $booking_id, Nama: $nama, Hotel: $hotel, Tanggal: $tanggal, TxHash: $tx_hash\n";
    file_put_contents($log_file, $log_data, FILE_APPEND);

    // Simpan ke admin
    $post_id = wp_insert_post(array(
        'post_title'  => $booking_id . ' – ' . $nama,
        'post_type'   => 'stc_booking',
        'post_status' => 'publish',
    ));

    if ($post_id && !is_wp_error($post_id)) {
        update_post_meta($post_id, 'nama', $nama);
        update_post_meta($post_id, 'hotel', $hotel);
        update_post_meta($post_id, 'tanggal', $tanggal);
        update_post_meta($post_id, 'txhash', $tx_hash);
        update_post_meta($post_id, 'mode', 'onchain');
        update_post_meta($post_id, 'timestamp', $timestamp);
    }

    $link_qr = 'https://sepolia.etherscan.io/tx/' . $tx_hash;

wp_send_json_success(array(
    'message'   => 'Reservasi on-chain berhasil dicatat.',
    'booking_id'=> $booking_id,
    'tx_hash'   => $tx_hash,
    'nama'      => $nama,
    'hotel'     => $hotel,
    'tanggal'   => $tanggal,
    'mode'      => 'onchain',
    'timestamp' => $timestamp,
    'qr'        => $link_qr
));


    die();
}
