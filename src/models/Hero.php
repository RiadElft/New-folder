<?php
/**
 * Hero Model
 * Stores homepage hero product selections
 */

require_once __DIR__ . '/../config/database.php';

class Hero {
    private static function ensureTable() {
        $db = db();
        $db->exec("CREATE TABLE IF NOT EXISTS home_hero (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            productId VARCHAR(27) NOT NULL,
            sortOrder INT NOT NULL DEFAULT 0,
            UNIQUE KEY uniq_product (productId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    public static function getProductIds(): array {
        self::ensureTable();
        $db = db();
        $stmt = $db->query("SELECT productId FROM home_hero ORDER BY sortOrder ASC, id ASC");
        return array_map(fn($r) => $r['productId'], $stmt->fetchAll());
    }

    public static function setProductIds(array $orderedIds): void {
        self::ensureTable();
        $db = db();
        $db->beginTransaction();
        try {
            $db->exec("DELETE FROM home_hero");
            $stmt = $db->prepare("INSERT INTO home_hero (productId, sortOrder) VALUES (?, ?)");
            $order = 0;
            foreach ($orderedIds as $pid) {
                if (!$pid) continue;
                $stmt->execute([$pid, $order++]);
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}

?>


