<?php

// Script to prepare the environment for the product inventory application
// Run this script before starting the application

// Set up the .env file with minimal required settings
$envContent = <<<EOT
APP_NAME="Product Inventory"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=null

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
EOT;

// Write to .env file
file_put_contents(__DIR__ . '/.env', $envContent);

// Generate application key
echo "Setting up environment...\n";
shell_exec('php artisan key:generate');

// Clear configuration cache
echo "Clearing cache...\n";
shell_exec('php artisan config:clear');
shell_exec('php artisan cache:clear');
shell_exec('php artisan optimize:clear');

// Create products.json if it doesn't exist
$productsPath = __DIR__ . '/storage/app/products.json';
if (!file_exists(dirname($productsPath))) {
    mkdir(dirname($productsPath), 0755, true);
}

if (!file_exists($productsPath)) {
    file_put_contents($productsPath, json_encode([]));
    echo "Created empty products.json file.\n";
}

echo "Setup complete! You can now run the application.\n";
