-- Sultan Library Database Schema
-- MySQL/MariaDB compatible
-- Generated from Prisma schema

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `shop` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shop`;

SET FOREIGN_KEY_CHECKS = 0;

-- Users table
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` VARCHAR(27) NOT NULL PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NULL,
  `phone` VARCHAR(50) NULL,
  `role` VARCHAR(50) NOT NULL DEFAULT 'user',
  `active` BOOLEAN NOT NULL DEFAULT TRUE,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories table
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` VARCHAR(27) NOT NULL PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `image` VARCHAR(500) NULL,
  `sortOrder` INT NOT NULL DEFAULT 0,
  `isActive` BOOLEAN NOT NULL DEFAULT TRUE,
  `parentId` VARCHAR(27) NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_slug` (`slug`),
  INDEX `idx_parentId` (`parentId`),
  FOREIGN KEY (`parentId`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` VARCHAR(27) NOT NULL PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `shortDescription` TEXT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `originalPrice` DECIMAL(10,2) NULL,
  `image` VARCHAR(500) NOT NULL,
  `images` TEXT NULL COMMENT 'JSON array of image URLs',
  `categoryId` VARCHAR(27) NOT NULL,
  `subcategoryId` VARCHAR(27) NULL,
  `tags` TEXT NULL COMMENT 'JSON array of tags',
  `rating` DECIMAL(3,2) NOT NULL DEFAULT 0.00,
  `reviewCount` INT NOT NULL DEFAULT 0,
  `inStock` BOOLEAN NOT NULL DEFAULT TRUE,
  `stockQuantity` INT NOT NULL DEFAULT 0,
  `badge` VARCHAR(50) NULL COMMENT 'new, bestseller, promo, etc.',
  
  -- Book-specific fields
  `author` VARCHAR(255) NULL,
  `publisher` VARCHAR(255) NULL,
  `isbn` VARCHAR(50) NULL,
  `pages` INT NULL,
  `language` VARCHAR(100) NULL,
  `format` VARCHAR(50) NULL COMMENT 'Broché, Relié, Poche',
  `dimensions` VARCHAR(100) NULL,
  `publicationDate` DATETIME NULL,
  
  -- Perfume-specific fields
  `volume` VARCHAR(50) NULL COMMENT '50ml, 100ml',
  `fragrance` VARCHAR(255) NULL,
  `concentration` VARCHAR(100) NULL,
  
  -- Clothing-specific fields
  `size` VARCHAR(50) NULL,
  `color` VARCHAR(100) NULL,
  `material` VARCHAR(255) NULL,
  
  -- Common fields
  `type` VARCHAR(100) NULL,
  `year` INT NULL,
  `specifications` TEXT NULL COMMENT 'JSON object for flexible specs',
  
  -- SEO
  `metaTitle` VARCHAR(255) NULL,
  `metaDescription` TEXT NULL,
  
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX `idx_slug` (`slug`),
  INDEX `idx_categoryId` (`categoryId`),
  INDEX `idx_subcategoryId` (`subcategoryId`),
  FOREIGN KEY (`categoryId`) REFERENCES `categories`(`id`),
  FOREIGN KEY (`subcategoryId`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Addresses table
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` VARCHAR(27) NOT NULL PRIMARY KEY,
  `userId` VARCHAR(27) NOT NULL,
  `firstName` VARCHAR(255) NOT NULL,
  `lastName` VARCHAR(255) NOT NULL,
  `street` VARCHAR(500) NOT NULL,
  `city` VARCHAR(255) NOT NULL,
  `postalCode` VARCHAR(20) NOT NULL,
  `country` VARCHAR(100) NOT NULL DEFAULT 'France',
  `phone` VARCHAR(50) NULL,
  `isDefault` BOOLEAN NOT NULL DEFAULT FALSE,
  `type` ENUM('SHIPPING', 'BILLING', 'BOTH') NOT NULL DEFAULT 'BOTH',
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_userId` (`userId`),
  FOREIGN KEY (`userId`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders table
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` VARCHAR(27) NOT NULL PRIMARY KEY,
  `userId` VARCHAR(27) NOT NULL,
  `orderNumber` VARCHAR(50) NOT NULL UNIQUE,
  `status` ENUM('PENDING', 'CONFIRMED', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED', 'REFUNDED') NOT NULL DEFAULT 'PENDING',
  `subtotal` DECIMAL(10,2) NOT NULL,
  `shipping` DECIMAL(10,2) NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  `paymentMethod` VARCHAR(100) NOT NULL,
  `shippingAddressId` VARCHAR(27) NOT NULL,
  `billingAddressId` VARCHAR(27) NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_userId` (`userId`),
  INDEX `idx_orderNumber` (`orderNumber`),
  FOREIGN KEY (`userId`) REFERENCES `users`(`id`),
  FOREIGN KEY (`shippingAddressId`) REFERENCES `addresses`(`id`),
  FOREIGN KEY (`billingAddressId`) REFERENCES `addresses`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order items table
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` VARCHAR(27) NOT NULL PRIMARY KEY,
  `orderId` VARCHAR(27) NOT NULL,
  `productId` VARCHAR(27) NOT NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_orderId` (`orderId`),
  INDEX `idx_productId` (`productId`),
  FOREIGN KEY (`orderId`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`productId`) REFERENCES `products`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews table
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` VARCHAR(27) NOT NULL PRIMARY KEY,
  `productId` VARCHAR(27) NOT NULL,
  `userId` VARCHAR(27) NOT NULL,
  `rating` INT NOT NULL COMMENT '1-5 stars',
  `comment` TEXT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_productId` (`productId`),
  INDEX `idx_userId` (`userId`),
  FOREIGN KEY (`productId`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`userId`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wishlist table
DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE `wishlist` (
  `id` VARCHAR(27) NOT NULL PRIMARY KEY,
  `userId` VARCHAR(27) NOT NULL,
  `productId` VARCHAR(27) NOT NULL,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_user_product` (`userId`, `productId`),
  INDEX `idx_userId` (`userId`),
  INDEX `idx_productId` (`productId`),
  FOREIGN KEY (`userId`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`productId`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Newsletter table
DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE `newsletter` (
  `id` VARCHAR(27) NOT NULL PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;


