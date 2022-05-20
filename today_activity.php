<?php
//$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'research_common.php';

//print_r($_POST);
//my_print_r($_FILES);
//my_print_r($_SESSION);
//my_print_r($_SERVER);

$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

//$sql_user='SELECT * FROM `user` WHERE `type` = \'ecm\' or `type` =\'srcm\' or `type` = \'researcher\'';
$sql_user='SELECT * FROM `user` WHERE `id` ="111134841087" ';
echo $sql_user;

$result_user=run_query($link,'research',$sql_user);

while($ar_user=get_single_row($result_user))
{
	ob_start();
	
	//mail -a 'Content-Type: text/html' -s 'Proposals arrived today' biochemistrygmcs@gmail.com,ritambhara.surat@gmail.com,patelkrishna117@gmail.com  <<< $tdp

		$email='mail -a "Content-Type: text/html" -s "HREC_activity_today"';

		//echo '<table border=1>';
		$sql_decision='select * from decision where reviewer_id=\''.$ar_user['id'].'\' and approval=0';
		$result_decision=run_query($link,'research',$sql_decision);		
		if(my_count_rows($result_decision)>0)
		{
	   // echo'<tr><td>';
		//echo '<h2>'.$ar_user['id'].','.$ar_user['email'].'</h2>';
		//echo'</td></tr>';
		while($ar_decision=get_single_row($result_decision))
		{	
			
		/*	$sql_c='select * from comment 
						where 
							proposal_id=\''.$ar_decision['proposal_id'].'\' and 
							date_time like "%2019-09-%" ';*/

                        $sql_c='select * from comment 
                                                where 
                                                        proposal_id=\''.$ar_decision['proposal_id'].'\' and 
                                                        date_time like concat(\'%\',substring(now(),1,10),\'%\')';

			$result_c=run_query($link,'research',$sql_c);
			if(my_count_rows($result_c)>0)
			{
				$content='';
				//echo''.$ar_user['email'].'';
				echo'<table border=1><tr><td>';
				echo '<h5>'.$ar_decision['proposal_id'];
				$sql_p='select * from proposal where id=\''.$ar_decision['proposal_id'].'\'';
				$result_p=run_query($link,'research',$sql_p);
				$ar_p=get_single_row($result_p);
				echo ':('.$ar_p['proposal_name'].')</h5>';
				echo'</td></tr>';
				
				
				while($ar_c=get_single_row($result_c))
				{
					echo '<tr><td><pre>'.$ar_c['comment'].'</pre></td></tr>';	 
					//$content=$content.'<table border="1"><tr><td>'.$ar_decision['proposal_id'].'</td><td>'.$ar_p['proposal_name'].'</td><td>'.$ar_c['comment'].'</td></tr></table>';
					//echo '<table border="1"><tr><td>'.$ar_decision['proposal_id'].'</td><td>'.$ar_p['proposal_name'].'</td><td>'.$ar_c['comment'].'</td></tr></table>';
					//echo $content;
				}
				echo '</table>';
				
			
			}
			
			
		}
		
		}


$out1 = ob_get_contents();
//$out1='echo'.'\''.htmlspecialchars($out1).'\''|'.$email.' '.$ar_user['email'];
//$out1='echo'.htmlspecialchars($out1).' |'.$email.' '.$ar_user['email'];
$out2='echo "'.$out1.'" |'.$email.' '.$ar_user['email'];
//echo $out1;
//$out2 = ob_get_contents();
//echo $out2;
if(strlen($out1)> 0){
//$sql_email='INSERT INTO `email`(`reviewer_id`, `email`, `email_content`) VALUES ('.$ar_user['id'].',\''.$ar_user['email'].'\',\''.$out1 .'\')';
$sql_email='INSERT INTO `email` (email_content) VALUES (\''.my_safe_string($link,$out2).'\')';
					echo $sql_email;
					$result_email=run_query($link,'research',$sql_email);	
				if($result_email==false)
					{
							echo '<h3 style="color:red;">email not send</h3>';
					}
					else
					{
						echo '<h3 style="color:green;">send email </h3>';	
					}
}
else
{
	echo "gvnfv";
}

	
}

?>




