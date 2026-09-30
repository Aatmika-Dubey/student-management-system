<?php

session_start();
include "db.php";

$success_message = $_SESSION["success_message"] ?? "";
unset($_SESSION["success_message"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_name = trim($_POST["student_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $course = trim($_POST["course"]);

    $errors = [];

    if (empty($student_name)) {
        $errors[] = "Student name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email.";
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Phone number must contain exactly 10 digits.";
    }

    if (empty($course)) {
        $errors[] = "Course is required.";
    }

    if (empty($errors)) {

        $sql = "INSERT INTO students (student_name, email, phone, course)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $student_name,
            $email,
            $phone,
            $course
        );

        if (mysqli_stmt_execute($stmt)) {

            $_SESSION["success_message"] = "Student added successfully!";
            header("Location: index.php");
            exit();

        } else {

            echo "<p>Something went wrong: " . mysqli_error($conn) . "</p>";

        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    if (!empty($success_message)) {
        echo "<div class='success-toast'>$success_message</div>";
    }
    ?>

    <div class="container">
    <h1>Student Management System</h1>

    <h2>Add Student</h2>

    <form method="POST" action="">
    <?php

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error-message'>$error</p>";
        }
    }

    ?>
        <label>Student Name:</label>
        <input type="text" name="student_name" required>

        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Phone:</label>
        <input type="text" name="phone" required minlength="10" maxlength="10">

        <br><br>

        <label>Course:</label>
        <input type="text" name="course" required>

        <br><br>

        <button type="submit">Add Student</button>

    </form>
  
    <h2>Student Records</h2>

<table>

    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Course</th>
        <th>Action</th>
    </tr>

    <?php

    $result = mysqli_query($conn, "SELECT * FROM students");

    while ($row = mysqli_fetch_assoc($result)) {

        echo "<tr>";

        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["student_name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["phone"] . "</td>";
        echo "<td>" . $row["course"] . "</td>";
        echo "<td>";
        echo "<a href='edit.php?id=" . $row["id"] . "'>Edit</a> | ";
        echo "<a href='delete.php?id=" . $row["id"] . "' onclick=\"return confirm('Are you sure you want to delete this student?');\">Delete</a>";
        echo "</td>";
        echo "</tr>";
    }

    ?>

</table>
</div>
</body>
</html>