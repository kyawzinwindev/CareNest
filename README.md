# CareNest - Online Appointment Management System

CareNest is a web-based appointment management system designed to streamline scheduling workflows for both patients and healthcare administrators. Built on the TALL stack, the application delivers dynamic, real-time interface updates without requiring full page reloads.

---

## Key Features

### For Patients & Users
- **Real-Time Booking:** Schedule, reschedule, or cancel appointments dynamically with instant availability checks.
- **Interactive Calendar:** View available time slots and practitioner schedules through a responsive calendar interface.
- **Automated Notifications:** Receive instant feedback and status updates for appointment confirmations and updates.
- **Medical History & Dashboard:** Manage upcoming visits, view past appointment history, and update personal profile details.

### For Administrators & Staff
- **Doctor & Staff Management:** Organize practitioner schedules, working hours, and department allocations.
- **Appointment Queue Control:** Approve, reject, or reassign incoming booking requests in real time.
- **Role-Based Access Control:** Manage operational permissions for doctors, administrative staff, and system administrators.

---

## Tech Stack

- **Framework:** [Laravel 11.x](https://laravel.com/)
- **Frontend Interactivity:** [Livewire 3.x](https://livewire.laravel.com/) & [Alpine.js](https://alpinejs.dev/)
- **Styling & UI:** [Tailwind CSS](https://tailwindcss.com/)
- **Database:** MySQL

---

## System Requirements

- **PHP:** >= 8.2
- **Composer:** >= 2.x
- **Node.js:** >= 18.x & NPM
- **Database:** MySQL 8.0+ 

---

## Installation & Setup

Follow these steps to set up the project locally.

### 1. Repository Setup
Clone the repository and navigate into the project directory:

git clone https://github.com/kyawzinwindev/CareNest.git
cd CareNest

### 2. Dependency Installation
Install backend PHP packages and frontend JavaScript dependencies:

composer install
npm install

### 3. Environment Configuration
Duplicate the example environment file and generate the application key:

cp .env.example .env
php artisan key:generate

Configure your database connection in the `.env` file:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carenest_db
DB_USERNAME=root
DB_PASSWORD=

### 4. Database & Storage Migration
Run database migrations along with seeders (if available), and link the storage directory:

php artisan migrate --seed
php artisan storage:link

### 5. Compile Assets & Run Development Server
Build frontend assets and start the local Laravel development server:

npm run dev
php artisan serve

Access the application at http://127.0.0.1:8000.

---

## Project Architecture & Highlights

CareNest leverages the TALL stack to maintain high performance with minimal complexity:
- **Livewire Components:** Form validation, appointment calendar updates, and filtering operate reactivity without custom JavaScript API pipelines.
- **Alpine.js Utility:** Manages localized UI state such as dropdowns, modals, and mobile navigation menus directly in the DOM.
- **Tailwind CSS:** Provides a cohesive, responsive design tailored for accessible medical dashboards.

---

## Author

**Kyaw Zin Win**
- GitHub: [@kyawzinwindev](https://github.com/kyawzinwindev)

---

## License

This project is open-sourced software licensed under the [MIT License](LICENSE).
