<?php
class DB
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=rest_api', 'root', '');
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
}
?>
