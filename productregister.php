<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

#loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
        </style>
  <title>Product Registration Page for Analytics 360 </title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
 <!--===============================================================================================-->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <!--===============================================================================================-->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <!--===============================================================================================-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script
  <!--===============================================================================================-->
</head>
<body>

  
  <form class="login100-form validate-form" method="post" action="productregister.php">
  	<?php include('errors.php'); ?>
	<div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg-01.jpg');">
        <img id="loader" src="images/logo.jpeg"></img>
			<div class="wrap-login100">
                
				<form class="login100-form validate-form">
					<span>
						<img class="login100-form-logo" src="images/logo.jpeg"/>
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Product Registration Form
					</span>
					
					<div class="wrap-input100 validate-input" data-validate = "Enter Product Name">
						<input class="input100" type="text" name="Product_Name" placeholder="Product Name" value="<?php echo $Product_Name; ?>">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "Enter Twitter Hashtags">
						<input class="input100" type="text" name="Twitter_Hashtags" placeholder="Twitter Hashtags" value="<?php echo $Twitter_Hashtags; ?>">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "Enter Amazon product link">
						<input class="input100" type="text" name="Amazon_product_link" placeholder="Amazon product link" value="<?php echo $Amazon_product_link; ?>">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>
                    
					<div class="wrap-input100 validate-input" data-validate = "Other details">
						<input class="input100" type="text" name="Other_details" placeholder="Other details" value="<?php echo $Other_details; ?>">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "Card Number">
						<input class="input100" type="text" name="Card Number" placeholder="Card Number" value="<?php echo $Card_Number; ?>">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>
					<div class="wrap-input100 validate-input" data-validate = "CVV">
						<input class="input100" type="text" name="CVV" placeholder="CVV" value="<?php echo $CVV; ?>">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>
                    
					<select name="No_of_Months" class="wrap-input100 validate-input" data-validate = "No_of_Months" size="1" id="months">
					<option value=""> select number of months</option>
					<option value"1">1</option>
					<option value"2">2</option>
					<option value"3">3</option>
					<option value"4">4</option>
					<option value"5">5</option>
					<option value"6">6</option>
					<option value"7">7</option>
					<option value"8">8</option>
					<option value"9">9</option>
					<option value"10">10</option>
					<option value"11">11</option>
					<option value"12">12</option>
					</select>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn"  type="submit" onclick="divload()"" name="product_reg_user">
							Submit
						</button>
                        
					</div>
                    
  </form>
 
  <script>
   window.onload = function() {
  <!-- document.getElementById('loader').style.display = 'none'; -->
  $("#loader").hide();
  
};  

function divload()
{
    <!-- document.getElementById('loader').style.display = 'block'; -->
    $("#loader").show();
    $(".wrap-login100").hide();
}
</script>
</body>
</html>