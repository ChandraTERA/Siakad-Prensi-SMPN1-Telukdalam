#!/bin/bash

# PHP CS Fixer Script untuk Laravel Project
# Usage: ./format-php.sh [file_or_directory]

PHP_CS_FIXER="./php-cs-fixer.phar"
CONFIG_FILE=".php-cs-fixer.php"

# Check if php-cs-fixer exists
if [ ! -f "$PHP_CS_FIXER" ]; then
    echo "❌ PHP CS Fixer not found. Please run:"
    echo "curl -L https://github.com/PHP-CS-Fixer/PHP-CS-Fixer/releases/latest/download/php-cs-fixer.phar -o php-cs-fixer.phar"
    echo "chmod +x php-cs-fixer.phar"
    exit 1
fi

# Check if config exists
if [ ! -f "$CONFIG_FILE" ]; then
    echo "❌ Config file not found. Please create .php-cs-fixer.php"
    exit 1
fi

# Default target
TARGET=${1:-"app"}

echo "🔧 Formatting PHP files in: $TARGET"
echo "📋 Using config: $CONFIG_FILE"
echo ""

# Run PHP CS Fixer
if [ -f "$TARGET" ]; then
    # Single file
    echo "📄 Formatting file: $TARGET"
    $PHP_CS_FIXER fix "$TARGET" --config="$CONFIG_FILE" --verbose
else
    # Directory
    echo "📁 Formatting directory: $TARGET"
    $PHP_CS_FIXER fix "$TARGET" --config="$CONFIG_FILE" --verbose
fi

echo ""
echo "✅ PHP formatting completed!"

