<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php
$nameErr=$emailErr=$passwordErr=$genderErr="";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
$error = "";
$success = "";
$flag = 0;

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}


//Name
////////////////////////////////////////////////////////////////////
if (empty($_POST["name"])) {
    $nameErr = "Username is required !";
    $flag = 1;
} else {
    $name = test_input($_POST["name"]);

    if (!preg_match("/^[A-Z\d\s]+$/i", $name )) {
        $nameErr = "Special characters not allowed !";
        $flag = 1;
    }
  }
////////////////////////////////////////////////////////////////////



//E mail
////////////////////////////////////////////////////////////////////
if (empty($_POST["email"])){
    $emailErr = "E-mail is required !";
    $flag = 1;
} else {
    $email = test_input($_POST["email"]);

    if (filter_var($email,FILTER_VALIDATE_EMAIL) == false) {
        $emailErr = "Invalid E-mail ! ";
        $flag = 1;
    }
}
/////////////////////////////////////////////////////////////////////



//Password
/////////////////////////////////////////////////////////////////////
if (empty($_POST["password"])) {
     $passwordErr = "Password is required !";
     $flag = 1;
} else {
     $password = test_input($_POST["password"]);
    }

/////////////////////////////////////////////////////////////////////


//Gender
////////////////////////////////////////////////////////////////////
if (empty($_POST["gender"])) {
    $genderErr = "Gender is required !";
} else {
    $gender = test_input($_POST["gender"]);
}
////////////////////////////////////////////////////////////////////


if ($flag == 0) {

$con = new mysqli('localhost','root','','website');

$query = "SELECT * FROM users WHERE username='$name'";

$result = $con->query($query) or die($con->error);



if ($result->num_rows > 0) {
    $error = "<b>Username is already taken. Try again ! <b>";
} else {
    $query = 'INSERT INTO users (username,email,gender,password,secret) VALUES(?,?,?,?,?)';

    $insert = $con->prepare($query);
    $insert->bind_param("sssss",$username_db,$email_db,$gender_db,$password_db,$secret_db);

    $salt = random_bytes(16);

    $password_db = crypt($password, $salt);
    $username_db = $name;
    $email_db = $email;
    $gender_db = $gender;
    $secret_db = bin2hex($salt);



    $insert->execute();

    $success = "<p style='color:green'='red'>Registration Successful !</p>";
    $success.= "<a href='http://localhost/complete_form.php'>Go to login page</a>";
    echo $success;
    die();
}

}


}
?>
 <h2>Registration Form</h2>
 <p><span class="error">* required field</span></p>
 <form method="post" action="forma.php">
   Username: <input type="text" name="name" value="<?php  ?>">
   <span class="error">* <?php echo "$nameErr";?></span>
   <br><br>
   E-mail: <input type="text" name="email" value="<?php  ?>">
   <span class="error">* <?php echo "$emailErr";?></span>
   <br><br>
   Password: <input type="password" name="password" value="<?php ?>">
   <span class="error">*<?php echo "$passwordErr"; ?></span>
   <br><br>
   Gender:
   <input type="radio" name="gender" value="female">Female
   <input type="radio" name="gender"  value="male">Male
   <input type="radio" name="gender"  value="other">Other
   <span class="error"><?php echo "$genderErr";?> </span>
   <br><br>
   <input type="submit" name="submit" value="Submit">
 </form>
<?php if (isset($_POST['submit'])) { if($error!=""){echo "<br>".$error;} } ?>
</body>
</html>
