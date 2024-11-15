<?php

require_once('apiSecure.php'); // Asegúrate de que el nombre del archivo es correcto

// Obtener la entidad a partir del parámetro 'action'
$entity = isset($_GET['action']) ? $_GET['action'] : null;

// Verificar que se ha proporcionado una entidad
if ($entity) {
    // Crear una instancia de GenericAPI con la entidad especificada en 'action'
    $api = new GenericAPI($entity);
    
    // Ejecutar el método API para procesar la solicitud
    $api->API();
} else {
    // Si no se proporciona una entidad, devolver un mensaje de error en JSON
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'No se ha especificado ninguna acción válida'
    ]);
}

?>
