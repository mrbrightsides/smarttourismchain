<form id="stcdrk-booking-form">
    <label><?php esc_html_e( 'Nama:', 'smarttourismchain' ); ?></label><br>
    <input type="text" id="nama" name="nama" required><br>

    <label><?php esc_html_e( 'Hotel:', 'smarttourismchain' ); ?></label><br>
    <input type="text" id="hotel" name="hotel" required><br>

    <label><?php esc_html_e( 'Tanggal:', 'smarttourismchain' ); ?></label><br>
    <input type="date" name="tanggal" required><br><br>

    <label><?php esc_html_e( 'Metode Pembayaran:', 'smarttourismchain' ); ?></label><br>
    <input type="radio" name="payment" value="offchain" checked> <?php esc_html_e( 'Off-Chain', 'smarttourismchain' ); ?>
    <input type="radio" name="payment" value="onchain"> <?php esc_html_e( 'On-Chain', 'smarttourismchain' ); ?><br><br>

    <button type="submit"><?php esc_html_e( 'Pesan Sekarang', 'smarttourismchain' ); ?></button>
</form>

<div id="hasilKonfirmasi"></div>

<div id="qrcode" style="margin-top: 20px; text-align: center;">
  <!-- QR Code akan muncul di sini -->
</div>