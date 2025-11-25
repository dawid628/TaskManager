# Task Manager - Laravel + Tailwind CSS + Docker

## 🎬 This is how I code

> **Want to see the app in action?** Check out the video presentation: [Watch on YouTube](https://www.youtube.com/watch?v=dQw4w9WgXcQ)

A simple and elegant task management web application built with Laravel, featuring drag-and-drop task reordering, project management, and a modern UI with Tailwind CSS.

## 🛠️ Technologies Used

- **Backend:**
    - Laravel 12.x (PHP 8.4)
    - MySQL 8.0
    - Repository Pattern + Service Layer

- **Frontend:**
    - Tailwind CSS 4.x
    - jQuery 3.7.1
    - jQuery UI 1.13.2 (Sortable)
    - SweetAlert2 11.x
    - Vite

- **DevOps:**
    - Docker + Laravel Sail
    - WSL2 (Windows Subsystem for Linux)

## 📋 Requirements

Before running this project, make sure you have:

- **WSL2** (Windows Subsystem for Linux 2) installed and configured
- **Docker Desktop** with WSL2 integration enabled
- **Ubuntu** distribution installed in WSL2

## 🚀 Installation & Setup

### 1. Extract Project in WSL2

Open Ubuntu terminal and navigate to your home directory:
```bash
cd ~
```

If your ZIP file is in Windows Downloads folder, copy it to WSL2:
```bash
cp /mnt/c/Users/YOUR_WINDOWS_USERNAME/Downloads/TaskManager-main.zip ~/
```

Extract the ZIP file:
```bash
unzip TaskManager-main.zip
cd TaskManager-main
```

**Note:** If the extracted folder has a different name (e.g., `task-manager-main`), adjust the command accordingly.

### 2. Install Composer Dependencies

Install PHP dependencies using Docker (this installs Laravel Sail):
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

**Note:** This step takes a few minutes. It's required only once.

### 3. Set Up Environment
```bash
# Copy environment file
cp .env.example .env
```

**Important:** Open the `.env` file and verify that the database configuration is set correctly:
```env
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

If these values are different or commented out, update them as shown above.

### 4. Set Up Sail Alias
```bash
echo "alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'" >> ~/.bashrc
source ~/.bashrc
```

### 5. Start Docker Containers
```bash
# Start all Docker containers (first run takes 5-10 minutes)
sail up -d
```

**Note:** Docker will download and build images. Wait until you see "done" messages.

### 6. Install Frontend Dependencies & Configure Application
```bash
# Generate application key
sail artisan key:generate

# Install Node packages
sail npm install

# Run database migrations
sail artisan migrate

# Set proper permissions
sail shell
chmod -R 775 storage bootstrap/cache
exit
```

### 7. Start Development Server

Open **two terminal windows** in Ubuntu:

**Terminal 1 - Docker Containers (keep running):**
```bash
cd ~/task-manager
sail up
```

**Terminal 2 - Vite for CSS/JS compilation:**
```bash
cd ~/task-manager
sail npm run dev
```

### 8. Access the Application

Open your browser and navigate to:
```
http://localhost
```

You should see the Task Manager application! 🎉

## 🗂️ Project Structure
```
task-manager/
├── app/
│   ├── Http/Controllers/      # Request handlers
│   ├── Http/Requests/         # Custom requests
│   ├── Models/                # Eloquent models
│   ├── Repositories/          # Data access layer
│   └── Services/              # Business logic
├── database/
│   └── migrations/            # Database schema
├── resources/
│   ├── css/                   # Tailwind CSS
│   └── views/                 # Blade templates
│       ├── layouts/           # Layout files
│       ├── tasks/             # Task views & modals
│       └── projects/          # Project views & modals
├── routes/
│   └── web.php               # Application routes
└── README.md
```

## 💾 Database Schema

### Tasks Table
- `id` - Primary key
- `name` - Task name (string)
- `priority` - Position in list (integer, auto-assigned)
- `project_id` - Foreign key to projects (nullable)
- `created_at` - Timestamp
- `updated_at` - Timestamp

### Projects Table
- `id` - Primary key
- `name` - Project name (string)
- `created_at` - Timestamp
- `updated_at` - Timestamp

## 🎨 Key Features

### Task Management
- Create tasks with optional project assignment
- Edit task details
- Delete tasks with confirmation
- Automatic priority numbering (#1, #2, #3...)

### Drag & Drop Reordering
- Intuitive drag-and-drop interface
- Real-time priority updates
- Visual feedback during dragging
- Automatic renumbering after reorder

### Project Filtering
- View all tasks or filter by project
- Dropdown selection with instant filtering
- Clear filter option
- Task count per project

## 🐛 Troubleshooting

### Issue: Containers won't start
```bash
# Check if Docker Desktop is running
# Restart Docker Desktop, then:
sail down -v
sail up -d
```

### Issue: Permission errors
```bash
sail shell
chmod -R 775 storage bootstrap/cache
chown -R sail:sail storage bootstrap/cache
exit
```

### Issue: Styles not loading
```bash
# Make sure Vite is running in a separate terminal
sail npm run dev

# Check browser console for errors (F12)
```

### Issue: Vite module not found error
```bash
# Clean install node modules
sail shell
rm -rf node_modules package-lock.json
npm cache clean --force
exit

sail npm install
sail npm run dev
```

### Issue: Database connection refused
```bash
# Restart containers and wait for MySQL to start
sail down
sail up -d
# Wait 20-30 seconds, then:
sail artisan migrate
```

### Issue: Database connection error
Verify your `.env` file has:
```env
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

### Issue: Port already in use
```bash
# Check what's using port 80
sudo lsof -i :80

# Or change port in docker-compose.yml
# Then restart: sail down && sail up -d
```

## 🔐 Shutting Down

When you're done working:
```bash
# Stop Vite (Ctrl + C in Terminal 2)

# Stop Docker containers
sail down

# Optional: Shutdown WSL2 to free up RAM (in Windows PowerShell)
wsl --shutdown
```

## 📝 Architecture Decisions

### Repository Pattern
Data access is abstracted through repositories, making the codebase more testable and maintainable.

### Service Layer
Business logic is separated from controllers into service classes for better organization and reusability.

### Priority System
Tasks use numeric priorities (1, 2, 3...) representing their position in the list, not importance levels. Priority is automatically assigned and updated through drag-and-drop.

### Modal-Based UI
All create/edit operations use modals for a seamless user experience without page reloads.

## 🎯 Task Requirements Fulfilled

✅ Create task (task name, priority, timestamps)  
✅ Edit task  
✅ Delete task  
✅ Reorder tasks with drag and drop (priority auto-updates, #1 at top)  
✅ Tasks saved to MySQL table  
✅ **BONUS:** Project functionality with dropdown filtering

## 📧 Notes

- The application runs entirely in Docker containers - no need to install PHP, MySQL, or Node.js locally
- All commands must be run through `sail` (e.g., `sail artisan` instead of `php artisan`)
- Files are stored in WSL2 filesystem for better performance
- The priority field represents the task's position in the list, not its importance level
---

**Happy task managing!** 🎉
