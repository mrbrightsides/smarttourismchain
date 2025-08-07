<?php
function stc_generate_token_form() {
    ob_start();
?>
    <form id="generateTokenForm">
        <label>Nama Token: <input type="text" id="tokenName" required></label><br>
        <label>Simbol: <input type="text" id="tokenSymbol" required></label><br>
        <label>Total Supply: <input type="number" id="tokenSupply" required></label><br>
        <button type="submit">Generate Token</button>
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

