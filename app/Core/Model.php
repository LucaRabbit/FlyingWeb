<?php
namespace App\Core;

use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = "id";

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Récupérer tous les enregistrements
    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un enregistrement par ID
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :pk";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["pk" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insérer un enregistrement
    public function create(array $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        $id = $this->db->lastInsertId();

        // Log
        $this->log($this->table, "INSERT", $id, null, $data);

        return $id;
    }

    // Mettre à jour un enregistrement
    public function update($id, array $data)
    {
        // Récupérer l'ancien état
        $old = $this->findById($id);

        $set = implode(", ", array_map(fn($col) => "$col = :$col", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = :pk";

        $data["pk"] = $id;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        // LOG
        $this->log($this->table, "UPDATE", $id, $old, $data);

        return true;
    }

    // Supprimer un enregistrement
    public function delete($id)
    {
        // Récupérer l'ancien état
        $old = $this->findById($id);

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :pk";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["pk" => $id]);

        // LOG
        $this->log($this->table, "DELETE", $id, $old, null);

        return true;
    }

    // Log les actions CRUD
    protected function log($table, $action, $recordId, $oldData = null, $newData = null)
    {
        $sql = "INSERT INTO logs (TableName, Action, RecordId, OldData, NewData)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $table,
            $action,
            $recordId,
            $oldData ? json_encode($oldData) : null,
            $newData ? json_encode($newData) : null
        ]);
    }
}