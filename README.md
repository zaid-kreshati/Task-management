# Task Management System

A full-featured task management platform built with **Laravel (backend)**, **Blade (HTML, CSS, JavaScript, AJAX)** for interactive single-page requests, and **MySQL** as the database.

The application follows a **clean MVC architecture** with a **Service layer** for business logic and a **Repository layer** for database operations.

---

## 🚀 Features

### 🔐 Authentication & Authorization
- **Laravel Passport** for API authentication.  
- **Spatie Roles & Permissions** for **Admin** and **User** roles.  
- Unified dashboard (admin operations hidden from normal users).  

### ✅ Task Management
- CRUD operations for **Tasks, Subtasks, Categories, and Comments**.  
- **Soft deletes & restore** support.  
- **Task status via Enum**.  
- **Helper methods** to calculate task prices.  
- Assign **users** and **categories** to tasks.  

### 💳 Payments
- **Stripe (Sandbox)** integration for payments.  
- **Coupons, discounts, and vouchers** supported.  
- Payment success & cancel callbacks handled.  

### 📧 Notifications & Scheduling
- Automatic **email notifications** when a task deadline is exceeded.  
- Implemented using **Commands, Jobs, Mailables, and Scheduler**.  

### 🌍 Multi-language Support
- Fully supports **English** and **Arabic**, with runtime language switching.  

### 🛠️ Additional
- **Logging with Listeners & Observers**.  
- **Validation Trait** for reusable rules.  
- **Seeder** for test data.  
- **Postman** used for API testing.  

---

## 🗂️ Web Routes Overview

### Auth
- Login, Register, Logout, Leader/User registration.  

### Home & Logs
- Dashboard, activity logs.  

### Tasks
- List, search, create, edit, delete, restore, assign users/categories, payment callbacks.  

### Subtasks
- CRUD, soft delete & restore.  

### Categories
- CRUD, search, assign to tasks, soft delete & restore.  

### Comments
- CRUD on tasks and subtasks with soft delete & restore.  

### Payments
- Stripe checkout, discount, coupon & voucher management.  

### Languages
- Switch between English & Arabic.  

### Emails
- Test email endpoint.  

---

## 🛠️ Tech Stack
- **Backend**: Laravel (PHP)  
- **Frontend**: Blade (HTML, CSS, JavaScript, AJAX)  
- **Database**: MySQL  
- **Architecture**: MVC + Services + Repository  
- **Payments**: Stripe (Sandbox)  
- **Auth**: Laravel Passport, Spatie Roles & Permissions  
- **Other**: Enum, Traits, Helpers, Listeners, Observers, Seeder, Postman, Scheduler  

---

## ⚙️ Installation

1. **Clone the repository**  
   ```bash
   git clone https://github.com/your-username/task-management.git
   cd task-management
   ```

2. **Install dependencies**  
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Configure environment**  
   - Copy `.env.example` to `.env`  
   - Update database, mail, and stripe credentials in `.env`  

4. **Generate app key**  
   ```bash
   php artisan key:generate
   ```

5. **Run migrations & seeders**  
   ```bash
   php artisan migrate --seed
   ```

6. **Install Passport**  
   ```bash
   php artisan passport:install
   ```

7. **Run the server**  
   ```bash
   php artisan serve
   ```

8. **Schedule & Queue Workers**  
   - Add Laravel scheduler to your cron:  
     ```bash
     * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
     ```
   - Start queue worker:  
     ```bash
     php artisan queue:work
     ```
9. Import the provided Postman collection (Home Services.postman_collection.json) into Postman.


---

## 📬 Notifications
- Emails sent automatically when tasks exceed deadlines.  
- Powered by Laravel **Mail, Jobs, Commands, and Scheduler**.  

---

## 🌍 Multi-Language
- English & Arabic support with dynamic switching.  

---

## 📄 License
This project is for educational and professional showcase purposes.
