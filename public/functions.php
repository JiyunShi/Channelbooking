<?php
//Jiyun Shi
//300260478

/* This file contents some variables, functions to be used across different webpages */


//initialize array for Genre
$genreArr = array('e'=>"Entertainment", 'f'=>"Family", 'i'=>"Information", 
				'm'=>"Movie",'n'=>"Nuews/Business",'o'=>"Old TV Shows", 's'=>"Sci-Fi", 't'=>"Sports");


//Connection to to Database, return $myCon if connection is sucessful.
function connectDatabase(){
	
	$servername = "localhost";
	$username = "root";
	$password = "root";
	// Create connection
	$myCon = mysqli_connect($servername, $username, $password,"channelwatchdb");
	
	if(mysqli_connect_errno()) 
	{
  	print "error connection to server";
	exit();
	
  }	else return $myCon;
	
}

//login usrname/password check
function loginCheck($user,$pwd){
	
	//connect Database
	$myCon = connectDatabase();
  	
	//query to get password, customer first name and customer id from the database where lastname = input
	$sql = "select cust_passw, cust_fname, cust_id from customertbl where cust_lname ='$user'";
	$res = mysqli_query($myCon,$sql);
	if($res)
	{	
		//if no record return, there's no lastname = input in the database, return false.
		if (mysqli_num_rows($res)<=0) return false;
		else
		{	
			//get all record lastname = input
			for($row=1;$row<=mysqli_num_rows($res);$row++)
			{
				$record = mysqli_fetch_row($res);
				//if input password == database password
				if($record[0]==$pwd) {
					//set cookie to stroe firstname and user id 
					setcookie("fname",$record[1]);
					setcookie("usrid",$record[2]);
					//close the database connection
					mysqli_close($myCon);
					return true;
				}
			}
			//otherwise no password is equal to input, close the connection and return false
			mysqli_close($myCon);
			return false;
		}
	}else 
	{
		print "problem".mysqli_error($myCon);
		exit();
	}  
	 
}




?>