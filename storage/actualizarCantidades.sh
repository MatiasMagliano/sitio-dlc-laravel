#!/bin/bash

# Ruta al proyecto Laravel
cd /sitios/sitio-dlc-laravel/

# Ejecutar el comando personalizado y redirigir la salida al archivo de registro
php artisan deposito-casa-central:actualizar >> /sitios/sitio-dlc-laravel/storage/logs/registro.log 2>&1
