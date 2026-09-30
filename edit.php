<?php

session_start();

include "db.php";

$success_message = $_SESSION["success_message"] ?? "";
unset($_SESSION["success_message"]);

$id = $_GET["id"];

$sql = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

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

        $sql = "UPDATE students
                SET student_name = ?, email = ?, phone = ?, course = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $student_name,
            $email,
            $phone,
            $course,
            $id
        );
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["success_message"] = "Student updated successfully!";
            header("Location: edit.php?id=" . $id);
            exit();
        } else {
            echo "<p>Something went wrong.</p>";
        }

        mysqli_stmt_close($stmt);
    } else {

        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    if (!empty($success_message)) {
        echo "<div class='success-toast'>$success_message</div>";
    }
    ?>
    <div class="container">
    <h1>Edit Student</h1>

    <form method="POST">

        <label>Student Name:</label>
        <input type="text" name="student_name"
               value="<?php echo $student['student_name']; ?>"
               required>

        <br><br>

        <label>Email:</label>
        <input type="email" name="email"
               value="<?php echo $student['email']; ?>"
               required>

        <br><br>

        <label>Phone:</label>
        <input type="text" name="phone"
               value="<?php echo $student['phone']; ?>"
               required>

        <br><br>

        <label>Course:</label>
        <input type="text" name="course"
               value="<?php echo $student['course']; ?>"
               required>

        <br><br>

        <button type="submit">Update Student</button>

    </form>
</div>
</body>
</html>