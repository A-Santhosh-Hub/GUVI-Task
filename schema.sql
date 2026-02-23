-- Create database
CREATE DATABASE IF NOT EXISTS guvi_task;
USE guvi_task;

-- Create users table (with all required columns)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- If table already exists but is missing the username column, run this:
-- ALTER TABLE users ADD COLUMN username VARCHAR(100) NOT NULL AFTER id;
