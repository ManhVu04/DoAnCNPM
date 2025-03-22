<?php
require_once __DIR__ . '/Model.php';

class Theater extends Model {
    protected $table = 'theaters';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (name, address, city, state, zip_code, phone_number) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['address'],
            $data['city'],
            $data['state'],
            $data['zip_code'],
            $data['phone_number']
        ]);
    }
}