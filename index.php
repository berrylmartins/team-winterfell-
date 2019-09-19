<?php
date_default_timezone_set('Africa/Lagos');

// start session
session_start();
// if logged in already, redirect
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    $msg = "You are already logged in";
    header("location:home.php?message=$msg");
    exit;
}

//this is the basic User sign up
if (isset($_POST["submit"])) {
    if (file_exists('users.json')) {
        $current_data = file_get_contents('users.json');
        $array_data = json_decode($current_data, true);

        // validation
        $fullname = trim($_POST["fullname"]);
        $username = trim($_POST["username"]);
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phone"]);
        $password = trim($_POST["password"]);
        $cpassword = (trim($_POST["Cpassword"]));

        // check that all field are valid
        if (strlen($fullname) < 1 || strlen($username) < 1 || strlen($email) < 1 || strlen($phone) < 1 || strlen($password) < 1 || strlen($cpassword) < 1) {
            $msg = "Fill all required fields";
            header("location:index.php?message=$msg");
            exit;
        }

        // check if email && username doesn't exist
        $emails = array_column($array_data, "email");
        $usernames = array_column($array_data, "username");
        if (in_array($email, $emails)) {
            $msg = "User with this email exists";
            header("location:index.php?message=$msg");
            exit;
        }
        if (in_array($username, $usernames)) {
            $msg = "Username has been choosen";
            header("location:index.php?message=$msg");
            exit;
        }

        // check if password match
        if ($password != $cpassword) {
            $msg = "Password don't match";
            header("location:home.php?message=$msg");
            exit;
        }

        // then store
        $extra = array(
            'fullname' => $fullname,
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'password' => md5($password),
            'created_at' => date("Y-m-d h:i:s a", time()),
        );
        $array_data[] = $extra;
        $final_data = json_encode($array_data);
        $final_data .= "\n";
        if (file_put_contents('users.json', $final_data)) {
            $msg = "Signup Successful";
            header("location:index.php?message=$msg");
            exit;
        }
    } else {
        $msg = 'Error loading database';
        header("location:index.php?message=$msg");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Welcome To Team Winterfell</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="css/font-awesome.min.css" />
	<link href="css/style.css" rel='stylesheet' type='text/css' media="all">

<link href="//fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
</head>

<body>
<?php if (isset($_GET['message'])): ?>
        <script>alert("<?=$_GET['message']?>");</script>
	<?php endif;?>
	
	<h1 class="header-w3ls">
		Welcome to Team Winterfell..</h1>
			<div class="mid-cls">
<div class="swm-left-w3ls">
				<form action="#" method="post">
			<div class="main">
			<div class="icon-head-wthree">
			        <h2><span class="fa fa-diamond t-w3" aria-hidden="true"></span></h2>
					<h4>Sign Up</h4>
					</div>
									<div class="form-left-to-w3l">

							<input type="text" name="name" id="name" placeholder="Full Name" required>
							<div class="clear"></div>
						</div>
									<div class="form-left-to-w3l">

							<input type="text" name="username" id="username" placeholder="Username" required>
							<div class="clear"></div>
						</div>
				<div class="form-left-w3l">
				
					<input type="email" name="email" id="email" placeholder="Email" required>
				</div>
				<div class="form-left-w3l">
				
					<input type="text" name="phone" id="mobile" placeholder="Mobile 08160257899" required>
				</div>
				<div class="form-right-w3ls ">

					<input type="password" name="password" id="password" placeholder="Password" required>

					<div class="clear"></div>
				</div>
				<div class="form-right-w3ls ">

					<input type="password" name="Cpassword" id="Cpassword" placeholder="Confirm Password" required>

					<div class="clear"></div>
				</div>
			<div class="btnn">
				<button type="submit" name="submit" id="submit">Sign Up</button><br>
				<div class="clear"></div>
			</div>
			</div>

		</form>
		<div class="col-md-2 header-side">
		<h4>Or Sign Up With</h4>
				<div class="buttom-social-grids">

					<ul>
						<li><a href="www.facebook.com"><span class="fa fa-facebook"></span></a></li>
						<li><a href="www.twitter.com"><span class="fa fa-twitter"></span></a></li>
						<li><a href="www.gmail.com"><span class="fa fa-google"></span></a></li>
					</ul>
				</div>
			</div>
</div>
<?php
// start session
session_start();

// if logged in already, redirect
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    $msg = "You are already logged in";
    header("location:home.php?message=$msg");
}

// if remeber was set before, login directly
if (isset($_COOKIE["winfelluser"])) {
    if (file_exists('users.json')) {
        $users = json_decode(file_get_contents("users.json"));
        $usernames = array_column($users, "username");
        if (in_array($_COOKIE["winfelluser"], $usernames)) {
            $user = $users[array_search($_COOKIE["winfelluser"], $usernames)];
            // store all vars in session
            $_SESSION['loggedin'] = true;
            $_SESSION['fullname'] = $user->fullname;
            $_SESSION['username'] = $user->username;
            $_SESSION['email'] = $user->email;
            $_SESSION['phone'] = $user->phone;
            $msg = "Logged in successfully";
            header("location:index.php?message=$msg");
        } else {
            $msg = "User does not exist";
            header("location:home.php?message=$msg");
        }
    } else {
        $msg = "Database not present";
        header("location:home.php?message=$msg");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // validation
    // check if username is not empty and password is not empty
    if (isset($_POST["username"]) and isset($_POST['password'])) {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        // Check that both field are not empty
        if (strlen($username) < 1 || strlen($password) < 1) {
            $msg = "Fill all required fields";
            header("location:login.php?message=$msg");
        }
        // check that the username exist
        if (file_exists('users.json')) {
            $users = json_decode(file_get_contents("users.json"));
            $usernames = array_column($users, "username");
            if (in_array($username, $usernames)) {
                $user = $users[array_search($username, $usernames)];

                if (md5($password) == $user->password) {
                    // if remember me isset
                    if (isset($_POST["remember"])) {
                        setcookie("winfelluser", $_POST["username"], time() + (30 * 24 * 60 * 60));
                    } else {
                        if (isset($_COOKIE["winfelluser"])) {
                            setcookie("winfelluser", "");
                        }
                    }

                    // store all vars in session
                    $_SESSION['loggedin'] = true;
                    $_SESSION['fullname'] = $user->fullname;
                    $_SESSION['username'] = $user->username;
                    $_SESSION['email'] = $user->email;
                    $_SESSION['phone'] = $user->phone;
                    $msg = "Logged in successfully";
                    header("location:home.php?message=$msg");
                } else {
                    $msg = "Incorrect Password";
                    header("location:index.php?message=$msg");
                }
            } else {
                $msg = "User does not exist";
                header("location:index.php?message=$msg");
            }
        } else {
            $msg = "Error loading database";
            header("location:home.php?message=$msg");
        }
    }
}
?>

 <?php if (isset($_GET['message'])): ?>
      <script>alert("<?= $_GET['message'] ?>");</script>
	<?php endif;?>
	
<div class="swm-right-w3ls">
 <form action="#" method="post">
 			<div class="main">
				<div class="icon-head-wthree">
        <h2><span class="fa fa-diamond t-w3" aria-hidden="true"></span></h2>
		<h4>Login</h4>
		</div>
         <div class="form-left-w3l">
             
		     <input type="email" name="email" placeholder="Email" required>
			
          <div class="clear"></div> 
		</div> 		   
		 <div class="form-right-w3ls ">
				
				<input type="password" name="password" placeholder="Password" required>
			
		         <div class="clear"></div>
		</div>
	     <div class="btnn">

	          <button type="submit">Login</button><br>
       	      <a href="#" class="for" >Forgot password...?</a>  
       	      
        </div>	
     </div>

   </form>
<div class=" header-side">
<h4>Or Login With</h4>
				<div class="buttom-social-grids">

					<ul>
						<li><a href="www.facebook.com"><span class="fa fa-facebook"></span></a></li>
						<li><a href="www.twitter.com"><span class="fa fa-twitter"></span></a></li>
						<li><a href="www.gmail.com"><span class="fa fa-google"></span></a></li>
					</ul>
				</div>
			</div>
</div>
</div>
			<div class="copy">
		<p>&copy;Team Winterfell homepage. All Rights Reserved | Designed by <a href="#" target="_blank">Team Winterfell</a></p>
	</div>

	
	<script>
		addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); }
	</script>
	 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type ='text/javascript' src = 'js/script.js'></script>
</body>
</html>