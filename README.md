# Student Management System

A web-based Student Management System built using PHP and MySQL. The application allows users to manage student records through basic CRUD operations.

## Features

* Add new student records
* View student records
* Edit existing student records
* Delete student records
* Form validation
* Email validation
* 10-digit phone number validation
* Prepared statements for database operations
* Success notifications
* Prevents duplicate submissions when refreshing after adding or updating a record

## Technologies Used

* PHP
* MySQL
* HTML
* CSS
* XAMPP
* Git & GitHub

## Database

The project uses a MySQL database named:

`student_management`

The main table is:

`students`

It contains the following fields:

* `id`
* `student_name`
* `email`
* `phone`
* `course`

## How to Run

1. Install XAMPP.

2. Start **Apache** and **MySQL** from the XAMPP Control Panel.

3. Place the project inside:

   `C:\xampp\htdocs\`

4. Create a MySQL database named `student_management`.

5. Create the `students` table with the required fields.

6. Open the application in a browser:

   `http://localhost/student_management/`

## Project Structure

```text
student_management/
│
├── index.php
├── edit.php
├── delete.php
├── db.php
├── style.css
└── README.md
```

## Learning Purpose

This project was created to practice PHP, MySQL, CRUD operations, form validation, prepared statements, session handling, and Git/GitHub workflow.
