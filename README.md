# 🦅 Workout Hub 2.0 - Industry Grade Fitness Platform

Workout Hub 2.0 is a premium, production-ready web application designed for home workout enthusiasts. Built with a stunning **Glassmorphism UI** and a secured **JWT-style backend**, it offers a seamless experience for discovering exercises, tracking progress, and receiving AI-powered recommendations.

![Workout Hub 2.0](https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&q=80&w=1200)

## 💎 Features & Industry Standards

### 🛡️ Security First
- **Stateless Token Auth**: Implemented modern JWT-style authentication. No more messy session conflicts.
- **XSS Protection**: All user inputs are sanitized on the backend.
- **SQL Injection Prevention**: 100% prepared statements using PDO.
- **Session Fixation Shield**: Automatic ID regeneration and secure cookie settings.
- **Access Control**: Strict Role-Based Access Control (RBAC) for Admins and standard Users.

### ⚡ Performance & UX
- **Glassmorphism UI**: High-end aesthetic using modern CSS variables and backdrop filters.
- **Skeleton Loading**: Progressive UI states that prevent layout shifts and keep users engaged.
- **Toast Notifications**: Real-time feedback for all user actions (login, saving, errors).
- **SEO Optimized**: Custom meta tags, Open Graph support, and semantic HTML5.

### 🏋️ Core Functionality
- **Exercise Library**: 14+ pro-tier exercises with video guides and muscle focus.
- **Smart Dashboard**: Personalized statistics and activity tracking.
- **AI Recommendations**: Intelligent logic that suggests workouts based on what you *haven't* trained recently.
- **Admin Portal**: Full CRUD (Create, Read, Update, Delete) capability for managing the exercise database.

---

## 🛠️ Technology Stack
- **Frontend**: HTML5, Vanilla CSS3, Javascript (ES6+)
- **Backend**: PHP 8.1 (PDO)
- **Database**: MySQL (Optimized with Performance Indexes)
- **Authentication**: Custom Bearer Token System (Stateless)

---

## 🚀 Quick Start (Installation)

1. **Clone & Extract**: Place the project folder in your `xampp/htdocs/` directory.
2. **Setup Database**:
   - Create a database named `workout_hub` in phpMyAdmin.
   - Import `database/schema.sql` first.
   - Import `database/seed_data.sql` to populate exercises and admin accounts.
3. **Configure**:
   - Open `backend/config.php`.
   - Update `DB_PASS` and `BASE_URL` if necessary.
4. **Launch**:
   - Start Apache and MySQL in XAMPP.
   - Visit `http://localhost/workout/Workout_hub/frontend/index.html`.

### 🔑 Test Credentials (from Seed)
| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@workouthub.com` | `admin123` |
| **User** | `john@example.com` | `password123` |

---

## 📁 Project Structure 
```text
Workout_hub/
├── backend/               # Secured Backend Logic
│   ├── api.php            # Stateless API Controller
│   ├── config.php         # Centralized Environment Config
│   └── db_connect.php     # PDO Connection Engine
├── database/              # SQL Architecture & Seeding
├── frontend/              # Modern UI Layer
│   ├── auth/              # Login & Signup Pages
│   ├── js/                # Production API Helpers & Logic
│   └── style.css          # Premium Glassmorphism Design
└── README.md
```

## ✅ Production Checklist
Before taking this live, refer to the [PROD_GUIDE.md](PROD_GUIDE.md) included in the root directory for final hardening steps.

---
*Built for excellence. Workout Hub 2.0.*