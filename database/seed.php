<?php
/**
 * Database Seeding Script
 * Run this after creating the database schema
 * Usage: php database/seed.php
 */

require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/config/database.php';

$db = db();

// Helper function to generate CUID-like IDs
function generateId($prefix = '') {
    return $prefix . bin2hex(random_bytes(12));
}

// Helper function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

echo "Starting database seed...\n";

try {
    $db->beginTransaction();

    // Create test user
    $userId = generateId('usr');
    $hashedPassword = hashPassword('password123');
    
    $stmt = $db->prepare("INSERT INTO users (id, email, password, name, phone, role, active) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $userId,
        'test@sultanlibrary.com',
        $hashedPassword,
        'Test User',
        '+33612345678',
        'user',
        true
    ]);
    echo "Created test user: test@sultanlibrary.com\n";

    // Get category IDs
    $categories = [];
    $stmt = $db->query("SELECT id, slug FROM categories");
    while ($row = $stmt->fetch()) {
        $categories[$row['slug']] = $row['id'];
    }

    // Sample products
    $products = [
        [
            'name' => '100 Trésors de l\'Islam',
            'slug' => '100-tresors-islam',
            'description' => 'Un ouvrage magnifique qui présente 100 trésors spirituels et historiques de l\'Islam. Ce livre richement illustré explore les merveilles de la civilisation islamique à travers les siècles.',
            'shortDescription' => 'Découvrez 100 trésors spirituels et historiques de l\'Islam',
            'price' => 24.90,
            'originalPrice' => 29.90,
            'image' => 'images/100-tresors-de-lislam.jpeg',
            'images' => json_encode(['images/100-tresors-de-lislam.jpeg']),
            'categoryId' => $categories['livres'],
            'subcategoryId' => $categories['adultes'],
            'tags' => json_encode(['Histoire', 'Culture', 'Spiritualité']),
            'rating' => 4.8,
            'reviewCount' => 45,
            'inStock' => true,
            'stockQuantity' => 25,
            'badge' => 'bestseller',
            'author' => 'Dr. Ahmed Hassan',
            'publisher' => 'Al Imam',
            'isbn' => '978-2-1234-5678-9',
            'pages' => 320,
            'language' => 'Français',
            'format' => 'Relié',
            'dimensions' => '24 x 17 cm',
            'publicationDate' => '2023-01-15',
            'specifications' => json_encode([
                'Langue' => 'Français',
                'Format' => 'Relié',
                'Nombre de pages' => 320,
                'Dimensions' => '24 x 17 cm',
                'Poids' => '850g',
            ]),
        ],
        [
            'name' => '99 Remèdes contre les péchés - Ibn Al-Qayyim',
            'slug' => '99-remedes-peches-ibn-qayyim',
            'description' => 'Un guide spirituel profond d\'Ibn Al-Qayyim qui offre des remèdes pratiques et spirituels pour se purifier des péchés et améliorer sa relation avec Allah.',
            'shortDescription' => 'Guide spirituel d\'Ibn Al-Qayyim pour la purification de l\'âme',
            'price' => 18.50,
            'image' => 'images/99-remedes-contre-les-peches-ibn-al-qayyim.jpeg',
            'images' => json_encode(['images/99-remedes-contre-les-peches-ibn-al-qayyim.jpeg']),
            'categoryId' => $categories['livres'],
            'subcategoryId' => $categories['lame'],
            'tags' => json_encode(['Spiritualité', 'Purification', 'Ibn Al-Qayyim']),
            'rating' => 4.9,
            'reviewCount' => 67,
            'inStock' => true,
            'stockQuantity' => 40,
            'badge' => 'new',
            'author' => 'Ibn Al-Qayyim',
            'publisher' => 'Tawbah',
            'isbn' => '978-2-9876-5432-1',
            'pages' => 256,
            'language' => 'Français',
            'format' => 'Broché',
            'dimensions' => '21 x 14 cm',
            'publicationDate' => '2023-06-10',
            'specifications' => json_encode([
                'Langue' => 'Français',
                'Format' => 'Broché',
                'Nombre de pages' => 256,
                'Dimensions' => '21 x 14 cm',
                'Poids' => '380g',
            ]),
        ],
        [
            'name' => 'Ainsi étaient nos pieux prédécesseurs',
            'slug' => 'ainsi-etaient-pieux-predecesseurs',
            'description' => 'Un recueil fascinant de récits sur la vie des Salafs, les pieux prédécesseurs de l\'Islam. Découvrez leur piété, leur sagesse et leur dévotion exemplaire.',
            'shortDescription' => 'Récits inspirants sur les Salafs',
            'price' => 22.00,
            'originalPrice' => 25.00,
            'image' => 'images/ainsi-etaient-nos-pieux-predecesseurs.jpeg',
            'images' => json_encode(['images/ainsi-etaient-nos-pieux-predecesseurs.jpeg']),
            'categoryId' => $categories['livres'],
            'subcategoryId' => $categories['adultes'],
            'tags' => json_encode(['Histoire', 'Salafs', 'Biographies']),
            'rating' => 4.7,
            'reviewCount' => 38,
            'inStock' => true,
            'stockQuantity' => 30,
            'author' => 'Yusuf Al-Qaradawi',
            'publisher' => 'Al Imam',
            'isbn' => '978-2-3456-7890-1',
            'pages' => 412,
            'language' => 'Français',
            'format' => 'Broché',
            'dimensions' => '23 x 15 cm',
            'publicationDate' => '2022-11-20',
            'specifications' => json_encode([
                'Langue' => 'Français',
                'Format' => 'Broché',
                'Nombre de pages' => 412,
                'Dimensions' => '23 x 15 cm',
                'Poids' => '520g',
            ]),
        ],
    ];

    // Insert products
    // Note: createdAt and updatedAt have DEFAULT values, so we don't include them
    $productSql = "INSERT INTO products (
        id, name, slug, description, shortDescription, price, originalPrice, image, images,
        categoryId, subcategoryId, tags, rating, reviewCount, inStock, stockQuantity, badge,
        author, publisher, isbn, pages, language, format, dimensions, publicationDate, 
        volume, fragrance, concentration, size, color, material, type, year, specifications,
        metaTitle, metaDescription
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $productStmt = $db->prepare($productSql);

    foreach ($products as $product) {
        $productId = generateId('prd');
        $values = [
            $productId,                                    // 1. id
            $product['name'],                              // 2. name
            $product['slug'],                              // 3. slug
            $product['description'],                       // 4. description
            $product['shortDescription'],                   // 5. shortDescription
            $product['price'],                             // 6. price
            $product['originalPrice'] ?? null,             // 7. originalPrice
            $product['image'],                             // 8. image
            $product['images'],                            // 9. images
            $product['categoryId'],                        // 10. categoryId
            $product['subcategoryId'] ?? null,            // 11. subcategoryId
            $product['tags'],                              // 12. tags
            $product['rating'],                            // 13. rating
            $product['reviewCount'],                        // 14. reviewCount
            $product['inStock'] ? 1 : 0,                   // 15. inStock
            $product['stockQuantity'],                      // 16. stockQuantity
            $product['badge'] ?? null,                     // 17. badge
            $product['author'] ?? null,                    // 18. author
            $product['publisher'] ?? null,                 // 19. publisher
            $product['isbn'] ?? null,                      // 20. isbn
            $product['pages'] ?? null,                     // 21. pages
            $product['language'] ?? null,                  // 22. language
            $product['format'] ?? null,                    // 23. format
            $product['dimensions'] ?? null,                // 24. dimensions
            $product['publicationDate'] ?? null,           // 25. publicationDate
            null,                                          // 26. volume
            null,                                          // 27. fragrance
            null,                                          // 28. concentration
            null,                                          // 29. size
            null,                                          // 30. color
            null,                                          // 31. material
            null,                                          // 32. type
            null,                                          // 33. year
            $product['specifications'] ?? null,            // 34. specifications
            null,                                          // 35. metaTitle
            null,                                          // 36. metaDescription
        ];
        
        // Debug: Uncomment to see actual count
        // echo "Columns in SQL: 36, Values provided: " . count($values) . "\n";
        
        $productStmt->execute($values);
        echo "Created product: {$product['name']}\n";
    }

    $db->commit();
    echo "Seed completed successfully!\n";

} catch (Exception $e) {
    $db->rollBack();
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

