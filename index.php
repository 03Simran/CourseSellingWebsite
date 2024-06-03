<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CourseSellingWebsite</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Welcome to CourseHub</h1>
    <p>Your destination for the best online courses</p>
</header>

<div class="navbar">
    <a href="#home">Home</a>
    <a href="#courses">Courses</a>
    <a href="#about">About Us</a>
    <a href="#contact">Contact</a>
</div>

<div class="container">
    <h2>Our Courses</h2>

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "coursehub";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch courses from the database
    $sql = "SELECT id, title, description FROM courses";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo '<div class="course-card">';
            echo '<h3>' . htmlspecialchars($row["title"]) . '</h3>';
            echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
            echo '<a href="course.php?id=' . $row["id"] . '"><button>Enroll Now</button></a>';
            echo '</div>';
        }
    } else {
        echo "<p>No courses available at the moment.</p>";
    }

    $conn->close();
    ?>
</div>

<footer>
    <p>&copy; 2024 CourseHub. All rights reserved.</p>
</footer>
</body>
</html>
