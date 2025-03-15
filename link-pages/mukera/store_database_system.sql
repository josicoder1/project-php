-- Create tables in proper dependency order
CREATE DATABASE IF NOT EXISTS store_database_system;
USE store_database_system;

-- Users Table (should come first as other tables reference it)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('employee', 'manager', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Vehicles Table (should be created before transport_requests)
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id VARCHAR(10) NOT NULL UNIQUE,
    type ENUM('van', 'truck', 'car') NOT NULL,
    status ENUM('available', 'allocated', 'maintenance') DEFAULT 'available',
    location VARCHAR(100),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Transport Requests Table (now can reference vehicles)
CREATE TABLE transport_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id VARCHAR(10) NOT NULL UNIQUE,
    purpose VARCHAR(255) NOT NULL,
    request_date DATE NOT NULL,
    request_time TIME NOT NULL,
    destination VARCHAR(255) NOT NULL,
    submitted_by INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'canceled') DEFAULT 'pending',
    document_path VARCHAR(255),
    vehicle_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submitted_by) REFERENCES users(id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)
);

-- Approvals Table
CREATE TABLE approvals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    manager_id INT NOT NULL,
    action ENUM('approved', 'rejected') NOT NULL,
    comments TEXT,
    escalated BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES transport_requests(id),
    FOREIGN KEY (manager_id) REFERENCES users(id)
);