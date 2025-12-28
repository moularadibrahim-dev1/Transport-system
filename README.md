# 🚌 Suptech Transport System

A comprehensive university transport management system built with PHP and Oracle Database.

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Oracle](https://img.shields.io/badge/Oracle-XE-F80000?style=for-the-badge&logo=oracle&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

## ✨ Features

- 🔐 **Authentication System** - Secure login for Admin and Student roles
- 🚌 **Vehicle Management** - Add, edit, and track university vehicles
- 👨‍✈️ **Driver Management** - Manage driver information and assignments
- 🗺️ **Route Management** - Create and manage transport routes
- 📋 **Student Assignments** - Assign students to specific routes
- 📊 **Admin Dashboard** - Real-time statistics and overview

## 🛠️ Tech Stack

| Component | Technology |
|-----------|------------|
| Backend | PHP 7.4+ |
| Database | Oracle XE with PL/SQL |
| Frontend | HTML5, CSS3, JavaScript |
| Architecture | MVC Pattern |

## 📋 Prerequisites

- **PHP 7.4+** with OCI8 extension enabled
- **Oracle Database XE** (Express Edition)
- **XAMPP** or similar web server
- **Oracle SQL Developer** (optional, for database management)

## 🚀 Installation

### 1. Clone the repository

```bash
git clone https://github.com/moularadibrahim-dev1/Transport-system.git
cd Transport-system
```

### 2. Configure Database Connection

Edit `config/db.php` with your Oracle credentials:

```php
define('DB_USERNAME', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_CONNECTION_STRING', 'localhost/XE');
define('DB_CHARSET', 'AL32UTF8');
```

### 3. Enable OCI8 Extension

In your `php.ini`, uncomment:
```ini
extension=oci8_19
```

### 4. Run the Installer

Navigate to `http://localhost/Transport-system/install.php` to:
- Create database tables
- Set up PL/SQL procedures
- Create default admin account

### 5. Access the Application

- **URL**: `http://localhost/Transport-system/`
- **Default Admin**: Check `install.php` for credentials

## 📁 Project Structure

```
Transport-system/
├── assets/
│   ├── css/          # Stylesheets
│   └── js/           # JavaScript files
├── config/
│   └── db.php        # Database configuration
├── controllers/
│   ├── admin/        # Admin controllers
│   └── student/      # Student controllers
├── core/
│   ├── Auth.php      # Authentication handler
│   └── Database.php  # Database connection & queries
├── views/
│   ├── admin/        # Admin views
│   ├── auth/         # Login views
│   ├── layouts/      # Header & Footer
│   └── student/      # Student views
├── database_setup.sql    # Database schema
├── plsql_logic.sql       # PL/SQL procedures
├── install.php           # Installation script
└── index.php             # Application entry point
```

## 👥 User Roles

| Role | Capabilities |
|------|-------------|
| **Admin** | Full access: manage vehicles, drivers, routes, students, assignments |
| **Student** | View dashboard, select routes, view assignments |

## 🔧 Database Setup

Run these SQL files in Oracle SQL Developer:

1. `database_setup.sql` - Creates tables
2. `plsql_logic.sql` - Creates PL/SQL procedures

Or use the automated installer at `/install.php`.

## 📸 Screenshots

*Coming soon...*

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License.

## 👨‍💻 Author

**Ibrahim Moulrad**
- GitHub: [@moularadibrahim-dev1](https://github.com/moularadibrahim-dev1)

---

<p align="center">Made with ❤️ for Suptech University</p>
