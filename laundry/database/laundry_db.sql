CREATE DATABASE IF NOT EXISTS laundry_db;
USE laundry_db;

-- --------------------------------------------------------

-- Table structure for table `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `fullname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('customer', 'admin') DEFAULT 'customer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `services`
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `service_name` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `unit` VARCHAR(20) DEFAULT 'Kg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `orders`
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `user_id` INT NOT NULL,
  `service_id` INT NOT NULL,
  `quantity` DECIMAL(10,2) NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `dropoff_date` DATE NOT NULL,
  `pickup_date` DATE DEFAULT NULL,
  `status` ENUM('Pending', 'Processing', 'Ready', 'Completed', 'Cancelled') DEFAULT 'Pending',
  `notes` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Seeding data for table `users`
INSERT IGNORE INTO `users` (`username`, `fullname`, `email`, `phone`, `password`, `role`) VALUES
('admin', 'System Administrator', 'admin@laundry.com', '+254700000000', '$2y$10$ni81R.ycfgQu0TS/AM7BqOupf6rwzaNHTI.BdaRgTEO.GUfCIqvOW', 'admin'),
('john_doe', 'John Doe', 'customer@laundry.com', '+254711111111', '$2y$10$pkBR7kxj/mVnuyFrePkV..oNWNIRFiIUQqqBbA.AXpur5mv95Ziwm', 'customer');

-- Seeding data for table `services`
INSERT IGNORE INTO `services` (`service_name`, `price`, `unit`) VALUES
('Wash & Fold', 150.00, 'Kg'),
('Wash & Iron', 220.00, 'Kg'),
('Dry Cleaning (Suits)', 600.00, 'Piece'),
('Duvet / Blanket Cleaning', 450.00, 'Piece'),
('Ironing Only', 80.00, 'Piece');
