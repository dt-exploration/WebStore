<?php
if (isset($_COOKIE['auth_token']) and isset($_COOKIE['username'])) {

    $token_from_user = $_COOKIE['auth_token'];
    $name = $_COOKIE['username'];

    $con = new mysqli('localhost','root','','website');
    $query = "SELECT * FROM users WHERE username='$name'";
    $result = $con->query($query) or die($con->error);
    $row = $result->fetch_assoc();

    $token_from_db = $row['token'];

    $salt = pack("H*", $row['secret']);
    //var_dump($token_from_db);
    if (crypt($token_from_user, $salt) == $token_from_db) {
        echo "<h1>Welcome ".$_COOKIE['username']. " !</h1><br>";
    } else {
        header('Location: http://localhost/complete_form.php');
    }

} else {
        session_start();
        if (isset ($_SESSION['auth_token'])) {
            echo "<h1>Welcome ".$_SESSION['username']. " !</h1><br>";

       } else {
            header('Location: http://localhost/complete_form.php');
       }
}

if (isset($_POST['logout'])) {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
    session_destroy();
    setcookie('auth_token', '', time()-5, '/');
    setcookie('username', '', time()-5, '/');
    setcookie('sess', '', time()-5, '/');
    header("Location: http://localhost/complete_form.php");
}
//$token = $_COOKIE['auth_token'];
//$salt = pack("H*", '73dfcc77104a8593640db2349cd7c360');
//echo crypt($token, $salt);

 ?>
 <html>
 <body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="submit" name="logout" value="Log out">
</form>
