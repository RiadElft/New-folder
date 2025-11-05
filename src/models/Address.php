<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/Helpers.php';

class Address {
    public static function create($userId, $data) {
        $db = db();
        $addressId = generateId('addr');
        
        $stmt = $db->prepare("INSERT INTO addresses (id, userId, firstName, lastName, street, city, postalCode, country, phone, isDefault, type) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $addressId,
            $userId,
            $data['firstName'],
            $data['lastName'],
            $data['street'],
            $data['city'],
            $data['postalCode'],
            $data['country'] ?? 'France',
            $data['phone'] ?? null,
            $data['isDefault'] ?? false,
            $data['type'] ?? 'BOTH',
        ]);
        
        return $addressId;
    }

    public static function userAddresses($userId) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM addresses WHERE userId = ? ORDER BY isDefault DESC, createdAt DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public static function findById($id) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM addresses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}


