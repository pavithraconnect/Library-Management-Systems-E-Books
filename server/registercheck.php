<?php include 'password.php';
session_start();

// Create connection
$conn = mysqli_connect("localhost", "root", "root","library");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$purpose = $_POST["purpose"];
$username = $_POST["username"];
$password = $_POST["password"];
$fname = $_POST["fname"];
$lname = $_POST["lname"];
$email = $_POST["email"];

if ($username !='' && $purpose =='checkuser') {
	$query = "SELECT 1 from users where username ='$username'";
	$result = mysqli_query($conn,$query);
	//Check if user exists in the DB
	if ($result->num_rows > 0) {
		echo true;
	}
} 

if ($email !='' && $purpose =='checkusermail') {
	$query = "SELECT 1 from users where email ='$email'";
	$result = mysqli_query($conn,$query);
	//Check if user exists in the DB
	if ($result->num_rows > 0) {
		echo true;
	}
}

if ($username !='' && $password !='' && $fname !='' && $lname !='' && $email !='' && $purpose =='registeruser') {
	$options = ['cost' => 12];
	$password = password_hash($password, PASSWORD_DEFAULT, $options);
	$query = "insert into users(username,password,fname,lname,email,roleid) values('$username','$password','$fname','$lname','$email',1)";
	$result = mysqli_query($conn,$query);
	//Check if user exists in the DB
	if ($result) {
		echo true;
	}
}
if ($username !='' && $password !='' && $purpose =='loginuser') {	
	$query = "SELECT username,password,roles.adminrights from users,roles where username ='$username' and roleid = roles.id";
	$result = mysqli_query($conn,$query);
	//Check if user exists in the DB
	if ($result->num_rows > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			if(password_verify($password, $row['password'])) {
				$_SESSION["username"] = $username;
				if($row['adminrights'] == 'Y'){
					$arr = array ('username'=>$username,'adminrights'=>True);
					$_SESSION["adminrights"] = True;
					$cookie_name = "admin";
					$cookie_value = True;
					setcookie($cookie_name, $cookie_value, time() + (3600), "/");
				}else{
					$arr = array ('username'=>$username,'adminrights'=>False);
					$_SESSION["adminrights"] = False;
				}
				$cookie_name = "admin";
				$cookie_value = $row['adminrights'];
				setcookie($cookie_name, $cookie_value, time() + (3600), "/");
				$cookie_name = "username";
				$cookie_value = $username;
				setcookie($cookie_name, $cookie_value, time() + (3600), "/");
				echo json_encode($arr);
			}
		}
	}
	else {
		echo false;
	}
}
?>
