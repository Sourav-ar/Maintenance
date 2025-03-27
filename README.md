Student Maintenance Requisition System
Overview
This web application allows students to register maintenance requisitions for various types of work in their dormitories or campus housing. The system captures student details, maintenance requirements, and feedback, then stores them in a MySQL database. Administrators can generate reports in Excel or PDF format.

Features
Student Registration Form:

Registration Number input

Student Name

Block and Room Number

Type of Work (Electrical, Plumbing, Cleaning, Internet, Laundry, Other)

Suggestions/Improvements/Feedback field

Comments text area

File attachment option (PDF, DOC, JPG)

Reporting System:

Student-wise reports

Monthly reports

Weekly reports

Work-type based reports

Export options (Excel and PDF formats)

Technology Stack
Frontend: HTML, CSS, JavaScript 

Backend: PHP 

Database: MySQL

Reporting: Libraries for PDF/Excel generation (e.g., FPDF, PHPExcel, or similar)

Installation
Prerequisites:

Web server (Apache, Nginx)

PHP 7.0+ (if using PHP backend)

MySQL 5.7+

Composer (for PHP dependencies)

Setup:

bash
Copy
# Clone the repository
git clone https://github.com/yourusername/student-maintenance-system.git

# Navigate to project directory
cd student-maintenance-system

# Install dependencies (if using PHP)
composer install

# Create database (MySQL)
mysql -u root -p -e "CREATE DATABASE maintenance_system;"

# Import database schema
mysql -u root -p maintenance_system < database/schema.sql
Configuration:

Update database credentials in config/db.php (or equivalent config file)

Configure file upload settings if needed

Usage
Student Access:

Visit the application URL

Fill out the maintenance requisition form

Submit with required attachments

Admin Access:

Login to admin panel

View all submitted requests

Generate reports based on various filters

Export reports to Excel or PDF

File Structure
Copy
student-maintenance-system/
├── assets/               # CSS, JS, images
├── config/              # Configuration files
├── controllers/         # Application logic
├── models/              # Database models
├── views/               # HTML templates
├── uploads/             # Student attachments storage
├── reports/             # Generated reports storage
├── database/
│   └── schema.sql       # Database schema
├── README.md            # This file
└── index.php            # Main entry point
Contributing
Fork the repository

