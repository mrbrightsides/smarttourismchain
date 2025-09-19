<?php
function stc_generate_token_form() {
    ob_start();
?>
    <form id="generateTokenForm">
        <label>
            <?php esc_html_e( 'Token Name:', 'smarttourismchain' ); ?>
            <input type="text" id="tokenName" required>
        </label><br>

        <label>
            <?php esc_html_e( 'Symbol:', 'smarttourismchain' ); ?>
            <input type="text" id="tokenSymbol" required>
        </label><br>

        <label>
            <?php esc_html_e( 'Total Supply:', 'smarttourismchain' ); ?>
            <input type="number" id="tokenSupply" required>
        </label><br>

        <button type="submit">
            <?php esc_html_e( 'Generate Token', 'smarttourismchain' ); ?>
        </button>
    </form>

    <div id="generateTokenResult"></div>

    <!-- Load ethers.js dari CDN -->
    <script src="https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.umd.min.js"></script>

    <!-- Perbaikan di path JS lokal -->
    <script src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'js/generate-token.js'; ?>"></script>
<?php
    return ob_get_clean();
}
add_shortcode('stc_generate_token', 'stc_generate_token_form');