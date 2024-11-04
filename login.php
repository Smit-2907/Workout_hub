<?php
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <h1>Conumer Login Page</h1>
    </header>
    <main>
        <form id="loginForm" method="post">
            <div class="textbox">
                <input type="text" id="name" name="nm" placeholder="Enter Name"><br>
                <input type="text" id="email" name="email" placeholder="Enter E-mail"><br>
                <input type="password" id="pass" name="pass" placeholder="Enter Password"><br>
            </div>
            <div class="button">
                <input type="submit" name="submit" value="Submit"><br>
                If you do not have an account <br> <a href="signup.php">Signup</a>
            </div>
        </form>
    </main>
    <footer>

    </footer>
</body>
<?php
include("connect.php");
if (isset($_POST['submit'])) {
    $mail = $_POST['email'];
    $pass = $_POST['pass'];
    $query = "SELECT * FROM consumer_mst WHERE c_mail = '$id' and c_pwd = $pwd";
    $exe = mysqli_query($conn, $query);
    echo "$id,$pwd";

    if (!$exe) {
        echo "<script>alert('Error executing query');</script>";
        exit();
    }

    $found = false;
    while ($row = mysqli_fetch_assoc($exe)) {
        $id = $row['c_email'];
        $pwd = $row['c_pwd'];
        if ($mail == $id && $pass == $pwd) {
            $found = true;
            break;
        }
    }

    if ($found) {
        echo "<script>alert('Login Success');</script>";
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}
?>

</html>