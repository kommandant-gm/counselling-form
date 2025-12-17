#!/bin/bash

echo "🚀 Setting up Counselling Form Application..."
echo ""

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Install dependencies
echo "📦 Installing dependencies..."
composer install

# Copy environment file
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create SQLite database
echo "🗄️  Creating database..."
touch database/database.sqlite

# Run migrations
echo "🔧 Running migrations..."
php artisan migrate

echo ""
echo "✅ Setup complete!"
echo ""
echo "To start the application locally, run:"
echo "  php artisan serve"
echo ""
echo "Then visit: http://localhost:8000"
echo ""
echo "Admin access: http://localhost:8000/admin"
echo "Default password: admin123 (change this in .env!)"
echo ""
