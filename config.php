<?php
// config.php
return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'api_rest', //Modificar con el nombre de tu base de datos
        'user' => 'root',
        'password' => '',
    ],
    'app' => [
        'debug' => true,  // Cambiar a false para producción
        'base_url' => 'https://sandbox.phpulse.es/',  // Base URL de tu aplicación (ajustar para producción)
    ],
];