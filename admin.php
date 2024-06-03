<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            height: 100vh;
            background-color: #FFFFFF; /* White background */
            color: #4B2E2E; /* Dark Brown text */
        }
        .sidebar {
            width: 250px;
            background-color: #4B2E2E; /* Dark Brown sidebar */
            padding: 20px;
            position: fixed;
            height: 100%;
            overflow: auto;
        }
        .sidebar h2 {
            color: #FFC0CB; /* Baby Pink text */
        }
        .sidebar a {
            display: block;
            color: #FFC0CB; /* Baby Pink text */
            padding: 10px;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .sidebar a:hover {
            background-color: #D2B48C; /* Light Brown/Tan hover */
            color: #4B2E2E; /* Dark Brown text */
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
            background-color: #F5F5F5; /* Soft Grey background */
        }
        header {
            background-color: #4B2E2E; /* Dark Brown header */
            color: #FFC0CB; /* Baby Pink text */
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            padding: 20px;
            background-color: #FFFFFF; /* White background */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .course-card {
            border: 1px solid #D2B48C; /* Light Brown/Tan border */
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
            background-color: #F5F5F5; /* Soft Grey background */
        }
        .course-card h3 {
            margin: 0;
            color: #4B2E2E; /* Dark Brown text */
        }
        .course-card p {
            margin: 10px 0;
            color: #4B2E2E; /* Dark Brown text */
        }
        .course-card button {
            background-color: #FFC0CB; /* Baby Pink button */
            color: #4B2E2E; /* Dark Brown text */
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 10px;
        }
        .course-card button:hover {
            background-color: #D2B48C; /* Light Brown/Tan on hover */
            color: #4B2E2E; /* Dark Brown text */
        }
        .add-course-btn {
            background-color: #4B2E2E; /* Dark Brown button */
            color: #FFC0CB; /* Baby Pink text */
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            display: block;
            margin: 20px 0;
            text-align: center;
            width: 100%;
        }
        .add-course-btn:hover {
            background-color: #D2B48C; /* Light Brown/Tan on hover */
            color: #4B2E2E; /* Dark Brown text */
        }
        footer {
            background-color: #4B2E2E; /* Dark Brown footer */
            color: #FFC0CB; /* Baby Pink text */
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            left: 0;
        }
    </style>
</head>
<body>

<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "coursehub";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch courses
function fetchCourses($conn) {
    $sql = "SELECT id, name, description FROM courses";
    $result = $conn->query($sql);
    return $result;
}

// Handle form submissions for adding, editing, and deleting courses
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_course'])) {
        $name = $_POST['course_name'];
        $description = $_POST['course_description'];
        $sql = "INSERT INTO courses (name, description) VALUES ('$name', '$description')";
        $conn->query($sql);
    } elseif (isset($_POST['edit_course'])) {
        $id = $_POST['course_id'];
        $name = $_POST['course_name'];
        $description = $_POST['course_description'];
        $sql = "UPDATE courses SET name='$name', description='$description' WHERE id=$id";
        $conn->query($sql);
    } elseif (isset($_POST['delete_course'])) {
        $id = $_POST['course_id'];
        $sql = "DELETE FROM courses WHERE id=$id";
        $conn->query($sql);
    }
}

// Fetch courses to display
$courses = fetchCourses($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="sidebar">
    <h2>Admin Dashboard</h2>
    <a href="#dashboard">Dashboard</a>
    <a href="#manage-courses">Manage Courses</a>
    <a href="#users">Manage Users</a>
    <a href="#reports">Reports</a>
    <a href="#settings">Settings</a>
    <a href="#logout">Logout</a>
</div>

<div class="main-content">
    <header>
        <h1>Admin Dashboard</h1>
        <p>Welcome, Admin</p>
    </header>

    <div class="container">
        <h2>Manage Courses</h2>
        
        <?php if ($courses->num_rows > 0): ?>
            <?php while($row = $courses->fetch_assoc()): ?>
                <div class="course-card">
                    <h3>Course: <?php echo $row['name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <form method="post" action="">
                        <input type="hidden" name="course_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="edit_course">Edit Course</button>
                        <button type="submit" name="delete_course">Delete Course</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No courses available.</p>
        <?php endif; ?>

        <button class="add-course-btn" onclick="document.getElementById('addCourseModal').style.display='block'">Add New Course</button>
        
        <!-- Add Course Modal -->
        <div id="addCourseModal" style="display:none;">
            <form method="post" action="">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" required>
                <label for="course_description">Course Description:</label>
                <textarea id="course_description" name="course_description" required></textarea>
                <button type="submit" name="add_course">Add Course</button>
            </form>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 CourseHub. All rights reserved.</p>
</footer>

</body>
</html>

<?php
$conn->close();
?>


<footer>
    <p>&copy; 2024 CourseHub. All rights reserved.</p>
</footer>

</body>
</html>
