<?php
// config.php
return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'elixir_multimedia', //Modificar con el nombre de tu base de datos
        'user' => 'root',
        'password' => '',
    ],
    'app' => [
        'debug' => true,  // Cambiar a false para producción
        'base_url' => 'https://sandbox.phpulse.es/',  // Base URL de tu aplicación (ajustar para producción)
    ],
];

// No subir a producción solo desarrollo
if ($config['app']['debug']) {
    // Permitir el acceso desde cualquier origen (esto es para desarrollo)
    header("Access-Control-Allow-Origin: *");
} else {
    // Configurar restricciones más estrictas en producción (puedes personalizarlo según tus necesidades)
    header("Access-Control-Allow-Origin: {$config['app']['base_url']}");
}

// Permitir ciertos métodos (GET, POST, PUT, DELETE, OPTIONS)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permitir ciertos encabezados (headers) en las solicitudes
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Si la aplicación está en modo de depuración, se puede ver más información (solo en desarrollo)
if ($config['app']['debug']) {
    ini_set('display_errors', 1);  // Mostrar errores para depuración
    error_reporting(E_ALL);  // Mostrar todos los errores
} else {
    ini_set('display_errors', 0);  // No mostrar errores en producción
    error_reporting(0);  // Desactivar reporte de errores
}
