# 🛒 POS (Point of Sale) & Integrated Warehouse Management System

An enterprise-grade Micro-ERP platform featuring an integrated **Point of Sale (POS) Cashier System**, **Real-Time Warehouse Inventory Tracker**, and a dynamic **Credit Management System**. Built strictly from the ground up using clean, optimized **Procedural PHP Native** and **PDO Database Drivers** to exhibit pure logical architectural understanding without reliance on high-level frameworks.

---

## 🌟 Key Functional Architecture

### 1. Point of Sale (POS) Cashier Module
* **Transaction Engine:** Lightweight cashier dashboard handling multi-item ordering via unique product codes.
* **String-Flattening Array Architecture:** Uses a custom performance-optimized string manipulation pattern (`explode` & `implode` with `.` delimiters) to serialize multi-item data fields (`code`, `name`, `price`, `quantity`, `subtotal`) inside a single transactional record.
* **Automated Change Calculation:** Computes instant change summaries for Cash operations and blocks execution if the submitted money is insufficient.
* **Flexible Payment Channels:** Supports modern hybrid retail flows including Cash, Bank Transfers (TF), Credit/Installments, and Credit-TF options.

### 2. Multi-Tier Warehouse & Inventory Management
* **Inventory Logs:** Automated double-entry logging into primary storage and incoming logistics trackers (`masuk`) whenever stock values are manipulated.
* **Real-Time Over-Quantity Validation:** Instantly halts cashier checkouts if requested sales volumes exceed current physical warehouse balances.
* **Dynamic Stock Level Thresholding:** UI tables implement visual color status warnings (🟢 Safe Stock, 🟡 Low Stock warning thresholds, and 🔴 Critical Stock depletion) to alert operators.

### 3. Dynamic Credit Management & Financial System
* **Custom Financial Parameters:** Secure administrative dashboard to adjust Down Payment (DP) minimum requirements, installment interest percentages, and vendor account numbers dynamically.
* **Installment Logic Matrix:** Automatically computes complex credit financing matrices—calculating precise Down Payments, compounding interest structures over configurable tenors, and amortized fixed installment values.
* **Risk Management & Overdue Indicators:** Monitors payment delays against timestamps; triggers instant status flags, alerts, and distinct color codes for bad/stagnant credit lines ("Kredit Macet").
* **Aggregated Analytical Reports:** Built-in calculation matrices to compile day-to-day revenue streams, month-over-month comparisons, and annualized financial growth charts.

---

## 🔒 Advanced Security Implementation

* **SQL Injection Immunity:** All critical application checkpoints utilize **PDO (PHP Data Objects) Prepared Statements** with explicit token bindings.
* **Cryptographic Security:** Administrative and staff credentials secured using strong `SHA-256` cryptographic hashing.
* **CSRF & Reload Protection:** Instantly prevents duplicate records or form re-submission attacks on crucial parameters by injecting random cryptographically secure runtime tokens via `bin2hex(random_bytes(32))` linked to active sessions.
* **Multi-Tier Authorization Matrix:** Role-based access gates segregating Super-User Administrators (Full Access) from Cashiers (Limited Operational Privileges).

---

## 🛠️ Technical Specifications

* **Backend Engine:** Pure PHP Native (PHP 8.x Compatible)
* **Database Driver:** Database-agnostic PDO (PHP Data Objects) Layer
* **Database Management System:** MySQL / MariaDB
* **UI Design Engine:** Custom Dynamic UI/UX Architecture via CSS Variables and Structured DOM manipulation.

---

## 🗄️ Database Architecture & Relations (ERD)

Aplikasi ini menggunakan arsitektur database relasional yang saling mengunci secara real-time untuk sinkronisasi stok gudang, antrean kasir, laporan keuangan, hingga manajemen risiko kredit macet.

Berikut adalah bagan visual interaktif relasi antar-tabel (Entity Relationship Diagram) yang akan otomatis di-render oleh GitHub:

<pre class="mermaid">
erDiagram
    USER ||--o{ PENJUALAN : "operates"
    USER {
        string user PK
        string password
        string email
        string type
    }
    BUAH ||--o{ MASUK : "tracks_inbound"
    BUAH ||--o{ PENJUALAN : "staged_in"
    BUAH {
        string kode PK
        string nama
        int stok
        int instok
        int price
        longblob foto
    }
    MASUK {
        int id PK
        string kode FK
        int jumlah
        string tgl
    }
    PENJUALAN {
        int id PK
        string inv
        string kode FK
        int kuantitas
        string pembeli
        string item_flattened
    }
    PAYMENT {
        string inv PK
        string kasir
        string pembeli
        int total
        int bayar
        int kembalian
        string via
        string tgl
    }
    KREDIT {
        string inv PK
        string kasir
        string pembeli
        int dp
        int sisa
        int bunga
        int tenor
        int angsuran
        string tgl
        string status
    }
    SETTINGS {
        int id PK
        int dp
        int bunga
        string rek
    }
</pre>

---

## 📦 Deployment & Local Replication

1. **Clone the Repository & Checkout to Branch:**
   
   git clone [https://github.com/yandrien/yandrien.my.id.git](https://github.com/yandrien/yandrien.my.id.git)
   cd yandrien.my.id
   git checkout toko

2. **Database Schema Injection:**

	- Open your Database Administrator Panel (e.g., phpMyAdmin).
	- Create a database instance named yandrien_ci4tutorial.
	- Import the corresponding application structural .sql dump file.

3. Establish Environment Connectivity:

	Modify your database connector configuration inside dbon.php to align with your local host credentials (User, Password, Hostname).

4. Boot Up Services:

	- Move the repository directory into your local server root (e.g., C:/xampp/htdocs/) or execute via local PHP build server.
	- Navigate to http://localhost/isales/login.php on your browser to sign in.