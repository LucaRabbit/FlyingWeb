<?php
namespace App\Models;

use App\Core\Model;

use PDO;

class LogModel extends Model
{
    protected string $table = "logs";
    protected string $primaryKey = "IdLog";

    // Lister tous les logs
    public function getAll()
    {
        $sql = "SELECT * FROM logs ORDER BY PerformedAt DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Compter les logs
    public function countAll()
    {
        return $this->db->query("SELECT COUNT(*) FROM logs")->fetchColumn();
    }

    // Pagination
    public function getPage($limit, $offset)
    {
        $sql = "SELECT * FROM logs ORDER BY PerformedAt DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}