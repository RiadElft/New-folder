-- Complete PostgreSQL Schema Migration
-- Copy and paste this entire file into Render's PostgreSQL console
-- Or run: psql <connection-string> -f database/run-migration.sql

-- Function to update updatedAt timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW."updatedAt" = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Users table
DROP TABLE IF EXISTS users CASCADE;
CREATE TABLE users (
  id VARCHAR(27) NOT NULL PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255) NULL,
  phone VARCHAR(50) NULL,
  role VARCHAR(50) NOT NULL DEFAULT 'user',
  active BOOLEAN NOT NULL DEFAULT TRUE,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updatedAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_email ON users(email);
CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Categories table
DROP TABLE IF EXISTS categories CASCADE;
CREATE TABLE categories (
  id VARCHAR(27) NOT NULL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT NULL,
  image VARCHAR(500) NULL,
  "sortOrder" INTEGER NOT NULL DEFAULT 0,
  "isActive" BOOLEAN NOT NULL DEFAULT TRUE,
  "parentId" VARCHAR(27) NULL,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updatedAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY ("parentId") REFERENCES categories(id) ON DELETE SET NULL
);
CREATE INDEX idx_slug ON categories(slug);
CREATE INDEX idx_parentId ON categories("parentId");
CREATE TRIGGER update_categories_updated_at BEFORE UPDATE ON categories
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Products table
DROP TABLE IF EXISTS products CASCADE;
CREATE TABLE products (
  id VARCHAR(27) NOT NULL PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT NULL,
  "shortDescription" TEXT NULL,
  price DECIMAL(10,2) NOT NULL,
  "originalPrice" DECIMAL(10,2) NULL,
  image VARCHAR(500) NOT NULL,
  images TEXT NULL,
  "categoryId" VARCHAR(27) NOT NULL,
  "subcategoryId" VARCHAR(27) NULL,
  tags TEXT NULL,
  rating DECIMAL(3,2) NOT NULL DEFAULT 0.00,
  "reviewCount" INTEGER NOT NULL DEFAULT 0,
  "inStock" BOOLEAN NOT NULL DEFAULT TRUE,
  "stockQuantity" INTEGER NOT NULL DEFAULT 0,
  badge VARCHAR(50) NULL,
  author VARCHAR(255) NULL,
  publisher VARCHAR(255) NULL,
  isbn VARCHAR(50) NULL,
  pages INTEGER NULL,
  language VARCHAR(100) NULL,
  format VARCHAR(50) NULL,
  dimensions VARCHAR(100) NULL,
  "publicationDate" TIMESTAMP NULL,
  volume VARCHAR(50) NULL,
  fragrance VARCHAR(255) NULL,
  concentration VARCHAR(100) NULL,
  size VARCHAR(50) NULL,
  color VARCHAR(100) NULL,
  material VARCHAR(255) NULL,
  type VARCHAR(100) NULL,
  year INTEGER NULL,
  specifications TEXT NULL,
  "metaTitle" VARCHAR(255) NULL,
  "metaDescription" TEXT NULL,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updatedAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY ("categoryId") REFERENCES categories(id),
  FOREIGN KEY ("subcategoryId") REFERENCES categories(id) ON DELETE SET NULL
);
CREATE INDEX idx_slug ON products(slug);
CREATE INDEX idx_categoryId ON products("categoryId");
CREATE INDEX idx_subcategoryId ON products("subcategoryId");
CREATE TRIGGER update_products_updated_at BEFORE UPDATE ON products
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Addresses table
DROP TABLE IF EXISTS addresses CASCADE;
CREATE TABLE addresses (
  id VARCHAR(27) NOT NULL PRIMARY KEY,
  "userId" VARCHAR(27) NOT NULL,
  "firstName" VARCHAR(255) NOT NULL,
  "lastName" VARCHAR(255) NOT NULL,
  street VARCHAR(500) NOT NULL,
  city VARCHAR(255) NOT NULL,
  "postalCode" VARCHAR(20) NOT NULL,
  country VARCHAR(100) NOT NULL DEFAULT 'France',
  phone VARCHAR(50) NULL,
  "isDefault" BOOLEAN NOT NULL DEFAULT FALSE,
  type VARCHAR(20) NOT NULL DEFAULT 'BOTH' CHECK (type IN ('SHIPPING', 'BILLING', 'BOTH')),
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updatedAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY ("userId") REFERENCES users(id) ON DELETE CASCADE
);
CREATE INDEX idx_userId ON addresses("userId");
CREATE TRIGGER update_addresses_updated_at BEFORE UPDATE ON addresses
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Orders table
DROP TABLE IF EXISTS orders CASCADE;
CREATE TABLE orders (
  id VARCHAR(27) NOT NULL PRIMARY KEY,
  "userId" VARCHAR(27) NOT NULL,
  "orderNumber" VARCHAR(50) NOT NULL UNIQUE,
  status VARCHAR(20) NOT NULL DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'CONFIRMED', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED', 'REFUNDED')),
  subtotal DECIMAL(10,2) NOT NULL,
  shipping DECIMAL(10,2) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  "paymentMethod" VARCHAR(100) NOT NULL,
  "shippingAddressId" VARCHAR(27) NOT NULL,
  "billingAddressId" VARCHAR(27) NOT NULL,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updatedAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY ("userId") REFERENCES users(id),
  FOREIGN KEY ("shippingAddressId") REFERENCES addresses(id),
  FOREIGN KEY ("billingAddressId") REFERENCES addresses(id)
);
CREATE INDEX idx_userId ON orders("userId");
CREATE INDEX idx_orderNumber ON orders("orderNumber");
CREATE TRIGGER update_orders_updated_at BEFORE UPDATE ON orders
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Order items table
DROP TABLE IF EXISTS order_items CASCADE;
CREATE TABLE order_items (
  id VARCHAR(27) NOT NULL PRIMARY KEY,
  "orderId" VARCHAR(27) NOT NULL,
  "productId" VARCHAR(27) NOT NULL,
  quantity INTEGER NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  name VARCHAR(255) NOT NULL,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY ("orderId") REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY ("productId") REFERENCES products(id)
);
CREATE INDEX idx_orderId ON order_items("orderId");
CREATE INDEX idx_productId ON order_items("productId");

-- Reviews table
DROP TABLE IF EXISTS reviews CASCADE;
CREATE TABLE reviews (
  id VARCHAR(27) NOT NULL PRIMARY KEY,
  "productId" VARCHAR(27) NOT NULL,
  "userId" VARCHAR(27) NOT NULL,
  rating INTEGER NOT NULL,
  comment TEXT NULL,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "updatedAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY ("productId") REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY ("userId") REFERENCES users(id) ON DELETE CASCADE
);
CREATE INDEX idx_productId ON reviews("productId");
CREATE INDEX idx_userId ON reviews("userId");
CREATE TRIGGER update_reviews_updated_at BEFORE UPDATE ON reviews
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Wishlist table
DROP TABLE IF EXISTS wishlist CASCADE;
CREATE TABLE wishlist (
  id VARCHAR(27) NOT NULL PRIMARY KEY,
  "userId" VARCHAR(27) NOT NULL,
  "productId" VARCHAR(27) NOT NULL,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE ("userId", "productId"),
  FOREIGN KEY ("userId") REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY ("productId") REFERENCES products(id) ON DELETE CASCADE
);
CREATE INDEX idx_userId ON wishlist("userId");
CREATE INDEX idx_productId ON wishlist("productId");

-- Newsletter table
DROP TABLE IF EXISTS newsletter CASCADE;
CREATE TABLE newsletter (
  id VARCHAR(27) NOT NULL PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  "createdAt" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Verify tables were created
SELECT 'Migration completed! Created tables:' as status;
SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name;

