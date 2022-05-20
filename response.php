<?php
session_name($_POST['session_name']);
session_start();
require_once 'config.php';
require_once 'base/common.php';
require_once 'research_common.php';
require_once $GLOBALS['main_user_location'];

$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
display_msg($link);

function display_msg($link)
{
	//in which project you are applicant and not approved by ecms?
	//in which project you are reviewer?
	//list comments of 	all these projects
	
	///////////////
	$sql_a='select id from proposal where applicant_id=\''.$_SESSION['login'].'\' and status!=\'070.ecms_approved\'';
	$result_a=run_query($link,'research',$sql_a);
	$my_application=array();
	while($ar_a=get_single_row($result_a))
	{
		$my_application[]=$ar_a['id'];
	}
	
	//my_print_r($my_application);
	$my_application_str=join('\',\'',$my_application);
	$my_application_str='\''.$my_application_str.'\'';
	//echo $my_application_str;
	
	$sql_application_comment='select * from comment 
								where proposal_id in ('.$my_application_str.')
								order by date_time desc limit '.$GLOBALS['recent_activity_data_count'];
	
	//echo $sql_application_comment;
	$result_application_comment=run_query($link,'research',$sql_application_comment);

	echo '<table border=1 class="table table-striped table-sm"><tr><th colspan=4>Recent activity in proposals by me</th></tr><tr><th>Proposal ID</th><th>Proposal</th><th>Comment by</th><th>Date and Time</th></tr>';

		while($ar=get_single_row($result_application_comment))
		{
			//print_r($ar);
			$proposal_data=get_proposal_info($link,$ar['proposal_id']);
			$reviewer_data=get_user_info($link, $ar['reviewer_id']);
			echo '<tr>';
				echo '<td>';
					echo $ar['proposal_id'];
				echo '</td>';
				echo '<td>';
					echo substr($proposal_data['proposal_name'],0,50).'...';
				echo '</td>';
				echo '<td>';
					echo $reviewer_data['name'];
				echo '</td>';
				echo '<td>';
					echo $ar['date_time'];
					echo popup('id_'.$ar['proposal_id'].$ar['id'],'Proposal:'.$proposal_data['proposal_name'].'<br>comment:<pre>'.$ar['comment'].'</pre>');
				echo '</td>';				
			echo '</tr>';
			//echo 'Proposal ('.$ar['proposal_id'].')'.$proposal_data['proposal_name'].'->Comment by: '.$reviewer_data['name'].' at '.$ar['date_time'].'<br>';
		}
	
	echo '</table>';	
	/////////////////
	$sql_r='select proposal_id from decision where reviewer_id=\''.$_SESSION['login'].'\'';
	
	$result_r=run_query($link,'research',$sql_r);
	$my_review=array();
	while($ar_r=get_single_row($result_r))
	{
		$my_review[]=$ar_r['proposal_id'];
	}
	
	//my_print_r($my_review);
	//my_print_r(array_diff($my_review,$my_application));
	$my_review_only=array_diff($my_review,$my_application);
	$my_review_str=join('\',\'',$my_review_only);
	$my_review_str='\''.$my_review_str.'\'';
	//echo $my_review_str;
	
	//$sql_review_comment='select * from comment 
	//					where proposal_id in ('.$my_review_str.') and
	//					reviewer_id!=\''.$_SESSION['login'].'\'
	//					order by date_time desc limit '.$GLOBALS['recent_activity_data_count'];
	
        $sql_review_comment='select * from comment 
                                                where proposal_id in ('.$my_review_str.') 
                                                order by date_time desc limit '.$GLOBALS['recent_activity_data_count'];


	//echo $sql_review_comment;
	$result_review_comment=run_query($link,'research',$sql_review_comment);

	echo '<table border=1 class="table table-striped table-sm"><th colspan=4>Recent activity in proposals where I am Reviewer</th><tr><th>Proposal ID</th><th>Proposal</th><th>Comment by</th><th>Date and Time</th></tr>';

		while($arr=get_single_row($result_review_comment))
		{
			//print_r($arr);
			$proposal_data=get_proposal_info($link,$arr['proposal_id']);
			$reviewer_data=get_user_info($link, $arr['reviewer_id']);
			echo '<tr>';
				echo '<td>';
					echo $arr['proposal_id'];
				echo '</td>';
				echo '<td>';
					echo substr($proposal_data['proposal_name'],0,50).'...';
				echo '</td>';
				echo '<td>';
					echo $reviewer_data['name'];
				echo '</td>';
				echo '<td>';
					echo $arr['date_time'];
					//echo popup('id_'.$arr['proposal_id'].$arr['id'],'<pre>'.$arr['comment'].'</pre>');
				echo popup('id_'.$arr['proposal_id'].$arr['id'],'Proposal:'.$proposal_data['proposal_name'].'<br>comment:<pre>'.$arr['comment'].'</pre>');
				echo '</td>';				
			echo '</tr>';
			//echo 'Proposal ('.$ar['proposal_id'].')'.$proposal_data['proposal_name'].'->Comment by: '.$reviewer_data['name'].' at '.$ar['date_time'].'<br>';
		}
	
	echo '</table>';			
			
	////////////////
	
}

/*function get_proposal_info($link,$proposal_id)
{
	$sql='select * from proposal where id=\''.$proposal_id.'\''; 
	$result=run_query($link,'research',$sql);
	return get_single_row($result);
}*/
?>
