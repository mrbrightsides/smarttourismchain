<?php
// Block direct access
if (!defined('ABSPATH')) {
    exit;
}

// === Handler untuk booking off-chain ===
add_action('wp_ajax_stc_offchain', 'handle_stc_offchain');
add_action('wp_ajax_nopriv_stc_offchain', 'handle_stc_offchain');

function handle_stc_offchain() {
    $nama    = isset($_POST['nama']) ? sanitize_text_field($_POST['nama']) : '';
    $hotel   = isset($_POST['hotel']) ? sanitize_text_field($_POST['hotel']) : '';
    $tanggal = isset($_POST['tanggal']) ? sanitize_text_field($_POST['tanggal']) : '';
    $payment = isset($_POST['payment']) ? sanitize_text_field($_POST['payment']) : '';

    // Simpan ke log file (opsional)
    $log_dir = plugin_dir_path(__FILE__) . '/../logs/';
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $log_file = $log_dir . 'offchain-log.txt';
    $log_data = date('Y-m-d H:i:s') . " - Nama: $nama, Hotel: $hotel, Tanggal: $tanggal, Payment: $payment\n";
    file_put_contents($log_file, $log_data, FILE_APPEND);

    // Buat ID booking & timestamp
    $booking_id = 'OFF-' . time();
    $timestamp = current_time('mysql');

    // Simpan ke post_type: stc_booking
    $post_id = wp_insert_post(array(
        'post_title' => "$booking_id – $nama",
        'post_type'   => 'stc_booking',
        'post_status' => 'publish',
    ));

    if ($post_id && !is_wp_error($post_id)) {
        update_post_meta($post_id, 'nama', $nama);
        update_post_meta($post_id, 'tanggal', $tanggal);
        update_post_meta($post_id, 'hotel', $hotel);
        update_post_meta($post_id, 'payment', $payment);
        update_post_meta($post_id, 'mode', 'offchain');
        update_post_meta($post_id, 'timestamp', $timestamp);

        error_log("✅ Booking berhasil disimpan: ID $post_id");

        wp_send_json_success(array(
    'message' => esc_html__( 'Booking berhasil disimpan ke sistem.', 'smarttourismchain' ),
    'post_id' => $post_id,
));

    } else {
        error_log("❌ Gagal simpan post: " . print_r($post_id, true));
        wp_send_json_error( esc_html__( 'Gagal simpan booking.', 'smarttourismchain' ) );
    }
}