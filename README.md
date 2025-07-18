# Online Pharmacy Portal

A comprehensive web-based pharmacy management system built with PHP and MySQL, featuring separate interfaces for customers, managers, and administrators.

<div align="center">
 <img src="./Preview/Screenshot 2024-10-10 at 1.02.21 PM.png" alt="Homepage" style="width: 80%;"/>
 <br><strong>Homepage</strong><br><br>
 <hr>
 <img src="./Preview/Screenshot 2024-10-10 at 1.02.40 PM.png" alt="Products Page" style="width: 80%;"/>
 <br><strong>Products Page</strong><br><br>
 <hr>
 <img src="./Preview/Screenshot 2024-10-10 at 1.03.00 PM.png" alt="Admin Dashboard" style="width: 80%;"/>
 <br><strong>Admin Dashboard</strong><br><br>
 <hr>
 <img src="./Preview/Screenshot 2024-10-10 at 1.03.12 PM.png" alt="Contact Us page" style="width: 80%;"/>
 <br><strong>Contact Us page</strong><br><br>
 <hr>
</div>

## 🚀 Features

- **Customer Portal**: Browse products, place orders, manage account
- **Manager Dashboard**: Inventory management, order processing  
- **Admin Panel**: User management, system administration
- **Responsive Design**: Mobile-friendly interface
- **Secure Authentication**: Role-based access control

## 🛠️ Technologies Used

- **Backend**: PHP 8.1
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache
- **Containerization**: Docker

## 📋 Prerequisites

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Apache Web Server
- Git

## 🚀 Quick Start

### Local Development

1. **Clone the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/Online_Pharmacy_Portal.git
   cd Online_Pharmacy_Portal
   ```

2. **Set up the database**
   - Import the SQL file: `Database/PharmacyX_DB.sql`
   - Update database configuration in `db_Config/config.php`

3. **Configure the application**
   ```bash
   cp db_Config/config.example.php db_Config/config.php
   ```
   Edit `config.php` with your database credentials.

4. **Start the server**
   - Using XAMPP/WAMP: Place files in `htdocs` folder
   - Using built-in PHP server: `php -S localhost:8000`

### Docker Deployment

1. **Build and run with Docker**
   ```bash
   docker build -t pharmacy-portal .
   docker run -p 8080:80 pharmacy-portal
   ```

2. **Using Docker Compose** (recommended)
   ```bash
   docker-compose up -d
   ```

## 🌐 Deployment Options

### 1. Shared Hosting (cPanel)
1. Upload files via File Manager or FTP
2. Import database via phpMyAdmin
3. Update config.php with hosting database credentials

### 2. VPS/Cloud Server
```bash
# Install LAMP stack
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql

# Clone and setup
git clone https://github.com/YOUR_USERNAME/Online_Pharmacy_Portal.git
sudo mv Online_Pharmacy_Portal/* /var/www/html/
sudo chown -R www-data:www-data /var/www/html/
```

### 3. Heroku
1. Create a `Procfile`
2. Add ClearDB MySQL add-on
3. Deploy via Git

### 4. Railway/Vercel/Render
- Connect GitHub repository
- Add MySQL database
- Set environment variables

## 📁 Project Structure

```
├── CSS/                 # Stylesheets
├── JS/                  # JavaScript files
├── Images/              # Static images and uploads
├── Database/            # SQL files
├── db_Config/           # Database configuration
├── *.php               # Main application files
├── Dockerfile          # Docker configuration
└── README.md           # This file
```

## 🔧 Configuration

### Database Setup
1. Create a MySQL database
2. Import `Database/PharmacyX_DB.sql`
3. Update `db_Config/config.php` with your credentials

### Environment Variables (Recommended)
Create a `.env` file for sensitive configurations:
```
DB_HOST=localhost
DB_USER=root
DB_PASS=your_password
DB_NAME=PharmacyX_DB
```

## 🎨 Colour Palette

**Headings** = #111111  
**Content** = #444444  
**Buttons** = #0098DA

## 🔐 Default Login Credentials

### Admin Login
**Username**: admin01  
**Password**: mod123

### Manager Login
**Username**: manager01  
**Password**: managerPass02

**Username**: manager02  
**Password**: managerPass03

### Customer Login
**Username**: user01  
**Password**: userPass01

**Username**: user02  
**Password**: userPass02

## 🚀 Deployment Guide

### Step 1: Prepare for Deployment

1. **Initialize Git Repository**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   ```

2. **Create GitHub Repository**
   - Go to GitHub and create a new repository
   - Follow the instructions to push your local repo

### Step 2: Choose Deployment Platform

#### A. Heroku (Free Tier Available)
```bash
# Install Heroku CLI first
heroku create your-pharmacy-app
heroku addons:create cleardb:ignite
heroku config:get CLEARDB_DATABASE_URL
# Update your config.php with the provided database URL
git push heroku main
```

#### B. Railway (Recommended)
1. Visit [railway.app](https://railway.app)
2. Connect GitHub repository
3. Add MySQL database service
4. Deploy automatically

#### C. Render
1. Visit [render.com](https://render.com)
2. Connect GitHub repository
3. Add PostgreSQL/MySQL database
4. Configure environment variables

### Step 3: Database Migration
- Export your local database
- Import to production database
- Update config.php with production credentials

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature-name`
3. Commit changes: `git commit -am 'Add feature'`
4. Push to branch: `git push origin feature-name`
5. Submit Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Contributors

[**Moditha Marasingha**](https://github.com/ModithaM) | 
[**Hasindu Chanuka**](https://github.com/hasindu1998) | 
[**Kulanya Lisaldi**](https://github.com/KulanyaLisaldi) | 
[**Deshan**](https://github.com/Deshan-z) | 
[**Medhani W A P**](https://github.com/PabodaWA) - IT23569522

## 📞 Support

For support and questions:
- Open an issue on GitHub
- Contact the development team

## 🔗 Live Demo

[View Live Demo](https://your-deployed-site.com) (Update with your deployment URL)

---

⭐ Star this repository if you find it helpful!
