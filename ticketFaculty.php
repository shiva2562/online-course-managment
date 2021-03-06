<?php
/**
 * Created by PhpStorm.
 * User: merah
 * Date: 03-May-18
 * Time: 7:17 AM
 */
session_start();
if (isset($_GET['logout'])) {
    echo "entered here";
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}

if (isset($_POST['course_insert'])) {
    $db_connection = mysqli_connect('localhost', 'root', '', 'university_database');
// Check The Connection
    if (!$db_connection) {
        die("Connection Failed: " . mysqli_connect_error());
    }
    mysqli_select_db($db_connection, "crp");
    $course_code = $_POST['course_id'];
    $semester = 7;
    $room_number = $_POST['room'];
    $slot = $_POST['slot'];
    $cgpa_limit = $_POST['cgpa_limit'];
    $number_of_students = $_POST['no_of_students'];
    $username1 = $_SESSION['username'];

    $insert_q = "INSERT INTO course_offering(id, semester, room_number, slot,cpa_limit,number_of_students) 
VALUES ('$course_code',$semester,'$room_number','$slot',$cgpa_limit,$number_of_students);";
    $insert_q1 = "INSERT INTO teaches (id, courseid) values ($username1,'$course_code');";
//    $insert_q2 = "INSERT INTO enrolls (id, courseid) values ($course_code,'$course_code');";
    $results1 = mysqli_query($db_connection, $insert_q);
    if (!$results1) {
        printf("Error: %s\n", mysqli_error($db_connection));
        exit();
    }

    $results = mysqli_query($db_connection, $insert_q1);
    if (!$results) {
        printf("Error: %s\n", mysqli_error($db_connection));
        exit();
    }
    mysqli_close($db_connection);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome Teacher <?php
        echo $_SESSION['username'];
        ?></title>
    <script src="universal_js.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
<nav class="navbar navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="#" class="navbar-brand">FACULTY</a>
        </div>
        <!-- Collection of nav links and other content for toggling -->
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="teacherLoggedIn.php">My Courses</a></li>
                <li><a href="facultyGradeEntry.php">Enter Grades</a></li>
                <li class="active"><a href="#">Ticket</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="studentloggedIn.php?logout='1'" style="color: red;">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container">

    <!--    <button id="myButton" onclick="viewCourses()">View_Courses</button>-->

    <h2>All Courses</h2>
    <table class="table table-striped" id="table-ticket-faculty">
        <caption class="title"></caption>
        <thead>
        <tr>
            <th>#</th>
            <th>Student Id</th>
            <th>Faculty Id</th>
            <th>Course Id</th>
            <th>Ticket Description</th>
        </tr>
        </thead>
        <tbody>

        <?php

        $username = $_SESSION['username'];

        $db_connection = mysqli_connect('localhost', 'root', '', 'university_database');
        // Check The Connection
        if (!$db_connection) {
            die("Connection Failed: " . mysqli_connect_error());
        }

        mysqli_select_db($db_connection, "crp");
        $sql1 = 'SELECT * FROM ticket_table WHERE faculty_id=' . $username . ' and Current_Status=\'In Progress\';';
        $result = mysqli_query($db_connection, $sql1);
        $no = 1;
        $total = 0;

        while ($row = mysqli_fetch_row($result)) {

            echo '<tr>
					<td>' . $no . '</td>
					<td>' . $row[0] . '</td>
					<td>' . $row[1] . '</td>
					<td>' . $row[2] . '</td>
					<td>' . $row[3] . '</td>
					
					<td><button class="btn btn-success" id = ' . $no . ' onclick="ticketApprove(this)">Approve</button></td>
					<td><button class="btn btn-danger" id = ' . $no . ' onclick="ticketDisapprove(this)">Decline</button></td>

				</tr>';
            $no++;
        } ?>
        </tbody>
    </table>
</div>

</body>
</html>