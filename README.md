# Top Educational Center Project

## Overview

The **Top Educational Center** project is designed for managing private curriculum courses. This system provides comprehensive features for managing students, teachers, sessions, and reports. It aims to streamline administrative tasks and track educational progress effectively.

## Features

### Student Management
- **Add**, **Edit**, **Delete**, and **View** students.

### Teacher Management
- **Add**, **Edit**, **Delete**, and **View** teachers.
- Assign **Subjects** to teachers.

### Session Management
- **Add** and **Edit** sessions/lectures.
- **Assign** students to sessions.
- **Log attendance** when a teacher starts a session.
- Admin can **add/remove attended students** based on each session.

### Teacher Status
- Teachers can have two status levels:
  - **50%**: For regular sessions.
  - **75%**: For one-time sessions.

### Reporting
- Generate various reports, including:
  - **Attendance Reports**
  - **Sessions Report**
  - **Incomes Reports**
  - **Outcomes Reports**
  - **Income Outcome Summary Report**
  - And many other features.

## Installation

### Prerequisites
- PHP 7.x
- MySQL/MariaDB
- Composer

### Steps

1. **Clone the Repository**

   ```bash
   git clone <repository-url>
   cd top
2. **Install Dependencies**
   ```bash
   composer install
3. **Configure the Database**

   ```bash
   git clone <repository-url>
   cd top
4. Configure the Database

- Import the SQL database dump into your MySQL/MariaDB server.
- Update the database configuration in dbconnection.php.


5. **Set Up Apache**

- Place the project files in /var/www/html/top.
- Configure Apache with the appropriate VirtualHost settings.

6. **Set Permissions**

   ```bash
   sudo chown -R www-data:www-data /var/www/html/top

7. **Restart Apache**
   ```bash
   sudo systemctl restart apache2
