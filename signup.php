<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>Conumer Signup Page</h1>
    <main>
        <form id="signupForm" method="post">
            <div class="textbox">
                <input type="text" id="name" name="nm" placeholder="Enter Name"><br>
                <input type="text" id="age" name="age" placeholder="Enter Age"><br>
                <input type="date" id="dob" name="dob" placeholder="Enter Date of Birth (YYYY-MM-DD)"><br>
                Gender:
                <input type="radio" id="male" name="gender" value="male" checked>Male
                <input type="radio" id="female" name="gender" value="female">Female <br>
                <input type="number" id="mno" name="mno" placeholder="Enter Mobile No"><br>
                <input type="text" id="email" name="email" placeholder="Enter E-mail"><br>
                <input type="password" id="pass" name="pass" placeholder="Enter Password"><br>
                <input type="password" id="rpass" name="rpass" placeholder="Re-enter Password"><br>
            </div>
            <div class="button">
                <input type="submit" id="submitBtn" name="submit" value="Submit"><br>
                If you have an account <br> <a href="login.php">Login</a>
            </div>
        </form>
    </main>
    <footer>
        <!-- <script src="signup.js"></script> -->
    </footer>
    <?php
include("connect.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    // Debug: Print the form data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $nm = $_POST['nm'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $mno = $_POST['mno'];
    $gen = $_POST['gender'];
    $mail = $_POST['email'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);  // Secure password handling

    // SQL query
    $query = "INSERT INTO consumer_mst (c_nm, c_gen, c_age, c_dob, c_mno, c_email, c_pwd) VALUES ('$nm','$gen','$age','$dob','$mno','$mail','$pass')";

    // Debug: Print the query
    echo $query;

    // Execute query and check for errors
    $exe = mysqli_query($conn, $query);

    if (!$exe) {
        die("Error in query: " . mysqli_error($conn));
    } else {
        echo "Record inserted successfully";
        // Redirect on successful insertion
        header("Location:welcome.php");
        exit();
    }
}
?>



</body>
</html>
