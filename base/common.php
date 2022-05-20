<?php


function login()
{
echo'<div class="row">
			<div class="col-sm-3 bg-light mx-auto ">
				<form method=post action=start.php>
				<br>
					<div class="form-group">
					 <div class="col-sm-5 bg-light mx-auto ">
					 <img src="./img/person.png" height="60" width="60" > 
						</div>
					</div>
					<div class="form-group">
						<label for=user>Login ID</label>
						<input  class="form-control" id=user type=text name=login placeholder=Username>
					</div>
					<div class="form-group">						
						<label for=password>Password</label>
						<input  class="form-control" id=password type=password name=password placeholder=Password>
						<input type=hidden name=session_name value=\''.session_name().'\'>
					</div>
					<div class="form-group">						
						<button class="form-control btn btn-primary" type=submit name=action value=login>Login</button>
					</div>
				</form>
			</div>
		</div>	
                <div class="col-sm-7 bg-light mx-auto ">
		   <table class="table table-bordered">
			<tr ><th colspan=2 style="background-color:gray;color:white;text-align: center;margin :0 !important;padding :0 !important;"><h4>NOTE</h4></th></tr>
			<tr><td class="text-primary"><h5>1)</h5></td><td class="text-primary"><h5>To Create New USER Login ID and Password, contact to HREC Office </h5></td></tr>
			<tr><td class="text-primary"><h5>2)</h5></td><td class="text-primary"><h5>If you Forget the Password, contact to HREC Office</h5></td></tr>
			</table>
		</div>




		<div>';
        echo'		   </div>
			</div>
		 </div>';	
		
}

function head()
{
	if(!isset($GLOBALS['nojunk']))
	{
		echo '
		<!DOCTYPE html>
		<html lang="en">
		<head>
		  <title>'.$GLOBALS['title'].'</title>
		  <meta charset="utf-8">
		  <meta name="viewport" content="width=device-width, initial-scale=1">
		  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<link rel="icon" href="favicon.ico">
		  <script src="bootstrap/jquery-3.3.1.js"></script>
		  <script src="bootstrap/popper.js"></script>
		  <script src="bootstrap/js/bootstrap.min.js"></script> 
		</head>
		<body>
		<div class="container">';
	}
}
//		 <meta http-equiv="refresh" content="60">

function tail()
{
	if(!isset($GLOBALS['nojunk']))
	{
		echo '</div></body></html>';
	}
}


/////////////////////////////////////


function get_link($u,$p)
{
	$link=mysqli_connect('127.0.0.1',$u,$p);
	if(!$link)
	{
		echo 'error1:'.mysqli_error($link); 
		return false;
	}
	return $link;
}
function get_remote_link($ip,$u,$p)
{
	$link=mysqli_connect($ip,$u,$p);
	if(!$link)
	{
		echo 'error1:'.mysqli_error($link); 
		return false;
	}
	return $link;
}
function run_query($link,$db,$sql)
{
	$db_success=mysqli_select_db($link,$db);
	
	if(!$db_success)
	{
		echo 'error2:'.mysqli_error($link); return false;
	}
	else
	{
		$result=mysqli_query($link,$sql);
	}
	
	if(!$result)
	{
		echo 'error3:'.mysqli_error($link); return false;
	}
	else
	{
		return $result;
	}	
}

function get_single_row($result)
{
		if($result!=false)
		{
			return mysqli_fetch_assoc($result);
		}
		else
		{
			return false;
		}
}


function my_safe_string($link,$str)
{
	return mysqli_real_escape_string($link,$str);
} 

function  last_autoincrement_insert($link)
{
	return mysqli_insert_id($link);
}

function my_count_rows($result)
{
	return mysqli_num_rows($result);
}



////////////////////////////////////////

?>
