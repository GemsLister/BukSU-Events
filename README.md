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

## 💾 Installation Instructions

1. - Open your XAMPP Control Panel and start Apache and MySQL.
2. - Extract the downloaded source code zip file.
3. - Copy the extracted source code folder and paste it into XAMPP's htdocs directory.
4. - Open a web browser and navigate to PHPMyAdmin (http://localhost/phpmyadmin).
5. - Create a new database named buksu_events.
6. - Import the provided SQL file (buksu_events.sql) from the database folder.
7. - Access BukSU Event Management System:
8. - Student & Faculty Side: http://localhost/BukSU-Events/
9. - Admin Side: http://localhost/BukSU-Events/php-forms/admin-sign-in.php


🔑 Default User Credentials
| Role    | Username                     | Password   | 
| Admin   | admin@buksu.edu.ph           | admin123   | 
| Faculty | faculty@buksu.edu.ph         | faculty123 | 
| Student | student@student.buksu.edu.ph | student123 | 


🎉 Thank You! We hope BukSU Event Management System (BEMS) makes event management effortless and more engaging for Bukidnon State University. 
If you have suggestions for improvements or encounter any issues, feel free to contribute and collaborate

📸 System Screenshots


### 🏠 Landing Page

![Landing Page](/screenshots/landing-page1.png)

### 🎓 Browse Courses

![Browse Courses](public/screenshots/browse-courses.png)

### 📖 View Course

![View Course](public/screenshots/view-course.png)

### 🧑‍🏫 Take Course

![Take Course](public/screenshots/take-course.png)

### 🛠️ Create Course

![Create Course](public/screenshots/create-course.png)

### 📝 Edit Course

![Edit Course](public/screenshots/edit-course.png)

### 📚 Manage Course

![Manage Course](public/screenshots/manage-course.png)

### 🧮 Course Quizzes

![Course Quizzes](public/screenshots/course-quizzes.png)

### 📋 Learner Submissions (Quiz)

![Quiz Submissions](public/screenshots/course-quiz-learners-submissions.png)

### 📩 Learner Submissions (Course)

![Course Submissions](public/screenshots/learner-course-submissions.png)

### 👨‍🎓 Course Learners

![Course Learners](public/screenshots/course-learners.png)

### 📅 Create Booking

![Create Booking](public/screenshots/create-booking.png)

### 📆 Manage Bookings

![Manage Bookings](public/screenshots/manage-bookings.png)

### 👤 Learner Dashboard

![Learner Dashboard](public/screenshots/learner-dashboard.png)

### ⚙️ Update Profile

![Update Profile](public/screenshots/update-profile.png)



