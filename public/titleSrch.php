<?php
//Jiyun Shi
//300260478

//if login, get customer name for welcome message
if (isset($_COOKIE["lname"])){
	$fname = $_COOKIE["fname"];
	$lname = $_COOKIE["lname"];
}
else
{
	print "<h1> Please move back to the login page to sign in </h1>";
	exit();
}


?>

<!doctype html>
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
}
</style>
<title>Title Search</title>
</head>

<body>

	<h1> Shaw Channel </h1>
	<h2> <b>Welcome <?php

		print $fname." ".$lname;
	
 	?> </b></h2>
    <h1> Title Search </h1>

	<form action="fetchDisplayChannel.php" method="POST">
		<table >
			<tr>
            	<td><b>		Title</b></td>
                <td colspan="3" align="center"> <input type="text" name="searchItem" value = "<?php if(isset($_POST["username"])) print ($_POST["username"]); ?>" size="35"/></td>
                <td> <input type="submit" name="submit" value="Search" align="middle"/></td>
            </tr>         	
            <tr>
            	<td rowspan="3"></td>
            	<td><b> Search By: </b></td>
                <td> <select name="searchby"> 
                	<option value="within" selected="selected">Within Title</option>
                    <option value="start">Starting With</option>
                    <option value="exact">Exact Title</option>
                    </select>
                </td>
                <td rowspan="3"><b>Genre</b> 
                	<select name="genre"> 
                		<option value='a' selected="selected">All</option>
                    	<option value='e'>Entertainment</option>
                    	<option value='f'>Family</option>
                        <option value="i">Information</option>
                        <option value="m">Movie</option>
                        <option value="n">News/Business</option>
                        <option value="o">Old TV Shows</option>
                        <option value="s">Sci-Fi</option>
                        <option value="t">Sports</option>
                    </select><br /><br />
                    
                    
                    <b>Group by Genre</b> <input type="checkbox" name="groupby" value="genre"/>
                </td>
                <td rowspan="3"></td>             
            </tr>
            <tr>
            	<td rowspan="2"></td>
                <td> <input type="radio" name="ordby" value="title" checked />Order By Title </td>
                          
            </tr>
            <tr>
            	<td> <input type="radio" name="ordby" value="price" />Order By Price(Highest)</td>
            </tr>
		</table>
        
        <p><input type="reset" value="Clear"/></p>
	</form>


</body>
</html>