<!doctype html>
<?php
//Jiyun Shi
//300260478

include 'functions.php';

//initialize variables for the warning for input validation
$nameWarning = "";
$pwdWarning = "";
$pwdCorrect = "";

//clear all login cookie from this login page.
setcookie("lname","",time()-3600);
setcookie("fname","",time()-3600);
setcookie("usrid","",time()-3600);

//check if reset button is clicked
if(isset($_POST["clear"])) {
	unset($_POST["submit"]);
	unset($_POST["clear"]);
	}

//if submit is clicked
if (isset($_POST["submit"])){
	
	//get user input of the lastname, password
	$lName = $_POST["username"];
	$pwd = $_POST["pwd"];
	//initial the input validation to false
	$nameinput =false;
	$pwdinput =false;
	//check lastname input is empty or characters less than 20
	if (empty($lName)) $nameWarning = " ***Your lastname ?*** ";
	else if (strlen($lName)>20) $nameWarning = " ***Your lastname has TOO many characters?*** ";
	//if validation is passed, set the flag to true
	else $nameinput = true;
	
	//check password input is empty or not equal to 7 characters
	if (empty($pwd)) $pwdWarning = " ***Your password ?*** ";
	else if (strlen($pwd)!=7) $pwdWarning = " ***Your password MUST HAVE 7 characters ?*** ";
	//if validation is passed, set the flag to true
	else $pwdinput = true;
	 
	 //if both inputs validation are passed
	if($nameinput&&$pwdinput) 
	{	
		//call function to check the lastname/password in database
		if(loginCheck($lName,$pwd)) 
		{	
			//if matched found, set cookie to stroe last name. 
			setcookie("lname", $lName);
			//go to next titlesrch page
			header("location: titleSrch.php");
			
			//otherwise, alert user the username/password is not matched in database
		}else $pwdCorrect = " ***Your Password DO NOT MATCH, Please Re-enter*** ";
	}
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
.warning{
	color:red;
}
</style>




<meta charset="UTF-8">
<title>Shaw Channel</title>
</head>

<body>

	<h1>Shaw Channel</h2>
	<h2>Member Login<h3>

	<form action="<?php 	print $_SERVER['PHP_SELF']; ?>" method="POST">
		<table align="center">
        	<tr>
            	<td>
                	<p>Enter Your Lastname (MAX:20 characters)</p>
                </td>
                <td>
                	<input type="text" name="username" value = "<?php 
                	if(isset($_POST["username"])) print ($_POST["username"]); ?>"/>
                </td>
                <td>
                	<?php print "<p class='warning'> $nameWarning </p>"?>
                </td>
            </tr>
            <tr>
                <td>
                	<p>Enter Your Password (7 characters)</p>
                </td>
                <td align="left">
                	<input type="password" name="pwd" style="width:50%" value = "<?php 
                	if(isset($_POST["pwd"])) print ($_POST["pwd"]); ?>" />
                </td>
                <td>
                	<?php print "<p class='warning'> $pwdWarning </p>"?>
                </td>
            </tr>
            <tr>
            	<td></td>
                <td>
                	<input type="submit" name="submit" value="Login" autofocus/>  <input type="submit" name="clear" value="Clear"/>
                </td>
            </tr>
		</table>
        
		<?php print "<p class='warning'> $pwdCorrect </p>"?>             
		</form>


		<form action="addNewCust.php" method="POST">
        <p style="color:blue">For New Members, Please login here <input type="submit" value="New Member" /> </p>
		</form>


</body>
</html>