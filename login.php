<?php
$errors = array();
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

/* Declare `errors` array and get everything from $_POST, coalescing to `null`
 * to avoid errors */
//checks if submit button existts. Then the session will start
if(isset($_POST['submit'])){
    require_once './include/library.php';
    $pdo = connectDB();
    //if checkbox with name remember is checked
    $query = "select id, username, password from ass2_users where username = ?";

    $stmt=$pdo->prepare($query);
$stmt->execute([$username]);
$row = $stmt->fetch(); //get single row

   if (isset($_POST['remember'])){
    //cookie will expire in 24 hours
        setcookie('count', $_POST['username'], time()+60*60*24);

    }
    
    if (!$row){
        $errors['username'] = false;
      
      }
      else{
        echo "enter:".$password . "db:" .$row['password'];
        if (password_verify($password, $row['password'])) { //where $pass is collected, and $row is our database row, and pass the field name.
        //   session_start(); //start session
            echo "test";
          //store anything you need to identify the user on other pages
          $_SESSION['username'] = $username; 
          $_SESSION['userid'] = $row['id']; 
          header('Location: index.php');
          exit();
      
       } else {
        $errors['password'] = true;
           //set error flag for failed login
       }
      }
      }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="Stylesheet" href = "style.css">
</head>

<body>
<?php include './include/primeheader.php' ?>
    <div class="center">
        <h1>Log in </h1>
        <form method="post">
            <div class="txt_field">            
            <label>Username</label><input type="text" name='username' required <?php if(isset($_COOKIE['count'])){echo "value='$_COOKIE[count]'";}?>>
        </div>
        

        <div class="txt_field">
        <label>Password </label><input type="password" name='password' required>   
    </div>
    <label for='remember'>Remember Me? </label><input type='checkbox'name='remember' id='remember'>
   
    
    <div class="pass">
        <a href="forget.php"> Forgot password? </a>
    </div>
    <div>
        <!--notice variable which triggers output of error message if sticky processing fails-->
        <span class="error <?= !isset($errors['username']) ? 'hidden' : "" ?>">Please enter a valid username</span>
        <span class="error <?= !isset($errors['password']) ? 'hidden' : "" ?>">Please enter a valid password</span>
      </div>

    <input type="submit" value="Login" name='submit'>
    <div class="signup_link">
        Not a member? <a href="register.php">Sign up</a>
    </div>
    


        </form>
    </div>
        </body>
        </html>