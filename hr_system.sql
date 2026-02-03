-- ==============================
-- DATABASE CREATION
-- ==============================
CREATE DATABASE IF NOT EXISTS hr_system;
USE hr_system;

-- ==============================
-- EMPLOYEES TABLE
-- ==============================
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_code VARCHAR(20) UNIQUE NOT NULL,   -- KGL0001
    first_name VARCHAR(100) NOT NULL,
    second_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(150),
    department VARCHAR(100),
    position VARCHAR(100),
    salary DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==============================
-- OVERTIME TABLE
-- ==============================
CREATE TABLE overtime (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    overtime_date DATE NOT NULL,
    overtime_hours DECIMAL(5,2) NOT NULL,
    hourly_rate DECIMAL(10,2) NOT NULL,
    overtime_pay DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_employee
        FOREIGN KEY (employee_id)
        REFERENCES employees(id)
        ON DELETE CASCADE,
    CONSTRAINT unique_employee_overtime_date
        UNIQUE (employee_id, overtime_date)
);

-- ==============================
-- USERS TABLE (ADMIN LOGIN)
-- ==============================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==============================
-- SAMPLE ADMIN USER
-- password: admin123
-- ==============================
INSERT INTO users (username, password)
VALUES ('admin', 'admin123');

-- ==============================
-- SAMPLE EMPLOYEE
-- ==============================
INSERT INTO employees (employee_code, first_name, second_name, phone, email, department, position, salary)
VALUES ('KGL0001', 'John', 'Doe', '0781234567', 'john@example.com', 'IT', 'Developer', 1200);
 INSERT INTO users (username, password, role, employee_code)
VALUES ('KGL0001','KGL0001','employee','KGL0001')
