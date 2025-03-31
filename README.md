# FilaPHP - Laravel Blog System with Filament Admin Panel

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-purple.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![Filament Version](https://img.shields.io/badge/Filament-3.x-blue.svg)](https://filamentphp.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A modern, feature-rich blog system built with Laravel 10 and Filament 3, featuring a robust admin panel and user-friendly interface.

## 🚀 Features

### Core Functionality
- **Authentication System**
  - User registration and login
  - Role-based access control (Admin/User)
  - Remember me functionality
  - Password hashing with bcrypt

- **Blog Management**
  - CRUD operations for posts
  - Rich text content editor
  - Featured image support
  - Post scheduling
  - Draft/publish status

- **Category System**
  - Hierarchical category management
  - Category-based post organization
  - Category-specific views
  - Category post count tracking

- **Tagging System**
  - Dynamic tag creation
  - Many-to-many relationship with posts
  - Tag-based filtering
  - Tag cloud generation

- **Comment System**
  - Nested comments
  - User-specific comment management
  - Comment moderation tools
  - Real-time comment updates

- **Like System**
  - Post liking functionality
  - Like count tracking
  - User-specific like management
  - Real-time like updates

### Technical Features
- **Database Structure**
  ```sql
  posts
    - id (primary key)
    - title
    - slug
    - content
    - featured_image
    - is_published
    - published_at
    - category_id (foreign key)
    - created_at
    - updated_at

  categories
    - id (primary key)
    - name
    - slug
    - description
    - created_at
    - updated_at

  tags
    - id (primary key)
    - name
    - slug
    - created_at
    - updated_at

  post_tag (pivot)
    - post_id (foreign key)
    - tag_id (foreign key)

  comments
    - id (primary key)
    - content
    - user_id (foreign key)
    - post_id (foreign key)
    - created_at
    - updated_at

  likes
    - id (primary key)
    - user_id (foreign key)
    - post_id (foreign key)
    - created_at
    - updated_at
  ```

- **API Endpoints**
  ```
  GET    /api/posts
  POST   /api/posts
  GET    /api/posts/{id}
  PUT    /api/posts/{id}
  DELETE /api/posts/{id}
  ```

## 🛠️ Technical Stack

- **Backend Framework:** Laravel 10.x
- **Admin Panel:** Filament 3.x
- **Frontend:**
  - Blade Templates
  - TailwindCSS
  - Alpine.js
- **Database:** SQLite/MySQL
- **Authentication:** Laravel Auth
- **File Storage:** Laravel Storage

## 📋 Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- SQLite or MySQL

## 🔧 Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/filaphp.git
cd filaphp
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env`
```env
DB_CONNECTION=sqlite
# or
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=filaphp
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations and seeders
```bash
php artisan migrate
php artisan db:seed
```

6. Create storage link
```bash
php artisan storage:link
```

7. Build assets
```bash
npm run dev
```

8. Start the server
```bash
php artisan serve
```

## 🔐 Security

- CSRF protection enabled
- XSS prevention
- SQL injection protection
- Input validation
- Role-based access control
- Secure password hashing

## 📦 Dependencies

```json
{
    "require": {
        "php": "^8.1",
        "laravel/framework": "^10.0",
        "filament/filament": "^3.0",
        "laravel/sanctum": "^3.2"
    }
}
```

## 🔄 Development Workflow

1. Create feature branch
```bash
git checkout -b feature/your-feature
```

2. Make changes and commit
```bash
git add .
git commit -m "feat: add new feature"
```

3. Push changes
```bash
git push origin feature/your-feature
```

4. Create pull request

## 📝 Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## 📄 License

MIT License

Copyright (c) 2024 FilaPHP

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
