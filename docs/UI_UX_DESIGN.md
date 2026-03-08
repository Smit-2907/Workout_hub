# Workout Hub 2.0 - UI/UX Design

## 1. Page Hierarchy
- **Public**
  - `index.html` (Landing/Home)
  - `exercises.html` (Library with Filters)
  - `login.html` / `signup.html`
- **User (Authenticated)**
  - `dashboard.html` (Recent activity, saved workouts)
  - `exercise-detail.html` (Video + Steps)
- **Admin**
  - `admin/dashboard.html` (Stats)
  - `admin/manage-exercises.html` (Table view + Add/Edit modals)

## 2. Core Components
- **Navbar**: Sticky top, links to Home, Exercises, Login/Dashboard.
- **Hero Section**: Catchy title + "Start Training" CTA.
- **Exercise Card**: Image, Title, Muscle Group badge, Difficulty badge.
- **Filter Sidebar**: Checkboxes for Muscle Groups (Chest, Back, Legs) and Difficulty.
- **Video Player**: High-quality embed with description below.

## 3. UI Layout (Draft)
### Home Page
```text
[ Navbar ]
----------------------------------
| [ Hero: Transformation Starts ]|
| [ CTA: Explore Workouts ]     |
----------------------------------
| [ Muscle Group Categories ]   |
----------------------------------
| [ Featured Exercises Cards ]  |
----------------------------------
[ Footer ]
```

### Exercise Detail Page
```text
[ Navbar ]
----------------------------------
| [ Title: Push-Ups ]            |
| [ Difficulty: Beginner ]       |
----------------------------------
|      [ Video Player ]          |
----------------------------------
| [ Instructions / Steps ]       |
| 1. Place hands...              |
| 2. Lower body...               |
----------------------------------
[ Footer ]
```
