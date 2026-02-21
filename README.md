# NexTask

NexTask is a task management application built with Laravel and Tailwind CSS, focused on simplicity, usability, and a clean user interface. The app allows users to manage projects, organize tasks, and track progress in a responsive and modern layout.

## Features

- **Project & Task Management:** Create projects and organize your tasks easily.
- **Drag & Drop Reordering:** Seamlessly reorder tasks using SortableJS.
- **Dark Mode Support:** Fully integrated dark mode with system preference detection and manual toggle (saved in localStorage).
- **Progress Indicators:** Real-time visual progress bars for each project.
- **Undo System (Soft Deletes):** Accidental deletions can be restored instantly via toast notifications.
- **Responsive Design:** Optimized for different screen sizes.

## Tech Stack

- **Backend:** Laravel (PHP)
- **Frontend:** Tailwind CSS, Alpine.js, Blade Templates
- **Database:** MySQL

## How to Run Locally

Follow these steps to get the project up and running on your local machine.

### Prerequisites

Ensure you have the following installed:

* PHP (>= 8.2)
* Composer
* Node.js & NPM
* MySQL 

### Installation Steps

1. **Clone the repository:**
```bash
git clone https://github.com/beyazfurkan7/nextask-todo-app.git
cd nextask-todo-app
```

2. **Install PHP dependencies:**
```bash
composer install
```

3. **Install frontend dependencies:**
```bash
npm install
```

4. **Environment Setup:**
Copy the example `.env` file and configure your database settings.
```bash
cp .env.example .env
```
*Open the `.env` file and update your `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.*

5. **Generate Application Key:**
```bash
php artisan key:generate
```

6. **Run Migrations:**
Create the necessary database tables.
```bash
php artisan migrate
```

7. **Compile Frontend Assets:**
```bash
npm run build
```

8. **Start the Local Development Server:**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser to start using NexTask.

## License

This project is open-source and available under the MIT license.