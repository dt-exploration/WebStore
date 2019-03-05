<?php

if (isset($_COOKIE['sess']) or isset($_COOKIE['auth_token'])) {
    header("Location: http://localhost/welcome.php");
}


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

$error = "";

if (isset($_POST['submit'])) {

    $name = test_input($_POST['name']);

    $con = new mysqli('localhost','root','','website');

    $query = "SELECT * FROM users WHERE username='$name'";

    $result = $con->query($query) or die($con->error);

    if ($result->num_rows == 0 ) {

        $error = "Invalid username or password !";

    } else {

        $password_from_user = test_input($_POST['password']);

        $row = $result->fetch_assoc();

        $password_from_db = $row['password'];
        $salt = pack("H*",$row['secret']);

        if ($password_from_db == crypt($password_from_user, $salt)) {
            $auth_token = bin2hex(random_bytes(16));
            $hashed_token = crypt($auth_token, $salt);
////////////ukoliko klikne remember me
            if ($_POST['remember'] == 1) {

                $query = "UPDATE users SET token='$hashed_token' WHERE username='$name'";
                $con->query($query);
                setcookie('auth_token', $auth_token, time()+10000, "/");
                setcookie('username', $name, time()+10000, "/");

             } else {

             ////////////ukoliko nema rembember
             session_start();

             $_SESSION['auth_token'] = $auth_token;
             $_SESSION['username'] = $name;
             setcookie('sess','1',time()+300,"/");
             }
             header('Location: http://localhost/welcome.php');

        } else {
             $error = "Invalid username or password !";
        }

    }
}

 ?>
 <!doctype html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport"
           content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Log IN</title>
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
   <style>
   .signup{
   background-color: #4CAF50;
   border: none;
   color: white;
   padding: 15px 32px;
   text-align: center;
   text-decoration: none;
   display: inline-block;
   font-size: 16px;
   margin: 4px 2px;
   cursor: pointer;}
   .login {
    background-color: #008CBA;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
 cursor: pointer;}
 #h { font-family: cursive;}

 </style>
 </head>
 <body>
 	<div class="container" style="margin-top:70px;">
 		<div class="row justify-content-center">
 			<div class="col-md-6 col-md-offset-3" align="center">
         <h2 id="h">LORD TUTZAWELLA WEBSTORE</h1>
 				<img src="tutzawela.png" height="250" width="250"><br><br>
                 <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
                     <input class="form-control" type="username" placeholder="Username..." name="name"><br>
                     <input class="form-control" type="password" placeholder="Password..." name="password"><?php echo "<span style='color:red'>$error</span>"; ?><br>
                     <input type="submit" name="submit" class="login" value="Log In">
                     <input onclick="javascript: window.location = '<?php echo "http://localhost/forma.php"; ?>';" type="button" class="signup" value="Sign Up">
                     <br>
                     <input type="checkbox" name="remember" value="1"> Remember me
                 </form>
 			</div>
 		</div>
 	</div>
 </body>
 </html>
