function hasilKonfirmasi(data) {
    const container = document.getElementById("hasilKonfirmasi");
    if (!container) {
        console.error("❌ Elemen hasilKonfirmasi tidak ditemukan di halaman.");
        return;
    }

    container.innerHTML = `
        <h3>✅ Reservasi Berhasil!</h3>
        <p><strong>Booking ID:</strong> ${data.booking_id}</p>
        <p><strong>Nama:</strong> ${data.nama}</p>
        <p><strong>Hotel:</strong> ${data.hotel}</p>
        <p><strong>Tanggal:</strong> ${data.tanggal}</p>
        <p><strong>Mode:</strong> ${data.mode}</p>
        <p><strong>Tx Hash:</strong> ${data.tx_hash || '-'}</p>
        <div id="qrcode"></div>
    `;

    // Tambahkan QR code dengan sedikit delay agar div sudah ada di DOM
    setTimeout(() => {
    const qrTarget = document.getElementById("qrcode");
    if (qrTarget) {
        const qr = qrcode(0, 'L'); // typeNumber, errorCorrectionLevel
        qr.addData(data.booking_id);
        qr.make();
        qrTarget.innerHTML = qr.createImgTag(4); // 4 = pixel size
    } else {
        console.error("❌ Elemen #qrcode tidak ditemukan.");
    }
}, 100);
}