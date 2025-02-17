composer require wuangdz/qutility

php artisan schedule:work



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
booking_fare DECIMAL(28,8) NOT NULL DEFAULT 0.00000000 COMMENT 'Total of room \* nights fare',
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

-- 20-09
CREATE TABLE `stock_entries` (
`id` int NOT NULL,
`warehouse_entry_id` int UNSIGNED NOT NULL,
`product_id` bigint UNSIGNED NOT NULL,
`quantity` int NOT NULL,
`entry_date` datetime NOT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `stock_entries`
ADD PRIMARY KEY (`id`),
ADD KEY `product_id` (`product_id`),
ADD KEY `warehouses_id` (`warehouse_entry_id`);

ALTER TABLE `stock_entries`
MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

ALTER TABLE `stock_entries`
ADD CONSTRAINT `stock_entries_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE RESTRICT,
ADD CONSTRAINT `stock_entries_ibfk_2` FOREIGN KEY (`warehouse_entry_id`) REFERENCES `warehouse_entries` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;



-- 21/09 dev_quang
ALTER TABLE booked_rooms MODIFY COLUMN booked_for DATETIME;

ALTER TABLE bookings MODIFY COLUMN check_in DATETIME;

ALTER TABLE bookings MODIFY COLUMN check_out DATETIME;

--23/9/2024
CREATE TABLE transactions (
id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(255) NOT NULL,
status INT NOT NULL
);

25/09/2004 (dat09)
CREATE TABLE room_prices (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL, -- Tên của loại giá (giá ngày, giá giờ, giá sự kiện, ...)
price DECIMAL(10, 2) NOT NULL, -- Giá trị của loại giá
start_date DATETIME NOT NULL, -- Ngày bắt đầu áp dụng giá
end_date DATETIME, -- Ngày kết thúc áp dụng giá (nếu có)
status ENUM('active', 'inactive') DEFAULT 'active', -- Trạng thái của loại giá

24/9/2024
ALTER TABLE `transactions`
ADD `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
ADD `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

24/9/2024 v2
CREATE TABLE fees (
id INT PRIMARY KEY AUTO_INCREMENT,
per_hour BIGINT DEFAULT 0,
per_day BIGINT DEFAULT 0,
per_night BIGINT DEFAULT 0,
per_season BIGINT DEFAULT 0,
per_event BIGINT DEFAULT 0,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Thêm cột code vào bảng room_prices
ALTER TABLE room_prices
ADD COLUMN code VARCHAR(50) NOT NULL AFTER name; -- Thêm cột code sau cột name

-- Thêm ràng buộc UNIQUE cho cặp name và code
ALTER TABLE room_prices
ADD UNIQUE (name, code); -- Thêm ràng buộc tính duy nhất cho name và code

CREATE TABLE room_price_rooms (
room_id INT NOT NULL, -- Khóa ngoại đến bảng rooms
price_id INT NOT NULL, -- Khóa ngoại đến bảng room_prices
PRIMARY KEY (room_id, price_id), -- Khóa chính kép để ngăn trùng lặp
FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
FOREIGN KEY (price_id) REFERENCES room_prices(id) ON DELETE CASCADE
);

CREATE TABLE additional_fees (
id INT PRIMARY KEY AUTO_INCREMENT,
early_checkout BIGINT DEFAULT 0,
late_checkout BIGINT DEFAULT 0,
none_checkin BIGINT DEFAULT 0,
cancellation BIGINT DEFAULT 0,
extra_bed BIGINT DEFAULT 0,
early_checkin BIGINT DEFAULT 0,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE rooms
ADD room_id VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE room_types
ADD room_type_id VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE amenities
ADD amenity_id VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE facilities
ADD facility_id VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE premium_services
ADD service_id VARCHAR(255) NULL DEFAULT NULL;



-- 26/09 đạt 09
ALTER TABLE `returns` ADD `total` INT NOT NULL DEFAULT '0' AFTER `status`;



-- 26/09

ALTER TABLE `room_prices`
ADD COLUMN start_time TIME NULL,
ADD COLUMN end_time TIME NULL,
ADD COLUMN specific_date DATE NULL;

ALTER TABLE`room_prices`
MODIFY COLUMN start_date DATE,
MODIFY COLUMN end_date DATE;

ALTER TABLE room_price_rooms
ADD COLUMN start_date DATE,
ADD COLUMN end_date DATE,
ADD COLUMN start_time TIME NULL,
ADD COLUMN end_time TIME NULL,
ADD COLUMN specific_date DATE NULL;

26/9/2024 - phong
ALTER TABLE categories
ADD category_id VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE brands
ADD brand_id VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE room_price_rooms
ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE room_price_rooms
ADD updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


27/9/2024 phong_dev
ALTER TABLE suppliers
ADD supplier_id VARCHAR(255) NULL DEFAULT NULL;


ALTER TABLE `room_price_rooms` ADD `status` BOOLEAN NOT NULL DEFAULT FALSE AFTER `specific_date`;


-- 27-09
ALTER TABLE `room_price_rooms` ADD `status` BOOLEAN NOT NULL DEFAULT FALSE AFTER `specific_date`;

-- 28-09
CREATE TABLE wishlists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    room_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_user_room (user_id, room_id),
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_room FOREIGN KEY (room_id) REFERENCES rooms (id) ON DELETE CASCADE
);


-- 30/09
ALTER TABLE `rooms` ADD `main_image` VARCHAR(255) NOT NULL AFTER `room_number`, ADD `total_adult` INT NOT NULL DEFAULT '0' AFTER `main_image`, ADD `total_child` INT NOT NULL DEFAULT '0' AFTER `total_adult`, ADD `description` TEXT NULL AFTER `total_child`, ADD `beds` TEXT NULL AFTER `description`, ADD `cancellation_fee` DECIMAL(10.) NOT NULL AFTER `beds`, ADD `cancellation_policy` TEXT NULL AFTER `cancellation_fee`, ADD `code` VARCHAR(6) NOT NULL AFTER `cancellation_policy`;

ALTER TABLE `rooms` DROP `room_id`;

ALTER TABLE `room_types` CHANGE `room_type_id` `code` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

ALTER TABLE `brands` CHANGE `brand_id` `code` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

ALTER TABLE `amenities` CHANGE `amenity_id` `code` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

ALTER TABLE `facilities` CHANGE `facility_id` `code` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

ALTER TABLE `premium_services` CHANGE `service_id` `code` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

ALTER TABLE `categories` CHANGE `category_id` `code` VARCHAR(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;

ALTER TABLE `suppliers` ADD `code` VARCHAR(6) NULL AFTER `is_active`;


--30-9 --phong
ALTER TABLE room_type_amenities RENAME TO room_amenities;
ALTER TABLE room_type_facilities RENAME TO room_facilities;
ALTER TABLE room_type_images RENAME TO room_images;
ALTER TABLE room_type_products RENAME TO room_products;

ALTER TABLE room_facilities RENAME COLUMN room_type_id TO room_id;
ALTER TABLE room_amenities RENAME COLUMN room_type_id  TO room_id;
ALTER TABLE room_images RENAME COLUMN room_type_id TO room_id;
ALTER TABLE room_products RENAME COLUMN room_type_id TO room_id;

ALTER TABLE `room_types`
  DROP `total_adult`,
  DROP `total_child`,
  DROP `fare`,
  DROP `keywords`,
  DROP `description`,
  DROP `beds`,
  DROP `cancellation_fee`,
  DROP `cancellation_policy`,
  DROP `is_featured`,
  DROP `room_type_id`;

--30-9 --Quang
ALTER TABLE `rooms` ADD `is_featured` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_clean`;


-- 01/10
ALTER TABLE `wishlists` ADD `publish` BOOLEAN NOT NULL DEFAULT TRUE AFTER `room_id`;

ALTER TABLE `booking_requests`
    DROP COLUMN `number_of_rooms`,        -- Loại bỏ cột số lượng phòng
    DROP COLUMN `room_type_id`,           -- Loại bỏ cột loại phòng
    ADD COLUMN `room_id` INT UNSIGNED NOT NULL AFTER `user_id`;  -- Thêm cột room_id


ALTER TABLE `bookings`
ADD COLUMN product_cost DECIMAL(28,8) NOT NULL DEFAULT 0.00000000;  
CREATE TABLE userd_product_rooms (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL DEFAULT 0,
    product_id INT UNSIGNED NOT NULL DEFAULT 0,
    room_id INT UNSIGNED NOT NULL DEFAULT 0,
    booked_room_id INT UNSIGNED,
    qty INT UNSIGNED NOT NULL DEFAULT 0,
    unit_price DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    total_amount DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    product_date DATE,
    admin_id INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- 02/10
CREATE TABLE `booking_request_items` (
  `id` int NOT NULL,
  `booking_request_id` bigint UNSIGNED NOT NULL,
  `room_id` bigint UNSIGNED NOT NULL,
  `unit_fare` decimal(12,0) NOT NULL,
  `tax-charge` decimal(12,0) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '	0 = pending, 1 = approved, 3 = cancelled;	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `booking_request_items`
  ADD KEY `booking_request_id` (`booking_request_id`),
  ADD KEY `room_id` (`room_id`);

ALTER TABLE `booking_request_items`
  ADD CONSTRAINT `booking_request_items_ibfk_1` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `booking_request_items_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

ALTER TABLE `booking_requests`
  DROP `room_id`,
  DROP `unit_fare`,
  DROP `tax_charge`;

-- 03/10
ALTER TABLE `booking_request_items` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);

ALTER TABLE `booking_request_items` CHANGE `tax-charge` `tax_charge` DECIMAL(12,0) NOT NULL;

ALTER TABLE `booked_rooms` DROP `room_type_id`;



--04/10
php artisan migrate --path=/database/migrations/2024_10_04_145642_create_jobs_table.php
php artisan migrate --path=/database/migrations/2024_10_04_153437_create_failed_jobs_table.php

--05/10
CREATE TABLE notification_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    act VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    name VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    email_body TEXT COLLATE utf8mb4_unicode_ci,
    subject VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1, -- 1: active, 0: inactive
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 


ALTER TABLE `rooms` ADD `deleted_at` TIMESTAMP NULL AFTER `updated_at`;



07/10
ALTER TABLE `booking_request_items` DROP FOREIGN KEY `booking_request_items_ibfk_1`; ALTER TABLE `booking_request_items` ADD CONSTRAINT `booking_request_items_ibfk_1` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

12/11 devquangnv
# giá giờ
CREATE TABLE room_prices_first_hour (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_price_id INT,
    price DECIMAL(10, 2),
    created_at DATETIME,
    updated_at DATETIME
);
# giá phòng thêm giờ 
CREATE TABLE room_prices_additional_hour (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_price_id INT,
    price DECIMAL(10, 2),
    created_at DATETIME,
    updated_at DATETIME
);
# giá phòng cả ngày
CREATE TABLE room_prices_full_day (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_price_id INT,
    price DECIMAL(10, 2),
    created_at DATETIME,
    updated_at DATETIME
);
# giá qua đêm
CREATE TABLE room_prices_overnight (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_price_id INT,
    price DECIMAL(10, 2),
    created_at DATETIME,
    updated_at DATETIME
);
# giá ngày lễ 
CREATE TABLE room_prices_per_day (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_price_id INT,
    date DATE,
    hourly_price DECIMAL(10, 2),
    daily_price DECIMAL(10, 2),
    overnight_price DECIMAL(10, 2),
    created_at DATETIME,
    updated_at DATETIME
);
# giá ngày thứ 
CREATE TABLE room_prices_per_day_of_week (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_price_id INT,
    day_of_week INT,
    hourly_price DECIMAL(10, 2),
    daily_price DECIMAL(10, 2),
    overnight_price DECIMAL(10, 2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
);
# giá thường lệ
CREATE TABLE regular_room_prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_price_id INT,
    hourly_price DECIMAL(10, 2),
    daily_price DECIMAL(10, 2),
    overnight_price DECIMAL(10, 2),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
ALTER TABLE regular_room_prices
MODIFY hourly_price DECIMAL(10, 2) DEFAULT NULL,
MODIFY daily_price DECIMAL(10, 2) DEFAULT NULL,
MODIFY overnight_price DECIMAL(10, 2) DEFAULT NULL;

18/11/2024

ALTER TABLE room_prices_per_day_of_week
MODIFY COLUMN day_of_week VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE room_prices_additional_hour
ADD COLUMN hour INT;

# giá mặc định của phòng theo giờ 
CREATE TABLE room_prices_weekday_hour (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_price_id INT NULL,
    hour INT NULL,
    price DECIMAL(10,2) NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);

//26/11
ALTER TABLE room_prices_additional_hour
ADD COLUMN date VARCHAR(255);

ALTER TABLE `general_settings`
ADD COLUMN `checkin_time_night` TIME NULL AFTER `checkout_time`,
ADD COLUMN `checkout_time_night` TIME NULL AFTER `checkin_time_night`;

/04/12           
ALTER TABLE `bookings`
ADD COLUMN `last_overtime_calculated_at` INT NULL DEFAULT NULL 
COMMENT 'Thời gian cuối cùng đã tính giờ vượt' 
AFTER `booking_fare`;

06/12
CREATE TABLE user_cleanroom (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    clean_date DATE NOT NULL,
    admin_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


13/12/24
ALTER TABLE booked_rooms
ADD COLUMN key_status TINYINT(1) DEFAULT 0;

ALTER TABLE bookings
ADD COLUMN note VARCHAR(255) DEFAULT NULL;

ALTER TABLE booked_rooms
ADD COLUMN option_room VARCHAR(10) DEFAULT NULL;

ALTER TABLE `bookings` DROP `option`

ALTER TABLE booking
ADD total_people INT DEFAULT 0;

-- 12/23

ALTER TABLE booked_rooms
ADD check_in_at DATETIME DEFAULT NULL;



# chỉnh sửa các bảng giá 
-- Create the regular_room_prices table
CREATE TABLE regular_room_prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_type_id INT,
    hourly_price DECIMAL(10, 2) DEFAULT NULL,
    daily_price DECIMAL(10, 2) DEFAULT NULL,
    overnight_price DECIMAL(10, 2) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO regular_room_prices (room_type_id, hourly_price, daily_price, overnight_price) VALUES
(1, 100.00, 800.00, 500.00),
(2, 150.00, 1000.00, 600.00);

-- Create the room_prices_per_day_of_week table
CREATE TABLE room_prices_per_day_of_week (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_type_id INT,
    day_of_week VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    hourly_price DECIMAL(10, 2) DEFAULT NULL,
    daily_price DECIMAL(10, 2) DEFAULT NULL,
    overnight_price DECIMAL(10, 2) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO room_prices_per_day_of_week (room_type_id, day_of_week, hourly_price, daily_price, overnight_price) VALUES
(1, 'Monday', 120.00, 850.00, 550.00),
(1, 'Friday', 130.00, 900.00, 600.00),
(2, 'Sunday', 180.00, 1100.00, 700.00);

-- Create the room_prices_per_day table
CREATE TABLE room_prices_per_day (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_type_id INT,
    date DATE,
    hourly_price DECIMAL(10, 2) DEFAULT NULL,
    daily_price DECIMAL(10, 2) DEFAULT NULL,
    overnight_price DECIMAL(10, 2) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO room_prices_per_day (room_type_id, date, hourly_price, daily_price, overnight_price) VALUES
(1, '2024-01-01', 200.00, 1000.00, 700.00),
(2, '2024-01-02', 250.00, 1200.00, 800.00);

-- Create the room_prices_weekday_hour table
CREATE TABLE room_prices_weekday_hour (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_type_id INT,
    hour INT,
    price DECIMAL(10, 2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO room_prices_weekday_hour (room_type_id, hour, price) VALUES
(1, 2, 50.00),
(1, 3, 75.00),
(2, 2, 60.00),
(2, 4, 90.00);

12/27/2024


CREATE TABLE hotel_facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ma_coso VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ten_coso VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    trang_thai VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE hotel_facilities
MODIFY trang_thai VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0';
ALTER TABLE hotel_facilities
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;




SELECT CONCAT('ALTER TABLE ', table_name, ' ADD COLUMN base_code VARCHAR(10);')
FROM information_schema.tables
WHERE table_schema = 'quanlykhachsan';

SELECT CONCAT('UPDATE ', table_name, ' SET base_code = 1;')
FROM information_schema.tables
WHERE table_schema = 'quanlykhachsan';



CREATE TABLE `setup_pricing` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    price_code VARCHAR(50),
    price_name VARCHAR(100),
    price_requirement VARCHAR(255),
    check_in_time TIME,
    check_out_time TIME,
    round_time INT,
    description TEXT,
    unit_code VARCHAR(50)
);
ALTER TABLE  `setup_pricing`
ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


CREATE TABLE room_type_price (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_type_id INT NOT NULL,
    setup_pricing_id INT NOT NULL,
    unit_price DECIMAL(10,2) DEFAULT NULL,
    overtime_price DECIMAL(10,2) DEFAULT NULL,
    extra_person_price DECIMAL(10,2) DEFAULT NULL,
    auto_calculate TINYINT(1) DEFAULT NULL,
    unit_code VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
ALTER TABLE room_type_price 
ADD COLUMN price_validity_period DATE DEFAULT NULL;

06/01/2025

ALTER TABLE `booked_rooms`
ADD COLUMN `check_in` DATETIME NULL,
ADD COLUMN `check_out` DATETIME NULL 

ALTER TABLE `bookings`
ADD COLUMN `document_date` DATETIME NULL;

ALTER TABLE `booked_rooms`
ADD COLUMN `unit_code` VARCHAR(50) NULL;
ALTER TABLE `bookings`
ADD COLUMN `unit_code` VARCHAR(50) NULL;



CREATE TABLE check_in_rooms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id INT UNSIGNED NOT NULL DEFAULT 0,
    room_type_id INT UNSIGNED NOT NULL DEFAULT 0,
    room_id INT UNSIGNED NOT NULL DEFAULT 0,
    booked_for DATETIME NULL,
    fare DECIMAL(10,2) NULL,
    check_in DATETIME NULL,
    check_out DATETIME NULL,
    check_in_at DATETIME NULL,
    cancellation_fee DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    status TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '1= success/active; 3 = cancelled; 9 = checked Out',
    key_status TINYINT(1) NULL DEFAULT 0,
    option_room VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    tax_charge DECIMAL(28,8) NOT NULL DEFAULT 0.00000000,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    unit_code VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL
);

ALTER TABLE check_in_rooms
ADD COLUMN book_room_id INT UNSIGNED NOT NULL DEFAULT 0;


13/1
CREATE TABLE customer (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Khóa chính tự tăng
    name VARCHAR(255) NOT NULL,             -- Tên khách hàng
    phone VARCHAR(15) NOT NULL,             -- Số điện thoại
    address TEXT,                           -- Địa chỉ
    email VARCHAR(255) UNIQUE,              -- Email (không trùng lặp)
    unit_code VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, -- Mã đơn vị
    status TINYINT DEFAULT 1,               -- Trạng thái (1: Hoạt động, 0: Không hoạt động)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Thời gian tạo
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Thời gian cập nhật
);
CREATE TABLE room_status (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Khóa chính tự tăng
    status_code VARCHAR(50) NOT NULL,       -- Mã tình trạng
    status_name VARCHAR(255) NOT NULL,      -- Tên tình trạng
    unit_code VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, -- Mã đơn vị
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Thời gian tạo
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Thời gian cập nhật
);
CREATE TABLE room_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Khóa chính tự tăng
    room_id VARCHAR(50) NOT NULL,             -- Mã phòng
    status_code VARCHAR(50) NOT NULL,           -- Trạng thái (liên kết với room_status)
    start_date DATE NOT NULL,                   -- Từ ngày
    end_date DATE NOT NULL,                     -- Đến ngày
    unit_code VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci -- Mã đơn vị 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Thời gian tạo
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Thời gian cập nhật
);
CREATE TABLE room_booking (
    id INT AUTO_INCREMENT PRIMARY KEY,              -- Khóa chính tự động tăng
    booking_id VARCHAR(50) NOT NULL,                -- ID Đặt phòng
    room_code VARCHAR(50) NOT NULL,                 -- Mã phòng
    document_date DATE NOT NULL,                    -- Ngày chứng từ
    checkin_date DATE NOT NULL,                     -- Ngày nhận
    checkout_date DATE NOT NULL,                    -- Ngày trả
    customer_code VARCHAR(50) NULL,                     -- Mã khách hàng (có thể NULL)
    customer_name VARCHAR(255) NULL,                    -- Tên khách hàng (có thể NULL)
    phone_number VARCHAR(20) NULL,                  -- Số điện thoại (có thể NULL)
    email VARCHAR(255) NULL,                        -- Email (có thể NULL)
    price_group INT NULL,                           -- Nhóm giá (có thể NULL)
    guest_count INT NULL,                           -- Số người (có thể NULL)
    total_amount DECIMAL(15, 2) NULL,               -- Thành tiền (có thể NULL)
    deposit_amount DECIMAL(15, 2) NULL,             -- Đặt cọc (có thể NULL)
    note TEXT NULL,                                 -- Ghi chú (có thể NULL)
    user_source VARCHAR(50) NULL,                   -- Nguồn khách (có thể NULL)
    unit_code VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, -- Mã đơn vị (có thể NULL)
    created_by VARCHAR(50) NULL,                    -- Người tạo (có thể NULL)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Ngày tạo (mặc định là thời điểm hiện tại)
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE check_in (
    id INT AUTO_INCREMENT PRIMARY KEY,              -- Khóa chính tự động tăng
    check_in_id VARCHAR(50) NOT NULL,                -- ID Đặt phòng 
    id_room_booking INT NULL,                       -- ID phòng đặt (có thể NULL)
    room_code VARCHAR(50) NOT NULL,                 -- Mã phòng
    document_date DATE NOT NULL,                    -- Ngày chứng từ
    checkin_date DATE NOT NULL,                     -- Ngày nhận
    checkout_date DATE NOT NULL,                    -- Ngày trả
    customer_code VARCHAR(50) NULL,                     -- Mã khách hàng (có thể NULL)
    customer_name VARCHAR(255) NULL,                    -- Tên khách hàng (có thể NULL)
    phone_number VARCHAR(20) NULL,                  -- Số điện thoại (có thể NULL)
    email VARCHAR(255) NULL,                        -- Email (có thể NULL)
    price_group INT NULL,                           -- Nhóm giá (có thể NULL)
    guest_count INT NULL,                           -- Số người (có thể NULL)
    total_amount DECIMAL(15, 2) NULL,               -- Thành tiền (có thể NULL)
    deposit_amount DECIMAL(15, 2) NULL,             -- Đặt cọc (có thể NULL)
    note TEXT NULL,                                 -- Ghi chú (có thể NULL)
    user_source VARCHAR(50) NULL,                   -- Nguồn khách (có thể NULL)
    unit_code VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, -- Mã đơn vị (có thể NULL)
    created_by VARCHAR(50) NULL,                    -- Người tạo (có thể NULL)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Ngày tạo (mặc định là thời điểm hiện tại)
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Ngày cập nhật
);
CREATE TABLE room_change (
    id INT AUTO_INCREMENT PRIMARY KEY,              -- Khóa chính tự động tăng
    room_change_id VARCHAR(50) NOT NULL,                -- ID Đặt phòng
     id_room_booking INT NULL,                       -- ID phòng đặt (có thể NULL)
    id_check_in INT NULL,                     		-- ID nhận phòng (có thể NULL, tham chiếu từ bảng check_in)
    old_room_code VARCHAR(50) NOT NULL,             -- Mã phòng cũ
    new_room_code VARCHAR(50) NOT NULL,             -- Mã phòng mới
    document_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày đổi phòng (mặc định là thời điểm hiện tại)
    checkin_date DATE NOT NULL,                     -- Ngày nhận
    checkout_date DATE NOT NULL,                    -- Ngày trả
    customer_code VARCHAR(50) NULL,                     -- Mã khách hàng (có thể NULL)
    customer_name VARCHAR(255) NULL,                    -- Tên khách hàng (có thể NULL)
    phone_number VARCHAR(20) NULL,                  -- Số điện thoại (có thể NULL)
    email VARCHAR(255) NULL,                        -- Email (có thể NULL)
    price_group INT NULL,                           -- Nhóm giá (có thể NULL)
    guest_count INT NULL,                           -- Số người (có thể NULL)
    total_amount DECIMAL(15, 2) NULL,               -- Thành tiền (có thể NULL)
    deposit_amount DECIMAL(15, 2) NULL,             -- Đặt cọc (có thể NULL)
    note TEXT NULL,                                 -- Ghi chú (có thể NULL)
    created_by VARCHAR(50) NULL,                    -- Người tạo (có thể NULL)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Ngày tạo (mặc định là thời điểm hiện tại)
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Ngày cập nhật
);

CREATE TABLE check_out (
    id INT AUTO_INCREMENT PRIMARY KEY,              -- Khóa chính tự động tăng
    check_out_id VARCHAR(50) NOT NULL,             -- ID trả phòng
    id_check_in INT NOT NULL,                      -- ID nhận phòng (tham chiếu từ bảng check_in)
    room_code VARCHAR(50) NOT NULL,                -- Mã phòng
    document_date DATE NOT NULL,                   -- Ngày chứng từ
    checkin_date DATE NOT NULL,                    -- Ngày nhận
    checkout_date DATE NOT NULL,                   -- Ngày trả
    actual_checkin_date DATE NULL,                 -- Ngày thực nhận (có thể NULL)
    checkin_time TIME NULL,                        -- Giờ nhận (có thể NULL)
    actual_checkout_date DATE NULL,                -- Ngày thực trả (có thể NULL)
    checkout_time TIME NULL,                       -- Giờ trả (có thể NULL)
    customer_code VARCHAR(50) NULL,                -- Mã khách hàng (có thể NULL)
    customer_name VARCHAR(255) NULL,               -- Tên khách hàng (có thể NULL)
    phone_number VARCHAR(20) NULL,                 -- Số điện thoại (có thể NULL)
    email VARCHAR(255) NULL,                       -- Email (có thể NULL)
    price_group INT NULL,                          -- Nhóm giá (có thể NULL)
    guest_count INT NULL,                          -- Số người (có thể NULL)
    total_amount DECIMAL(15, 2) NULL,              -- Thành tiền (có thể NULL)
    deposit_amount DECIMAL(15, 2) NULL,            -- Đặt cọc (có thể NULL)
    discount_amount DECIMAL(15, 2) NULL,           -- Giảm giá (có thể NULL)
    payment_amount DECIMAL(15, 2) NULL,            -- Thanh toán (có thể NULL)
    remaining_amount DECIMAL(15, 2) NULL,          -- Còn lại (có thể NULL)
    status VARCHAR(20) NULL,                       -- Trạng thái (có thể NULL)
    note TEXT NULL,                                -- Ghi chú (có thể NULL)
    user_source VARCHAR(50) NULL,                  -- Nguồn khách (có thể NULL)
    created_by VARCHAR(50) NULL,                   -- Người tạo (có thể NULL)
    unit_code VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, -- Mã đơn vị (có thể NULL)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày tạo (mặc định là thời điểm hiện tại)
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Ngày cập nhật
   
);

CREATE TABLE check_out_details (
    id INT AUTO_INCREMENT PRIMARY KEY,              -- Khóa chính tự động tăng
    check_out_id INT NOT NULL,                      -- ID trả phòng (tham chiếu từ bảng check_out)
    room_code VARCHAR(50) NOT NULL,                -- Mã phòng
    document_date DATE NOT NULL,                   -- Ngày tạo đơn
    product_code VARCHAR(50) NOT NULL,             -- Mã sản phẩm
    quantity INT NOT NULL,                         -- Số lượng
    unit_price DECIMAL(15, 2) NOT NULL,            -- Đơn giá
    total_amount DECIMAL(15, 2) NOT NULL,          -- Thành tiền
    note TEXT NULL,                                -- Ghi chú (có thể NULL)
    unit_code VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL, -- Mã đơn vị (có thể NULL)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Ngày tạo (mặc định là thời điểm hiện tại)
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  -- Ngày cập nhật
   
);

CREATE TABLE status_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,               -- Khóa chính tự động tăng
    status_code VARCHAR(50) NOT NULL UNIQUE,         -- Mã trạng thái (duy nhất)
    status_name VARCHAR(255) NOT NULL,               -- Tên trạng thái
    note TEXT NULL,                                  -- Ghi chú (có thể NULL)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Ngày tạo (mặc định là thời điểm hiện tại)
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Ngày cập nhật
);

CREATE TABLE customer_sources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    source_code VARCHAR(50) NOT NULL,
    source_name VARCHAR(255) NOT NULL,
    unit_code VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


ALTER TABLE admins ADD COLUMN unit_code VARCHAR(255) NOT NULL DEFAULT 'coso1';
