# ANC Management System

A modern web application starter kit with RBAC (Role-Based Access Control) services, built with Laravel and Vue.js. This project serves as a robust foundation for building CMS (Content Management System) applications with built-in user management, role-based permissions, and authentication systems.

## 🌟 Features

### Role-Based Access Control (RBAC)
- **User Management**: Complete user management system with registration, authentication, and profile management
- **Role Management**: Create and manage different user roles (e.g., Admin, Editor, Viewer) using Spatie Laravel Permission package
- **Permission System**: Granular permission control for different actions and resources with predefined permissions
- **Access Control**: Middleware-based route protection and component-level access control

### CMS Capabilities
- **Modular Architecture**: Easy to extend and customize for different CMS needs
- **Component-Based UI**: Reusable Vue components for rapid development
- **Form Management**: Advanced form handling with validation and file uploads
- **Data Management**: CRUD operations with proper authorization checks
- **API Ready**: RESTful API endpoints for integration with other systems

## 🚀 Tech Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Vue.js 3 with TypeScript & intertia
- **UI Framework**: Tailwind CSS
- **State Management**: Pinia
- **Form Validation**: Vee-Validate with Zod
- **Build Tool**: Vite
- **Database**: MySQL (configured in .env)
- **RBAC Package**: [Spatie Laravel Permission](https://github.com/spatie/laravel-permission)

## 🛠️ Prerequisites

Before you begin, ensure you have the following installed:
- PHP >= 8.1
- Node.js >= 18.x
- Composer
- MySQL
- npm or yarn

## 📦 Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd anc_management
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations:
```bash
php artisan migrate
```

## 🚀 Development

To start the development server:

```bash
# Start Laravel development server
php artisan serve

# Start Vite development server
npm run dev
```

The application will be available at `http://localhost:8000`

## 🏗️ Building for Production

```bash
# Build frontend assets
npm run build

# Optimize Laravel
php artisan optimize
```

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run frontend tests (if configured)
npm run test
```

## 📝 Code Style

The project uses ESLint and Prettier for code formatting:

```bash
# Format code
npm run format

# Check formatting
npm run format:check

# Lint code
npm run lint
```

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👥 Authors

- ssbhattarai - Initial work

## 🎯 Use Cases

This starter kit is perfect for:
- Building custom CMS applications
- Developing role-based management systems
- Creating admin panels with user management
- Implementing secure multi-user applications
- Rapid prototyping of authenticated web applications

## 🔐 Security Features

- **Authentication**: Secure login system with remember me functionality
- **Authorization**: Role and permission-based access control
- **CSRF Protection**: Built-in Cross-Site Request Forgery protection
- **XSS Prevention**: Input sanitization and output escaping
- **Secure Password Handling**: Bcrypt password hashing
- **Session Management**: Secure session handling and timeout

## 🔧 RBAC Setup

### Initial Setup
The project comes with predefined roles and permissions. To set up the RBAC system:

1. Run the permissions seeder command:
```bash
php artisan permissions:seed
```

This command will:
- Create default permissions:
  - User management: `view users`, `create users`, `edit users`, `delete users`
  - Role management: `view roles`, `create roles`, `edit roles`, `delete roles`
  - Permission management: `view permissions`, `create permissions`, `edit permissions`, `delete permissions`
  - Dashboard access: `view dashboard`
- Create a `super_admin` role if it doesn't exist
- Assign all permissions to the `super_admin` role
- Handle existing permissions gracefully (won't duplicate if already exists)

The command uses database transactions to ensure data integrity and will rollback if any error occurs during the seeding process.

### Available Roles
- **Super Admin**: Has all permissions (automatically assigned when running `permissions:seed`)
- **Admin**: Can manage users and content
- **Editor**: Can create and edit content
- **User**: Basic access with limited permissions

### Checking Permissions
In your Vue components, you can check permissions using the provided composable:
```vue
<script setup>
import { usePermission } from '@/composables/usePermission'

const { hasPermission } = usePermission()
</script>

<template>
  <button v-if="hasPermission('edit-posts')">
    Edit Post
  </button>
</template>
```

In Laravel controllers, use the permission middleware:
```php
public function __construct()
{
    $this->middleware('permission:edit-posts');
}
```
