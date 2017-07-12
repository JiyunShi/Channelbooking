<?php
//Jiyun Shi
//300260478
include "functions.php";

//get user input from page titlesrch
extract($_POST);
//initialize groupby if it's not set from previous page
if(!isset($groupby)) $groupby="";

//connect to the database
$myCon = connectDatabase();

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

<title>Shaw Channel</title>
</head>

<body>

<h1> Shaw Channel </h1>
<h2> Title Search Results </h2>

<form action="addOrderChannel.php" method="post">

<?php

//set up the query based on the condition user selected
//if search keyword is empty
if(empty($searchItem)) 
{	
	//order by title
	if($ordby=="title") {
		//Genre is All
		if($genre=='a') {
			//if Group by Genre is selcted
			if($groupby=="genre") $sql = "select * from channeltbl  order by ch_genre,ch_title";
			else $sql="select * from channeltbl order by ch_title";
		}
		//Genre is selected -not All
		else $sql = "select * from channeltbl where ch_genre = '$genre' order by ch_title";
	}
	//order by price
	else{
		//Genre is All
		if($genre=="a"){
			//if Group by Genre is selcted
			if($groupby=="genre") $sql = "select * from channeltbl order by ch_genre,ch_price desc ";
			else $sql = "select * from channeltbl order by ch_price desc";
		}
		//Genre is selected -not All
		else $sql = "select * from channeltbl where ch_genre = '$genre' order by ch_price desc";	
	}
}
//if search keyword is not empty
else
{	
	//order by title
	if($ordby=="title") 
	{	//Genre is All
		if($genre=='a') {
			//if Group by Genre is selcted
			if($groupby=="genre"){
				switch($searchby){
					case "within": $sql = "select * from channeltbl where ch_title like '%$searchItem%' order by ch_genre,ch_title"; break;
					case "start": $sql = "select * from channeltbl where ch_title like '$searchItem%' order by ch_genre,ch_title"; break;
					case "exact": $sql = "select * from channeltbl where ch_title = '$searchItem' order by ch_genre,ch_title"; break;
				}					
			}
			//if Group by Genre is not selcted
			else{
				switch($searchby){
					case "within": $sql = "select * from channeltbl where ch_title like '%$searchItem%' order by ch_title"; break;
					case "start": $sql = "select * from channeltbl where ch_title like '$searchItem%' order by ch_title"; break;
					case "exact": $sql = "select * from channeltbl where ch_title = '$searchItem' order by ch_title"; break;
				}					
			}
		}
		//Genre is selected -not All
		else switch($searchby){
					case "within": $sql = "select * from channeltbl where ch_title like '%$searchItem%' and ch_genre = '$genre' order by ch_title"; break;
					case "start": $sql = "select * from channeltbl where ch_title like '$searchItem%' and ch_genre = '$genre' order by ch_title"; break;
					case "exact": $sql = "select * from channeltbl where ch_title = '$searchItem' and ch_genre = '$genre' order by ch_title"; break;
		}
	}
	//order by price
	else
	{	//Genre is All
		if($genre=="a"){
			//if Group by Genre is selcted
			if($groupby=="genre") {
				switch($searchby){
					case "within": $sql = "select * from channeltbl where ch_title like '%$searchItem%' order by ch_genre,ch_price desc"; break;
					case "start": $sql = "select * from channeltbl where ch_title like '$searchItem%' order by ch_genre,ch_price desc"; break;
					case "exact": $sql = "select * from channeltbl where ch_title = '$searchItem' order by ch_genre,ch_price desc"; break;
				}					
			}
			//if Group by Genre is not selcted
			else {
				switch($searchby){
					case "within": $sql = "select * from channeltbl where ch_title like '%$searchItem%' order by ch_price desc"; break;
					case "start": $sql = "select * from channeltbl where ch_title like '$searchItem%' order by ch_price desc"; break;
					case "exact": $sql = "select * from channeltbl where ch_title = '$searchItem' order by ch_price desc"; break;
				}					
			}
		}
		//Genre is selected -not All
		else switch($searchby){
					case "within": $sql = "select * from channeltbl where ch_title like '%$searchItem%' and ch_genre = '$genre' order by ch_price desc"; break;
					case "start": $sql = "select * from channeltbl where ch_title like '$searchItem%' and ch_genre = '$genre' order by ch_price desc"; break;
					case "exact": $sql = "select * from channeltbl where ch_title = '$searchItem' and ch_genre = '$genre' order by ch_price desc"; break;
		}	
	}


}

//run the query on the database
$res = mysqli_query($myCon,$sql);

if($res)
	{
			//print the table based on the result from the database	
			print "<table align='center'>
					<tr><th width='250px'>Title</th><th>id</th><th>Logo</th><th>Genre</th><th>Price</th><th>Add to<br />Cart</th></tr>";
			for($row=1;$row<=mysqli_num_rows($res);$row++)
			{	
				print "<tr>";
			
				$record = mysqli_fetch_row($res);
				$genreDisplay = $genreArr[$record[2]];
				print "	<td>$record[1]</td>
						<td>$record[0]</td>
						<td><img src='logos/$record[4]' alt='$record[0]' height='40' width='60'></td>
						<td>$genreDisplay</td>
						<td>$record[3]</td>
						<td><input type='checkbox' name='addCart[]' value=$record[0] /></td></tr>";		
			}
			
			print "</table>";
			
		
	}else 
	{
		print "problem".mysqli_error($myCon);
		exit();
	}  
	//close the connection
	mysqli_close($myCon);
?>
	<p><input type="submit" name="submit" value="Submit" /> <input type="reset" value="Clear" /></p>
</form>

</body>
</html>