<?php

// Permitir acceso desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Permitir ciertos métodos (GET, POST, PUT, DELETE, OPTIONS)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permitir ciertos encabezados (headers) en las solicitudes
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Permitir la pre-solicitud (preflight) CORS
header("Access-Control-Allow-Credentials: true");


// Incluir la configuración desde config.php
$config = require 'config.php';
require_once('api.php'); // Asegúrate de que el nombre del archivo es correcto
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
        'message' => 'No se ha especificado ninguna action válida. Prueba a enviar por GET ?action=NombreTabla'
    ]);
}

?>
