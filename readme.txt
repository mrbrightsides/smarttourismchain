=== SmartTourismChain ===
Contributors: khudri
Tags: tourism, smart contract, blockchain, booking, decentralization
Requires at least: 5.5
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Plugin URI: https://smartourism.elpeef.com/
Author URI: https://elpeef.com/
Author: ELPEEF Dev Team

SmartTourismChain helps local tourism actors simulate and prepare for real blockchain-based booking systems, without spending real ETH or risking real assets.

== Description ==

With this plugin, you can:

- Experience real smart contract interaction using testnet Sepolia

- Customize and extend the plugin for your own business logic

- Use it for training, demos, thesis, or pilot projects

When you're ready, the same code can be migrated to mainnet or Layer 2 with just a few tweaks

== Main features: ==

* Direct booking without intermediaries
* Transaction simulation on the Sepolia Ethereum testnet
* Easy WordPress integration via shortcode
* SmartTourismChain branding (free version)
* Open-source and extensible

== Installation ==

1. Upload the `SmartTourismChain` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the *Plugins* menu in WordPress.
3. Use the shortcode `[smartwisata_booking]` to display the booking button.

== Frequently Asked Questions ==

1. Is this plugin connected to real blockchain payments?
No. The current version runs on Ethereum **Sepolia testnet**, allowing you to simulate transactions **safely and freely**.  
You can deploy your own smart contract to **mainnet or Layer 2** when you're ready.

2. Why should I install a testnet plugin?
Because this plugin:  
- Prepares you for **real blockchain adoption**
- Enables **hands-on experience** with Web3 in tourism  
- Can be **extended to production use** with your own token, contract, and payment flow

3. Can I use my own smart contract?
Yes. The system is flexible. You can insert your own smart contract address and ABI for full customization.

4. Is there a PRO version?
Yes, a PRO version with **white-label features**, **API access**, and **real token integration** is available at https://smartourism.elpeef.com

== External Dependencies ==

This plugin uses the following external JavaScript libraries via CDN:
* `ethers.js` from JSDelivr (https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.umd.min.js)
* `qrcode.min.js` from Cloudflare (https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js)

These libraries are essential to support wallet connection (MetaMask) and QR code generation functionalities. If required by the plugin repository guidelines, these files can be bundled locally.

== Screenshots ==

1. Booking button interface
2. MetaMask wallet connection popup
3. Transaction simulation on testnet

== Changelog ==

= 1.0.0 =
* Initial release of SmartTourismChain plugin (free version)
* Supports smart contract-based reservation (testnet only)

== Upgrade Notice ==

= 1.0.0 =
Initial version. Please backup your site before upgrading in future releases.

== License ==

SmartTourismChain is released under the GNU GPL v3 license.

== Credits ==

SmartTourismChain is maintained by [Khudri](https://profiles.wordpress.org/khudri) and the [ELPEEF Dev Team](https://elpeef.com).  
This plugin is part of the [RANTAI](https://rantai.elpeef.com) (Trusted Research for Technology and Integration) initiative to promote Web3 adoption in the tourism sector.

Last updated: 2025-08-08
