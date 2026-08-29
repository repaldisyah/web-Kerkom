# 📊 Nusa Karsa Event - Sistem Manajemen Pembayaran

Aplikasi web internal untuk manajemen piutang dan pencatatan pembayaran event dari berbagai cabang Nusa Karsa Event. Dibangun dengan PHP PDO backend dan JavaScript vanilla frontend dengan role-based access control.

**Status:** Production-ready (dengan security improvements)
**Tech Stack:** PHP 7.4+, MySQL 5.7+, HTML5, CSS3, JavaScript (ES6+)
**Authentication:** Session-based with role-based access control (RBAC)

---

## 📋 Table of Contents
1. [Quick Start](#quick-start)
2. [Struktur Proyek](#struktur-proyek)
3. [Database Schema](#database-schema)
4. [API Endpoints](#api-endpoints)
5. [Frontend Pages](#frontend-pages)
6. [User Roles & Permissions](#user-roles--permissions)
7. [Setup & Installation](#setup--installation)
8. [Security Guidelines](#security-guidelines)
9. [Troubleshooting](#troubleshooting)

---

## 🚀 Quick Start

### Minimum Requirements
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache dengan mod_rewrite enabled
- Web server (XAMPP, WAMP, atau setup lokal lainnya)

### 5-Minute Setup
```bash
# 1. Setup database
- Jalankan Apache dan MySQL melalui XAMPP Control Panel
- Akses phpMyAdmin (http://localhost/phpmyadmin)
- Buat database baru bernama "nusa_karsa"
- Import file database/nusa_karsa.sql

# 2. Konfigurasi aplikasi
- Copy api/config.example.php menjadi api/config.php
- Edit api/config.php dengan kredensial MySQL Anda:
  $DB_HOST = 'localhost';
  $DB_USER = 'root';
  $DB_PASS = '';
  $DB_NAME = 'nusa_karsa';

# 3. Jalankan aplikasi
- Buka browser: http://localhost/web-Kerkom/
- Login dengan akun default (lihat Database Schema)
```

---

## 📁 Struktur Proyek

```
web-Kerkom/
│
├── 📄 halaman.html                    # Dashboard utama (landing page)
├── 📄 login.html                      # Halaman login redirect
├── 📄 loading.html                    # Loading screen transisi
├── 📄 README.md                       # Dokumentasi ini
│
├── 📁 api/                            # BACKEND - REST API Endpoints
│   ├── bootstrap.php                  # Core functions & utilities (CRITICAL)
│   ├── config.php                     # Database credentials (LOCAL ONLY)
│   ├── config.example.php             # Template config
│   ├── session.php                    # [GET] Check authentication status
│   ├── login.php                      # [POST] User login
│   ├── logout.php                     # [GET] User logout
│   ├── dashboard.php                  # [GET] Summary & recent transactions
│   ├── branches.php                   # [GET] List all branches
│   ├── customers.php                  # [GET] List customers (filtered by role)
│   ├── receivables.php                # [GET] List active invoices
│   ├── payments.php                   # [POST] Record new payment
│   ├── payment-history.php            # [GET] Payment history (filtered by role)
│   └── reports.php                    # [GET] Yearly reports (admin only)
│
├── 📁 Html/                           # FRONTEND - HTML Pages
│   ├── login.html                     # Form login (direct access)
│   ├── cabang.html                    # Branch management page
│   ├── pelanggan.html                 # Customer list page
│   ├── pembayaran.html                # Payment entry form
│   ├── history.html                   # Payment history page
│   └── laporan.html                   # Yearly report page
│
├── 📁 Js/                             # FRONTEND - JavaScript
│   ├── portal.js                      # Main handler (cabang, pelanggan, history, laporan)
│   ├── pembayaran.js                  # Payment form handler & receipt generator
│   └── navigation.js                  # Navigation menu & page routing
│
├── 📁 Css/                            # FRONTEND - Stylesheets
│   ├── portal.css                     # Styling untuk pages di portal.js
│   ├── pembayaran.css                 # Styling untuk pembayaran.html
│   └── navigation.css                 # Styling untuk navigation menu
│
├── 📁 database/                       # Database setup (⚠️ DO NOT UPLOAD TO SERVER)
│   ├── nusa_karsa.sql                 # Main schema & initial data
│   ├── migration_add_user_branch.sql   # Add branch_id column to users
│   ├── migration_add_customer_account.sql # Add customer_id column to users
│   ├── migration_add_payment_method.sql   # Add payment_method ENUM to payments
│   ├── migration_add_payment_token.sql    # Add payment_token to payments
│   └── README.md                      # Database documentation
│
├── 📁 assets/                         # Static assets
│   ├── logo.jpeg                      # Logo v1
│   ├── logo2.0.png                    # Logo v2
│   └── logo3.0.png                    # Logo v3
│
├── .htaccess                          # Apache security configuration
├── .git/                              # Git repository (DO NOT UPLOAD)
└── .gitignore                         # Git ignore rules
```

### 📌 Penjelasan Folder Penting

| Folder | Tujuan | Production Upload |
|--------|--------|-------------------|
| `api/` | Backend REST endpoints | ✅ YA |
| `Html/` `Js/` `Css/` | Frontend files | ✅ YA |
| `database/` | SQL schema & migrations | ❌ JANGAN (security risk) |
| `.git/` | Git repository | ❌ JANGAN |
| `assets/` | Logo & images | ✅ YA |

---

## 🗄️ Database Schema

### Table Relationships
```
users (1) ──┬──→ (many) branches
            ├──→ (many) customers
            └──→ (many) events

branches (1) ──┬──→ (many) customers
               ├──→ (many) events
               └──→ (many) receivables

customers (1) ──┬──→ (many) events
                └──→ (many) receivables

events (1) ──→ (many) receivables

receivables (1) ──→ (many) payments
```

### Main Tables

#### **users**
| Column | Type | Constraint | Notes |
|--------|------|-----------|-------|
| id | INT | PK, AUTO_INCREMENT | |
| username | VARCHAR(100) | UNIQUE | Login identifier |
| email | VARCHAR(100) | UNIQUE | |
| password_hash | VARCHAR(255) | | bcrypt hash |
| name | VARCHAR(255) | | Full name |
| role | ENUM(...) | | super_admin, admin_cabang, pelanggan |
| branch_id | INT | FK branches.id | Branch assignment |
| customer_id | INT | FK customers.id | For pelanggan role |

**Default Test Account:**
- Username: `admin` / Email: `admin@nusa-karsa.local`
- Password: `admin123` (hash di database)
- Role: `super_admin`

#### **branches**
| Column | Type | Constraint |
|--------|------|-----------|
| id | INT | PK |
| name | VARCHAR(255) | |
| city | VARCHAR(100) | |

#### **customers**
| Column | Type | Constraint |
|--------|------|-----------|
| id | INT | PK |
| branch_id | INT | FK |
| name | VARCHAR(255) | |
| email | VARCHAR(100) | |
| phone | VARCHAR(20) | |
| address | TEXT | |

#### **receivables** (Piutang/Invoice)
| Column | Type | Constraint | Range |
|--------|------|-----------|-------|
| id | INT | PK | |
| branch_id | INT | FK | |
| customer_id | INT | FK | |
| event_id | INT | FK | |
| invoice_date | DATE | | |
| due_date | DATE | | |
| total_amount | DECIMAL(12,2) | CHECK ≥ 0 | |
| balance | DECIMAL(12,2) | CHECK ≥ 0 | |
| status | ENUM(...) | | 'pending', 'partial', 'paid', 'overdue' |

#### **payments**
| Column | Type | Constraint | Notes |
|--------|------|-----------|-------|
| id | INT | PK | |
| receivable_id | INT | FK | Which invoice |
| payment_date | DATE | | |
| amount | DECIMAL(12,2) | CHECK > 0 | Must be > 0 |
| payment_method | ENUM(...) | | QRIS, BRI, BCA, SEABANK, PAYPAL |
| payment_token | VARCHAR(255) | UNIQUE | Auto-generated receipt ID |
| note | VARCHAR(255) | | Payment notes |

### Migrations (Feature Flags)
Aplikasi mendukung gradual schema updates:
```sql
-- migration_add_user_branch.sql        → users.branch_id
-- migration_add_customer_account.sql   → users.customer_id
-- migration_add_payment_method.sql     → payments.payment_method
-- migration_add_payment_token.sql      → payments.payment_token
```

---

## 🔌 API Endpoints

### Base URL
```
http://localhost/web-Kerkom/api/
```

### Response Format
Semua endpoint mengembalikan JSON dengan format:
```json
{
  "success": true/false,
  "message": "Status message",
  "data": { ... }
}
```

### Endpoints Documentation

#### **Authentication**

| Endpoint | Method | Auth | Purpose |
|----------|--------|------|---------|
| `login.php` | POST | ❌ No | Login dengan username/email + password |
| `logout.php` | GET | ✅ Yes | Logout & destroy session |
| `session.php` | GET | ❌ No | Check login status & get user info |

**POST /login.php**
```javascript
Request:
{
  "username": "admin",
  "password": "admin123"
}

Response (Success):
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user_id": 1,
    "username": "admin",
    "name": "Admin",
    "role": "super_admin",
    "branch_id": 1
  }
}
```

#### **Dashboard & Data**

| Endpoint | Method | Auth | Purpose | Role Restriction |
|----------|--------|------|---------|-----------------|
| `dashboard.php` | GET | ✅ | Get summary data | super_admin, admin_cabang |
| `branches.php` | GET | ✅ | List branches | All (except pelanggan) |
| `customers.php` | GET | ✅ | List customers | All |
| `receivables.php` | GET | ✅ | Active invoices | super_admin, admin_cabang |

**GET /dashboard.php?period=monthly**
```json
Response:
{
  "success": true,
  "data": {
    "total_receivables": 50000000,
    "total_payments": 35000000,
    "balance": 15000000,
    "invoice_count": 25,
    "recent_payments": [ ... ]
  }
}
```

#### **Payments**

| Endpoint | Method | Auth | Purpose |
|----------|--------|------|---------|
| `payments.php` | POST | ✅ | Record new payment |
| `payment-history.php` | GET | ✅ | Get payment history |

**POST /payments.php**
```javascript
Request:
{
  "receivable_id": 5,
  "amount": 5000000,
  "payment_method": "QRIS",
  "note": "Pembayaran penuh invoice #INV-001"
}

Response (Success):
{
  "success": true,
  "message": "Pembayaran berhasil dicatat",
  "data": {
    "payment_id": 42,
    "payment_token": "TRX-20240830-ABC123DEF456",
    "amount": 5000000,
    "new_balance": 0,
    "invoice_status": "paid"
  }
}
```

**GET /payment-history.php?customer_id=3&limit=10**
```json
Response:
{
  "success": true,
  "data": [
    {
      "payment_id": 42,
      "payment_date": "2024-08-30",
      "amount": 5000000,
      "payment_method": "QRIS",
      "payment_token": "TRX-20240830-ABC123DEF456",
      "note": "Pembayaran penuh"
    }
  ]
}
```

#### **Reports**

| Endpoint | Method | Auth | Purpose | Role |
|----------|--------|------|---------|------|
| `reports.php` | GET | ✅ | Yearly report | super_admin, admin_cabang |

**GET /reports.php?year=2024&branch_id=1**
```json
Response:
{
  "success": true,
  "data": {
    "year": 2024,
    "branch_id": 1,
    "monthly_summary": [ ... ],
    "top_customers": [ ... ],
    "total_collected": 125000000
  }
}
```

---

## 🎨 Frontend Pages

### Page Overview

| Page | File | Purpose | Accessible Roles |
|------|------|---------|-----------------|
| **Login** | Html/login.html | User authentication | All |
| **Dashboard** | halaman.html | Summary & recent activity | All (authenticated) |
| **Branch** | Html/cabang.html | Branch data management | super_admin, admin_cabang |
| **Customer** | Html/pelanggan.html | Customer list & details | All |
| **Payment** | Html/pembayaran.html | Payment entry form | All |
| **History** | Html/history.html | Payment history | All |
| **Report** | Html/laporan.html | Yearly reports | super_admin, admin_cabang |

### Frontend Validation

| Input | Validation |
|-------|-----------|
| Username/Email | Required, trimmed |
| Password | Required, min 6 chars |
| Amount | Numeric, > 0, ≤ invoice balance |
| Payment Method | Whitelist: QRIS, BRI, BCA, SEABANK, PAYPAL |
| Note | Max 255 characters |
| Date fields | Valid date format (YYYY-MM-DD) |

### JavaScript Modules

#### **navigation.js**
- Dynamic menu injection berdasarkan role
- Page transition dengan loading screen
- Session status checking
- Logout handler

#### **portal.js**
- Table rendering untuk cabang, pelanggan, history, laporan
- Role-based content hiding
- Currency formatting (IDR)
- Search & filter functionality

#### **pembayaran.js**
- Invoice selection & loading
- Real-time balance calculation
- Amount validation
- Payment method selection
- Receipt generation dengan payment_token
- WhatsApp sharing integration
- Print & copy token functions

---

## 👥 User Roles & Permissions

### Role Matrix

#### **super_admin** (Administrator)
```
Permissions:
✅ View semua branch
✅ View semua customer
✅ View semua receivable
✅ Record pembayaran untuk semua invoice
✅ View payment history lengkap
✅ Access dashboard & reports
✅ Manage semua data

Restriction: NONE
```

#### **admin_cabang** (Branch Manager)
```
Permissions:
✅ View branch sendiri + customers
✅ View receivable branch sendiri saja
✅ Record pembayaran branch sendiri
✅ View payment history branch sendiri
✅ Access dashboard & reports (branch-filtered)
❌ Edit customer data
❌ View branch lain

Restriction: branch_id = session.branch_id
```

#### **pelanggan** (Customer)
```
Permissions:
✅ View profil data sendiri
✅ View payment history sendiri
✅ Record pembayaran sendiri (via payments.php)
❌ View branch data
❌ View customer lain
❌ Edit apapun

Restriction: customer_id = session.customer_id
```

### Creating New User Accounts

```sql
-- Super Admin
INSERT INTO users (username, email, password_hash, name, role, branch_id)
VALUES ('admin2', 'admin2@nusa-karsa.local', '$2y$10$...hash...', 'Admin 2', 'super_admin', NULL);

-- Branch Manager (untuk branch_id = 2)
INSERT INTO users (username, email, password_hash, name, role, branch_id)
VALUES ('manager1', 'manager@cabang1.local', '$2y$10$...hash...', 'Manager Cabang 1', 'admin_cabang', 2);

-- Customer Account (untuk customer_id = 5)
INSERT INTO users (username, email, password_hash, name, role, branch_id, customer_id)
VALUES ('customer1', 'customer@email.com', '$2y$10$...hash...', 'Customer Name', 'pelanggan', NULL, 5);
```

**Generate password hash:**
```php
// Use in PHP terminal or web scratchpad
echo password_hash('password123', PASSWORD_BCRYPT);
```

---

## 🔧 Setup & Installation

### Step 1: Database Setup
```bash
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create new database: "nusa_karsa"
3. Select database
4. Go to Import tab
5. Upload file: database/nusa_karsa.sql
6. Click Import
```

### Step 2: Configuration
```bash
1. Navigate ke api/ folder
2. Copy config.example.php → config.php
3. Edit config.php dengan MySQL credentials:

$DB_HOST = 'localhost';     # MySQL server
$DB_USER = 'root';          # MySQL user
$DB_PASS = '';              # MySQL password (default kosong di XAMPP)
$DB_NAME = 'nusa_karsa';    # Database name
```

### Step 3: Verify Installation
```bash
1. Start Apache & MySQL (XAMPP Control Panel)
2. Open: http://localhost/web-Kerkom/
3. Login dengan:
   - Username: admin
   - Password: admin123
4. Should see Dashboard with data
```

### Step 4: Database Migrations (if needed)
```bash
1. Check if tables have required columns
2. If missing, import migration files in order:
   - migration_add_user_branch.sql
   - migration_add_customer_account.sql
   - migration_add_payment_method.sql
   - migration_add_payment_token.sql
3. Test each endpoint after migration
```

---

## 🔒 Security Guidelines

### ⚠️ CRITICAL SECURITY NOTES

1. **NEVER upload `config.php` to version control**
   - Contains database password
   - Already in .gitignore (verify!)

2. **NEVER upload `database/` folder to production server**
   - Contains SQL schema (information disclosure)
   - Should be imported once during setup

3. **HTTPS is REQUIRED for production**
   - Add to .htaccess:
   ```apache
   RewriteCond %{HTTPS} off
   RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

4. **Add CSRF protection before deployment**
   - Current implementation lacks CSRF tokens
   - Add token to form submissions
   - Validate on backend

5. **Implement rate limiting on login**
   - Prevent brute force attacks
   - Max 5 attempts per IP per hour

6. **Change default admin password after setup**
   - Current: admin123 (for testing only)
   - Use strong password: min 12 chars + special chars

7. **Add security headers to .htaccess**
   ```apache
   Header set X-Content-Type-Options "nosniff"
   Header set X-Frame-Options "DENY"
   Header set X-XSS-Protection "1; mode=block"
   Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'"
   ```

8. **Enable error logging, not display**
   ```php
   // In config.php or bootstrap.php
   ini_set('display_errors', 0);
   ini_set('log_errors', 1);
   ini_set('error_log', '/var/log/php-errors.log');
   ```

9. **Regular database backups**
   - Export nusa_karsa.sql weekly
   - Store in secure location

10. **Monitor payment_token for duplicates**
    - Ensure unique payment receipts
    - Check database for duplicate tokens

### User Input Validation
- ✅ Server-side validation mandatory (not just frontend)
- ✅ Whitelist payment methods (ENUM)
- ✅ Validate numeric fields (amounts)
- ✅ Validate email format
- ✅ Trim whitespace from input

### Session Security
- ✅ Session ID regenerated on login
- ✅ Sessions stored securely
- ⚠️ Add custom session timeout (currently 24 min default)
- ⚠️ Add "session expiring" warning

### Database Security
- ✅ Using PDO with prepared statements (prevents SQL injection)
- ✅ No direct string concatenation in queries
- ✅ Password stored as bcrypt hash
- ⚠️ No database user accounts with SELECT * privilege

---

## 🐛 Troubleshooting

### Common Issues & Solutions

#### **1. "Koneksi database gagal" Error**
```
Penyebab: 
- MySQL tidak running
- Wrong credentials di config.php
- Database tidak ada

Solusi:
1. Check MySQL status di XAMPP Control Panel
2. Verify credentials: DB_HOST, DB_USER, DB_PASS, DB_NAME
3. Check phpMyAdmin - buka http://localhost/phpmyadmin
4. Ensure database "nusa_karsa" exists
```

#### **2. Login page redirect loop**
```
Penyebab:
- Session tidak tersimpan
- PHP session folder tidak writable

Solusi:
1. Check php.ini: session.save_path pointing to writable dir
2. Linux/Mac: chmod 777 /tmp (or configured session path)
3. Windows: Verify folder permissions
4. Restart Apache
```

#### **3. Payment not saved**
```
Penyebab:
- Transaction failed
- Insufficient permission
- Database constraint violated

Solusi:
1. Check browser console (F12) for error
2. Check server error log
3. Verify receivable_id exists
4. Verify amount ≤ balance
5. Check user role has permission
```

#### **4. "Access Denied" on specific page**
```
Penyebab:
- User role tidak have permission
- Wrong role-based filtering

Solusi:
1. Login dengan super_admin account
2. Check user.role in database
3. Verify branch_id matching untuk admin_cabang
4. Check API endpoint access control
```

#### **5. Payment history showing "No results"**
```
Penyebab:
- Filter query wrong
- Empty payment records
- Access control filtering

Solusi:
1. Check database: SELECT * FROM payments LIMIT 10;
2. Verify receivable exists
3. Logout & login as different role
4. Check filter parameters di UI
```

#### **6. Receipt token not generating**
```
Penyebab:
- Column payments.payment_token missing
- Migration not applied

Solusi:
1. Check database: ALTER TABLE payments ADD COLUMN payment_token VARCHAR(255) UNIQUE;
2. Run migration_add_payment_token.sql
3. Verify column exists: DESCRIBE payments;
```

### Debug Mode
Untuk enable debugging:
```php
// Edit api/bootstrap.php
define('DEBUG', true);

// Then check browser console & server logs
```

### View Server Logs
```bash
# Windows (XAMPP):
C:\xampp\apache\logs\error.log
C:\xampp\mysql\data\[hostname].err

# Linux/Mac:
tail -f /var/log/apache2/error.log
tail -f /var/log/mysql/error.log
```

---

## 📞 Support & Contact

Untuk pertanyaan atau issues:
1. Check troubleshooting section di atas
2. Check server logs
3. Review database schema (database/README.md)
4. Contact development team

---

## 📝 License & Notes

- **Version:** 1.0
- **Last Updated:** 2024-08-30
- **Developed for:** Nusa Karsa Event
- **Internal Use Only:** Jangan share credentials atau database files

---

## 🔄 Changelog

### Version 1.0 (Initial Release)
- ✅ Login & authentication system
- ✅ Dashboard dengan summary data
- ✅ Payment recording dengan receipt token
- ✅ Payment history tracking
- ✅ Role-based access control
- ✅ Yearly reports
- ✅ Multi-branch support

### Known Limitations
- ❌ No CSRF protection (add before production)
- ❌ No rate limiting on login
- ❌ No 2FA authentication
- ❌ No audit logging
- ❌ No pagination on large datasets

### Future Roadmap
- [ ] CSRF token implementation
- [ ] Rate limiting & brute force protection
- [ ] Email notification on payment
- [ ] SMS reminder untuk overdue invoices
- [ ] Dashboard analytics & charts
- [ ] Mobile app version
- [ ] API key-based authentication (for third-party integration)
