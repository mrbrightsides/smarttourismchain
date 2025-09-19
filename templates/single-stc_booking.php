<?php echo get_header(); ?>

<div class="container" style="max-width: 700px; margin: 40px auto; font-family: sans-serif;">
    <?php $txhash = get_post_meta(get_the_ID(), 'txhash', true); ?>
    <h1 style="margin-bottom: 10px;"><?php the_title(); ?></h1>

    <div class="booking-details" style="background: #f8f8f8; padding: 20px; border-radius: 10px;">
        <p><strong><?php esc_html_e( 'Nama:', 'smarttourismchain' ); ?></strong> 
        <?php echo esc_html( get_post_meta( get_the_ID(), 'nama', true ) ); ?></p>

        <p><strong><?php esc_html_e( 'Hotel:', 'smarttourismchain' ); ?></strong> 
        <?php echo esc_html( get_post_meta( get_the_ID(), 'hotel', true ) ); ?></p>

        <p><strong><?php esc_html_e( 'Tanggal:', 'smarttourismchain' ); ?></strong> 
        <?php echo esc_html( get_post_meta( get_the_ID(), 'tanggal', true ) ); ?></p>

        <p><strong><?php esc_html_e( 'TxHash:', 'smarttourismchain' ); ?></strong>
            <a href="https://sepolia.etherscan.io/tx/<?php echo esc_attr( $txhash ); ?>" target="_blank">
                <?php echo esc_html( $txhash ); ?>
            </a>
        </p>

        <p><strong><?php esc_html_e( 'QR Link:', 'smarttourismchain' ); ?></strong></p>

        <?php if ( ! empty( $txhash ) && $txhash !== '-' && strtolower( $txhash ) !== 'null' ) : ?>
            <div id="qrcode"></div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
            <script>
                var qrcode = new QRCode(document.getElementById("qrcode"), {
                    text: "https://sepolia.etherscan.io/tx/<?php echo $txhash; ?>",
                    width: 128,
                    height: 128
                });
            </script>
        <?php else : ?>
            <p><?php esc_html_e( '—', 'smarttourismchain' ); ?></p>
        <?php endif; ?>

        <p><strong><?php esc_html_e( 'Mode:', 'smarttourismchain' ); ?></strong> 
        <?php echo esc_html( get_post_meta( get_the_ID(), 'mode', true ) ); ?></p>

        <p><strong><?php esc_html_e( 'Timestamp:', 'smarttourismchain' ); ?></strong> 
        <?php echo esc_html( get_post_meta( get_the_ID(), 'timestamp', true ) ); ?></p>
    </div>
</div>

<?php get_footer(); ?>