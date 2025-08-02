async function connectWallet() {
    if (typeof window.ethereum !== 'undefined') {
        const provider = new ethers.providers.Web3Provider(window.ethereum);
        await provider.send("eth_requestAccounts", []);
        return provider.getSigner();
    } else {
        alert("MetaMask belum terpasang.");
    }
}

async function sendToken(toAddress, amount) {
    const signer = await connectWallet();
    const contractAddress = swc_ajax.contract_address;
    const abi = JSON.parse(swc_ajax.contract_abi);

    const tokenContract = new ethers.Contract(contractAddress, abi, signer);

    try {
        const decimals = await tokenContract.decimals();
        const amountWithDecimals = ethers.utils.parseUnits(amount.toString(), decimals);
        const tx = await tokenContract.transfer(toAddress, amountWithDecimals);
        await tx.wait();
        alert("Transaksi berhasil.");
    } catch (err) {
        console.error("Gagal kirim token:", err);
        alert("Transaksi gagal.");
    }
}

async function makeBooking(bookingId, nama, tanggal, hotel) {
    const signer = await connectWallet();
    const contractAddress = swc_ajax.booking_contract_address;
    const abi = JSON.parse(swc_ajax.booking_contract_abi);

    const bookingContract = new ethers.Contract(contractAddress, abi, signer);

    try {
        const tx = await bookingContract.makeReservation(bookingId, nama, tanggal, hotel);
        await tx.wait();

// Kirim txHash ke backend
const postData = new FormData();
postData.append('action', 'swc_onchain');
postData.append('nama', nama);
postData.append('tanggal', tanggal);
postData.append('txhash', tx.hash);

const response = await fetch(swc_ajax.ajax_url, {
    method: 'POST',
    body: postData
});

const result = await response.json();
if (result.success) {
    alert("Booking on-chain berhasil dicatat.");
} else {
    alert("Booking sukses, tapi gagal simpan ke server: " + result.data);
}

        alert("Booking berhasil. Hash: " + tx.hash);
    } catch (err) {
        console.error("Gagal booking:", err);
        alert("Gagal melakukan reservasi.");
    }
}


document.querySelector('#swc-booking-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const metode = document.querySelector('input[name="payment"]:checked').value;

    const nama = document.querySelector('#nama').value;
    const tanggal = document.querySelector('#tanggal').value;
    const hotel = document.querySelector('#hotel').value;

    if (metode === 'onchain') {
        const tujuan = "0x17A1e4875c125ad6e89388d9F042A361499495Da";
        const jumlah = 10;

        // 1. Kirim token
        await sendToken(tujuan, jumlah);

        // 2. Simpan booking on-chain
        const bookingId = "BKG-" + Date.now();
        await makeBooking(bookingId, nama, tanggal, hotel);
    } else {
        const formData = new FormData(e.target);
        formData.append('action', 'swc_offchain');

        const response = await fetch(swc_ajax.ajax_url, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        alert(result.data || 'Sukses.');
    }
});

document.querySelector('#swc-result').textContent = "Mohon tunggu, memproses...";

if (!confirm(`Kamu yakin ingin booking ke hotel ${hotel} pada ${tanggal}?`)) return;