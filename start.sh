#!/bin/bash

# Install Node.js dependencies
npm install

# Install Laravel dependencies
composer install

# Generate Laravel application key if not exists
php artisan key:generate

# Start the server
npm start 