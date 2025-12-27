-- Database Schema for Buildyourstudio

-- Create database if not exists (optional, usually done in panel)
-- CREATE DATABASE IF NOT EXISTS buildyourstudio;
-- USE buildyourstudio;

-- Users Table (for Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(191) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id VARCHAR(50) PRIMARY KEY, -- using string ID to match existing frontend expectations if strictly needed, or INT usually better
    name VARCHAR(255) NOT NULL,
    name_sw VARCHAR(255),
    description TEXT,
    description_sw TEXT,
    icon VARCHAR(100), -- Lucide icon name
    helper_text TEXT,
    helper_text_sw TEXT,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id VARCHAR(50) PRIMARY KEY,
    category_id VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    file_size_gb DECIMAL(10, 2),
    is_free BOOLEAN DEFAULT FALSE,
    price_usd DECIMAL(10, 2) DEFAULT 0.00,
    price_tzs DECIMAL(10, 2) DEFAULT 0.00,
    tier ENUM('cheap', 'medium', 'expensive') DEFAULT 'medium',
    features JSON, -- Storing features as JSON array
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Library Packs (Add-ons)
CREATE TABLE IF NOT EXISTS library_packs (
    id VARCHAR(50) PRIMARY KEY,
    product_id VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    file_size_gb DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Storage Devices
CREATE TABLE IF NOT EXISTS storage_devices (
    id VARCHAR(50) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('usb', 'ssd', 'hdd') NOT NULL,
    capacity_gb INT NOT NULL,
    price_usd DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id VARCHAR(50) PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),
    shipping_country VARCHAR(100),
    shipping_city VARCHAR(100),
    shipping_address TEXT,
    
    total_storage_gb DECIMAL(10, 2),
    storage_device_id VARCHAR(50),
    
    subtotal_usd DECIMAL(10, 2),
    shipping_cost_usd DECIMAL(10, 2),
    total_usd DECIMAL(10, 2),
    
    currency VARCHAR(10) DEFAULT 'USD',
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items (Normalization)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) NOT NULL,
    product_id VARCHAR(50) NOT NULL,
    price_at_purchase DECIMAL(10, 2),
    is_pack BOOLEAN DEFAULT FALSE, -- If it represents a library pack
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Settings (Key-Value storage for simple settings)
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value JSON,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Default Admin User (Password: password123)
-- You should change this immediately after deployment
INSERT INTO users (name, email, password_hash, role) 
VALUES ('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE email=email;
