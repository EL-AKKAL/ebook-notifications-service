## About Project

This project is a dedicated Notifications Microservice. It is designed to handle user notifications independently from authentication and core user services, following a scalable microservices architecture.

The service is responsible for receiving, retrieving, updating, and managing notification states (read/unread) while enforcing strict user-level data isolation using JWT-based identity propagation.

- [see api endpoints here](https://ebook-notifications-service.elakkalayoub.cloud/).

## Installation & Usage

### Requirements

- PHP 8.2+
- Composer
- MySQL (or SQLite for testing)
- Node.js (for frontend)
- Git

### Clone the Repository

```
git clone https://github.com/EL-AKKAL/ebook-notifications-service.git
cd ebook-notifications-service
```

### Install Dependencies

```
composer install
```

### Environment Setup

```
cp .env.example .env
```

Then configure the environment variables below with the appropriate credentials.

- this includes jwt config , rabbitmq config , pusher config

```
# pusher configs for broadcasting notifications
PUSHER_APP_ID="your-pusher-app-id"
PUSHER_APP_KEY="your-pusher-key"
PUSHER_APP_SECRET="your-pusher-secret"
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME="https"
PUSHER_APP_CLUSTER="mt1"

# switch to pusher
BROADCAST_CONNECTION=log

# switch to rabbitmq to connect with other micros
QUEUE_CONNECTION=database

# same jwt settings as authentication service
JWT_SECRET=

#rabbitmq config / see config/queue
RABBITMQ_HOST=127.0.0.1
RABBITMQ_PORT=5672
RABBITMQ_USER=guest
RABBITMQ_PASSWORD=guest
RABBITMQ_VHOST=/
```

Generate the application key:

```
php artisan key:generate
```

dont forget to configure your database connection.

Run migrations:

```
php artisan migrate
```

for test purposes you can run this custom command:

```
php artisan app:generate-notifications

possible parameters :
# user id exists in the auth table outside this service (you decide)
# notifications number
```

### Run the Server

```
php artisan serve

#note : save the url u see in terminal , you gonna use it for front-end.
```

### Run queue

```
php artisan queue:listen
```

### Run rabbitmq custom command

```
php artisan app:listen-registered-users

# this command will listen to any published new user
In production, RabbitMQ is the primary event source.
Pusher is currently used for real-time UI updates, but duplicate events are expected during transition. This behavior is temporary.
```

## Architecture Principles

- Microservice-first architecture — no user model, no auth system, only trusted JWT identity
- Separation of concerns — API logic, domain logic, persistence, and middleware clearly separated
- Security by design — user access validated via JWT middleware
- Test-driven mindset — full Pest Feature Test coverage for API behavior
- CI-ready — automated test execution before deployment
- Frontend-ready API — enriched response structure (computed attributes, enums, UI-ready metadata)

## Notification Domain Features

- Cursor-paginated notification listing
- Read / unread status tracking
- Read-all / unread-all actions
- User-scoped query filtering
- Enum-backed notification types with API-friendly label mapping
- Hidden internal fields with computed UI-safe output
- Safe ownership enforcement (users cannot access others’ notifications)

### Testing Strategy (PestPHP)

The project includes Feature-level API tests that validate:

- Authorized vs unauthorized access
- Correct user-scoped data retrieval
- State transitions (read/unread)
- Cross-user access protection
- JWT-required route enforcement

A reusable test helper layer provides:

- Token generation
- Authorization headers
- Seeded test data
- Clean, readable test suites

## CI/CD Pipeline

The project integrates GitHub Actions to:

- Run tests automatically on every push
- Inject secrets securely (JWT_SECRET)
- Prevent deployments if tests fail
- Deploy safely to a VPS via SSH

This guarantees continuous quality enforcement and safe delivery.
