# GUVI Task - Full Stack Authentication System

A premium user authentication and profile management system built with a modern Full-Stack architecture.

## 🚀 Features
- **Frontend**: Responsive UI with Bootstrap 5 and Glassmorphism.
- **Backend**: PHP 8.x (Decoupled with AJAX).
- **Security**: MySQL Prepared Statements, BCRYPT Hashing, and Token-based Sessions.
- **Databases**: 
  - **MySQL**: Account registration.
  - **MongoDB**: User profile details.
  - **Redis**: Server-side session cache.

## 🛠️ Prerequisites
- **Laragon Full** (Apache, MySQL)
- **Redis for Windows**
- **MongoDB**
- **PHP Extensions**: `redis`, `mongodb`

## 📦 Installation
1. Clone the repository to your `www` folder.
2. Run `schema.sql` in your MySQL database.
3. Ensure Redis and MongoDB services are active.
4. Access via `http://localhost/GUVI Task/`

## 🗄️ Architecture
- **No PHP Sessions**: Uses a custom token stored in **LocalStorage** and verified against **Redis**.
- **Data Isolation**: Login data is in MySQL; personal details are in MongoDB.
