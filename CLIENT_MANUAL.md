# 📖 Client Operations Manual: Boutique Commerce Store

Welcome to your boutique e-commerce platform. This website is tailored for high-converting boutique sales with in-store synchronization, real-time stock scarcity counters, and seamless UPI payments.

---

## 1. 👗 How to Add a New Boutique Dress / Kurthi

Every piece in your boutique typically has multiple sizes (XS, S, M, L, XL, XXL) with limited inventory.

1. In WordPress Admin, go to **Products → Add New**.
2. Enter the Product Title (e.g., *Marigold Peplum Top* or *Amber Leaf Cotton Kurthi*).
3. Set the **Product Category** (e.g., *Kurthis*, *Peplum Tops*, *Dresses*, or *In Store*).
4. Under **Product Data**, select **Variable product** from the dropdown.
5. Go to the **Attributes** tab:
   * Select **Size** and click **Add**.
   * Click **Select all** (or pick the sizes you have in stock: S, M, L, etc.).
   * Check the box: **"Used for variations"** and click **Save attributes**.
6. Go to the **Variations** tab:
   * Click **Generate variations** (or add individual sizes).
   * For each size:
     * Check **Manage stock?**
     * Enter **Stock quantity** (e.g., `1` or `2`).
     * Set **Allow backorders?** to **"Do not allow"** (this prevents overselling).
     * Enter the **Regular price** (e.g., `1450`).
7. Upload the **Product Image** and add secondary angles in the **Product Gallery** (for the automatic hover-swap effect).
8. Click **Publish**.

> 💡 **Automatic Urgency Bar:** If you set a size quantity between `1` and `5`, the website automatically displays the *"🔥 Only X left in stock"* progress bar to encourage fast checkout!

---

## 2. 🏪 How In-Store & Boutique POS Sync Works

If you have a physical counter/shop:
1. Open the **WCPOS** app or web register on your counter tablet / PC.
2. When a walk-in customer buys an item in person, scan the barcode or tap the product in the register.
3. Complete the sale at the counter.
4. **Immediate Sync:** The online store instantly updates and reduces the stock by 1. If that was the last piece, the website immediately marks that size as **Sold Out**, protecting you from double-selling!

---

## 3. 💳 Cashfree Payment Gateway Setup (UPI & Cards)

To receive customer payments directly into your Indian bank account:
1. Log in to your [Cashfree Merchant Dashboard](https://merchant.cashfree.com).
2. Go to **Developers → API Keys**.
3. In WordPress Admin, go to **WooCommerce → Settings → Payments → Cashfree**.
4. Paste your **App ID** and **Secret Key**.
5. Save changes.
6. Customers will now see instant UPI options (Google Pay, PhonePe, Paytm, BHIM) and Credit/Debit cards at checkout.

---

## 4. 📲 WhatsApp Order Confirmations

When a customer completes an order:
1. Go to **Settings → Boutique WhatsApp Notifications**.
2. Enter your support WhatsApp Number (e.g. `9198XXXXXXXX`).
3. If using an automated WhatsApp provider (AiSensy, Interakt, or Wati), paste your webhook URL into the **WhatsApp Webhook URL** field.
4. Customers will automatically receive real-time order updates with their Order ID and tracking link!

---

## 5. 📦 Order Fulfillment & Tracking

1. When a new order arrives, its status will be **Processing**.
2. Pack the parcel within **2–3 business days**.
3. Generate the courier label (via Shiprocket, Delhivery, or your local courier).
4. Paste the Tracking Number into the order notes and change the order status to **Completed**.
5. Customers can track their order anytime on the `/track-order/` page using their Order ID.
