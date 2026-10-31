# Boutique E-Commerce Turnkey Template (Blocksy Free + WooCommerce)

A high-performance, open-source boutique fashion e-commerce template designed to replicate the visual aesthetic, stock scarcity mechanics, omnichannel POS sync, and payment/order workflow of [Kaka by Eliza](https://kakabyeliza.com).

Built on top of **Blocksy (100% Free & Open-Source GPL)**, eliminating paid theme licenses while maintaining sub-second performance.

---

## 🌟 Key Architecture & Features

### 1. Aesthetic Design System
- **Base Theme:** [Blocksy Core](https://wordpress.org/themes/blocksy/) (GPLv2 Open-Source).
- **Custom Child Theme:** `blocksy-child-boutique` housing custom design tokens, typography, and boutique UI components.
- **Palette & Typography:** Warm earthy boutique styling (off-white `#FFFDF9`, subtle terracotta accents, charcoal text, elegant serif/sans-serif heading pairing).
- **Interactive UI:** AJAX slide-out mini-cart drawer, secondary image swap on hover, mobile sticky "Add to Cart" bar.

### 2. Omnichannel Stock & Inventory Management
- **In-Store & Online Sync:** WCPOS-compatible REST API integration allowing real-time stock synchronization between physical boutique registers and the online store.
- **Size Variations:** Global size taxonomy (XS, S, M, L, XL, XXL) with strict zero-backorder locking (`backorders_allowed: false`).
- **Stock Scarcity Counter:** Dynamic urgency progress bar (*"Only X left in stock"*) on low-stock items (1–5 units) matching high-converting boutique UX.
- **In-Stock Catalog Filter:** AJAX query filter (`?in-stock=1`) allowing shoppers to browse available inventory instantly.

### 3. Payment & Order Handling (India-Tailored)
- **Payment Gateway:** Cashfree Payments SDK v3 integration with 1-click UPI (GPay, PhonePe, Paytm), Cards, and NetBanking.
- **Email Notifications:** WP Mail SMTP integration for guaranteed inbox delivery of invoices and order confirmations.
- **WhatsApp Alerts:** Automated webhook triggers sending real-time order confirmation and tracking links to customer WhatsApp numbers.
- **Fulfillment & Dispatch:** Standard 2–3 business days processing with domestic tracking lookup.

---

## 📁 Repository Structure

```text
├── BMAD_PLAN.md                 # Full BMAD phased architecture & ticket roadmap
├── README.md                    # Project overview & architectural guide
└── wp-content/
    └── themes/
        └── blocksy-child-boutique/
            ├── style.css        # Custom theme stylesheet & design tokens
            └── functions.php    # Custom hooks, stock bar & WooCommerce customizations
```

---

## 📋 Implementation Roadmap (BMAD Method)

- [ ] **Phase 1: Baseplate & Child Theme Scaffold** (Tokens, styling, header/drawer configuration)
- [ ] **Phase 2: Stock Handling & Inventory Engine** (Variation locking, urgency bar, WCPOS hooks)
- [ ] **Phase 3: Checkout, Payment & Order Handling** (Cashfree UPI, SMTP emails, WhatsApp triggers)
- [ ] **Phase 4: Visual Polish & High-Converting Boutique UX** (Hero slider, category badges, mobile sticky bar)
- [ ] **Phase 5: Hardening, Performance & Client Handover** (LiteSpeed optimization, test orders, documentation)

See [BMAD_PLAN.md](./BMAD_PLAN.md) for detailed epics, stories, and execution steps.
