# 🏋️ Workout Hub 2.0

Workout Hub 2.0 is a premium, modern fitness web platform designed for users to browse high-quality workout routines and for admins to manage fitness content. Built as a recreation and enhancement of a BCA college project, it follows industry-standard security practices and features a stunning **Glassmorphism** UI.

## 🌟 Key Features

### 👤 User Features
- **Modern Authentication**: Secure registration and login system using PHP PDO and password hashing.
- **Smart Dashboard**: Personalized view with session-based welcome messages and quick stats.
- **Exercise Library**: Searchable and filterable database of exercises categorized by muscle group and difficulty.
- **Detailed Guides**: Dedicated exercise pages with embedded video tutorials and step-by-step instructions.
- **Workout Tracker**: Log your sets, reps, and weights to keep track of your progress.
- **AI Recommendation**: Intelligent system that suggests exercises based on muscle groups you haven't trained recently.
- **Calorie Calculator**: Scientific estimate of calories burned using MET (Metabolic Equivalent of Task) values.

### 🛡️ Admin Features
- **Admin Dashboard**: Secure management portal for content oversight.
- **Exercise Management (CRUD)**: Easily add, edit (name), and delete exercises from the library through a clean UI.
- **Real-time Updates**: Changes made by admins reflect instantly across the platform via the API.

## 🛠️ Technology Stack
- **Frontend**: HTML5, Vanilla CSS3 (Custom Design System), JavaScript (Fetch API / AJAX)
- **Backend**: PHP 8.x (Procedural with PDO)
- **Database**: MySQL 
- **Design Style**: Glassmorphism / Modern Dark Mode

## 📂 Project Structure
```text
workout-hub/
├── assets/             # Images and local media
├── backend/            # PHP API logic and DB connections
│   ├── api.php         # Central API for all operations
│   └── db_connect.php  # Secure PDO connection
├── database/           # SQL scripts
│   ├── schema.sql      # Database tables and structure
│   └── seed_data.sql   # Initial exercise data
├── docs/               # Project documentation (Planning, UI/UX, Design)
└── frontend/           # Public facing website files
    ├── index.html      # Landing Page
    ├── exercises.html  # Library Page
    └── dashboard.html  # User Dashboard
```

## 🚀 Getting Started

### 1. Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) or any PHP/MySQL local server environment.

### 2. Database Setup
1. Open **phpMyAdmin**.
2. Create a new database named `workout_hub_2`.
3. Import `database/schema.sql` to create the tables.
4. Import `database/seed_data.sql` to populate the library with initial workouts.

### 3. Installation
1. Clone or download the project into your `htdocs` folder.
2. Ensure the database connection in `backend/db_connect.php` matches your local MySQL credentials.
3. Open your browser and navigate to `http://localhost/workout/Workout_hub/frontend/index.html`.

## 📜 Documentation
Full project phases and system designs can be found in the `docs/` folder:
- [System Design](docs/SYSTEM_DESIGN.md)
- [Project Plan](docs/PROJECT_PLAN.md)
- [UI/UX Design](docs/UI_UX_DESIGN.md)

---
*Created with ❤️ for BCA Project Excellence.*