<?php echo get_header(); ?>

<div class="container" style="max-width: 700px; margin: 40px auto; font-family: sans-serif;">
    <?php $txhash = get_post_meta(get_the_ID(), 'txhash', true); ?>
  <h1 style="margin-bottom: 10px;"><?php the_title(); ?></h1>

  <div class="booking-details" style="background: #f8f8f8; padding: 20px; border-radius: 10px;">
    <p><strong>Nama:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'nama', true)); ?></p>
    <p><strong>Hotel:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'hotel', true)); ?></p>
    <p><strong>Tanggal:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'tanggal', true)); ?></p>
    <p><strong>TxHash:</strong>
      <a href="https://sepolia.etherscan.io/tx/<?php echo esc_attr(get_post_meta(get_the_ID(), 'txhash', true)); ?>" target="_blank">
        <?php echo esc_html(get_post_meta(get_the_ID(), 'txhash', true)); ?>
      </a>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
      <p><strong>QR Link:</strong></p>
<?php if (!empty($txhash) && $txhash !== '-' && strtolower($txhash) !== 'null') : ?>
    <div id="qrcode"></div>
    <script>
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "https://sepolia.etherscan.io/tx/<?php echo $txhash; ?>",
            width: 128,
            height: 128
        });
    </script>
<?php else : ?>
    <p>—</p>
<?php endif; ?>
<div id="qrcode" style="text-align: center; margin-top: 20px;"></div>
    </p>
    <p><strong>Mode:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'mode', true)); ?></p>
    <p><strong>Timestamp:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), 'timestamp', true)); ?></p>
  </div>
</div>
<?php get_footer(); ?>
