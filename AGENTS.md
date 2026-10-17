# AI Assistant & Development Guidelines (AGENTS.md)

Welcome to the **SIGAP** project. This file serves as the single source of truth for AI agents (Antigravity, Cursor, Copilot, Claude) and developers working on this codebase.

---

## 1. Tech Stack Overview
- **Framework**: CodeIgniter 4 (v4.7+)
- **PHP Version**: PHP 8.2+ (active: PHP 8.5)
- **Database**: PostgreSQL (Driver: `Postgre`, port `5432`, schema `public`)
- **Static Analysis**: PHPStan Level 5 (`phpstan.neon`)
- **Testing**: PHPUnit 11 (`phpunit.xml`)
- **Server**: Built-in CLI (`php spark serve`)

---

## 2. Core Architecture & Coding Conventions

### A. Entity Pattern (Mandatory for Data Models)
Avoid passing unstructured associative arrays across the application. Always define and use **CI4 Entities** with clear property definitions:
- Place entities in `app/Entities/` (e.g., `app/Entities/User.php`).
- Annotate class docblocks with `@property` tags for all table columns so IDEs and AI models know all valid properties and types:
```php
<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * @property int|null $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class User extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'id' => 'integer',
    ];
}
```

### B. Models
- Define `$table`, `$primaryKey`, `$allowedFields`, and explicitly set `$returnType` to the corresponding Entity class.
- Always enable `$useTimestamps = true` unless specifically not needed:
```php
<?php

namespace App\Models;

use App\Entities\User;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = User::class;
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'email', 'password'];
}
```

### C. Controllers (Slim Controllers)
- Keep controllers thin: handle HTTP request parsing, validation, call Model/Service, and return response.
- For API responses, use standard JSON structures:
  - Success: `return $this->response->setJSON(['status' => 'success', 'data' => $data]);`
  - Error: `return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => $message]);`

---

## 3. Database Conventions (PostgreSQL Specifics)
- **Table Names**: Lowercase, plural, snake_case (e.g. `users`, `transaction_logs`).
- **Primary Keys**: `id` auto-incrementing integer / serial / bigserial.
- **Foreign Keys**: `singular_id` (e.g., `user_id`, `category_id`).
- **Compatibility**:
  - PostgreSQL does not support MySQL `ENUM` types directly in CI4 migrations; use `VARCHAR` with validation rules or checks.
  - Boolean columns use native `BOOLEAN` (`true`/`false`).
  - Standard timestamps: `created_at`, `updated_at`, `deleted_at`.
- **Database as Code**:
  - **NEVER** create or modify tables manually. Always write Migrations in `app/Database/Migrations/` via:
    ```bash
    php spark make:migration CreateUsersTable
    ```
  - For sample data, write Seeders in `app/Database/Seeds/` via:
    ```bash
    php spark make:seeder UserSeeder
    ```

---

## 4. AI Verification & Self-Healing Loop
Whenever you (the AI) generate or modify PHP code:
1. **Analyze & Test**: Run the verification command:
   ```bash
   composer check
   ```
   *(This runs both PHPStan static analysis and PHPUnit tests)*
2. If PHPStan flags an error (missing return types, undefined methods, typos), fix it immediately before concluding the task.
3. If new routes or database tables are added:
   - Check routes: `php spark routes`
   - Run migrations: `php spark migrate`

---

## 5. Directory Reference
```
app/
├── Config/          # Configuration files (Routes, Database, App, etc.)
├── Controllers/     # HTTP Controllers
├── Entities/        # Data Entities (strongly typed properties)
├── Models/          # Database Models
├── Database/
│   ├── Migrations/  # Version-controlled DB schema
│   └── Seeds/       # Test / Initial data seeders
├── Views/           # HTML / Blade templates
public/              # Web root (assets: css, js, images)
tests/               # PHPUnit test cases
writable/            # Cache, logs, uploads, session storage
```
