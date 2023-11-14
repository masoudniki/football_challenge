# Check if .env file exists; if not, create it
if [ ! -f ".env" ]; then
    touch .env
    echo "Created .env file"
else
    echo ".env file already exists skip it"
fi
# Check if APP_KEY is set; if not, generate the key
if ! grep -q "^APP_KEY=" .env; then
    echo "APP_KEY=" >> .env
    php artisan key:generate
    echo "Generated APP_KEY"
else
    echo "APP_KEY already set in .env"
fi
# Read host and port from .env file or set default values
HOST=$(grep -E "^APP_HOST" .env | cut -d '=' -f2)
PORT=$(grep -E "^APP_PORT" .env | cut -d '=' -f2)

# Start Laravel development server
php artisan serve --host "${HOST:-0.0.0.0}" --port "${PORT:-8000}"