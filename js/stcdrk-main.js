document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("stcdrk-booking-form");
  const resultDiv = document.getElementById("stcdrk-result");

  if (!form || !resultDiv) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    const nama = formData.get("nama");
    const tanggal = formData.get("tanggal");
    const payment = formData.get("payment");

    if (!nama || !tanggal) {
      alert("Nama dan tanggal wajib diisi.");
      return;
    }

    if (payment === "offchain") {
      const res = await fetch(stcdrk_ajax.ajax_url, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          action: "stcdrk_offchain",
          nama,
          tanggal,
        }),
      });

      const data = await res.json();
      resultDiv.innerHTML = data.success ? data.data : "❌ " + data.data;
      return;
    }

    // On-Chain flow
    try {
      if (!window.ethereum) {
        alert("Metamask tidak terdeteksi.");
        return;
      }

      await window.ethereum.request({ method: "eth_requestAccounts" });

      const provider = new ethers.providers.Web3Provider(window.ethereum);
      const signer = provider.getSigner();

      const contractAddress = stcdrk_ajax.contract_address;
      const abi = JSON.parse(stcdrk_ajax.contract_abi);
      const contract = new ethers.Contract(contractAddress, abi, signer);

      console.log("📝 Booking:", nama, tanggal);

      const tx = await contract.recordBooking(nama, tanggal, {
        gasLimit: 300000,
      });

      const receipt = await tx.wait();
      const txHash = receipt.transactionHash;

      console.log("📦 Response dari tx.wait():", receipt);

      // Simpan ke backend
      const simpan = await fetch(stcdrk_ajax.ajax_url, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
          action: "stcdrk_onchain",
          nama,
          tanggal,
          txhash: txHash,
        }),
      });

      const simpanRes = await simpan.json();
      console.log("📦 Response dari simpan ke backend:", simpanRes);

      if (simpanRes.success) {
        showSuccess(txHash);
      } else {
        resultDiv.innerHTML = "✅ Transaksi berhasil, tapi gagal simpan ke backend.";
      }

    } catch (error) {
      console.error("❌ Gagal menyimpan booking ke blockchain:", error);
      resultDiv.innerHTML = "❌ Gagal menyimpan booking ke blockchain.";
    }
  });
});

// 🔽 Fungsi tampilkan hasil + QR Code
function showSuccess(txHash) {
  const resultDiv = document.getElementById("stcdrk-result");
  const etherscanUrl = `https://sepolia.etherscan.io/tx/${txHash}`;

  resultDiv.innerHTML = `
    <h3>✅ Transaksi Berhasil</h3>
    <p><strong>Tx Hash:</strong> <a href="${etherscanUrl}" target="_blank">${txHash}</a></p>
    <div id="qrcode" style="margin-top: 15px;"></div>
    <p>Silakan tunjukkan QR ini ke petugas hotel untuk verifikasi transaksi di Etherscan.</p>
  `;

  // ✅ Gunakan constructor QRCode (BUKAN toCanvas)
  setTimeout(() => {
    try {
      new QRCode(document.getElementById("qrcode"), {
        text: etherscanUrl,
        width: 200,
        height: 200,
      });
      console.log("✅ QR Code berhasil dibuat:", etherscanUrl);
    } catch (err) {
      console.error("❌ Gagal generate QR Code:", err);
    }
  }, 200);

}
