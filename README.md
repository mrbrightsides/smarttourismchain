# SmartTourismChain

**Secure by design, simple by intention.**

![License](https://img.shields.io/github/license/elpeef/smarttourismchain)
![Build](https://img.shields.io/badge/build-passing-brightgreen)
![Contributions](https://img.shields.io/badge/contributions-welcome-blue)
[![DOI](https://zenodo.org/badge/DOI/10.5281/zenodo.16763720.svg)](https://doi.org/10.5281/zenodo.16763720)

SmartTourismChain helps local tourism actors **simulate and prepare for real blockchain-based booking systems**, without spending real ETH or risking real assets.

You can:
- Experience **real smart contract interaction** using Ethereum Sepolia testnet  
- **Customize and extend** the plugin for your own business logic  
- Use it for **training, demos, thesis, or pilot projects**  
- Deploy on mainnet or Layer 2 with minor adjustments
🔗 Smart Contract Repo: [smartourism (Solidity)](https://github.com/mrbrightsides/smartourism)  
📘 Documentation: [smartourism.elpeef.com/docs](https://smartourism.elpeef.com/docs)

---

## 🧩 Features
- Ethereum + MetaMask integration
- On-chain and off-chain transaction modes
- Shortcode-based booking form
- Admin panel for contract config
- AJAX/JS Web3.js integration
- Compatible with WooCommerce (extensible)

---

## 📦 Installation
1. Clone this repository:
   ```bash
   git clone https://github.com/ELPEEF/smarttourismchain.git
2. Upload the folder to your WordPress installation under wp-content/plugins/.
3. Activate the plugin via WP Admin → Plugins.
4. Open SmartTourismChain Settings:
- Input your contract address
- Paste the ABI JSON
- Put them into form token or booking mode or both
5. Use the shortcode below on any page or post:
  [smartwisata_booking]
6. Connect MetaMask in frontend and start testing!

---

## 💬 Frequently Asked Questions

### Is this plugin connected to real blockchain payments?  
No. The current version runs on Ethereum **Sepolia testnet**, allowing you to simulate transactions **safely and freely**.  
You can deploy your own smart contract to **mainnet or Layer 2** when you're ready.

### Why should I install a testnet plugin?  
Because this plugin:  
- Prepares you for **real blockchain adoption**
- Enables **hands-on experience** with Web3 in tourism  
- Can be **extended to production use** with your own token, contract, and payment flow

### Can I use my own smart contract?  
Yes. The system is flexible. You can insert your own smart contract address and ABI for full customization.

### Is there a PRO version?  
Yes, a PRO version with **white-label features**, **API access**, and **real token integration** is available at [https://smartourism.elpeef.com](https://smartourism.elpeef.com)

---

## 🤝 Contributing
We welcome contributions! To get started:

Read our Contribution Guide on https://github.com/ELPEEF/stc-docs.git

or open an issue on GitHub Issues

---

## 🧾 License
This project is licensed under the GNU General Public License v3.0.

Use it, improve it, and share it freely. ❤️

Built with 🔗 by ELPEEF Dev Team

---

## 🙌 Support
You can support SmartTourismChain [here](./README-support.md) to help us keep building awesome tools.

---

# 🛠️ SmartTourismChain System Roadmap

Adapted from modern website development flow, this roadmap documents the key stages in the design, implementation, and deployment of the SmartTourismChain system — a blockchain-integrated reservation plugin for the tourism sector.

---

## 1. 🧭 Discovery

- Researching local tourism and UMKM ecosystem
- Identifying OTA inefficiencies and trust gaps
- Literature review on Blockchain, Smart Contracts, and CMS
- Platform selection: Ethereum (Sepolia) + WordPress
- Initial DSRM problem formulation and scope

**📌 Output:** Problem definition, personas, use case draft

---

## 2. ✏️ Prototyping

- Creating system flow wireframes (on-chain/off-chain)
- Designing reservation process and whitelist mechanism
- Initial smart contract draft using Remix IDE
- Planning user journey and admin experience

**📌 Output:** UX mockups, initial smart contract, interaction flow

---

## 3. 🧱 Architecture

- Defining plugin folder structure
- Integrating contract ABI and address into frontend/backend
- Splitting contracts: `SmartTourismToken.sol` and `SmartReservation.sol`
- Planning IoT integration and data pipelines

**📌 Output:** Modular architecture, data flow diagrams, API maps

---

## 4. 🎨 Design

- Designing the UI for reservation and admin panels
- Creating landing page and token logo
- Branding "SmartTourismChain" and "RANTAI"
- Data-informed design decisions based on insights from user behavior, keyword trends, and tourism service gaps

📊 Source data collected via:
  - Web scraping from local OTA platforms
  - Crawling UMKM and hotel directories
  - Result --> Exploratory Data Analysis (EDA) at: [mrbrightsides/dashboard-EDA](https://github.com/mrbrightsides/dashboard-EDA)

**📌 Output:** UI kit, page templates, data-driven feature prioritization, visualized data

---

## 5. 📝 Content

- Writing technical documentation in `stc-docs`
- Educational narrative for RANTAI landing page
- Creating Blockchain Enterprise class material
- GitHub Classroom starter templates

**📌 Output:** Docs site, content modules, course-ready repos

---

## 6. 🧑‍💻 Development

- Building the WordPress plugin with Web3.js and AJAX
- Implementing on-chain & off-chain reservation logic
- Logging transactions (txHash, timestamp, etc.)
- Verifying booking via QR and wallet address

**📌 Output:** Plugin v1.0 (SmartTourismChain), simulation portal

---

## 7. 🧪 Testing

- Running test transactions on Sepolia Testnet
- Gas fee analysis and performance benchmarking
- Security and logic validation
- Feedback from supervisor and pilot users

**📌 Output:** Evaluation report, bug fixes, ready-to-deploy code

---

## 8. 🚀 Deployment

- Publishing plugin to WordPress repository
- Hosting documentation via GitHub Pages
- Launching [https://smartourism.elpeef.com](https://smartourism.elpeef.com)
- Submitting papers to IMED and IRICT end of 2025

**📌 Output:** Public release, academic publications, open access tools

---

## 9. 🔧 Maintenance

- Updating plugin based on user feedback
- Adding advanced features (NFT tickets, DAO voting, API hooks)
- Field testing with real UMKM and tourism offices
- Exploring multi-chain and Layer-2 scalability

**📌 Output:** Plugin v1.x, updated roadmap, community engagement

STC Architecture from scraped dataset to on-chain transactions
<img width="589" height="726" alt="work flow STC" src="https://github.com/user-attachments/assets/c9b550f6-6cf7-4c94-97b7-2086bef4b8f1" />

---

> This adapted roadmap is aligned with Design Science Research Methodology (DSRM), ensuring that every development stage contributes to a rigorous, problem-driven, and impact-oriented innovation process.
