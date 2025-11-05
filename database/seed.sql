-- Seed Data for Sultan Library
-- Note: This file contains basic seed data. For passwords, use the seed.php script instead.

-- Insert categories (main categories first)
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `sortOrder`, `isActive`, `parentId`, `createdAt`, `updatedAt`) VALUES
('clx1livres', 'Livres', 'livres', 'Découvrez notre sélection de livres islamiques pour tous les âges', '/img_8104-scaled.jpeg', 1, TRUE, NULL, NOW(), NOW()),
('clx2calendriers', 'Calendriers', 'calendriers', 'Calendriers islamiques et éducatifs', '/img_8236-scaled.jpeg', 2, TRUE, NULL, NOW(), NOW()),
('clx3coffrets', 'Coffrets & Personnalisation', 'coffrets-personnalisation', 'Coffrets cadeaux et produits personnalisables', '/img_8309-scaled.jpeg', 3, TRUE, NULL, NOW(), NOW()),
('clx4jeux', 'Jeux interactifs', 'jeux-interactifs', 'Jeux éducatifs et interactifs pour enfants', '/img_8310-scaled.jpeg', 4, TRUE, NULL, NOW(), NOW()),
('clx5parfum', 'Parfum', 'parfum', 'Parfums d\'intérieur et pour le corps', '/img_9313-scaled.jpeg', 5, TRUE, NULL, NOW(), NOW()),
('clx6qamis', 'Qamis', 'qamis', 'Qamis traditionnels de qualité', '/img_0126-scaled.jpeg', 6, TRUE, NULL, NOW(), NOW());

-- Insert subcategories for Livres
INSERT INTO `categories` (`id`, `name`, `slug`, `sortOrder`, `isActive`, `parentId`, `createdAt`, `updatedAt`) VALUES
('clx1livres1', 'Adultes', 'adultes', 1, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres2', 'Enfants 0-3 ans', 'enfants-0-3', 2, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres3', 'Enfants 3-6 ans', 'enfants-3-6', 3, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres4', 'Enfants 6-9 ans', 'enfants-6-9', 4, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres5', 'Enfants 9-12 ans', 'enfants-9-12', 5, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres6', 'Enfants 12-15 ans', 'enfants-12-15', 6, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres7', 'Adolescents', 'adolescents', 7, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres8', 'Al Imam', 'al-imam', 8, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres9', 'MuslimLife', 'muslimlife', 9, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres10', 'Tawbah', 'tawbah', 10, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres11', 'Coran', 'coran', 11, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres12', 'L\'âme', 'lame', 12, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres13', 'La femme en Islam', 'femme-islam', 13, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres14', 'Le mariage', 'mariage', 14, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres15', 'Le jeûne & ramadan', 'jeune-ramadan', 15, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres16', 'Les prophètes', 'prophetes', 16, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres17', 'Livre de poche', 'livre-poche', 17, TRUE, 'clx1livres', NOW(), NOW()),
('clx1livres18', 'Médecine prophétique', 'medecine-prophetique', 18, TRUE, 'clx1livres', NOW(), NOW());

-- Insert subcategories for Parfum
INSERT INTO `categories` (`id`, `name`, `slug`, `sortOrder`, `isActive`, `parentId`, `createdAt`, `updatedAt`) VALUES
('clx5parfum1', 'Parfum d\'intérieur', 'parfum-interieur', 1, TRUE, 'clx5parfum', NOW(), NOW()),
('clx5parfum2', 'Parfum pour le corps', 'parfum-corps', 2, TRUE, 'clx5parfum', NOW(), NOW()),
('clx5parfum3', 'Parfum Voiture', 'parfum-voiture', 3, TRUE, 'clx5parfum', NOW(), NOW()),
('clx5parfum4', 'Parfum 50ml', 'parfum-50ml', 4, TRUE, 'clx5parfum', NOW(), NOW());

-- Note: User creation with password hashing should be done via PHP script
-- Products will be inserted via seed.php script to handle JSON fields properly


