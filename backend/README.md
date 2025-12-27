# Backend Implementation Guide

This folder contains the PHP backend and Admin Panel for Buildyourstudio.

## 1. Database Setup
1. Create a MySQL database (e.g., `buildyourstudio`).
2. Import the `db_structure.sql` file into your database using phpMyAdmin or CLI.
3. Update `backend/config.php` with your database credentials.

## 2. API Endpoints
The React frontend (src) will communicate with these PHP scripts found in `backend/api/`.

## 3. Admin Panel
Access the Admin Panel at `/backend/admin/index.php`.
Default Credentials:
- Email: `admin@example.com`
- Password: `password123`
