# IntraSpots

**Web-Based Interactive Tourism Guide for Intramuros, Manila**
*Academic Year 2025–2026*

---

## 1. Project Overview

IntraSpots is a web-based interactive tourism guide for Intramuros, Manila. It lets visitors explore heritage spots, read and submit reviews, and engage with a community — while giving administrators a full CRUD panel with image management, data visualization, and a virtual assistant.

---

## 2. Project Objectives

- Develop a dynamic website with full database integration.
- Implement CRUD (Create, Read, Update, Delete) operations for all core entities.
- Present data using appropriate visualization techniques on an admin dashboard.
- Integrate a virtual assistant for user guidance and FAQ automation.
- Apply software engineering principles in teamwork and project development.

---

## 3. Technology Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Backend | PHP 8.2 |
| Database | MySQL / MariaDB |
| Web Server | Apache (XAMPP / WAMP) |
| Image Uploads | PHP multipart/form-data, stored in `/images` |
| Virtual Assistant | Chaindesk AI (embedded chatbot widget) |
| Version Control | Git |

---

## 4. Features & Functionality

### 4.1 CRUD Operations

The admin panel (`admin.php` + `admin.js`) provides full create, read, update, and delete operations for:

- **Spots** — name, category, address, description, history, featured flag, and image upload.
- **Reviews** — view all user reviews, add admin replies, or delete inappropriate entries.
- **Users** — promote/demote roles, delete accounts.

### 4.2 Image Management (Spots)

Admins can upload, replace, or remove a photo for each spot directly from the edit modal:

- Drag-and-drop or click-to-browse upload zone with live preview.
- Server-side validation: accepted types are JPG, PNG, WEBP, GIF; maximum size 5 MB.
- Uploaded files are saved to `/images/` with a unique timestamped name (`spot_<time>_<hex>.ext`).
- Replacing an image automatically deletes the previous uploaded file; original seed assets are never touched.

### 4.3 Data Visualization — Admin Dashboard

The Dashboard tab aggregates live statistics from the database:

- **Stat cards:** Total Users, Total Spots, Total Reviews, Featured Spots.
- **Recent Reviews feed** — the latest community submissions at a glance.
- **Visits by Page** — ranked table showing which pages receive the most traffic.
- **Visits per Day** — last 14 days, showing daily visit trends.

### 4.4 Virtual Assistant

A Chaindesk AI-powered chatbot widget is embedded site-wide. It handles:

- Answering common questions about Intramuros spots, opening hours, and admission.
- Navigating users to relevant pages within the site.
- Providing recommendations and help prompts for first-time visitors.

### 4.5 User Authentication

- Login / logout system with PHP sessions.
- Role-based access: `admin` vs. `user`.
- Admin routes are protected — non-admin requests receive HTTP 403.

### 4.6 Community & Reviews

- Authenticated users can submit star ratings and written reviews per spot.
- Community voting (upvotes) on reviews.
- Admins can reply to reviews directly from the admin panel.

---

## 5. Project Structure

```
webdev-project/
├── admin/
│   ├── spots.php           # Spots CRUD + image upload
│   ├── reviews.php         # Reviews CRUD + admin reply
│   ├── users.php           # User management
│   ├── stats.php           # Dashboard statistics
│   └── visits.php          # Page visit analytics
├── api/
│   ├── fetch_reviews.php   # Fetch reviews per spot
│   └── vote.php            # Community voting
├── css/                    # Stylesheets
├── images/                 # Spot images (incl. uploads)
├── includes/               # Shared PHP (db.php)
├── database/               # SQL schema & seed data
├── admin.php               # Admin panel (HTML shell)
├── admin.js                # Admin panel JavaScript
└── home.php / *.html       # Public-facing pages
```

---

## 6. Setup & Installation

### Prerequisites

- PHP 8.2+
- MySQL / MariaDB
- Apache web server (XAMPP, WAMP, or Laragon)

### Steps

1. Clone or copy the project into your web server root (e.g. `htdocs/webdev-project`).

2. Import the database schema:
   ```bash
   mysql -u root -p < database/intraspot_db.sql
   ```

3. Configure your database credentials in `includes/db.php`.

4. Ensure the `images/` directory is writable by the web server:
   ```bash
   chmod 775 images/
   ```

5. Start Apache and MySQL, then navigate to:
   ```
   http://localhost/webdev-project/home.php
   ```

6. Create an admin account by visiting:
   ```
   http://localhost/webdev-project/create_admin.php
   ```

---

## 7. Admin API Endpoints

All endpoints require an active admin session. Requests without one return HTTP 403.

| Method | Endpoint | Description |
|---|---|---|
| GET | `admin/spots.php` | List all spots |
| POST | `admin/spots.php` | Create or update a spot (multipart/form-data) |
| DELETE | `admin/spots.php` | Delete a spot by id |
| GET | `admin/reviews.php` | List all reviews |
| POST | `admin/reviews.php` | Save admin reply to a review |
| DELETE | `admin/reviews.php` | Delete a review |
| GET | `admin/users.php` | List all users |
| POST | `admin/users.php` | Promote or demote a user |
| DELETE | `admin/users.php` | Delete a user |
| GET | `admin/stats.php` | Dashboard statistics |
| GET | `admin/visits.php` | Visit analytics |

---

## 8. Evaluation Criteria

| Criteria | Weight |
|---|---|
| Functionality (CRUD) | 25% |
| Data Visualization | 20% |
| Virtual Assistant Feature | 15% |
| UI/UX Design | 15% |
| Code Quality & Documentation | 15% |
| Presentation & Teamwork | 10% |
| **Total** | **100%** |

---

## 9. Deliverables

- Working web application (hosted or local demo).
- Organized and documented source code.
- Project documentation (this README).
- Presentation / live demo.
- Group Contribution Report.

---

## 10. Developer Notes

- The spots card grid in the admin panel replaces the old table view — each card shows the image thumbnail, category, address snippet, and description preview.
- Image uploads are POST requests using `multipart/form-data`, not JSON. The form uses `FormData` in JavaScript to include the file alongside all other fields.
- Only files uploaded through the admin panel (prefixed `spot_`) are ever deleted automatically. The original seed images (`fort-santiago.jpg`, etc.) are never removed.
- The `admin.css` file uses CSS custom properties (`--gold`, `--bg`, `--border`, etc.) — keep these consistent if extending styles.

---

*— End of README —*
