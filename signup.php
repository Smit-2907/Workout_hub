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
                <input type="text" id="name" name="nm" placeholder="Enter Name" required><br>
                <input type="text" id="age" name="age" placeholder="Enter Age" required><br>
                <input type="date" id="dob" name="dob" placeholder="Enter Date of Birth (YYYY-MM-DD)" required><br>
                Gender:
                <input type="radio" id="male" name="gender" value="male" checked>Male
                <input type="radio" id="female" name="gender" value="female" >Female <br>
                <input type="number" id="mno" name="mno" placeholder="Enter Mobile No" required><br>
                <input type="text" id="email" name="email" placeholder="Enter E-mail" required><br>
                <input type="password" id="pass" name="pass" placeholder="Enter Password" required><br>
                <input type="password" id="rpass" name="rpass" placeholder="Re-enter Password" required><br>
            </div>
            <div class="button">
                <input type="submit" id="submitBtn" name="submit" value="Submit"><br>
                If you have an account <br> <a href="login.php">Login</a>
            </div>
        </form>
    </main>
    <footer>
    </footer>
<?php
session_start();

include("connect.php");
if(isset($_POST['submit'])){
    $nm=$_POST['nm'];
    $age=$_POST['age'];
    $dob=$_POST['dob'];
    $mno=$_POST['mno'];
    $gen=$_POST['gender'];
    $mail=$_POST['email'];
    $pass=$_POST['pass'];
    $rpass=$_POST['rpass'];
    $query="INSERT INTO consumer_mst (c_nm, c_gen, c_age, c_dob, c_mno, c_email, c_pwd) VALUES ('$nm','$gen','$age','$dob','$mno','$mail','$pass')";

    if($age < 12){
        echo "<script>Age must be above 12</script>";        
    }elseif(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
        echo "<script>alert('Please Check your email format');</script>";
    }elseif($pass != $rpass){
        echo "<script>alert('Passwords do not match');</script>";
    }elseif(!($mnoc("/^[6-9]\d{9}$/", $mno))) {
        echo "<script>alert('Enter valid Phone Number');</script>";
    }

    // $exe=mysqli_query($conn,$query);
    

    // Redirect to the same page to prevent form resubmission
    // header("Location: " . $_SERVER['PHP_SELF']);
    // exit();
}
?>



</body>
</html>
