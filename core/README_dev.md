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

ALTER TABLE seasonal_rate
ADD hourly_rate DECIMAL(10, 2) NULL DEFAULT 0 AFTER hourly_rate;
