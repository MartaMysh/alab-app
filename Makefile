.PHONY: up down build restart logs bash migrate seed import composer npm db php-version

.PHONY: all
all: setup-env build up composer migrate-refresh import npm

setup-env:
	@echo "Konfiguracja środowiska..."
	cd backend && \
	if [ ! -f .env ]; then \
		cp .env.example .env && \
		echo ".env został utworzony"; \
	else \
		echo ".env już istnieje, nie nadpisuję"; \
	fi

# Start containers
up:
	docker-compose up -d

# Stop containers
down:
	docker-compose down

# Build containers
build:
	docker-compose build --no-cache

# Restart
restart:
	docker-compose down && docker-compose up -d

# Logs
logs:
	docker-compose logs -f

# Enter backend container
bash:
	docker exec -it laravel_app bash

# Laravel migrate
migrate:
	docker exec -it laravel_app php artisan migrate

# Laravel migrate refresh
migrate-refresh:
	docker exec -it laravel_app php artisan migrate:refresh

# Import CSV
import:
	docker exec -it laravel_app php artisan app:import-patient-data results.csv

# Composer install
composer:
	docker exec -it laravel_app composer install

# Frontend install
npm:
	docker exec -it vue_app npm install

# PostgreSQL shell
db:
	docker exec -it postgres_db psql -U postgres -d medical

# PHP version check
php-version:
	docker exec -it laravel_app php -v

