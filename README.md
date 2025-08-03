# SmartWisataChain

**Secure by design, simple by intention.**

![License](https://img.shields.io/github/license/elpeef/smartwisatachain)
![Build](https://img.shields.io/badge/build-passing-brightgreen)
![Contributions](https://img.shields.io/badge/contributions-welcome-blue)

SmartWisataChain is a WordPress plugin that enables dual-mode (on-chain/off-chain) booking transactions using Ethereum smart contracts and MetaMask. It’s built for real-world CMS systems like WordPress and WooCommerce to empower decentralized services for tourism, e-commerce, and more.

🔗 Smart Contract Repo: [smarttourism (Solidity)](https://github.com/mrbrightsides/smarttourism)  
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
   git clone https://github.com/ELPEEF/smartwisatachain.git
2. Upload the folder to your WordPress installation under wp-content/plugins/.
3. Activate the plugin via WP Admin → Plugins.
4. Open SmartWisataChain Settings:
- Input your contract address
- Paste the ABI JSON
- Put them into form token or booking mode or both
5. Use the shortcode below on any page or post:
  [smartwisata_booking]
6. Connect MetaMask in frontend and start testing!

---

## 🤝 Contributing
We welcome contributions! To get started:

Read our Contribution Guide on https://github.com/ELPEEF/swc-docs.git

or open an issue on GitHub Issues

---

## 🧾 License
This project is licensed under the GNU General Public License v3.0.

Use it, improve it, and share it freely. ❤️

Built with 🔗 by ELPEEF Dev Team

---

## 🙌 Support
You can support SmartWisataChain [here](./README-support.md) to help us keep building awesome tools.

---

# 🛠️ SmartWisataChain System Roadmap

Adapted from modern website development flow, this roadmap documents the key stages in the design, implementation, and deployment of the SmartWisataChain system — a blockchain-integrated reservation plugin for the tourism sector.

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

## ## 4. 🎨 Design

- Designing the UI for reservation and admin panels
- Creating landing page and token logo
- Branding "SmartWisataChain" and "RANTAI"
- Data-informed design decisions based on insights from user behavior, keyword trends, and tourism service gaps

📊 Source data collected via:
  - Web scraping from local OTA platforms
  - Crawling UMKM and hotel directories
  - Result --> Exploratory Data Analysis (EDA) at: [mrbrightsides/dashboard-EDA](https://github.com/mrbrightsides/dashboard-EDA)

**📌 Output:** UI kit, page templates, data-driven feature prioritization, visualized data

---

## 5. 📝 Content

- Writing technical documentation in `swc-docs`
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

**📌 Output:** Plugin v1.0 (SmartWisataChain), simulation portal

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
- Submitting papers to IJSDEP and IRICT 2025

**📌 Output:** Public release, academic publications, open access tools

---

## 9. 🔧 Maintenance

- Updating plugin based on user feedback
- Adding advanced features (NFT tickets, DAO voting, API hooks)
- Field testing with real UMKM and tourism offices
- Exploring multi-chain and Layer-2 scalability

**📌 Output:** Plugin v1.x, updated roadmap, community engagement

<img width="588" height="881" alt="image" src="https://github.com/user-attachments/assets/37f7b0cb-b801-4d10-913a-7b101930a867" />

---

> This adapted roadmap is aligned with Design Science Research Methodology (DSRM), ensuring that every development stage contributes to a rigorous, problem-driven, and impact-oriented innovation process.
