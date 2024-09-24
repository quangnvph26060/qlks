composer require wuangdz/qutility



CREATE TABLE cache (
    `key` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL PRIMARY KEY,
    `value` MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `expiration` INT DEFAULT NULL
);


CREATE TABLE sessions (
    id VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL PRIMARY KEY,
    user_id BIGINT UNSIGNED DEFAULT NULL,
    ip_address VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    user_agent TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    payload LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    last_activity INT NOT NULL,
    INDEX (user_id),
    INDEX (last_activity)
);



CREATE TABLE bookings (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    booking_number VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    user_id INT NOT NULL DEFAULT 0,
    check_in DATE DEFAULT NULL,
    check_out DATE DEFAULT NULL,
    guest_details TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    tax_charge DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    booking_fare DECIMAL(28,8) NOT NULL DEFAULT 0.00000000 COMMENT 'Total of room * nights fare',
    service_cost DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    extra_charge DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    extra_charge_subtracted DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    paid_amount DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    cancellation_fee DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    refunded_amount DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    key_status TINYINT(1) NOT NULL DEFAULT 0,
    status TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '1= success/active; 3 = cancelled; 9 = checked Out',
    checked_in_at DATETIME DEFAULT NULL,
    checked_out_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT NULL,
    INDEX (user_id)
);

ALTER TABLE room_types
ADD hourly_rate DECIMAL(10, 2) NULL DEFAULT 0 AFTER existing_column;

ALTER TABLE room_types
ADD seasonal_rate DECIMAL(10, 2) NULL DEFAULT 0 AFTER hourly_rate;

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL, -- Tên danh mục hàng hóa
    description TEXT NULL, -- Mô tả danh mục
    status BOOLEAN DEFAULT 1 NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);


CREATE TABLE brands (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT 1 NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);


CREATE TABLE suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_info TEXT NULL,
    address VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

ALTER TABLE `suppliers` ADD `is_active` BOOLEAN NOT NULL DEFAULT TRUE AFTER `address`;

CREATE TABLE supplier_representatives (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    supplier_id BIGINT UNSIGNED NOT NULL, -- Tham chiếu tới nhà cung cấp
    name VARCHAR(255) NOT NULL, -- Tên người đại diện
    phone VARCHAR(20) NULL, -- Số điện thoại người đại diện
    email VARCHAR(255) NULL, -- Email của người đại diện
    position VARCHAR(255) NULL, -- Chức vụ của người đại diện
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);


CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NULL, -- Tham chiếu đến danh mục
    brand_id BIGINT UNSIGNED NULL, -- Tham chiếu đến thương hiệu
    name VARCHAR(255) NOT NULL, -- Tên sản phẩm
    description TEXT NULL, -- Mô tả sản phẩm
    price DECIMAL(10, 2) NOT NULL, -- Giá bán sản phẩm
    stock INT DEFAULT 0, -- Số lượng tồn kho
    is_published BOOLEAN DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL
);


CREATE TABLE product_supplier (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL, -- Tham chiếu đến sản phẩm
    supplier_id BIGINT UNSIGNED NOT NULL, -- Tham chiếu đến nhà cung cấp
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);

CREATE TABLE warehouse_entries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    supplier_id BIGINT UNSIGNED NOT NULL, -- Tham chiếu đến nhà cung cấp của đơn nhập kho
    reference_code VARCHAR(255) NOT NULL UNIQUE, 
    total DECIMAL(10, 2) DEFAULT 0.00, -- Tổng giá trị đơn nhập
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE CASCADE
);


CREATE TABLE warehouse_entry_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    warehouse_entry_id BIGINT UNSIGNED NOT NULL, -- Tham chiếu đến đơn nhập kho
    product_id BIGINT UNSIGNED NOT NULL, -- Tham chiếu đến sản phẩm trong đơn nhập kho
    quantity INT NOT NULL, -- Số lượng sản phẩm nhập
    cost DECIMAL(10, 2) NOT NULL, -- Giá nhập của sản phẩm
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (warehouse_entry_id) REFERENCES warehouse_entries(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE returns (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL, -- Tham chiếu đến sản phẩm bị trả
    quantity INT NOT NULL, -- Số lượng bị trả
    return_date DATE NOT NULL, -- Ngày trả hàng
    reason TEXT NULL, -- Lý do trả hàng
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);



CREATE TABLE banks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(10) NOT NULL,
    bin VARCHAR(20) NOT NULL,
    sort_name VARCHAR(255) NOT NULL
);

-- 13/9/2024 / devquang
ALTER TABLE rooms
ADD COLUMN is_clean BOOLEAN NOT NULL DEFAULT TRUE;

-- 20/9/2024 / phong
ALTER TABLE `general_settings`
ADD COLUMN `deposit` INT NULL AFTER `available_version`;

-- 21/09  dev_quang
ALTER TABLE booked_rooms MODIFY COLUMN booked_for DATETIME;

ALTER TABLE bookings MODIFY COLUMN check_in DATETIME;

ALTER TABLE bookings MODIFY COLUMN check_out DATETIME;





