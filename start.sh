#!/bin/bash

# Configuration
PHP_BIN="/Applications/MAMP/bin/php/php8.4.1/bin/php"
PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Check if PHP binary exists
if [ ! -f "$PHP_BIN" ]; then
    echo "Error: PHP binary not found at $PHP_BIN"
    echo "Please check your MAMP installation or update the path in this script."
    exit 1
fi

# Navigate to project directory
cd "$PROJECT_DIR" || exit

echo "Starting Laravel server..."
echo "Access the app at: http://127.0.0.1:8000"
echo "Press Ctrl+C to stop."

"$PHP_BIN" artisan serve
