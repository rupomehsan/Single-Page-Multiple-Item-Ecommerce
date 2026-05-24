# Database Documentation - Shefa Natural Food E-Commerce

এই ডকুমেন্টটি Shefa Natural Food এর সম্পূর্ণ ডাটাবেস স্ট্রাকচার বর্ণনা করে।

---

## 📋 Table of Contents
1. [Products Table](#products-table)
2. [Orders Table](#orders-table)
3. [Order Items Table](#order-items-table)
4. [Customers Table](#customers-table)
5. [Payments Table](#payments-table)
6. [Inventory Table](#inventory-table)
7. [Reviews Table](#reviews-table)
8. [FAQs Table](#faqs-table)
9. [Promotional Codes Table](#promotional-codes-table)
10. [Delivery Areas Table](#delivery-areas-table)
11. [Admin Users Table](#admin-users-table)

12. [CMS Content Management](#cms-tables)
    - [12.1 Page Sections](#page-sections-table)
    - [12.2 Hero Section](#hero-section-table)
    - [12.3 Benefit Cards](#benefit-cards-table)
    - [12.4 How It Works Steps](#how-it-works-table)
    - [12.5 Testimonials](#testimonials-table)
    - [12.6 Banners/Sliders](#banners-table)
    - [12.7 Site Settings](#site-settings-table)
    - [12.8 Media Files](#media-table)

---

## 1. Products Table {#products-table}

পণ্যের সকল তথ্য সংরক্ষণ করে।

```sql
CREATE TABLE products (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL COMMENT 'পণ্যের নাম (বাংলা/ইংরেজি)',
    slug VARCHAR(255) UNIQUE NOT NULL COMMENT 'URL slug',
    description LONGTEXT COMMENT 'বিস্তারিত বিবরণ',
    price DECIMAL(10, 2) NOT NULL COMMENT 'বিক্রয় মূল্য',
    cost_price DECIMAL(10, 2) COMMENT 'ক্রয় মূল্য',
    discount_price DECIMAL(10, 2) COMMENT 'ছাড়প্রাপ্ত মূল্য',
    discount_percentage INT DEFAULT 0 COMMENT 'ছাড়ের শতাংশ',
    category VARCHAR(100) COMMENT 'পণ্যের ক্যাটাগরি (মধু, হানিনাট)',
    icon LONGTEXT COMMENT 'Emoji বা আইকন',
    sku VARCHAR(100) UNIQUE COMMENT 'Stock Keeping Unit',
    weight DECIMAL(8, 3) COMMENT 'ওজন (গ্রাম)',
    unit VARCHAR(50) COMMENT 'পরিমাপের একক (গ্রাম, কেজি)',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'পণ্য সক্রিয় আছে কিনা',
    is_featured BOOLEAN DEFAULT FALSE COMMENT 'বিশেষ হিসেবে প্রদর্শন',
    ingredients LONGTEXT COMMENT 'উপাদান তালিকা (JSON format)',
    benefits LONGTEXT COMMENT 'স্বাস্থ্য উপকারিতা (JSON format)',
    storage_instructions VARCHAR(255) COMMENT 'সংরক্ষণ নির্দেশনা',
    expiry_months INT COMMENT 'মেয়াদ উত্তীর্ণ (মাসে)',
    source_location VARCHAR(255) COMMENT 'উৎস স্থান (দিনাজপুর, নাটোর)',
    lab_certified BOOLEAN DEFAULT TRUE COMMENT 'ল্যাব টেস্টেড সার্টিফাইড',
    certified_by VARCHAR(255) COMMENT 'কোন ল্যাব দ্বারা সার্টিফাইড',
    certification_date DATE COMMENT 'সার্টিফিকেশন তারিখ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL COMMENT 'Soft delete (তথ্য সংরক্ষিত থাকে)',
    INDEX idx_category (category),
    INDEX idx_is_active (is_active),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='পণ্য তালিকা';
```

### Fields Description:
- **id**: প্রতিটি পণ্যের অনন্য পরিচয়
- **price**: ক্রেতাকে প্রদর্শিত মূল্য
- **cost_price**: আমাদের ক্রয় মূল্য (লাভ ক্যালকুলেশন)
- **discount_price**: বিশেষ অফারে ছাড়ের মূল্য
- **category**: বিভিন্ন ধরনের মধু আলাদা করতে
- **is_featured**: হোমপেজে প্রধান পণ্য হিসেবে দেখাতে

---

## 2. Orders Table {#orders-table}

প্রতিটি অর্ডারের তথ্য সংরক্ষণ করে।

```sql
CREATE TABLE orders (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_number VARCHAR(50) UNIQUE NOT NULL COMMENT 'অনন্য অর্ডার নম্বর (ORD-2025-XXXXX)',
    customer_id BIGINT UNSIGNED COMMENT 'গ্রাহক ID (users table থেকে)',
    customer_name VARCHAR(255) NOT NULL COMMENT 'গ্রাহকের নাম',
    customer_phone VARCHAR(20) NOT NULL COMMENT 'গ্রাহকের ফোন নম্বর',
    customer_email VARCHAR(255) COMMENT 'গ্রাহকের ইমেইল',
    delivery_address LONGTEXT NOT NULL COMMENT 'ডেলিভারির ঠিকানা',
    delivery_area_id INT UNSIGNED COMMENT 'ডেলিভারি এলাকা ID',
    delivery_district VARCHAR(100) COMMENT 'জেলা',
    delivery_thana VARCHAR(100) COMMENT 'থানা',
    delivery_village VARCHAR(100) COMMENT 'গ্রাম/এলাকা',
    delivery_date DATE COMMENT 'প্রত্যাশিত ডেলিভারি তারিখ',
    delivered_date DATE COMMENT 'প্রকৃত ডেলিভারি তারিখ',
    subtotal DECIMAL(12, 2) NOT NULL COMMENT 'পণ্যের মোট মূল্য',
    shipping_charge DECIMAL(10, 2) DEFAULT 0 COMMENT 'শিপিং চার্জ',
    discount_amount DECIMAL(10, 2) DEFAULT 0 COMMENT 'ছাড়ের পরিমাণ',
    promo_code_used VARCHAR(100) COMMENT 'ব্যবহৃত প্রমোশনাল কোড',
    total_amount DECIMAL(12, 2) NOT NULL COMMENT 'চূড়ান্ত মোট (সাবটোটাল + শিপিং - ছাড়)',
    payment_method VARCHAR(50) COMMENT 'পেমেন্ট পদ্ধতি (COD, bKash, Nagad)',
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending' COMMENT 'পেমেন্ট অবস্থা',
    order_status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned') DEFAULT 'pending' COMMENT 'অর্ডার অবস্থা',
    special_notes LONGTEXT COMMENT 'বিশেষ নোট/কমেন্ট',
    admin_notes LONGTEXT COMMENT 'ম্যানেজমেন্টের জন্য অভ্যন্তরীণ নোট',
    ip_address VARCHAR(45) COMMENT 'অর্ডারকারীর IP',
    source ENUM('website', 'mobile', 'whatsapp', 'phone') DEFAULT 'website' COMMENT 'অর্ডারের উৎস',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_number (order_number),
    INDEX idx_customer_phone (customer_phone),
    INDEX idx_order_status (order_status),
    INDEX idx_payment_status (payment_status),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (delivery_area_id) REFERENCES delivery_areas(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='অর্ডার তালিকা';
```

### Fields Description:
- **order_number**: মানুষ-বান্ধব অর্ডার নম্বর
- **order_status**: পুরো অর্ডার প্রক্রিয়া ট্র্যাক করতে
- **payment_status**: পেমেন্ট সফল হয়েছে কিনা
- **source**: কিভাবে অর্ডার এসেছে তা জানতে

---

## 3. Order Items Table {#order-items-table}

প্রতিটি অর্ডারে কোন কোন পণ্য আছে তা সংরক্ষণ করে।

```sql
CREATE TABLE order_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL COMMENT 'অর্ডার ID',
    product_id BIGINT UNSIGNED NOT NULL COMMENT 'পণ্য ID',
    product_name VARCHAR(255) NOT NULL COMMENT 'পণ্যের নাম (ঐতিহাসিক রেকর্ডের জন্য)',
    quantity INT NOT NULL COMMENT 'পরিমাণ',
    unit_price DECIMAL(10, 2) NOT NULL COMMENT 'প্রতিটি পণ্যের মূল্য',
    total_price DECIMAL(12, 2) NOT NULL COMMENT 'মোট = quantity * unit_price',
    discount_per_item DECIMAL(10, 2) DEFAULT 0 COMMENT 'প্রতিটি পণ্যে ছাড়',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='অর্ডারের আইটেমগুলি';
```

---

## 4. Customers Table {#customers-table}

গ্রাহক তথ্য ব্যবস্থাপনা (users table এর সাথে যুক্ত)।

```sql
CREATE TABLE customers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED UNIQUE COMMENT 'ব্যবহারকারী ID (users table থেকে)',
    phone_number VARCHAR(20) UNIQUE NOT NULL COMMENT 'মোবাইল নম্বর',
    address LONGTEXT COMMENT 'বাড়ির ঠিকানা',
    district VARCHAR(100) COMMENT 'জেলা',
    thana VARCHAR(100) COMMENT 'থানা',
    village VARCHAR(100) COMMENT 'গ্রাম',
    total_orders INT DEFAULT 0 COMMENT 'মোট অর্ডার সংখ্যা',
    total_spent DECIMAL(12, 2) DEFAULT 0 COMMENT 'মোট খরচ',
    lifetime_discount DECIMAL(10, 2) DEFAULT 0 COMMENT 'আজীবন ছাড় পেয়েছেন',
    loyalty_points INT DEFAULT 0 COMMENT 'লয়্যালটি পয়েন্ট',
    is_verified BOOLEAN DEFAULT FALSE COMMENT 'ফোন নম্বর যাচাইকৃত',
    last_order_date DATE COMMENT 'সর্বশেষ অর্ডারের তারিখ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_phone_number (phone_number),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='গ্রাহক তথ্য';
```

---

## 5. Payments Table {#payments-table}

সমস্ত পেমেন্ট লেনদেন ট্র্যাক করতে।

```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL COMMENT 'অর্ডার ID',
    transaction_id VARCHAR(100) UNIQUE COMMENT 'ব্যাংক/গেটওয়ে লেনদেন ID',
    payment_method VARCHAR(50) NOT NULL COMMENT 'পদ্ধতি (bKash, Nagad, COD, SSL, etc)',
    payment_gateway VARCHAR(100) COMMENT 'পেমেন্ট গেটওয়ে নাম',
    amount DECIMAL(12, 2) NOT NULL COMMENT 'পেমেন্টের পরিমাণ',
    currency VARCHAR(5) DEFAULT 'BDT' COMMENT 'মুদ্রা',
    status ENUM('pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending' COMMENT 'পেমেন্ট অবস্থা',
    reference_number VARCHAR(100) COMMENT 'রেফারেন্স নম্বর',
    merchant_id VARCHAR(100) COMMENT 'মার্চেন্ট ID',
    customer_phone VARCHAR(20) COMMENT 'পেমেন্টকারীর নম্বর (মোবাইল ব্যাংকিং)',
    paid_at TIMESTAMP NULL COMMENT 'পেমেন্ট সফল সময়',
    verified_at TIMESTAMP NULL COMMENT 'পেমেন্ট যাচাইকৃত সময়',
    notes LONGTEXT COMMENT 'পেমেন্ট সম্পর্কিত নোট',
    response_data LONGTEXT COMMENT 'পেমেন্ট গেটওয়ের সম্পূর্ণ রেসপন্স (JSON)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_status (status),
    INDEX idx_transaction_id (transaction_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='পেমেন্ট লেনদেন';
```

---

## 6. Inventory Table {#inventory-table}

স্টক ব্যবস্থাপনা।

```sql
CREATE TABLE inventory (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED NOT NULL COMMENT 'পণ্য ID',
    stock_quantity INT NOT NULL DEFAULT 0 COMMENT 'বর্তমান স্টক',
    reserved_quantity INT DEFAULT 0 COMMENT 'বুকিং করা স্টক (pending orders)',
    available_quantity INT GENERATED ALWAYS AS (stock_quantity - reserved_quantity) STORED COMMENT 'বিক্রয়ের জন্য উপলব্ধ',
    minimum_stock INT DEFAULT 10 COMMENT 'ন্যূনতম স্টক স্তর',
    reorder_quantity INT DEFAULT 50 COMMENT 'পুনরায় অর্ডার করার পরিমাণ',
    warehouse_location VARCHAR(255) COMMENT 'গুদাম স্থান',
    batch_number VARCHAR(100) COMMENT 'পণ্যের ব্যাচ নম্বর',
    expiry_date DATE COMMENT 'মেয়াদ উত্তীর্ণ তারিখ',
    received_date DATE COMMENT 'সরবরাহকারী থেকে পাওয়া তারিখ',
    supplier_id INT UNSIGNED COMMENT 'সরবরাহকারী ID',
    cost_per_unit DECIMAL(10, 2) COMMENT 'প্রতিটি ইউনিটের খরচ',
    total_cost DECIMAL(12, 2) GENERATED ALWAYS AS (stock_quantity * cost_per_unit) STORED COMMENT 'মোট খরচ',
    last_counted_at TIMESTAMP NULL COMMENT 'শেষ স্টক গণনার তারিখ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_product_id (product_id),
    INDEX idx_available_quantity (available_quantity),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='পণ্য স্টক ব্যবস্থাপনা';
```

---

## 7. Reviews Table {#reviews-table}

গ্রাহক রিভিউ এবং রেটিং।

```sql
CREATE TABLE reviews (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    order_id BIGINT UNSIGNED COMMENT 'অর্ডার ID (যদি অর্ডারের জন্য রিভিউ)',
    product_id BIGINT UNSIGNED NOT NULL COMMENT 'পণ্য ID',
    customer_id BIGINT UNSIGNED COMMENT 'গ্রাহক ID',
    customer_name VARCHAR(255) NOT NULL COMMENT 'রিভিউকারীর নাম',
    customer_location VARCHAR(255) COMMENT 'গ্রাহকের এলাকা (ঢাকা, চট্টগ্রাম, etc)',
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5) COMMENT 'রেটিং (1-5 stars)',
    title VARCHAR(255) COMMENT 'রিভিউ শিরোনাম',
    review_text LONGTEXT COMMENT 'বিস্তারিত রিভিউ',
    verified_purchase BOOLEAN DEFAULT FALSE COMMENT 'যাচাইকৃত ক্রয়',
    is_published BOOLEAN DEFAULT FALSE COMMENT 'প্রকাশিত হয়েছে কিনা (মডারেশনের পর)',
    is_featured BOOLEAN DEFAULT FALSE COMMENT 'হোমপেজে প্রদর্শিত',
    helpful_count INT DEFAULT 0 COMMENT 'কতজন এটি সহায়ক বলেছেন',
    unhelpful_count INT DEFAULT 0 COMMENT 'কতজন অসহায়ক বলেছেন',
    admin_response LONGTEXT COMMENT 'ম্যানেজমেন্টের রেসপন্স',
    admin_response_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_product_id (product_id),
    INDEX idx_rating (rating),
    INDEX idx_verified_purchase (verified_purchase),
    INDEX idx_is_published (is_published),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='পণ্য রিভিউ এবং রেটিং';
```

---

## 8. FAQs Table {#faqs-table}

সচরাচর জিজ্ঞাসিত প্রশ্নগুলি পরিচালনা করতে।

```sql
CREATE TABLE faqs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT UNSIGNED COMMENT 'পণ্য-নির্দিষ্ট FAQ (NULL হলে সাধারণ)',
    question VARCHAR(500) NOT NULL COMMENT 'প্রশ্ন (বাংলায়)',
    answer LONGTEXT NOT NULL COMMENT 'উত্তর (বাংলায়)',
    category VARCHAR(100) COMMENT 'ক্যাটাগরি (উৎপাদন, ডেলিভারি, পেমেন্ট, ইত্যাদি)',
    display_order INT DEFAULT 0 COMMENT 'প্রদর্শনের ক্রম',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'সক্রিয় আছে কিনা',
    view_count INT DEFAULT 0 COMMENT 'কতবার দেখা হয়েছে',
    helpful_count INT DEFAULT 0 COMMENT 'কতজন সহায়ক বলেছেন',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_is_active (is_active),
    INDEX idx_display_order (display_order),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='সচরাচর জিজ্ঞাসিত প্রশ্ন';
```

---

## 9. Promotional Codes Table {#promotional-codes-table}

ডিসকাউন্ট কোড এবং প্রমোশনাল অফার।

```sql
CREATE TABLE promo_codes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL COMMENT 'প্রমোশনাল কোড (SUMMER20)',
    description VARCHAR(255) COMMENT 'কোডের বর্ণনা',
    discount_type ENUM('percentage', 'fixed_amount') COMMENT 'ছাড়ের ধরন',
    discount_value DECIMAL(10, 2) NOT NULL COMMENT 'ছাড়ের পরিমাণ বা শতাংশ',
    max_discount_amount DECIMAL(10, 2) COMMENT 'সর্বোচ্চ ছাড় (যদি percentage)',
    min_order_amount DECIMAL(10, 2) DEFAULT 0 COMMENT 'ন্যূনতম অর্ডার পরিমাণ',
    max_usage INT DEFAULT 999 COMMENT 'সর্বোচ্চ ব্যবহারের সংখ্যা',
    usage_count INT DEFAULT 0 COMMENT 'ব্যবহৃত সংখ্যা',
    usage_per_customer INT DEFAULT 1 COMMENT 'প্রতি গ্রাহক ব্যবহার সীমা',
    applicable_products LONGTEXT COMMENT 'প্রযোজ্য পণ্যগুলি (JSON array)',
    applicable_categories VARCHAR(255) COMMENT 'প্রযোজ্য ক্যাটাগরি',
    valid_from DATE NOT NULL COMMENT 'কার্যকর তারিখ',
    valid_until DATE NOT NULL COMMENT 'মেয়াদ শেষ তারিখ',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'সক্রিয় আছে কিনা',
    created_by INT UNSIGNED COMMENT 'তৈরি করেছেন (admin user ID)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_valid_from (valid_from),
    INDEX idx_valid_until (valid_until),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='প্রমোশনাল কোড এবং ডিসকাউন্ট';
```

---

## 10. Delivery Areas Table {#delivery-areas-table}

ডেলিভারি জোন এবং চার্জ ম্যানেজমেন্ট।

```sql
CREATE TABLE delivery_areas (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    division VARCHAR(100) NOT NULL COMMENT 'বিভাগ (ঢাকা, চট্টগ্রাম, ইত্যাদি)',
    district VARCHAR(100) NOT NULL COMMENT 'জেলা',
    thana VARCHAR(100) NOT NULL COMMENT 'থানা/উপজেলা',
    is_available BOOLEAN DEFAULT TRUE COMMENT 'ডেলিভারি উপলব্ধ',
    delivery_days INT DEFAULT 3 COMMENT 'ডেলিভারি দিন',
    delivery_charge DECIMAL(8, 2) DEFAULT 0 COMMENT 'ডেলিভারি চার্জ',
    free_delivery_minimum DECIMAL(10, 2) DEFAULT 0 COMMENT 'ফ্রি ডেলিভারির ন্যূনতম অর্ডার',
    express_delivery_available BOOLEAN DEFAULT FALSE COMMENT 'এক্সপ্রেস ডেলিভারি উপলব্ধ',
    express_charge DECIMAL(8, 2) COMMENT 'এক্সপ্রেস ডেলিভারি চার্জ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_district (district),
    INDEX idx_thana (thana),
    INDEX idx_is_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ডেলিভারি এলাকা এবং চার্জ';
```

---

## 11. Admin Users Table {#admin-users-table}

অ্যাডমিন ব্যবহারকারী এবং পারমিশন ম্যানেজমেন্ট।

```sql
CREATE TABLE admin_users (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED UNIQUE COMMENT 'users table থেকে',
    email VARCHAR(255) UNIQUE NOT NULL,
    role ENUM('super_admin', 'manager', 'staff', 'viewer') DEFAULT 'staff' COMMENT 'ভূমিকা',
    permissions LONGTEXT COMMENT 'অনুমতি তালিকা (JSON)',
    is_active BOOLEAN DEFAULT TRUE,
    login_count INT DEFAULT 0,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='অ্যাডমিন ব্যবহারকারী';
```

---

## 12. CMS Content Management Tables {#cms-tables}

### 12.1 Page Sections Table {#page-sections-table}

ওয়েবসাইটের বিভিন্ন সেকশন ম্যানেজ করতে।

```sql
CREATE TABLE page_sections (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    page_name VARCHAR(100) NOT NULL COMMENT 'পেজের নাম (home, about, etc)',
    section_name VARCHAR(100) NOT NULL COMMENT 'সেকশনের নাম (hero, benefits, testimonials)',
    section_key VARCHAR(100) UNIQUE NOT NULL COMMENT 'সেকশনের ইউনিক কী (হোম পেজে রেন্ডার করতে)',
    title VARCHAR(255) COMMENT 'সেকশনের শিরোনাম',
    subtitle VARCHAR(255) COMMENT 'সেকশনের সাবটাইটেল',
    description LONGTEXT COMMENT 'বিস্তারিত বিবরণ',
    icon_emoji VARCHAR(10) COMMENT 'সেকশন আইকন (emoji)',
    display_order INT DEFAULT 0 COMMENT 'প্রদর্শনের ক্রম',
    is_visible BOOLEAN DEFAULT TRUE COMMENT 'দৃশ্যমান আছে কিনা',
    background_color VARCHAR(10) COMMENT 'ব্যাকগ্রাউন্ড কালার (HEX)',
    background_image_url VARCHAR(255) COMMENT 'ব্যাকগ্রাউন্ড ইমেজ URL',
    custom_css LONGTEXT COMMENT 'কাস্টম CSS',
    custom_js LONGTEXT COMMENT 'কাস্টম JavaScript',
    metadata LONGTEXT COMMENT 'অতিরিক্ত মেটাডেটা (JSON)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT UNSIGNED COMMENT 'তৈরি করেছেন (admin user ID)',
    updated_by INT UNSIGNED COMMENT 'শেষ সম্পাদনা করেছেন',
    INDEX idx_page_name (page_name),
    INDEX idx_section_key (section_key),
    INDEX idx_is_visible (is_visible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='পেজ সেকশন ম্যানেজমেন্ট';
```

---

### 12.2 Hero Section Table {#hero-section-table}

হোমপেজের হিরো সেকশন কন্টেন্ট।

```sql
CREATE TABLE hero_sections (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(500) NOT NULL COMMENT 'মূল শিরোনাম',
    highlighted_text VARCHAR(500) COMMENT 'হাইলাইট করা টেক্সট (রঙিন অংশ)',
    subtitle VARCHAR(255) COMMENT 'সাব হেডলাইন',
    description LONGTEXT COMMENT 'বিস্তারিত বর্ণনা',
    badge_text VARCHAR(100) COMMENT 'ব্যাজ টেক্সট (✨ ১০০% খাঁটি)',
    badge_icon VARCHAR(50) COMMENT 'ব্যাজ আইকন',
    cta_button_text VARCHAR(100) COMMENT 'CTA বাটন টেক্সট',
    cta_button_link VARCHAR(255) COMMENT 'বাটন লিংক',
    secondary_text VARCHAR(255) COMMENT 'সেকেন্ডারি টেক্সট (ডেলিভারি নোট)',
    image_url VARCHAR(255) COMMENT 'হিরো ইমেজ URL',
    background_gradient_1 VARCHAR(10) COMMENT 'গ্র্যাডিয়েন্ট কালার ১',
    background_gradient_2 VARCHAR(10) COMMENT 'গ্র্যাডিয়েন্ট কালার ২',
    floating_badge_1 VARCHAR(255) COMMENT 'ফ্লোটিং ব্যাজ ১ (রিভিউ স্টার)',
    floating_badge_2 VARCHAR(255) COMMENT 'ফ্লোটিং ব্যাজ ২ (খাঁটি)',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT UNSIGNED,
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='হিরো সেকশন কন্টেন্ট';
```

---

### 12.3 Benefit Cards Table {#benefit-cards-table}

বেনিফিট/ফিচার কার্ডগুলি।

```sql
CREATE TABLE benefit_cards (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL COMMENT 'কার্ডের শিরোনাম',
    description VARCHAR(500) NOT NULL COMMENT 'বিস্তারিত বর্ণনা',
    icon_emoji VARCHAR(10) COMMENT 'আইকন (emoji)',
    icon_color VARCHAR(10) COMMENT 'আইকনের কালার',
    display_order INT DEFAULT 0 COMMENT 'প্রদর্শনের ক্রম',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT UNSIGNED,
    INDEX idx_display_order (display_order),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='বেনিফিট/ফিচার কার্ড';
```

---

### 12.4 How It Works Steps Table {#how-it-works-table}

অর্ডার প্রক্রিয়ার ধাপগুলি।

```sql
CREATE TABLE how_it_works_steps (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    step_number INT NOT NULL COMMENT 'ধাপের সংখ্যা',
    title VARCHAR(255) NOT NULL COMMENT 'ধাপের শিরোনাম',
    description VARCHAR(500) COMMENT 'ধাপের বিস্তারিত বিবরণ',
    icon_emoji VARCHAR(10) COMMENT 'ধাপের আইকন',
    icon_background_color VARCHAR(10) COMMENT 'আইকনের ব্যাকগ্রাউন্ড কালার',
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT UNSIGNED,
    INDEX idx_step_number (step_number),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='কিভাবে অর্ডার করবেন এর ধাপ';
```

---

### 12.5 Testimonials (Dynamic Reviews) Table {#testimonials-table}

ডায়নামিক টেস্টিমোনিয়াল/রিভিউ।

```sql
CREATE TABLE testimonials (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL COMMENT 'গ্রাহকের নাম',
    customer_location VARCHAR(255) COMMENT 'গ্রাহকের এলাকা',
    rating INT DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
    review_text LONGTEXT NOT NULL COMMENT 'রিভিউ টেক্সট',
    customer_image_url VARCHAR(255) COMMENT 'গ্রাহকের ছবি URL',
    is_featured BOOLEAN DEFAULT FALSE COMMENT 'হোমপেজে প্রদর্শিত',
    display_order INT DEFAULT 0 COMMENT 'প্রদর্শনের ক্রম',
    is_active BOOLEAN DEFAULT TRUE,
    from_order_id BIGINT UNSIGNED COMMENT 'যদি প্রকৃত অর্ডার থেকে',
    manual_entry BOOLEAN DEFAULT FALSE COMMENT 'ম্যানুয়ালি এন্ট্রি করা',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT UNSIGNED,
    INDEX idx_is_featured (is_featured),
    INDEX idx_is_active (is_active),
    INDEX idx_display_order (display_order),
    FOREIGN KEY (from_order_id) REFERENCES orders(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='টেস্টিমোনিয়াল এবং রিভিউ';
```

---

### 12.6 Banners/Sliders Table {#banners-table}

প্রচারমূলক ব্যানার এবং স্লাইডার।

```sql
CREATE TABLE banners (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) COMMENT 'ব্যানারের শিরোনাম',
    description LONGTEXT COMMENT 'ব্যানারের বর্ণনা',
    image_url VARCHAR(255) NOT NULL COMMENT 'ব্যানার ইমেজ URL',
    background_color VARCHAR(10) COMMENT 'ব্যাকগ্রাউন্ড কালার',
    cta_button_text VARCHAR(100) COMMENT 'বাটন টেক্সট',
    cta_button_link VARCHAR(255) COMMENT 'বাটনের লিংক',
    banner_type ENUM('hero_banner', 'product_banner', 'promo_banner', 'seasonal') DEFAULT 'promo_banner' COMMENT 'ব্যানারের ধরন',
    display_location VARCHAR(100) COMMENT 'কোথায় প্রদর্শন করবেন',
    start_date DATE NOT NULL COMMENT 'প্রদর্শন শুরুর তারিখ',
    end_date DATE NOT NULL COMMENT 'প্রদর্শন শেষের তারিখ',
    display_order INT DEFAULT 0 COMMENT 'প্রদর্শনের ক্রম',
    is_active BOOLEAN DEFAULT TRUE,
    click_count INT DEFAULT 0 COMMENT 'ক্লিক সংখ্যা',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT UNSIGNED,
    INDEX idx_banner_type (banner_type),
    INDEX idx_display_location (display_location),
    INDEX idx_is_active (is_active),
    INDEX idx_start_date (start_date),
    INDEX idx_end_date (end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='প্রচারমূলক ব্যানার';
```

---

### 12.7 Site Settings Table {#site-settings-table}

ওয়েবসাইটের সাধারণ সেটিংস।

```sql
CREATE TABLE site_settings (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL COMMENT 'সেটিংয়ের কী',
    setting_value LONGTEXT COMMENT 'সেটিংয়ের মান',
    setting_type ENUM('string', 'integer', 'boolean', 'json', 'decimal') DEFAULT 'string',
    category VARCHAR(100) COMMENT 'ক্যাটাগরি (general, email, payment, sms)',
    label VARCHAR(255) COMMENT 'মানবিক-বান্ধব লেবেল',
    description LONGTEXT COMMENT 'বর্ণনা',
    is_editable BOOLEAN DEFAULT TRUE COMMENT 'এডিটযোগ্য আছে কিনা',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by INT UNSIGNED COMMENT 'শেষ আপডেট করেছেন',
    INDEX idx_setting_key (setting_key),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='সাইট সেটিংস';
```

**সাধারণ সেটিংস উদাহরণ:**
- `site_name`: Shefa Natural Food
- `site_phone`: 01775-300088
- `site_email`: info@shefa.com
- `free_delivery_threshold`: 0 (সবার জন্য ফ্রি ডেলিভারি)
- `default_delivery_days`: 3
- `company_description`: কোম্পানির সম্পর্কে
- `social_facebook`: Facebook লিংক
- `social_whatsapp`: WhatsApp নম্বর

---

### 12.8 Media Files Table {#media-table}

সমস্ত ছবি এবং মিডিয়া ফাইল ম্যানেজমেন্ট।

```sql
CREATE TABLE media_files (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    filename VARCHAR(255) NOT NULL COMMENT 'ফাইলের নাম',
    original_filename VARCHAR(255) COMMENT 'মূল ফাইলের নাম',
    file_path VARCHAR(255) NOT NULL UNIQUE COMMENT 'ফাইলের পাথ (storage/uploads/...)',
    file_url VARCHAR(255) NOT NULL COMMENT 'ফাইলের URL',
    file_type VARCHAR(50) COMMENT 'ফাইল টাইপ (image, pdf, video)',
    mime_type VARCHAR(100) COMMENT 'MIME type (image/jpeg, image/png)',
    file_size INT COMMENT 'ফাইলের সাইজ (বাইট)',
    width INT COMMENT 'ইমেজের প্রস্থ (px)',
    height INT COMMENT 'ইমেজের উচ্চতা (px)',
    alt_text VARCHAR(255) COMMENT 'বিকল্প টেক্সট (SEO)',
    used_in LONGTEXT COMMENT 'কোথায় ব্যবহৃত হয়েছে (JSON - product_id, banner_id)',
    uploaded_by INT UNSIGNED COMMENT 'আপলোড করেছেন (admin user)',
    is_public BOOLEAN DEFAULT TRUE COMMENT 'সবার জন্য অ্যাক্সেসযোগ্য',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_file_type (file_type),
    INDEX idx_uploaded_by (uploaded_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='মিডিয়া ফাইল ম্যানেজমেন্ট';
```

---

## Database Relations চিত্র (Updated)

```
Users (Laravel default)
  ├── Customers (1-to-1)
  ├── Orders (1-to-many)
  ├── Reviews (1-to-many)
  └── Admin_Users (1-to-1)

Products
  ├── Inventory (1-to-1)
  ├── Order_Items (1-to-many)
  ├── Reviews (1-to-many)
  └── FAQs (1-to-many)

Orders
  ├── Order_Items (1-to-many)
  ├── Payments (1-to-many)
  └── Delivery_Areas (many-to-1)

Promo_Codes
  └── Orders (many-to-many through order.promo_code_used)
```

---

## 🔧 Laravel Migration উদাহরণ

প্রতিটি টেবিলের জন্য Laravel migration তৈরি করুন:

```bash
php artisan make:migration create_products_table
php artisan make:migration create_orders_table
php artisan make:migration create_order_items_table
php artisan make:migration create_customers_table
php artisan make:migration create_payments_table
php artisan make:migration create_inventory_table
php artisan make:migration create_reviews_table
php artisan make:migration create_faqs_table
php artisan make:migration create_promo_codes_table
php artisan make:migration create_delivery_areas_table
php artisan make:migration create_admin_users_table
```

---

## 📊 সাধারণ ক্যোয়ারি উদাহরণ

### শীর্ষ বিক্রীত পণ্য
```sql
SELECT p.name, COUNT(oi.id) as sold_count
FROM products p
LEFT JOIN order_items oi ON p.id = oi.product_id
GROUP BY p.id
ORDER BY sold_count DESC
LIMIT 10;
```

### আজকের মোট বিক্রয়
```sql
SELECT SUM(total_amount) as total_sales
FROM orders
WHERE DATE(created_at) = CURDATE() 
AND order_status IN ('delivered', 'confirmed');
```

### গ্রাহক অভিযোগ পূর্ববর্তী ডেলিভারি
```sql
SELECT o.*, oa.division, oa.delivery_days
FROM orders o
LEFT JOIN delivery_areas oa ON o.delivery_area_id = oa.id
WHERE o.order_status NOT IN ('delivered', 'cancelled')
AND DATEDIFF(NOW(), o.created_at) > oa.delivery_days;
```

---

## ⚠️ গুরুত্বপূর্ণ নোট

1. **Backup নিয়মিত নিন**: প্রতিদিন ডাটাবেস ব্যাকআপ রাখুন
2. **Soft Delete ব্যবহার করুন**: তথ্য সম্পূর্ণ মুছবেন না (deleted_at ফিল্ড)
3. **Foreign Keys সক্রিয় রাখুন**: ডাটা ইন্টেগ্রিটি নিশ্চিত করতে
4. **Indexing**: Frequently searched fields এ index দিন (performance বৃদ্ধি)
5. **Transactions**: Critical operations এ transaction ব্যবহার করুন

---

**সংস্করণ**: 1.0  
**আপডেট**: ২০২৫ সালে  
**রক্ষণাবেক্ষণকারী**: Shefa Natural Food টিম
