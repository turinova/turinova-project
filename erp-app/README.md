# 🧱 Turinova ERP System

A modern, modular ERP system built with PHP, MySQL, and Bootstrap. Designed to be scalable and future-ready for React frontend and Python backend integration.

## 🚀 Features

- **Modular Architecture**: Clean separation of concerns with MVC pattern
- **Role-based Access Control**: Secure authentication and authorization
- **Product Management**: Complete CRUD operations for products
- **User Management**: User administration and profile management
- **Reporting System**: Built-in reporting and analytics
- **Responsive Design**: Mobile-friendly Bootstrap interface
- **API Ready**: JSON endpoints for future frontend integration

## 🛠 Tech Stack

- **Frontend**: HTML + PHP views (Bootstrap 5)
- **Backend**: PHP (modular controllers, models)
- **Database**: MySQL (via PDO)
- **Auth**: Session-based, role-based access control
- **Future-ready for**: React frontend + Python (FastAPI) + Supabase/PostgreSQL

## 📁 Project Structure

```
/erp-app/
├── public/                  # Entry point, assets, uploads
│   ├── index.php           # Main entry point
│   └── assets/, uploads/   # Static files
├── app/
│   ├── controllers/        # Feature logic
│   ├── models/            # DB logic
│   ├── views/             # HTML templates
│   │   └── layout/        # base.php, header.php, footer.php
│   ├── middleware/        # Access control
│   └── helpers/           # Utils (validation, flash, etc.)
├── routes/
│   ├── web.php            # UI routing
│   └── api.php            # JSON endpoints
├── database/
│   ├── connection.php     # PDO connection
│   ├── migrations/        # Database migrations
│   └── seeds/            # Database seeders
├── config/
│   ├── app.php           # Application config
│   └── session.php       # Session config
├── storage/              # logs, temp files
├── .htaccess            # URL rewriting
└── README.md
```

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd erp-app
   ```

2. **Set up your web server**
   - Point your web server document root to the `public/` directory
   - Ensure mod_rewrite is enabled for Apache

3. **Configure the database**
   - Create a MySQL database named `turinova_erp`
   - Update database credentials in `config/app.php`

4. **Set permissions**
   ```bash
   chmod 755 storage/
   chmod 644 .htaccess
   ```

5. **Run database migrations**
   ```bash
   # Create database tables
   php database/migrations/create_tables.php
   ```

## 🔧 Configuration

### Database Configuration
Edit `config/app.php` to set your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'turinova_erp');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### Environment Settings
- Set `APP_ENV` to `production` in production
- Configure error reporting based on environment

## 📚 Usage

### Accessing the Application
- Navigate to your web server URL
- Default route: `/dashboard`
- Login required for most features

### Key Routes
- `/dashboard` - Main dashboard
- `/products` - Product management
- `/users` - User management
- `/reports` - Reports and analytics
- `/settings` - System settings

## 🔒 Security Features

- Session-based authentication
- CSRF protection
- Input validation and sanitization
- SQL injection prevention via PDO
- XSS protection
- Secure headers configuration

## 🧪 Development

### Adding New Features
1. Create controller in `app/controllers/`
2. Add routes in `routes/web.php`
3. Create views in `app/views/`
4. Add models in `app/models/`

### Database Changes
1. Create migration in `database/migrations/`
2. Update models accordingly
3. Test thoroughly

## 🚀 Future Enhancements

- [ ] React frontend integration
- [ ] Python FastAPI backend
- [ ] Supabase/PostgreSQL migration
- [ ] Real-time notifications
- [ ] Advanced reporting
- [ ] Mobile app

## 📝 License

This project is licensed under the MIT License.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📞 Support

For support and questions, please contact the development team. 