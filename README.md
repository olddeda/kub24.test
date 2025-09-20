# Kub24 Test Application

Современное приложение на Yii2 с аутентификацией пользователей, ролевым контролем доступа и управлением товарами.

## Возможности

- **Аутентификация**: Регистрация, вход, сброс пароля через email
- **RBAC**: Гранулярный ролевой контроль доступа
- **Управление пользователями**: CRUD операции, смена ролей и паролей
- **Управление товарами**: Товары и категории с полным CRUD
- **Интернационализация**: Английский/русский языки
- **Soft Delete**: Мягкое удаление для всех сущностей
- **Кеширование**: L1/L2 кеш через Redis
- **Email**: Отправка уведомлений и сброс паролей
- **Docker**: Полная контейнеризация с автоинициализацией

## Технологический стек

- **Backend**: PHP 8.3, Yii2 Framework
- **Database**: PostgreSQL 15
- **Cache**: Redis 7
- **Frontend**: Bootstrap 5, JavaScript ES6+
- **Email**: Symfony Mailer
- **Containerization**: Docker + Docker Compose
- **Web Server**: Nginx + PHP-FPM

## Требования

### Docker (Рекомендуется):
- Docker Engine 20.0+
- Docker Compose 2.0+

### Локальная установка:
- PHP 8.3+
- PostgreSQL 12+
- Redis 6+
- Composer 2.0+

## Быстрый старт с Docker

### 1. Клонирование и запуск
```bash
git clone git@github.com:olddeda/kub24.test.git
cd kub24.test
make setup
```

### 2. Что происходит автоматически:
- Создание `.env` файла из `.env.docker`
- Сборка Docker контейнеров
- Запуск PostgreSQL, Redis, Nginx, PHP-FPM
- Установка Composer зависимостей
- Выполнение миграций базы данных
- Инициализация RBAC системы
- Генерация тестовых данных
- Создание администратора

### 3. Доступ к приложению:
- **Веб-приложение**: http://localhost:8080
- **Adminer (БД)**: http://localhost:8081
- **Админ**: `admin` / `admin123`

### 4. Docker команды:
```bash
# Запуск контейнеров
docker-compose up -d

# Остановка контейнеров
docker-compose down

# Просмотр логов
docker-compose logs -f app

# Вход в контейнер приложения
docker-compose exec app bash

# Подключение к PostgreSQL
docker-compose exec db psql -U kub24_user -d kub24_test

# Подключение к Redis
docker-compose exec redis redis-cli

# Перезапуск приложения
docker-compose restart app

# Полная очистка
docker-compose down -v --remove-orphans
```

## Локальная установка

### 1. Подготовка окружения
```bash
git clone git@github.com:olddeda/kub24.test.git
cd cd kub24.test
composer install
cp .env.example .env
```

### 2. Настройка .env файла
```bash
# Основные настройки
APP_ENV=dev
APP_DEBUG=true
APP_NAME="Kub24 Test App"

# База данных
DB_HOST=localhost
DB_PORT=5432
DB_NAME=kub24_test
DB_USERNAME=your_user
DB_PASSWORD=your_password

# Redis
REDIS_HOST=localhost
REDIS_PORT=6379
REDIS_DATABASE=0

# Безопасность
COOKIE_VALIDATION_KEY=your-secret-key-here

# Email (опционально)
MAIL_USE_FILE_TRANSPORT=true
```

### 3. Инициализация приложения
```bash
# Создание базы данных PostgreSQL
createdb kub24_test

# Полная настройка
composer run setup

# Или по шагам:
php yii migrate --interactive=0
php yii rbac/init
php yii seed/all
php yii user/create-admin admin admin@kub24.local admin123
```

## Архитектура приложения

### Модульная структура
```
modules/
├── auth/           # Аутентификация и регистрация
│   ├── controllers/
│   ├── models/
│   ├── views/
│   └── messages/   # Переводы модуля
├── users/          # Управление пользователями
│   ├── controllers/
│   ├── models/
│   ├── services/   # Бизнес-логика
│   ├── enums/      # PHP Enums
│   └── messages/
└── products/       # Управление товарами
    ├── controllers/
    ├── models/
    ├── services/
    └── messages/
```

### Сервисный слой
- `BaseService` - базовый класс с CRUD операциями
- `UserService` - логика пользователей
- `ProductService` - логика товаров
- `CategoryService` - логика категорий
- `EmailService` - отправка email

### Кеширование
- **L1 Cache** - в памяти (60 сек)
- **L2 Cache** - Redis (300 сек)
- **Schema Cache** - кеш схемы БД
- **RBAC Cache** - кеш разрешений

## Система разрешений (RBAC)

### Роли:
- **User** - базовые права пользователя
- **Admin** - полные административные права

### Разрешения пользователей:
- `user.index` - просмотр списка
- `user.view` - просмотр профиля
- `user.create` - создание пользователя
- `user.update` - редактирование
- `user.delete` - удаление

### Разрешения товаров:
- `product.index` - просмотр каталога
- `product.view` - просмотр товара
- `product.create` - создание товара
- `product.update` - редактирование
- `product.delete` - удаление

### Разрешения категорий:
- `product.category.*` - аналогично товарам

## Схема базы данных

### Основные таблицы:
- **users** - пользователи с аутентификацией
- **products** - товары с ценами и статусами
- **product_categories** - категории товаров
- **auth_*** - стандартные RBAC таблицы Yii2

### Аудит полей (во всех таблицах):
- `created_at` / `updated_at` - временные метки
- `created_by` / `updated_by` - ID пользователей
- `deleted_at` / `deleted_by` - мягкое удаление

### Enums (PHP 8.1+):
- `UserStatus` - статусы пользователей
- `ProductStatus` - статусы товаров
- `CategoryStatus` - статусы категорий

## Интернационализация

### Поддерживаемые языки:
- English (по умолчанию)
- Русский

### Структура переводов:
```
messages/
├── en-US/
│   ├── app.php      # Основные переводы
│   ├── error.php    # Ошибки
│   └── email.php    # Email шаблоны
└── ru-RU/
    ├── app.php
    ├── error.php
    └── email.php

modules/*/messages/
├── en-US/
│   ├── user.php     # Переводы модуля
│   └── enum.php     # Переводы Enum
└── ru-RU/
    ├── user.php
    └── enum.php
```

### Ключи переводов:
- `field.*` - поля форм
- `title.*` - заголовки страниц
- `button.*` - кнопки
- `message.*` - сообщения пользователю
- `error.*` - ошибки
- `count.*` - счетчики

## Email система

### Возможности:
- Приветственные письма новым пользователям
- Сброс пароля через email
- HTML и текстовые шаблоны
- Поддержка SMTP и file transport

### Конфигурация:
```bash
# Для разработки (файлы в runtime/mail/)
MAIL_USE_FILE_TRANSPORT=true

# Для продакшена (SMTP)
MAIL_USE_FILE_TRANSPORT=false
MAIL_DSN=smtp://user:pass@smtp.gmail.com:587
```

## Консольные команды

### Управление пользователями:
```bash
# Создание администратора
php yii user/create-admin [username] [email] [password]

# Смена пароля
php yii user/change-password [username] [new-password]

# Информация о пользователе
php yii user/info [email]

# Список пользователей
php yii user/list
```

### Управление RBAC:
```bash
# Инициализация ролей и разрешений
php yii rbac/init
```

### Генерация тестовых данных:
```bash
# Все данные
php yii seed/all

# Отдельно
php yii seed/users [count]
php yii seed/categories [count]
php yii seed/products [count]

# Очистка
php yii seed/clear
```

## Разработка

### Composer скрипты:
```bash
# Полная настройка локально
composer run setup

# Настройка Docker
composer run docker-setup

# Создание админа
composer run create-admin

# Генерация данных
composer run seed-data

# Копирование .env
composer run copy-env
```

### Добавление нового модуля:
1. Создайте структуру в `modules/new-module/`
2. Зарегистрируйте в `config/web.php` и `config/console.php`
3. Добавьте в bootstrap список
4. Реализуйте `BootstrapInterface` для URL правил

### Добавление разрешений:
1. Добавьте в `commands/RbacController::actionInit()`
2. Назначьте ролям
3. Используйте в контроллерах: `['allow' => true, 'roles' => ['permission.name']]`

## Деплой в продакшн

### 1. Подготовка:
```bash
# Клонирование
git clone git@github.com:olddeda/kub24.test.git
cd kub24.test

# Настройка окружения
cp .env.example .env
# Отредактируйте .env для продакшена
```

### 2. Конфигурация .env:
```bash
APP_ENV=prod
APP_DEBUG=false
DB_HOST=your-db-host
REDIS_HOST=your-redis-host
MAIL_USE_FILE_TRANSPORT=false
MAIL_DSN=smtp://user:pass@smtp.host:587
```

### 3. Установка и инициализация:
```bash
composer install --no-dev --optimize-autoloader
php yii migrate --interactive=0
php yii rbac/init
php yii user/create-admin admin admin@yourdomain.com secure-password
```

### 4. Настройка веб-сервера:
- Document Root: `/path/to/app/web/`
- PHP-FPM или mod_php
- Настройка SSL сертификата
- Gzip сжатие статики

## Дополнительные ресурсы

- [Yii2 Framework Documentation](https://www.yiiframework.com/doc/guide/2.0/en)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.0/)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)
- [Redis Documentation](https://redis.io/documentation)