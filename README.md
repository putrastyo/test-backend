## Test Backend

### DB Design
![DB Design](./public/db-design.png)

### API Design
![Api Design](./public/api-design.png)

### Tech Stack

1. PHP v8.2
2. Laravel v12
3. MySQL

## Prerequisites

- Git
- PHP (>=8.2)
- Composer
- MySQL

## Installation Steps

### 1. Clone Repository

```bash
git clone https://github.com/putrastyo/test-backend.git
cd test-backend
```

### 2. Install Dependencies

Install PHP dependencies using Composer:

```bash
composer install
```

### 3. Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

### 4. Generate Application Key

Generate a new application key:

```bash
php artisan key:generate
```

### 5. Database Configuration

Update the `.env` file with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 6. Run Migrations and Seeders

Set up the database structure and seed it with initial data:

```bash
php artisan migrate --seed
```

### 7. Start Development Server

Start the local development server:

```bash
php artisan serve
```

Your application will be available at [http://localhost:8000](http://localhost:8000).
