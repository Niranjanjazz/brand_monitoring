<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$Product_Name = "";
$Twitter_Hashtags = "";
$Amazon_product_link = "";
$Other_details = "";
$No_of_Months = "";
$CVV = "";
$Card_Number = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'sentimental_analysisdb');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
  // $login_user ='';
  // $login_user = $username;
  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: freetrail.php');
  }
}
// $login_user = $login_user;
// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  // $login_user ='';
  // $login_user = $username;
  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "User doesn't exists/invalid username or password");
  	}
  }
}
// $login_user = $login_user;
//Product Registration Page
if (isset($_POST['product_reg_user'])) {
	 // receive all input values from the form
  //  $username = mysqli_real_escape_string($db, $_POST['username']);
  $Product_Name = mysqli_real_escape_string($db, $_POST['Product_Name']);
  $Twitter_Hashtags = mysqli_real_escape_string($db, $_POST['Twitter_Hashtags']);
  $Amazon_product_link = mysqli_real_escape_string($db, $_POST['Amazon_product_link']);
  $Other_details = mysqli_real_escape_string($db, $_POST['Other_details']);
  $No_of_Months = mysqli_real_escape_string($db, $_POST['No_of_Months']);
  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($Product_Name)) { array_push($errors, "Product Name is required"); }
  if (empty($Twitter_Hashtags)) { array_push($errors, "Twitter_Hashtags is required"); }
  if (empty($Amazon_product_link)) { array_push($errors, "Amazon product link is required"); }
  if (empty($No_of_Months)) { array_push($errors, "No_of_Months  is required"); }
  // first check the database to make sure 
  // a product does not already exist with the same username and/or email
  $product_check_query = "SELECT * FROM products WHERE Product_Name='$Product_Name' LIMIT 1";
  $result = mysqli_query($db, $product_check_query);
  $product = mysqli_fetch_assoc($result);
  
  if ($product) { // if product exists
    if ($product['Product_Name'] === $Product_Name) {
      array_push($errors, "Product already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    set_time_limit(500);
  	$query = "INSERT INTO products (id,Product_Name, Twitter_Hashtags, Amazon_product_link, Other_details, No_of_Months) 
  			  VALUES(now(),'$Product_Name', '$Twitter_Hashtags', '$Amazon_product_link', '$Other_details', '$No_of_Months')";
  	mysqli_query($db, $query);
  	$_SESSION['Product_Name'] = $Product_Name;
  	$_SESSION['success'] = "You have registerd the product in Brand360";
    chdir('C:\Users\Niranjan\PycharmProjects\Amazon_review_scraper\amazon_reviews');
    shell_exec('scrapy crawl amazon_scrapper');
    $command = escapeshellcmd('python C:\Users\Niranjan\PycharmProjects\Amazon_review_scraper\amazon_reviews\amazon_nlp_model.py "' .$Product_Name.'"');
    $output = shell_exec($command);
    $twitter_command = escapeshellcmd('python C:\Users\Niranjan\PycharmProjects\Amazon_review_scraper\amazon_reviews\twitter.py "' .$Product_Name.'" "' .$Twitter_Hashtags.'"');
    $twitter_output = shell_exec($twitter_command);
  

  	header('location: index.php');
  }
}
//index.php
if (isset($_POST['gotoproductspage']))
{
	header('location: productregister.php');
}
//Free trail Product Registration Page
if (isset($_POST['free_product_reg_user'])) {
	 // receive all input values from the form
  $Product_Name = mysqli_real_escape_string($db, $_POST['Product_Name']);
  $Twitter_Hashtags = mysqli_real_escape_string($db, $_POST['Twitter_Hashtags']);
  $Amazon_product_link = mysqli_real_escape_string($db, $_POST['Amazon_product_link']);
  $Other_details = mysqli_real_escape_string($db, $_POST['Other_details']);
  $No_of_Months = 1;
  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($Product_Name)) { array_push($errors, "Product Name is required"); }
  if (empty($Twitter_Hashtags)) { array_push($errors, "Twitter_Hashtags is required"); }
  if (empty($Amazon_product_link)) { array_push($errors, "Amazon product link is required"); }
 // if (empty($No_of_Months)) { array_push($errors, "No_of_Months  is required"); }
  // first check the database to make sure 
  // a product does not already exist with the same username and/or email
  $product_check_query = "SELECT * FROM products WHERE Product_Name='$Product_Name' LIMIT 1";
  $result = mysqli_query($db, $product_check_query);
  $product = mysqli_fetch_assoc($result);
  
  if ($product) { // if product exists
    if ($product['Product_Name'] === $Product_Name) {
      array_push($errors, "Product already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    set_time_limit(500);
  	$query = "INSERT INTO products (id,Product_Name, Twitter_Hashtags, Amazon_product_link, Other_details, No_of_Months) 
  			  VALUES(now(),'$Product_Name', '$Twitter_Hashtags', '$Amazon_product_link', '$Other_details', '$No_of_Months')";
  	mysqli_query($db, $query);
  	$_SESSION['Product_Name'] = $Product_Name;
  	$_SESSION['success'] = "You have registerd the product in Brand360";
    chdir('C:\Users\Niranjan\PycharmProjects\Amazon_review_scraper\amazon_reviews');
    shell_exec('scrapy crawl amazon_scrapper');
    $command = escapeshellcmd('python C:\Users\Niranjan\PycharmProjects\Amazon_review_scraper\amazon_reviews\amazon_nlp_model.py "' .$Product_Name.'"');
    $output = shell_exec($command);
    $twitter_command = escapeshellcmd('python C:\Users\Niranjan\PycharmProjects\Amazon_review_scraper\amazon_reviews\twitter.py "' .$Product_Name.'" "' .$Twitter_Hashtags.'"');
    $twitter_output = shell_exec($twitter_command);
  	header('location: index.php');
  }
}
?>