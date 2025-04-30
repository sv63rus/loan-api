# Loan API

Система выдачи кредитов на Symfony 7 / PHP 8.3 / SQLite

## Описание

API позволяет:
- Регистрация клиента
- Проверка возможности выдачи кредита (с учётом правил)
- Оформление кредита (с фиксацией заявки и уведомлением)

Применена архитектура:
- DDD (Domain-Driven Design)
- Clean Architecture
- SOLID
- Модульность и разделение ответственности

## Требования

- PHP >=8.3
- Composer
- Docker & Docker Compose (опционально)

## Установка (локально)

1. Клонировать репозиторий:
   ```bash
   git clone <repo_url> loan-api
   cd loan-api
   ```
## Установить зависимости:

```bash
composer install
```
Подготовить базу данных и миграции:

```bash
php bin/console doctrine:migrations:migrate
```
## Запустить встроенный сервер:

```bash
symfony server:start
```
API будет доступен по адресу https://localhost:8000

## Установка с Docker
Собрать и запустить контейнеры:

```bash
docker-compose down -v
docker-compose up -d --build
```
Зайти в контейнер PHP и прогреть миграции:

```bash
docker exec -it loan_api_php bash
php bin/console doctrine:migrations:migrate --no-interaction
exit
```
Открыть в браузере http://localhost

### Эндпоинты
Клиенты
POST /api/clients — создать клиента (возвращает {id})

Кредиты
POST /api/credits/check — проверить выдачу (запускает check, возвращает {clientId, eligible, reason})

POST /api/credits/issue — оформить кредит (возвращает {loanId, message} или ошибку)

Документация Swagger: http://localhost/api/doc

Миграции
Скрипты Doctrine Migrations расположены в migrations/. Для создания новой миграции:

```bash
php bin/console make:migration
```

### Тесты
Запуск PHPUnit:

```bash
vendor/bin/phpunit
```

## Линтеры и статический анализ
```bash 
composer lint — запускает все: PHPCS, PHPStan, Psalm, PHP Insights

composer lint:phpcs

composer lint:phpstan

composer lint:psalm

composer lint:insights
```
## Docker
Образы собираются по Dockerfile (PHP-FPM + SQLite)

Nginx настроен в docker/nginx/default.conf

docker-compose.yml содержит сервисы php и nginx

Конфигурация
Настройки правил проверки кредитов в config/packages/credit.yaml:

```yaml
parameters:
credit.score_threshold: 500
credit.income_minimum: 1000
credit.age_min: 18
credit.age_max: 60
credit.allowed_regions: ['PR','BR','OS']
```
Примечания
Для расширения логики проверки добавляйте новые спецификации в Domain/Specification и помечайте тегом credit_specification в services.yaml.

Все операции создания/проверки/оформления кредитов реализованы через команды/хендлеры (DDD).

Уведомления клиенту логируются через LoggerCreditNotifier в var/log/dev.log
Можно было бы вынести в отдельный файл, но и так уже потратил слишком много времени