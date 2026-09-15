#!/usr/bin/env bash
set -e

# Railway / Nixpacks start script for Laravel API
php artisan config:clear || true
php artisan migrate --force

# Optional one-shot seed for empty demos (set RUN_SEEDERS=true)
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
  php artisan db:seed --force || true
fi

php artisan storage:link || true
php artisan config:cache || true
php artisan route:cache || true

# Bind to Railway-provided $PORT
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
