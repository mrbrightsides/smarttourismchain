//console.log('stc_ajax:', stc_ajax);

async function connectWallet() {
    if (typeof window.ethereum !== 'undefined') {
        const provider = new ethers.providers.Web3Provider(window.ethereum);
        await provider.send("eth_requestAccounts", []);
        return provider.getSigner();
    } else {
        alert("MetaMask belum terpasang.");
        throw new Error("MetaMask tidak tersedia");
    }
}

async function sendToken(toAddress, amount) {
    const signer = await connectWallet();
    const contractAddress = stc_ajax.contract_address;
    const abi = JSON.parse(stc_ajax.contract_abi);

    const tokenContract = new ethers.Contract(contractAddress, abi, signer);

    try {
        const decimals = await tokenContract.decimals();
        const amountWithDecimals = ethers.utils.parseUnits(amount.toString(), decimals);
        const tx = await tokenContract.transfer(toAddress, amountWithDecimals);
        await tx.wait();
        return tx.hash;
    } catch (err) {
        console.error("Gagal kirim token:", err);
        alert("Transaksi gagal saat kirim token.");
        return null;
    }
}

async function makeBooking(bookingId, nama, tanggal, hotel) {
    const signer = await connectWallet();
    const contractAddress = stc_ajax.booking_contract_address;
    const abi = JSON.parse(stc_ajax.booking_contract_abi);

    const bookingContract = new ethers.Contract(contractAddress, abi, signer);

    try {
        const tx = await bookingContract.makeReservation(bookingId, nama, tanggal, hotel);
        await tx.wait();
        return tx.hash;
    } catch (err) {
        console.error("Gagal booking:", err);
        alert("Gagal melakukan reservasi di blockchain.");
        return null;
    }
}

const urlParams = new URLSearchParams(window.location.search);
document.querySelector('#nama').value = urlParams.get('nama') || "";
document.querySelector('#hotel').value = urlParams.get('hotel') || "";
document.querySelector('input[name="tanggal"]').value = urlParams.get('tanggal') || "";
const metode = urlParams.get('payment');
if (metode === "onchain") {
    document.querySelector('input[value="onchain"]').checked = true;
} else {
    document.querySelector('input[value="offchain"]').checked = true;
}

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('#stc-booking-form');
    if (!form) {
        console.warn("Form #stc-booking-form tidak ditemukan");
        return;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const metode = document.querySelector('input[name="payment"]:checked')?.value;
        const nama = document.querySelector('#nama')?.value;
        const tanggal = document.querySelector('input[name="tanggal"]')?.value;
        const hotel = document.querySelector('#hotel')?.value;
        const bookingId = "BKG-" + Date.now();

        if (!nama || !tanggal || !hotel) {
            alert("Semua field wajib diisi.");
            return;
        }

        if (metode === 'onchain') {
            const tujuan = "0x17A1e4875c125ad6e89388d9F042A361499495Da"; // Ganti sesuai wallet tujuan
            const jumlah = 10;

            const tokenTx = await sendToken(tujuan, jumlah);
            if (!tokenTx) return;

            const bookingTx = await makeBooking(bookingId, nama, tanggal, hotel);
            if (!bookingTx) return;

            const formData = new FormData();
            formData.append('action', 'stc_record_onchain');
            formData.append('booking_id', bookingId);
            formData.append('nama', nama);
            formData.append('tanggal', tanggal);
            formData.append('hotel', hotel);
            formData.append('tx_hash', bookingTx);

            try {
                const response = await fetch(stc_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.data.message + "\nTxHash: " + bookingTx);

                    if (result.data?.qr) {
                        const qrContainer = document.getElementById("qrcode");

                    if (!qrContainer) {
                        console.warn("Elemen #qrcode tidak ditemukan di halaman.");
                        alert("Reservasi berhasil, tapi tidak bisa menampilkan QR karena elemen #qrcode tidak tersedia.");
                        return;
                }

                qrContainer.innerHTML = "";
                new QRCode(qrContainer, result.data.qr);


                        const instruction = document.createElement("p");
                        instruction.textContent = "Silakan tunjukkan kode QR ini ke petugas hotel saat check-in.";
                        instruction.style.marginTop = "15px";
                        instruction.style.fontSize = "16px";
                        instruction.style.fontWeight = "500";
                        instruction.style.color = "#333";
                        qrContainer.appendChild(instruction);

                        const etherscanLink = document.createElement("a");
                        etherscanLink.href = `https://sepolia.etherscan.io/tx/${bookingTx}`;
                        etherscanLink.textContent = "Lihat transaksi di Etherscan";
                        etherscanLink.target = "_blank";
                        etherscanLink.style.display = "block";
                        etherscanLink.style.marginTop = "10px";
                        etherscanLink.style.color = "#007bff";
                        etherscanLink.style.fontWeight = "bold";
                        qrContainer.appendChild(etherscanLink);
                    }
                } else {
                    alert("Gagal mencatat booking on-chain.");
                }

                console.log("🧾 Response backend:", result);
            } catch (e) {
                console.error("❌ Gagal parse JSON:", e);
                alert("Terjadi kesalahan saat proses konfirmasi.");
            }
        } else {
            // OFFCHAIN MODE
            const formData = new FormData(form);
            formData.append('action', 'stc_offchain');

            try {
                const response = await fetch(stc_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                alert(result.data?.message || 'Reservasi berhasil (off-chain).');

                if (result.data?.qr) {
                    const qrContainer = document.getElementById("qrcode");
                    qrContainer.innerHTML = "";
                    new QRCode(qrContainer, result.data.qr);

                    const instruction = document.createElement("p");
                    instruction.textContent = "Silakan tunjukkan kode QR ini ke petugas hotel saat check-in.";
                    instruction.style.marginTop = "15px";
                    instruction.style.fontSize = "16px";
                    instruction.style.fontWeight = "500";
                    instruction.style.color = "#333";
                    qrContainer.appendChild(instruction);
                    // Tidak perlu link Etherscan di sini
                }

            } catch (e) {
                console.error("❌ Error saat proses off-chain:", e);
                alert("Terjadi kesalahan saat proses off-chain.");
            }
        }
    });

});
