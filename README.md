# 📅 BukSU Event Management System (BEMS)

BukSU Event Management System (BEMS) is a full-stack web application designed for Bukidnon State University to streamline the creation, announcement, booking, and attendance tracking of academic and campus events. It simplifies the event management process by allowing faculty and students to interact with events in real time through a user-friendly online platform.

---

## 🔍 About the Project

BEMS replaces the traditional manual process of posting events and handling venue reservations with a digital solution. This system provides a centralized hub for event discovery, reservation requests, attendance confirmation, and admin-level control, all within a clean and responsive interface.

---

## ✅ Features

### 👥 User Authentication
1. Secure login for Students, Faculty, and Admin
2. Role-based access to features (Faculty/Students/Admin)

### 📢 Event Management
3. View all approved upcoming, ongoing, and past events
4. Submit event and venue booking requests (faculty)
5. RSVP to confirm attendance for events
6. Admin approval/rejection of events with feedback
7. Automatically sort events by date and status

### 📋 Attendance & Reports
8. Real-time attendee tracking during events
9. Admins can mark attendees as Present/Absent
10. View attendee lists with timestamps
11. Generate and print attendance reports in PDF format (mPDF)

### 📸 Visuals and Usability
12. Upload and display event images
13. Filter events by type, venue, and audience
14. Search bar for instant event lookup

---

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS, Bootstrap, JavaScript
- **Backend:** PHP
- **Database:** MySQL (MariaDB)
- **Email Service:** SMTP (for password reset)
- **PDF Generator:** mPDF
- **Security:** Google reCAPTCHA, Password Hashing (bcrypt)

---

## 🗃️ Database Schema Overview

The system uses MySQL with the following primary tables:

| Table     | Description                              | Key Columns                                             |
|-----------|------------------------------------------|---------------------------------------------------------|
| `users`   | Stores all registered students and faculty| `user_id`, `firstname`, `lastname`, `email`, `password`, `roles`, etc. |
| `admin`   | Admin credentials with access privileges  | `admin_id`, `email`, `password`, `roles`, etc.          |
| `events`  | Holds event details submitted by users    | `event_id`, `user_id`, `event_name`, `description`, `venue`, `status`, `image_path`, etc. |
| `attendees`| Stores RSVP responses and attendance     | `attendee_id`, `event_id`, `user_id`, `attendance_status`, `roles`, etc. |

Each table includes relationships via foreign keys (e.g., `event_id`, `user_id`) and is normalized to avoid redundancy.

---

## 🔐 API Routes Used

1. **Google reCAPTCHA v2**  
    Used on login forms to verify human users and prevent spam.
2. **Google OAuth 2.0**  
    Allows users to log in securely using their Google accounts.

---

## 💾 Installation Instructions

1. Open your XAMPP Control Panel and start Apache and MySQL.
2. Extract the downloaded source code zip file.
3. Copy the extracted source code folder into XAMPP's `htdocs` directory.
4. Open a web browser and navigate to [phpMyAdmin](http://localhost/phpmyadmin).
5. Create a new database named `buksu_events`.
6. Import the provided SQL file (`buksu_events.sql`) from the `database` folder.
7. Access BukSU Event Management System:
    - **Student & Faculty Side:** [http://localhost/BukSU-Events/](http://localhost/BukSU-Events/)
    - **Admin Side:** [http://localhost/BukSU-Events/php-forms/admin-sign-in.php](http://localhost/BukSU-Events/php-forms/admin-sign-in.php)

---

## 🔑 Default User Credentials

| Role    | Username                     | Password    |
|---------|------------------------------|-------------|
| Admin   | admin@buksu.edu.ph           | admin123    |
| Faculty | faculty@buksu.edu.ph         | faculty123  |
| Student | student@student.buksu.edu.ph | student123  |


🎉 Thank You! We hope BukSU Event Management System (BEMS) makes event management effortless and more engaging for Bukidnon State University. 
If you have suggestions for improvements or encounter any issues, feel free to contribute and collaborate

## 📸 System Screenshots

### 🏠 Landing Page

![Landing Page 1](/screenshots/land-page-1.png)
![Landing Page 2](/screenshots/land-page-2.png)
![Landing Page 3](/screenshots/land-page-3.png)

### 📝 Sign Up

![Sign Up](/screenshots/sign-up.png)

### 👨‍🎓 Student Sign In

![Student Sign In](/screenshots/student-sign-in.png)

### 📅 Student Events

![Student Events](/screenshots/Student-events.png)

### 🗂️ Student Dashboard (Registered Events)

![Student Dashboard Registered Events](/screenshots/student-dashboard-registered-events.png)

### ⚙️ Edit Profile

![Edit Profile](/screenshots/edit-profile.png)

### 👨‍🏫 Faculty Sign In

![Faculty Sign In](/screenshots/faculty-sign-in.png)

### 📅 Faculty Events

![Faculty Events](/screenshots/faculty-events.png)

### 🗂️ Faculty Dashboard

![Faculty Dashboard 1](/screenshots/faculty-dash-1.png)
![Faculty Dashboard 2](/screenshots/faculty-dash-2.png)

### 📋 Faculty Registered Events

![Faculty Registered Events](/screenshots/faculty-registered-events.png)

### 📝 Book an Event

![Book an Event](/screenshots/book-event.png)

### 🛡️ Admin Sign In

![Admin Sign In](/screenshots/admin-sign-in.png)

### 🖥️ Admin Dashboard

![Admin Dashboard 1](/screenshots/admin-dash-1.png)
![Admin Dashboard 2](/screenshots/admin-dash-2.png)

### ✅ Approve/Reject Event

![Approve/Reject Event](/screenshots/approve-reject-event.png)

### ✏️ Edit Event

![Edit Event](/screenshots/edit-event.png)

### ⏸️ Postpone Event

![Postpone Event](/screenshots/pospone-event.png)

### 👥 Event Attendees

![Event Attendees](/screenshots/event-attendees.png)

### 🖨️ Print Attendance

![Print Attendance](/screenshots/print-attendance.png)



