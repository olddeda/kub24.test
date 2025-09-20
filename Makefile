# Kub24 Docker Management
# Modern Yii2 application with automatic initialization

.PHONY: help setup up down restart logs shell db-shell redis-shell clean status test

help: ## Show this help message
	@echo "🚀 Kub24 Docker Commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@echo "💡 Quick start: make setup"

setup: ## 🔧 Complete setup with automatic initialization
	@echo "🚀 Setting up Kub24 application..."
	@echo "🔨 Building and starting containers..."
	docker-compose up -d --build
	@echo "📋 Follow initialization progress:"
	@echo "   make logs-app"
	@echo ""
	@echo "🎉 Setup initiated! Application will initialize automatically."
	@echo "📱 Web App:  http://localhost:8080"
	@echo "🗄️ Adminer:  http://localhost:8081"
	@echo "👤 Admin:    admin / admin123"

up: ## ▶️  Start all containers
	docker-compose up -d

down: ## ⏹️  Stop all containers
	docker-compose down

restart: ## 🔄 Restart all containers
	docker-compose restart

# Logs
logs: ## 📋 Show logs from all containers
	docker-compose logs -f

logs-app: ## 📱 Show app container logs
	docker-compose logs -f app

logs-db: ## 🗄️  Show database logs
	docker-compose logs -f db

logs-redis: ## 🔴 Show Redis logs
	docker-compose logs -f redis

# Shell access
shell: ## 🐚 Access app container shell
	docker-compose exec app bash

db-shell: ## 🗄️  Access PostgreSQL shell
	docker-compose exec db psql -U kub24_user -d kub24_test

redis-shell: ## 🔴 Access Redis shell
	docker-compose exec redis redis-cli

adminer: ## 🌐 Open Adminer in browser (macOS)
	open http://localhost:8081

web: ## 🌐 Open web application in browser (macOS)
	open http://localhost:8080

# Development commands
composer: ## 📦 Install/update Composer dependencies
	docker-compose exec app composer install

composer-update: ## 📦 Update Composer dependencies
	docker-compose exec app composer update

# Database commands
migrate: ## 🗄️  Run database migrations
	docker-compose exec app php yii migrate --interactive=0

migrate-down: ## ⬇️  Rollback last migration
	docker-compose exec app php yii migrate/down --interactive=0

migrate-fresh: ## 🔄 Fresh migrations (drop all tables and re-migrate)
	docker-compose exec app php yii migrate/fresh --interactive=0

# RBAC commands
rbac-init: ## 🔐 Initialize RBAC system
	docker-compose exec app php yii rbac/init

# User management
create-admin: ## 👤 Create admin user interactively
	docker-compose exec app php yii user/create-admin

user-list: ## 📋 List all users
	docker-compose exec app php yii user/list

user-info: ## ℹ️  Show user info (usage: make user-info EMAIL=user@example.com)
	docker-compose exec app php yii user/info $(EMAIL)

change-password: ## 🔑 Change user password interactively
	docker-compose exec app php yii user/change-password

# Data seeding
seed: ## 🌱 Seed all test data
	docker-compose exec app php yii seed/all

seed-users: ## 👥 Seed users (usage: make seed-users COUNT=50)
	docker-compose exec app php yii seed/users $(COUNT)

seed-categories: ## 📂 Seed categories (usage: make seed-categories COUNT=10)
	docker-compose exec app php yii seed/categories $(COUNT)

seed-products: ## 📦 Seed products (usage: make seed-products COUNT=100)
	docker-compose exec app php yii seed/products $(COUNT)

seed-clear: ## 🧹 Clear all test data
	docker-compose exec app php yii seed/clear

# Maintenance
clean: ## 🧹 Remove all containers, volumes, and images
	docker-compose down -v --remove-orphans
	docker system prune -af
	@echo "🧹 All Docker resources cleaned"

clean-soft: ## 🧽 Stop containers and remove volumes
	docker-compose down -v --remove-orphans

status: ## 📊 Show container status
	docker-compose ps

top: ## 📊 Show container resource usage
	docker-compose top

# Cache management
cache-clear: ## 🗑️  Clear application cache
	docker-compose exec app php yii cache/flush-all

cache-schema: ## 🗑️  Clear schema cache
	docker-compose exec app php yii cache/flush-schema

# Development tools
test: ## 🧪 Run tests (if configured)
	@echo "⚠️  Tests not configured yet"

lint: ## 🔍 Check code style (if configured)
	@echo "⚠️  Linting not configured yet"

# Backup and restore
backup-db: ## 💾 Backup database
	docker-compose exec db pg_dump -U kub24_user -d kub24_test > backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "💾 Database backup created"

# Monitoring
monitor: ## 📊 Monitor containers (requires docker stats)
	docker stats $(shell docker-compose ps -q)

# Quick development commands
dev: ## 🚀 Quick development start (setup + logs)
	make setup
	sleep 5
	make logs-app

rebuild: ## 🔨 Rebuild containers without cache
	docker-compose down
	docker-compose build --no-cache
	docker-compose up -d

# Environment management
env-copy: ## 📄 Copy environment file for Docker (manual)
	cp .env.docker .env

env-example: ## 📄 Copy example environment file (manual)
	cp .env.example .env

# Help for specific commands
help-user: ## ℹ️  Show user management help
	@echo "👤 User Management Commands:"
	@echo "  make create-admin              - Create admin user interactively"
	@echo "  make user-list                 - List all users"
	@echo "  make user-info EMAIL=user@...  - Show user information"
	@echo "  make change-password           - Change user password"

help-seed: ## ℹ️  Show seeding help
	@echo "🌱 Data Seeding Commands:"
	@echo "  make seed                      - Seed all test data"
	@echo "  make seed-users COUNT=50       - Seed 50 users"
	@echo "  make seed-categories COUNT=10  - Seed 10 categories"
	@echo "  make seed-products COUNT=100   - Seed 100 products"
	@echo "  make seed-clear                - Clear all test data"

# Default target
.DEFAULT_GOAL := help