<?php



class DB
{
    private $pdo;

    public function __construct()
    {
        $config = require 'config.php';
        $this->pdo = new PDO('mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'], $config['database']['user'], $config['database']['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }   

    // Obtener un registro
    public function getRecord($entity, $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $entity WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtiene varios registros
    public function getRecords($entity, $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM $entity WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Obtener todos los registros
    public function getAllRecords($entity)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $entity");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insertar un nuevo registro con validación de columnas
    public function insert($entity, $data)
    {
        // Obtener columnas de la tabla
        $columnsInTable = $this->getTableColumns($entity);
        $filteredData = array_intersect_key((array)$data, array_flip($columnsInTable));

        if (empty($filteredData)) {
            throw new Exception("No hay columnas válidas para insertar en la tabla $entity");
        }

        $columns = implode(", ", array_keys($filteredData));
        $placeholders = ":" . implode(", :", array_keys($filteredData));

        
        $stmt = $this->pdo->prepare("INSERT INTO $entity ($columns) VALUES ($placeholders)");

        foreach ($filteredData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
    }

    // Actualizar un registro con validación de columnas
    public function update($entity, $id, $data)
    {
        $columnsInTable = $this->getTableColumns($entity);
        $filteredData = array_intersect_key((array)$data, array_flip($columnsInTable));

        if (empty($filteredData)) {
            throw new Exception("No hay columnas válidas para actualizar en la tabla $entity");
        }

        $set = "";
        foreach ($filteredData as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ", ");

        $stmt = $this->pdo->prepare("UPDATE $entity SET $set WHERE id = :id");

        foreach ($filteredData as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Eliminar un registro
    public function delete($entity, $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM $entity WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Obtener las columnas de una tabla
    private function getTableColumns($entity)
    {
        $stmt = $this->pdo->prepare("DESCRIBE $entity");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $columns;
    }



        // DB.php
public function checkRow($entity, $conditions)
{
    $where = "";
    foreach ($conditions as $key => $value) {
        $where .= "$key = :$key AND ";
    }
    $where = rtrim($where, " AND ");

    $sql = "SELECT COUNT(*) as count FROM $entity WHERE $where";
    $stmt = $this->pdo->prepare($sql);

    foreach ($conditions as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['count'] > 0;
}


}
?>
