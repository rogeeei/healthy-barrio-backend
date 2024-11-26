<?php

// config/cors.php

return [
    'paths' => ['api/*', 'service/*', 'citizen/*', 'citizen-overview/*','medicine/*', 'equipment/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://127.0.0.1:5500', 'http://localhost:5500'],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['*'],
    'max_age' => 0,
    'supports_credentials' => false,
    'preflight_max_age' => 600, // Ensure preflight requests are cached
];
