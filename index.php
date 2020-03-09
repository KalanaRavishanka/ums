<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php 

	// check for form submition(submit button press or not)
	if(isset($_POST['submit'])) {

		$errors = array();

		//check if the user name nad the password entered
		if(!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1) {

			$errors[] = 'Username is Missing / Invalid';

		}

		if(!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1) {

			$errors[] = 'Password is Missing / Invalid';

		}

		// check if there any error in the form
		if(empty($errors)){

			//save username and password into variables
			$email 		= mysqli_real_escape_string($connection, $_POST['email']);
			$password 	= mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = sha1($password);

			//prepare a query for check the user name and the password are include in the databasae

			$query = "SELECT * FROM user
						WHERE email = '{$email}'
						AND password = '{$hashed_password}'
						LIMIT 1"; // we need only one user for we use limit 1

			// we should give this query to the database

			$result_set = mysqli_query($connection, $query);
			// we need to check query is successfull

			if($result_set) {
				// query successful

				if(mysqli_num_rows($result_set) == 1) {
					// valid user found
					$user = mysqli_fetch_assoc($result_set);
					$_SESSION['user_id'] = $user['id'];
					$_SESSION['first_name'] = $user['first_name'];
					//redirect to user.php
					header('Location: users.php');
				}
				else {
					$errors[] = 'Invalid Username / Password';
				}
			}else{
				$errors[] = 'Database query faild';
			}


		}
	}


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Log In - User Management System</title>
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	<div class="login">
		
		<form action="index.php" method="post">
			
			<fieldset>
				<legend><h1>Log In</h1></legend>

				<?php 

					if(isset($errors) && !empty($errors)){
						echo '<p class="error">Invalid Username / Password</p>';
					}

				 ?>

				 <?php 
				 	if (isset($_GET['logout'])) {
				 		echo '<p class="info">You have successfully Logged out from the system </p>';
				 	}

				  ?>

				<p>
					<label for="">Username:</label>
					<input type="text" name="email" id="" placeholder="Email Address">
				</p>

				<p>
					<label for="">Password:</label>
					<input type="password" name="password" id="" placeholder="Password">
				</p>

				<p>
					<button type="submit" name="submit">Log In</button>
				</p>

			</fieldset>

		</form>

	</div>
</body>
</html>
<? php mysqli_close($connection); ?>