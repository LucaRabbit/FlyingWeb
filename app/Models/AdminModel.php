<?php
// Namespace
namespace App\Models;

// Imports
use App\Core\Model;
use PDO;

class AdminModel extends Model
{
    // Trouver via email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM admin WHERE Email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}