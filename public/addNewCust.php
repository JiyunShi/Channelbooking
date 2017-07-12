<?php
//Jiyun Shi
//300260478

include 'functions.php';
//set variable and flags
$fnameflag = false;
$lnameflag =false;
$emailflag = false;
$pwdflag = false;
$indent = "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
$fnameWarning = $indent;
$lnameWarning = $indent;
$emailWarning = $indent;
$pwdWarning = $indent;

//if submit is clicked
if (isset($_POST["submit"]))
{	
	//initial validation warnings
	$fnameWarning = "";
	$lnameWarning = "";
	$emailWarning = "";
	$pwdWarning = "";
	//get user inputs
	extract($_POST);
	//check if first name input is empty or > 20 character
	if(strlen($firstname)>20) $fnameWarning = " </td><td> <p class='warning'>***Your Lastname has TOO many Characters?*** </p>";
	else if (empty($firstname)) $fnameWarning = "</td><td> <p class='warning'> ***Your Lastname ?*** </p>";
	//otherwise set validation flag correct
	else $fnameflag =true;
	
	//check if last name input is empty or > 20 character
	if(strlen($lastname)>20) $lnameWarning = "</td><td> <p class='warning'> ***Your Lastname has TOO many Characters?*** </p>";
	else if (empty($lastname)) $lnameWarning = "</td><td> <p class='warning'> ***Your Lastname ?*** </p>";
	//otherwise set validation flag correct
	else $lnameflag =true;
	//check if email input is empty or > 20 character
	if(strlen($email)>20) $emailWarning = "</td><td> <p class='warning'> ***Your e-mail has TOO many Characters?*** </p>";
	else if (empty($email)) $emailWarning = "</td><td> <p class='warning'> ***Your email ?*** </p>";
	//otherwise set validation flag correct
	else $emailflag =true;
	
	//check if password input is empty
	if (empty($pwd)) $pwdWarning = "</td><td> <p class='warning'> ***Your Password ?*** </p>";
	//if charater == 7
	else if(strlen($pwd)!=7) $pwdWarning = "</td><td> <p class='warning'> ***Your Password MUST be <br /> 7 characters?*** </p>";
	//if all are number
	else if (is_numeric($pwd)) $pwdWarning = "</td><td> <p class='warning'> ***Your Password cannot be <br /> numeric ***</p>";
	//if have captial letters
	else if (!ctype_lower(preg_replace('/[0-9]+/', '', $pwd))) $pwdWarning = "</td><td> <p class='warning'> ***Invalid character *** </p>";
	//if first letter is lowercase letter
	else if (is_numeric($pwd[0])) $pwdWarning = "</td><td> <p class='warning'> *** Password must begin with lowercase letter ***</p>";
	//otherwise set validation flag correct
	else $pwdflag =true;
	
	//if all flags are passed
	if($fnameflag&&$lnameflag&&$emailflag&&$pwdflag)
	{	
		//call the function, connect to database and check if the username/password combination is already in database
		if(loginCheck($lastname, $pwd)) $pwdWarning = "</td><td> <p class='warning'> ***Password is prohibited, <br />please re-enter ***</p>"; 
		else {
			//otherwise add the new customer record onto database
			$myCon = connectDatabase();
  			$sql = "insert into customertbl (cust_fname, cust_lname, cust_email, cust_passw) 
			values ('$firstname', '$lastname', '$email', '$pwd')";
			$res = mysqli_query($myCon,$sql);
			//get the currect user_id for the record inserted
			$id = mysqli_insert_id($myCon);
			if($res){
				//set cookie for the current user for login status.
				setcookie("lname", $lastname);
				setcookie("fname", $firstname);
				setcookie("usrid", $id);
				//move to the next page titleSrch
				header("location: titleSrch.php");
			}else 
			{
				print "problem".mysqli_error($myCon);
				exit();
			} 
			//close the connection 
			mysqli_close($myCon);
					
		}
		
	}
	
	
	
}





?>
<!doctype html>
<html>
<head>
<title>New Member</title>
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
.left{
	text-align:left;
}
.right{
	text-align:right;
}
table {
	border-style:ridge;
	margin-left:auto; 
    margin-right:auto;
	
}
th,td{
	border-style:ridge;
}


</style>



</head>
<body>

<h1>Shaw Channel</h1>
<h2>New Member</h2>

<form action="<?php 	print $_SERVER['PHP_SELF']; ?>" method="POST">


<table>
        	<tr>
            	<td class="right">
                	<p>Enter Your <b>First Name</b>(MAX:20 char.)</p>
                </td>
                <td class="left">
                	<input type="text" name="firstname" value = "<?php
                	if(isset($_POST["firstname"])) print ($_POST["firstname"]); ?>"/>
                
                	<?php print "$fnameWarning"?>
                </td>
            </tr>
            <tr>
            	<td class="right" >
                	<p>Enter Your <b>Last Name</b>(MAX:20 char.)</p>
                </td>
                <td class="left">
                	<input type="text" name="lastname" value = "<?php 
                	if(isset($_POST["lastname"])) print ($_POST["lastname"]); ?>"/>
                
                	<?php print "$lnameWarning "?>
                </td>
            </tr>
             <tr>
            	<td class="right">
                	<p>Your <b>e-mail</b> address (MAX:20 char.)</p>
                </td>
                <td class="left">
                	<input type="text" name="email" value = "<?php 
                	if(isset($_POST["email"])) print ($_POST["email"]); ?>"/>
                
                	<?php print "$emailWarning"?>
                </td>
            </tr>
            <tr>
                <td class="right">
                	<p>Your <b>Password</b></p>
                    <ul>
                    	<li>MUST BE 7 CHARACTERS</li>
                        <li><b>CANNOT</b> BE ALL DIGITS</li>
                        <li><b>MUST BEGIN</b> with a lowercase LETTER of <br /> the alphabet</li>
                        <li><b>ONLY lowercase LETTERS OF THE <br />ALPHABET ALLOWED</b></li>
                    </ul>
                </td>
                <td class="left">
                	<input type="text" name="pwd" style="width:70px" value = "<?php
                	if(isset($_POST["pwd"])) print ($_POST["pwd"]); ?>" />
                
                	<?php print "$pwdWarning"?>
                </td>
            </tr>
            <tr>
            	<td></td>
                <td align="left">
                	<input align="left" type="submit" name="submit" value="submit" autofocus/> 
                </td>
            </tr>
		</table>


</form>




</body>
</html>