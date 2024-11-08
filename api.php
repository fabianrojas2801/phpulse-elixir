<?php
require_once('DB.php'); // Consultas con PDO

class GenericAPI 
{
    private $entity; // Nombre de la entidad (por ejemplo: people, user, etc.)
    private $db;

    public function __construct($entity)
    {
        $this->entity = $entity;
        $this->db = new DB(); // Usamos la clase DB genérica para la conexión
    }

    public function API()
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'];
        
        switch ($method) {
            case 'GET': 
                $this->getData();
                break;

            case 'POST': 
                $this->saveData();
                break;

            case 'PUT': 
                $this->updateData();
                break;

            case 'DELETE': 
                $this->deleteData();
                break;

            default: 
                echo json_encode(['status' => 'error', 'message' => 'Método no soportado']);
                break;
        }
    }

    /**
     * Función de respuesta HTTP genérica
     */
    private function response($code = 200, $status = "", $message = "")
    {
        http_response_code($code);
        if (!empty($status) && !empty($message)) {
            $response = ["status" => $status, "message" => $message];
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    /**
     * Obtener datos según el tipo de entidad
     */
    private function getData()
    {
        if (isset($_GET['action']) && $_GET['action'] === $this->entity) {
            try {
                if (isset($_GET['id'])) {
                    // Obtener un solo registro si existe el ID
                    $stmt = $this->db->prepare("SELECT * FROM {$this->entity} WHERE id = :id");
                    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $response = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    // Obtener todos los registros
                    $stmt = $this->db->prepare("SELECT * FROM {$this->entity}");
                    $stmt->execute();
                    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
                echo json_encode($response, JSON_PRETTY_PRINT);
            } catch (Exception $e) {
                $this->response(500, "error", "Error al obtener los datos");
            }
        } else {
            $this->response(400, "error", "Acción no válida");
        }
    }

    /**
     * Guardar un nuevo registro
     */
    private function saveData()
    {
        if (isset($_GET['action']) && $_GET['action'] === $this->entity) {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data)) {
                $this->response(422, "error", "Nada para agregar. Verifica el JSON");
            } else {
                try {
                    // Generar la consulta con parámetros preparados
                    $fields = implode(", ", array_keys($data));
                    $placeholders = ":" . implode(", :", array_keys($data));
                    $stmt = $this->db->prepare("INSERT INTO {$this->entity} ($fields) VALUES ($placeholders)");

                    foreach ($data as $key => $value) {
                        $stmt->bindValue(":$key", $value);
                    }

                    $stmt->execute();
                    $this->response(200, "success", "Nuevo registro agregado");
                } catch (Exception $e) {
                    $this->response(500, "error", "Error al agregar el registro");
                }
            }
        } else {
            $this->response(400, "error", "Acción no válida");
        }
    }

    /**
     * Actualizar un registro
     */
    private function updateData()
    {
        if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] === $this->entity) {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data)) {
                $this->response(422, "error", "Nada para actualizar. Verifica el JSON");
            } else {
                try {
                    $updates = [];
                    foreach ($data as $key => $value) {
                        $updates[] = "$key = :$key";
                    }
                    $updateString = implode(", ", $updates);
                    $stmt = $this->db->prepare("UPDATE {$this->entity} SET $updateString WHERE id = :id");

                    foreach ($data as $key => $value) {
                        $stmt->bindValue(":$key", $value);
                    }
                    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

                    $stmt->execute();
                    $this->response(200, "success", "Registro actualizado");
                } catch (Exception $e) {
                    $this->response(500, "error", "Error al actualizar el registro");
                }
            }
        } else {
            $this->response(400, "error", "Acción o ID no válido");
        }
    }

    /**
     * Eliminar un registro
     */
    private function deleteData()
    {
        if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] === $this->entity) {
            try {
                $stmt = $this->db->prepare("DELETE FROM {$this->entity} WHERE id = :id");
                $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
                $stmt->execute();
                $this->response(200, "success", "Registro eliminado");
            } catch (Exception $e) {
                $this->response(500, "error", "Error al eliminar el registro");
            }
        } else {
            $this->response(400, "error", "Acción o ID no válido");
        }
    }
}

?>
