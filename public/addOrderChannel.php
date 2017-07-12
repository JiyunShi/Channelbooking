<?php
//Jiyun Shi
//300260478

include "functions.php";

//Check if customer is logged in
if (isset($_COOKIE["lname"])){
	
	extract($_COOKIE);
	}else{
		print "<h1> you have been logout, please log in first</h1>";
		print "<h2> <a href='channelLogin.php'>log in</a><h2>";
		exit();
		
	}

//display warning if user didn't select any from the channel list
if (empty($_POST["addCart"])) $formDesc = "You did not select any channel!<br />But below is your current Channel lineup(in WHITE) AND
								in your CART (in YELLOW) from before";
	else $formDesc = "Below is your current Channel lineup (in WHITE) AND in your CART (in YELLOW)";


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
<title>Order Details</title>
</head>

<body>

<h1> Shaw Channel </h1>
<h1> Your Cart / Channel Lineup</h1>
<h2> Order So Far for 
	<?php

		print $fname." ".$lname;
	
 	?> </h2>
<p><?php print $formDesc ?></p>

<table align="center">
	<tr>
    <th width="250px">Title</th><th>ID</th><th width="100">Logo</th><th width="150">Price</th>
    </tr>
    <?php
	
		//connect to the database
		$myCon = connectDatabase();
			
		//get correct records in ordertbl for current user
		$sql = "select ch_title, ord_ch_id, ch_logo, ch_price, ord_in_cart_ordered from ordertbl
					inner join channeltbl on ord_ch_id=ch_id 
					where ord_cust_id = $usrid
					order by ch_title";
		$res = mysqli_query($myCon,$sql);
		if($res)
		{	
			//initialize both cost 
			$current=0;
			$cart = 0;
			
			//if user didn't select any channel from the previous page			
			if(empty($_POST["addCart"]))
			{	
					//get previous record from database 
					for($row=1;$row<=mysqli_num_rows($res);$row++)
					{	
					
						$record = mysqli_fetch_row($res);
						//if this channel is lineup (*paid*) display in table
						if($record[4]=='n')
						{	
							//only display the *paid* channels
							print "<tr>";
							$current +=$record[3];
							print "	<td>$record[0]</td>
									<td>$record[1]</td>
									<td><img src='logos/$record[2]' alt='$record[0]' height='40' width='60'></td>
									<td>$record[3]</td></tr>";		
						}
					}
				}
			//if user selected any channel from the previous page list
			else
			{	
				//get the array of user selected channels
				extract($_POST);
				
				//get previous record from the database	
				for($row=1;$row<=mysqli_num_rows($res);$row++)
				{	
						$record = mysqli_fetch_row($res);
						//user selected channel is already in database record, remove it from the array	
						if(($key = array_search($record[1],$addCart))!==false) unset($addCart[$key]);
						//only display the *paid* channels
						if($record[4]=='n'){
							print "<tr>";
							$current +=$record[3];
							print "	<td>$record[0]</td>
									<td>$record[1]</td>
									<td><img src='logos/$record[2]' alt='$record[0]' height='40' width='60'></td>
									<td>$record[3]</td></tr>";		
						}
					}
				$addCart = array_values($addCart);
				
				//if the use selected array is not empty after above filter
				if (!empty($addCart))
				{		
						//for each channel in the array, add it onto the database table ordertbl
						foreach ($addCart as $item)
						{
							//get the channel price first
							$sql = "select ch_price from channeltbl where ch_id = $item";
							$res = mysqli_query($myCon, $sql);
							if($res){
								$ord_price = mysqli_fetch_row($res);
								//add the record to ordertbl
								$sql = "insert into ordertbl(ord_cust_id,ord_ch_id,ord_in_cart_ordered,ord_price)
										values ($usrid, $item, 'y', $ord_price[0])";
								$res2 = mysqli_query($myCon,$sql);
								if(!$res2) 
								{
									print "problem".mysqli_error($myCon);
									exit();
								}  
							}else 
							{
							print "problem".mysqli_error($myCon);
							exit();
							}  
						
						}
	
					}
			}
			
			//display the un-paid channel --in CART channel
			$sql = "select ch_title, ord_ch_id, ch_logo, ch_price from ordertbl
				inner join channeltbl on ord_ch_id=ch_id 
				where ord_cust_id = $usrid and ord_in_cart_ordered ='y'
				order by ch_title";
				
			$res = mysqli_query($myCon,$sql);
			if($res){
				if (mysqli_num_rows($res)>0){
						//initial flag for the bottom letters display
						$hasNew = true;
						for($row=1;$row<=mysqli_num_rows($res);$row++)
						{	
							$record = mysqli_fetch_row($res);
							print "<tr>";
							//add up in cart cost
							$cart +=$record[3];
							print "	<td bgcolor='yellow'>$record[0]</td>
									<td>$record[1]</td>
									<td><img src='logos/$record[2]' alt='$record[0]' height='40' width='60'></td>
									<td bgcolor='yellow'>**$record[3]**</td></tr>";	
						}
					}else{
					$hasNew = false;
				}
			}
			else
			{
					print "problem".mysqli_error($myCon);
					exit();
				}  			
		}
			
		else 
		{
		print "problem".mysqli_error($myCon);
		exit();
		}  
		//close the connection
		mysqli_close($myCon);
		
		//display the order costs
		$total = $current+$cart;
		print "<tr><td colspan='3' align='right'><b>Total:</b></td>
				<td> <b>Current: $$current</b><br />
					<b>Cart: **$$cart **</b><br />
					<b>Total: $$total</td></tr></table><br /><br />";
		
	?>
    
    <form action="processChannelOrders.php", method="post">
    	<p><?php
			//if has unpaid channel(IN CART Channel), display the input for credit card
			if($hasNew) print "Enter your Credit Card Number:<input type='text' name='credit' />";
			else print  "PLEASE CLICK BROWSER BACK BUTTON TO RETRY";
		    ?></p>
        <?php
			if($hasNew) print "<input type='submit' name='submit' value='CheckOut' />  Or 
								<input type='submit' formaction='channelLogin.php' value='Log Out'>";
 							
			else print  "Or <input type='submit' value='Log Out' formaction='channelLogin.php'>";
		
		     ?>  </form>
    	

</body>
</html>