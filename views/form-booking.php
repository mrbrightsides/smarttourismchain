<form id="stc-booking-form">
    <label>Nama:</label><br>
    <input type="text" id="nama" name="nama" required><br>

    <label>Hotel:</label><br>
    <input type="text" id="hotel" name="hotel" required><br>

    <label>Tanggal:</label><br>
    <input type="date" name="tanggal" required><br><br>

    <label>Metode Pembayaran:</label><br>
    <input type="radio" name="payment" value="offchain" checked> Off-Chain
    <input type="radio" name="payment" value="onchain"> On-Chain<br><br>

    <button type="submit">Pesan Sekarang</button>
</form>
<div id="hasilKonfirmasi"></div>
<div id="qrcode" style="margin-top: 20px; text-align: center;">
  <!-- QR Code akan muncul di sini -->
</div>

