<?php
//Jiyun
//300260478

include "functions.php";

//check user login status
if (isset($_COOKIE["lname"])){
	
	extract($_COOKIE);
	}else{
		print "<h1> you have been logout, please log in first</h1>";
		print "<h2> <a href='channelLogin.php'>log in</a><h2>";
		exit();
	}

?>


<html>
<head>
<style>
h1 {
    font-size:250%;
	font-family:Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", serif
}
h2 {
    font_size:200%;
}
body{
	text-align:center;
}
table {
	border-style:ridge;
	margin-left:auto; 
    margin-right:auto;
}
th,td{
	border-style:ridge;
	padding:5px;
	text-align:center;
}
</style>
<title>Order Process</title>
</head>
<body>
<h1>Shaw Channel Order Process</h1>
<h2>Order So Far for <?php

						print $fname." ".$lname;
	
 					?></h2>
                    
                    
<?php
	//if credit card input is empty, show the warning.
	if(empty($_POST["credit"])) print "<p><b>PLEASE PRESS BROWSER BACK BUTTON AND RE-ENTER
										YOUR CREDIT CARD NUMBER</b></p>";
	else
	{	
		//if credit card input is not numeric, or length not equal to 16 digits, show the warning
		$credit = $_POST["credit"];
	  	if(!is_numeric($credit)||strlen($credit)!=16)	print "<p><b>CREDIT CARD NUMBER WRONG! </b></p><p><b>PLEASE PRESS BROWSER BACK BUTTON AND RE-ENTER
										YOUR CREDIT CARD NUMBER</b></p><p><b>(Should be numeric and 16 digits)</b></p>";
		else
		{	
			//connect to the database
			$myCon = connectDatabase();
			//get all unpaid orders records for the current user
			$sql = "select * from ordertbl where ord_cust_id = $usrid and ord_in_cart_ordered = 'y'";
			$res = mysqli_query($myCon,$sql);
			if($res){
				//if there's unpaid (in CART) order
				if(mysqli_num_rows($res)>0){
					
					//update the records to be paid
					$sql = "update ordertbl set ord_in_cart_ordered = 'n' where ord_cust_id = $usrid";
					$res = mysqli_query($myCon,$sql);
					if($res){
						//print message once successful
						print "<p><b>Thank You, Please Close Your Browser to exit</b></p>
							<p><b>Or</b> <button onclick='window.location.href=\"channelLogin.php\"'>Log Out</button></p>";
						
					}
					else
					{
						print "problem".mysqli_error($myCon);
						exit();
					}  
					
					
				//if all orders are already paid before	
				}else{
					
					print "<p><b>Order has ALREADY been processed!!!! </b></p>
							<p><b>Please Close Your Browser to exit </b></p>
							<p><b>Or</b> <button onclick='window.location.href=\"channelLogin.php\"'>Log Out</button></p>";
					
				}	
				
				
			}
			else
			{
				print "problem".mysqli_error($myCon);
				exit();
			}  
			//close the connection
			mysqli_close($myCon);
			
		}
		
		
	}

	
?>


</body>
</html>