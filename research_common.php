<?php

function get_user_info($link,$id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from user where id=\''.$id.'\'');
	return get_single_row($result);
}
function get_user_info1($link,$id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where id=\''.$id.'\'');
	return get_single_row($result);
}

function my_print_r($a)
{
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}

//this function is never used
function list_application_for_srcm_assignment($link)
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where status=\'001.applied\'');
	echo '<table class="table table-striped"><tr><th colspan=10>List of research application where reviewer assignment is incomplate</th></tr>
			<tr><th>proposal id</th><th>Applicant id/Name/Department</th><th>Title</th><th>DateTime</th><th>Status</th></tr>';
	while($ar=get_single_row($result))
	{
		$user_info=get_user_info($link,$ar['applicant_id']);
		echo '<tr>
				<td>
					<form method=post>
						<button onclick="return confirm(\'Do you really want to assign application?\');"  class="btn btn-sm btn-block btn-info" name=action value=assign_reviewer >'.$ar['id'].'</button>
						<input type=hidden name=proposal_id value=\''.$ar['id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</form>
				</td>
				<td><span class="text-primary">'.$ar['applicant_id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></td>
				<td>'.$ar['proposal_name'].'</td>
				<td>'.$ar['date_time'].'</td>
				<td>'.$ar['status'].'</td>
		</tr>';
	}
	echo '</table>';
}

function list_application_status($link,$status,$action='none',$message='')
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where status=\''.$status.'\' and (forwarded!=1 or forwarded is null) ');
	echo '<table class="table table-striped"><tr><th colspan=10>List of research application with current status of <span class=bg-danger>'.$status.'</span></th></tr>
			<tr><th>proposal id</th><th>Applicant id/Name/Department</th><th>Title</th><th>DateTime</th><th>Status</th></tr>';
	while($ar=get_single_row($result))
	{
		
		//$user_info=get_user_info($link,$ar['applicant_id']);
		echo '<tr>
				<td>';
		
		if($action!='none')
		{
					echo '<form method=post>
						<button class="btn btn-sm btn-block btn-info" name=action value=\''.$action.'\'  >'.$ar['id'].' '.$message.'</button>
						<input type=hidden name=proposal_id value=\''.$ar['id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</form>';
		}
		else
		{
			echo $ar['id'];
		}			
		echo ' </td>';
		
		echo '<td>';
			echo_applicant_info_popup($link,get_applicant_id($link,$ar['id']),$ar['id']);
		echo '</td>';
		
				//<td><span class="text-primary">'.$ar['applicant_id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></td>
		echo '<td>'.$ar['proposal_name'].'</td>
				<td>'.$ar['date_time'].'</td>
				<td>
				<a data-toggle="collapse" href="#detail_'.$ar['id'].'">'.$ar['status'].'</a>
				<div class="collapse" id="detail_'.$ar['id'].'">';
				show_review_status($link,$ar['id']);
				echo'</div></td>
		</tr>';
	}
	echo '</table>';
}


function list_application_status_multiple($link,$status,$action=array('none'))
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where status=\''.$status.'\' and (forwarded!=1 or forwarded is null) ');
	echo '<table class="table table-striped"><tr><th colspan=10>List of research application with current status of <span class=bg-danger>'.$status.'</span></th></tr>
			<tr><th>proposal id</th><th>Applicant id/Name/Department</th><th>Title</th><th>DateTime</th><th>Status</th></tr>';
	while($ar=get_single_row($result))
	{
		
		//$user_info=get_user_info($link,$ar['applicant_id']);
		echo '<tr>
				<td>';
		
		if($action!='none')
		{
					echo '<form method=post>';
					foreach($action as $k=>$v)
					{
						echo '<button class="btn btn-sm btn-block btn-info" name=action value=\''.$v.'\'  >('.$v.')'.$ar['id'].' </button>';
					}
					
					echo '<input type=hidden name=proposal_id value=\''.$ar['id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</form>';
		}
		else
		{
			echo $ar['id'];
		}			
		echo ' </td>';
		
		echo '<td>';
			echo_applicant_info_popup($link,get_applicant_id($link,$ar['id']),$ar['id']);
		echo '</td>';
		
				//<td><span class="text-primary">'.$ar['applicant_id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></td>
		echo '<td>'.$ar['proposal_name'].'</td>
				<td>'.$ar['date_time'].'</td>
				<td>
				<a data-toggle="collapse" href="#detail_'.$ar['id'].'">'.$ar['status'].'</a>
				<div class="collapse" id="detail_'.$ar['id'].'">';
				show_review_status($link,$ar['id']);
				echo'</div></td>
		</tr>';
	}
	echo '</table>';
}

function list_application_for_reviewer($link,$status,$action='none',$reviewer_id)
{
	
	$sql='select * from proposal,decision where 
					proposal.id=decision.proposal_id
						and
					status=\''.$status.'\'
						and
					decision.reviewer_id=\''.$reviewer_id.'\'
						and
					applicant_id!=\''.$reviewer_id.'\'
					';
	//echo $sql;				
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<table class="table table-striped">
			<tr><th colspan=10>List of research application with current status of <span class=bg-danger>'.$status.'</span></th></tr>
			<tr><th>proposal id</th><th>Applicant</th><th>Reviewer</th><th>Title</th><th>DateTime</th><th>Status</th></tr>';
	while($ar=get_single_row($result))
	{
		$user_info=get_user_info($link,$ar['applicant_id']);
		$reviewer_info=get_user_info($link,$reviewer_id);
		echo '<tr>
				<td>';
		
		if($action!='none')
		{
					echo '<form method=post>
						<button  onclick="return confirm(\'Do you really want to view this application?\');" class="btn btn-sm btn-block btn-info" name=action value=\''.$action.'\' >'.$ar['id'].'</button>
						<input type=hidden name=proposal_id value=\''.$ar['id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</form>';
		}
		else
		{
			echo $ar['id'];
		}			
		echo ' </td>
				<td><span class="text-primary">'.$ar['applicant_id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></td>
				<td><span class="text-primary">'.$reviewer_id.'</span>/<span class="text-danger">'.$reviewer_info['name'].'</span>/<span class="text-primary">'.$reviewer_info['department'].'</span></td>
				<td>'.$ar['proposal_name'].'</td>
				<td>'.$ar['date_time'].'</td>
				<td>'.$ar['status'].'</td>
		</tr>';
	}
	echo '</table>';
}



function list_single_application($link,$id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where id=\''.$id.'\'');
	echo '<table class="table table-warning table-bordered">
			<tr><th>proposal id</th><th>applicant id/name/department</th><th>Proposal</th><th>DateTime</th><th>Status</th></tr>';
	while($ar=get_single_row($result))
	{
		$user_info=get_user_info($link,$ar['applicant_id']);
		echo '<tr>
				<td>'.$ar['id'].'</td>
				<td><span class="text-primary">'.$ar['applicant_id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></td>
				<td>'.$ar['proposal_name'].'</td>
				<td>'.$ar['date_time'].'</td>
				<td>'.$ar['status'].'</td>
		</tr>';
	}
	echo '</table>';
}



function echo_download_button($table,$field,$primary_key,$primary_key_value,$postfix='')
{
	echo '<form method=post action=download.php>
			<input type=hidden name=table value=\''.$table.'\'>
			<input type=hidden name=field value=\''.$field.'\' >
			<input type=hidden name=primary_key value=\''.$primary_key.'\'>
			<input type=hidden name=fname_postfix value=\''.$postfix.'\'>
			<input type=hidden name=primary_key_value value=\''.$primary_key_value.'\'>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
			
			<button class="btn btn-info btn-block"  
			formtarget=_blank
			type=submit
			name=action
			value=download>Download</button>
		</form>';
}


function list_single_application_with_all_fields($link,$id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where id=\''.$id.'\'');
	echo '<table class="table table-warning table-bordered">
			<tr class="bg-success"><th>proposal id</th>
			<th>applicant</th>
			<th>Title</th>
			<th>Type etc.</th>
			<th>Researcher</th>
			<th>DateTime</th><th>Status</th></tr>';
	while($ar=get_single_row($result))
	{
		echo '<tr>
				<td>'.$ar['id'].'</td>
				<td>';
						echo_applicant_info_popup($link,$ar['applicant_id'],$id);
				echo '</td>
				<td>'.$ar['proposal_name'].'</td>
				<td>'.$ar['type'].'<br>Intervantional:'.
				$ar['interventional_research'].'<br>Vulnerable:'.
				$ar['research_on_vulnerable_subjects'].'<br>Subject details:'.
				$ar['description_of_study_subjects'].'<br>Remarks:'.
				$ar['additional_remarks'].'
				<td>'.$ar['guide'].'<br>
				'.$ar['researcher_email_id'].'<br>
				'.$ar['researcher_mobile_no'].'</td>
				<td>'.$ar['date_time'].'</td>
				
				<td>
					<a data-toggle="collapse" href="#xdetail_'.$ar['id'].'">'.$ar['status'].'</a>
					<div class="collapse" id="xdetail_'.$ar['id'].'">';
						show_review_status($link,$ar['id']);
					echo'</div>';
				show_forward_proposal_button($link,$id);

				echo '</td>
			
		</tr>';
	}
	echo '</table>';
	list_attachment($link,$id);
}

function show_forward_proposal_button_original($link,$proposal_id)
{
	$user_info=get_user_info($link,$_SESSION['login']);
	$proposal_info=get_proposal_info($link,$proposal_id);
	if(!$user_info)
	{
		if($proposal_info['forwarded']==1)
		{
			echo '<span class="text-danger">not forwarded</span>';
		}
		else
		{
			echo '<span class="text-success">forwarded</span>';
		}
	}	//user is in dept_user, not in user table
	elseif($proposal_info['forwarded']==1 && $proposal_info['applicant_id']==$_SESSION['login'])
	{
	//print_r($user_info);
	echo '<form method=post>
			<input type=hidden name=proposal_id value=\''.$proposal_id.'\'>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
			
			<button class="btn btn-danger btn-block"  
			type=submit
			name=action
			value=forward_proposal>Forward</button>
		</form>';	
	}
}

function show_forward_proposal_button($link,$proposal_id)
{
	$user_info=get_user_info($link,$_SESSION['login']);
	$proposal_info=get_proposal_info($link,$proposal_id);
	
	//if dept-user
	//only message, no action
	if(!$user_info)
	{
		if($proposal_info['forwarded']==1)
		{
			echo '<span class="text-danger">not forwarded</span>';
		}
		else
		{
			echo '<span class="text-success">forwarded</span>';
		}
	}
	//if real user
	else
	{
		//if researcher (=PG Guide)
		if($proposal_info['applicant_id']==$_SESSION['login'])
		{
			if($proposal_info['forwarded']==1)
			{
				echo '<form method=post>
						<input type=hidden name=proposal_id value=\''.$proposal_id.'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
						
						<button class="btn btn-danger btn-block"  
						type=submit
						name=action
						value=forward_proposal>Forward</button>
					</form>';
			}
			else
			{
				echo '<span class="text-success">forwarded</span>';
			}			
		}
		else
		{
			if($proposal_info['forwarded']==1)
			{
				echo '<span class="text-danger">not forwarded</span>';
			}
			else
			{
				echo '<span class="text-success">forwarded</span>';
			}			
		}
	}
}


function show_forward_attachment_button($link,$attachment_id)
{
	//show only if user is in user table (not dept_user) and applicant of the proposal(that is PG teacher)
	$user_info=get_user_info($link,$_SESSION['login']);
	$applicant_id=get_attachment_applicant_id($link,$attachment_id);
	$afs=get_attachment_forwarding_status($link,$attachment_id);
	
	//user is in dept_user
	if(!$user_info)
	{
		if($afs==1)
		{
			echo '<span class="text-danger">not forwarded</span>';
		}
		else
		{
			echo '<span class="text-success">forwarded</span>';
		}
	}
	//if real user
	else
	{
		//if researcher (=PG Guide)
		if($applicant_id==$_SESSION['login'])
		{
			if($afs==1)
			{
				echo '<form method=post>
						<input type=hidden name=attachment_id value=\''.$attachment_id.'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
						
						<button class="btn btn-danger btn-block"  
						type=submit
						name=action
						value=forward_attachment>Forward</button>
					</form>';	
			}
			else
			{
				echo '<span class="text-success">forwarded</span>';
			}			
		}
		else
		{
			if($afs==1)
			{
				echo '<span class="text-danger">not forwarded</span>';
			}
			else
			{
				echo '<span class="text-success">forwarded</span>';
			}			
		}
	}
	
}

function show_forward_attachment_button_original($link,$attachment_id)
{
	//show only if user is in user table (not dept_user) and applicant of the proposal(that is PG teacher)
	$user_info=get_user_info($link,$_SESSION['login']);
	$applicant_id=get_attachment_applicant_id($link,$attachment_id);
	$afs=get_attachment_forwarding_status($link,$attachment_id);
	
	//user is in dept_user, not in user table	
	if(!$user_info)
	{
		if($afs==1)
		{
			echo '<span class="text-danger">not forwarded</span>';
		}
		else
		{
			echo '<span class="text-success">forwarded</span>';
		}
	}
	elseif($afs==1 && $applicant_id==$_SESSION['login'])	//user is PG teacher
	{
	//print_r($user_info);
	echo '<form method=post>
			<input type=hidden name=attachment_id value=\''.$attachment_id.'\'>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
			
			<button class="btn btn-danger btn-block"  
			type=submit
			name=action
			value=forward_attachment>Forward</button>
		</form>';	
	}
	else //not student, not applicant, forwarded
	{
		echo 'forwarded';
	}
	
}


function list_attachment($link,$proposal_id)
{
	//$result=run_query($link,$GLOBALS['database'],'select * from attachment where proposal_id=\''.$proposal_id.'\' order by date_time');

		echo '<table class="table table-warning table-bordered">';
		echo '<tr class="bg-success"><th>Attachment Type</th><th>Version</th><th>Document</th></tr>';	
foreach($GLOBALS['attachment_type'] as $key =>$value)
	{
		$sql='select * from attachment 
				where 
					proposal_id=\''.$proposal_id.'\' 
					and
					type=\''.$value.'\' 
				order by
					date_time';
					
		$result=run_query($link,$GLOBALS['database'],$sql);

		$prev_type='';
		while($ar=get_single_row($result))
		{
			$applicant_id=get_applicant_id($link,$proposal_id);

			if($ar['forwarded']==0)			//all circumstances
			{
				if($prev_type!=$ar['type'])
				{
					echo '<tr><th class="bg-white" colspan=3>'.$ar['type'].'</th></tr>';
				}
				echo '<tr>
						<td>'.$ar['type'].'</td>
						<td>'.$ar['date_time'].'</td>';
				echo '<td>';
				if(strlen($ar['attachment'])>0)
					{
					echo_download_button('attachment','attachment','id',$ar['id'],''.$proposal_id.'-'.$ar['type'].'-'.$ar['id'].'-'.$ar['attachment_name']);
				}
				show_forward_attachment_button($link,$ar['id']);
				echo '</td></tr>';
				$prev_type=$ar['type'];
			}
			//if 1 and PG teacher =>show download, show forward
			//if 1 and student =>show download, donot show forward
			//if 1 and SRCM/ECM/SRCMS/ECMS=> donot show anything			
			elseif($ar['forwarded']==1 &&  $applicant_id==$_SESSION['login'])	//PG teacher login
			{
				if($prev_type!=$ar['type'])
				{
					echo '<tr><th class="bg-white" colspan=3>'.$ar['type'].'</th></tr>';
				}
				echo '<tr>
						<td>'.$ar['type'].'</td>
						<td>'.$ar['date_time'].'</td>';
				echo '<td>';
				if(strlen($ar['attachment'])>0)
					{
					echo_download_button('attachment','attachment','id',$ar['id'],''.$proposal_id.'-'.$ar['type'].'-'.$ar['id'].'-'.$ar['attachment_name']);
				}
				
				show_forward_attachment_button($link,$ar['id']);
				echo '</td></tr>';
				$prev_type=$ar['type'];
			}

			elseif($ar['forwarded']==1 &&  !get_user_info($link,$_SESSION['login']))	//Department login
			{
				if($prev_type!=$ar['type'])
				{
					echo '<tr><th class="bg-white" colspan=3>'.$ar['type'].'</th></tr>';
				}
				echo '<tr>
						<td>'.$ar['type'].'</td>
						<td>'.$ar['date_time'].'</td>';
				echo '<td>';
				if(strlen($ar['attachment'])>0)
				{
					echo_download_button('attachment','attachment','id',$ar['id'],''.$proposal_id.'-'.$ar['type'].'-'.$ar['id'].'-'.$ar['attachment_name']);
				}
				show_forward_attachment_button($link,$ar['id']);
				
				echo '</td></tr>';
				$prev_type=$ar['type'];
			}
			else
			{
				//donot show if not forwarded and you are not PG teacher or PG student
			}			
		}	
	}
		echo '</table>';
	
}



function display_comment($link,$proposal_id)
{
	echo '<div class="jumbotron" >';
	echo '<h3 class="bg-warning">Comments</h3>';

	$applicant_id=get_applicant_id($link,$proposal_id);
	
	$result=run_query($link,$GLOBALS['database'],'select * from comment where proposal_id=\''.$proposal_id.'\' order by date_time DESC');
	while($ar=get_single_row($result))
	{
		echo '<div class="border border-secondary" >';
		//my_print_r($ar);
		$ri=get_user_info($link,$ar['reviewer_id']);
		//my_print_r($ri);
		echo '	<h3 class="d-inline"><span class="badge badge-primary ">'.$ri['name'].'</span></h3>
				<h4 class="d-inline"><span class="badge badge-secondary">'.$ri['department'].'</span></h4>
				<h5 class="d-inline"><span class="badge badge-info rounded-circle">'.$ri['type'].'</span></h5>';
		if($ar['reviewer_id']==$applicant_id)
		{
			echo '<h5 class="d-inline"><span class="badge badge-danger rounded-circle">APPLICANT</span></h5>';
		}
			
		if(substr($ar['comment'],-11)=='[FORWARDED]')
		{
			echo 	'<pre><span class="d-block pl-5 text-success">'.htmlspecialchars($ar['comment']).'<h5>&#10004;&#10004;&#10004;</h5></span></pre>';
		}
		else
		{
			echo 	'<pre><span class="d-block pl-5 ">'.htmlspecialchars($ar['comment']).'</span></pre>';
		}
		echo'<table width=30%>
		<tr><td>';
		
		if(strlen($ar['attachment'])>0)
		{
			echo_download_button('comment','attachment','id',$ar['id'],''.$proposal_id.'-'.$ar['id'].'-Reviewer_comment-'.$ar['attachment_name']);
		}
		echo'</td></tr>
		</table>';
        
		echo '<mark class="font-italic pl-5 small"> Commented on '.$ar['date_time'].'</mark>
			';
		echo '</div>';
	}	
	echo '</div>';
}

function make_comment($link,$proposal_id)
{
	$status=get_application_status($link,$proposal_id);
    if($status=="070.ecms_approved")
    { 
		echo'<h2>No more changes this application approved</h2>';
		
	}
	else
	{
	echo '<div class="jumbotron" >';
	echo '<h3 class="bg-danger">Make Comment</h3>';
      $applicant_id=get_applicant_id($link,$proposal_id);
				      
				      //echo $applicant_id ;
		echo '<div class="border border-secondary" >';
		$ri=get_user_info($link,$_SESSION['login']);
		//my_print_r($ri);
			echo '	<h3 class="d-inline"><span class="badge badge-primary ">'.$ri['name'].'</span></h3>
			<h4 class="d-inline"><span class="badge badge-secondary">'.$ri['department'].'</span></h4>
			<h5 class="d-inline"><span class="badge badge-info rounded-circle">'.$ri['type'].'</span></h5>';
			echo '<form method=post enctype="multipart/form-data">';
				echo '<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';
				echo '<input type=hidden name=proposal_id value=\''.$proposal_id.'\'>';
				echo '<textarea rows=20 class="form-control "  name=comment>
1.Reviewer:-
2.Permissions:-
3.Title:-
4.Objectives:-
5.Methodology:-
6.Sample size:-
7.Study design:
8.Statistical analysis:-
9.Tools:-
10.Data Sheet/Proforma:-
11.PIS:-
12.References:-
13.Dissemination plan:-
14.Conflicts of interests:-
15.Sources of funding:-
16.Any Ethical issues:-
17.Resubmit with changes:-
18.Overall remarks:-
19.Rejected:-

				      </textarea>';
				      //Researcher and Department-login can not upload in comment
				    if($_SESSION['login']!=$applicant_id && get_user_info($link,$_SESSION['login'])!=false)
				    {
				      	echo'<tr>	 
					   <th>File to upload</th>
					   <td><input type=file name=attachment></td>
					   <td><20 MB file size is accepted</td>
					    </tr>';
					}
					else
					{
					
					}
				echo '<button name=action onclick="return confirm(\'Do you really want to send comment?\');"  value=save_comment class="btn btn-primary btn-block">Send</button>';
			echo '</form>';
		echo '</div>';
		
	echo '</div>';
}
}


function get_proposal_info($link,$proposal_id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where id=\''.$proposal_id.'\'');
	return get_single_row($result);
}
function get_comment_info($link,$proposal_id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from comment where proposal_id=\''.$proposal_id.'\'');
	return get_single_row($result);
}

function save_comment($link,$reviewer_id,$proposal_id,$comment,$attachment='',$attachment_name='')
{	
	$sql='insert into comment 
			(proposal_id,reviewer_id,comment,date_time,attachment,attachment_name)
			values(
			\''.$proposal_id.'\',
			\''.$reviewer_id.'\',
			\''.my_safe_string($link,htmlspecialchars($comment)).'\',
			now(),
			\''.$attachment.'\',
			\''.$attachment_name.'\'
			)';

	//echo $sql;
	if(!run_query($link,$GLOBALS['database'],$sql))
	{
		echo '<br><span class="text-danger">Comment not Saved</span>';
	}
	else
	{
		echo '<br><span class="text-success">Comment Saved</span>';
	}
	
	
	$reviewer_data=get_user_info($link, $reviewer_id);

	$proposal_data=get_proposal_info($link,$proposal_id);
		
	$pre_comment=
	'<h2><b>HREC, GMC Surat.</b></h2>
	<h2><b>Proposal ID:'.$proposal_id.'<br>
	Proposal Name:- <u style=\'color: darkcyan;font-family: arial, sans-serif;font-size: 20px;font-weight: bold;\'>'.$proposal_data['proposal_name'].'</u></b></h2>
	<h3 style=\'font-family: arial, sans-serif;font-size: 20px;font-weight: bold;\'><b>Comment from:- <u style=\'color: darkviolet;font-family: arial, sans-serif;font-size: 20px;font-weight: bold;\'>'.$reviewer_data['name'].'</u></b></h3>
	<h4>Comment:<br>';
	$comment1='<h4>'.nl2br(htmlspecialchars($comment)).'</h4>';

	$final_comment=$pre_comment.$comment1.'<a href="http://gmcsurat.edu.in:12347/hrec">REC Login from Internet</a><br>
	 <a href="http://11.207.1.2/hrec/">REC Login from College Network</a></h4>';
	
	send_all_emails($link,$proposal_id,$final_comment);
}

function save_comment_for_reviewer_assignment($link,$reviewer_id,$proposal_id)
{	
	$comment='You are assigned as reviewer for this protocol';
	$sql='insert into comment 
			(proposal_id,reviewer_id,comment,date_time)
			values(
			\''.$proposal_id.'\',
			\''.$reviewer_id.'\',
			\''.$comment.'\',
			now()
			)';

	//echo $sql;
	if(!run_query($link,$GLOBALS['database'],$sql))
	{
		echo '<br><span class="text-danger">Comment not Saved</span>';
	}
	else
	{
		echo '<br><span class="text-success">Comment Saved</span>';
	}
	
	
	$reviewer_data=get_user_info($link, $reviewer_id);

	$proposal_data=get_proposal_info($link,$proposal_id);
		
	$pre_comment=
	'<h2><b>You received this email because you are reviewer/applicant to HREC, GMC Surat.</b></h2>
	<h2><b>	Proposal Name:- <u style=\'color: darkcyan;font-family: arial, sans-serif;font-size: 20px;font-weight: bold;\'>'.$proposal_data['proposal_name'].'</u></b></h2>
	<h3 style=\'font-family: arial, sans-serif;font-size: 20px;font-weight: bold;\'><b>Comment made by:- <u style=\'color: darkviolet;font-family: arial, sans-serif;font-size: 20px;font-weight: bold;\'>'.$reviewer_data['name'].'</u></b></h3>
	<h4>Following is content of Comment.<br>(for details login to HREC application on following link)<br>
	 <a href="http://gmcsurat.edu.in:12347/hrec">REC Login from Internet</a><br>
	 <a href="http://11.207.1.2/hrec/">REC Login from College Network</a></h4>';
	$comment1='<h4>'.nl2br(htmlspecialchars($comment)).'</h4>';

	$final_comment=$pre_comment.$comment1;
	
	save_email($reviewer_data['email'],$final_comment);
	//send_all_emails($link,$proposal_id,$final_comment);
}

function send_all_emails($link,$proposal_id,$comment)
{
	if($GLOBALS['send_email']!=1){return;}
	$result='select * from proposal where  id=\''.$proposal_id.'\'';
	$result_selected=run_query($link,$GLOBALS['database'],$result);
	$ar=get_single_row($result_selected);

		
		//echo $s['email'].'<br>';
		
		//$c=get_comment_info($link,$proposal_id);
		//echo $c['reviewer_id'];
		$s=get_user_info($link,$ar['applicant_id']);
		save_email($s['email'],$comment,$s['mobile']);
		//echo $s['email'].'<br>';
                $em=get_proposal_info($link,$ar['id']);
		if(strlen($em['researcher_email_id'])>0)
		{
		save_email($em['researcher_email_id'],$comment,$em['researcher_mobile_no']);
		//echo $em['researcher_email_id'].'<br>';
	        }	
	if($ar['status']=="010.srcm_assigned")
		{
			$srcm_reviewer_list=get_only_srcm_reviewer($link,$proposal_id);
			foreach($srcm_reviewer_list as $key=> $value)
			{
			$s=get_user_info($link,$value);
			//echo $s['email'].'<br>';
			//return $s['email'];
			save_email($s['email'],$comment);
			}
		}	
	
		if($ar['status']=="040.ecm_assigned")
		{
			$ecm_reviewer_list=get_only_ecm_reviewer($link,$proposal_id);
			foreach($ecm_reviewer_list as $key=> $value)
			{
			$s=get_user_info($link,$value);
			//echo $s['email'].'<br>';
			save_email($s['email'],$comment);
			//return $s['email'];
			}
		}
}

function save_email($emailid,$comment,$sms=0)
{
	//echo 'tttt'.$GLOBALS['send_email'].'ttttt';
	//return;
	if($GLOBALS['send_email']!=1){return;}
	////////remote save comment//////////
	//$main_server_link=get_remote_link('11.207.1.1',$GLOBALS['main_server_main_user'],$GLOBALS['main_server_main_pass']);
	$main_server_link=get_remote_link($GLOBALS['email_database_server'],$GLOBALS['main_server_main_user'],$GLOBALS['main_server_main_pass']);

    //$sql='INSERT INTO email(`id`,`to`,`subject`,`content`,`sent`)
	// 	VALUES (\'\',\''.$emailid.'\',\'HREC Notice: Action required\',\''.mysqli_real_escape_string($main_server_link,htmlspecialchars($comment)).'\',0)';
	
	if(!$main_server_link){echo 'can not connect to email server'; return false;}
	$sql='INSERT INTO email(`to`,`subject`,`content`,`sent`,sms,sms_sent)
	 	VALUES (\''.$emailid.'\',\'HREC Notice: Action required\',\''.
	 	my_safe_string($main_server_link,$comment).'\',0,\''.$sms.'\',0)';
	
     //echo $sql;
	if(!run_query($main_server_link,'email',$sql))
	{
		echo '<span class="text-danger">email not sent</span><br>';
	}
	else
	{
		//echo '<span class="text-success">Comment Saved</span><br>';
	}
	

}
function reviewer_assign_email()
{
	
	
}
function approve($link,$proposal_id,$who_string)
{
	echo '<div class="jumbotron" >';
	echo '<h3 class="bg-success">Comment and Forward</h3>';

		echo '<div class="border border-secondary" >';
		$ri=get_user_info($link,$_SESSION['login']);
		//my_print_r($ri);
			echo '	<h3 class="d-inline"><span class="badge badge-primary ">'.$ri['name'].'</span></h3>
			<h4 class="d-inline"><span class="badge badge-secondary">'.$ri['department'].'</span></h4>
			<h5 class="d-inline"><span class="badge badge-info rounded-circle">'.$ri['type'].'</span></h5>';
			echo '<form method=post>';
				echo '<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';
				echo '<input type=hidden name=proposal_id value=\''.$proposal_id.'\'>';
				echo '<textarea class="form-control" name=comment></textarea>';
				echo '<button  onclick="return confirm(\'Do you really want to approve application?\');" name=action value=\'approve_'.$who_string.'\' class="btn btn-primary btn-block">Forward</button>';
			echo '</form>';
		echo '</div>';
		
	echo '</div>';
}

function pending_review($link,$proposal_id)
{	
	$applicant_id=get_applicant_id($link,$proposal_id);
	
	$sql='select count(id) as pending_review from user,decision 
			where 
				proposal_id=\''.$proposal_id.'\' and 
				user.id=decision.reviewer_id and
				approval=0 and
				user.id!=\''.$applicant_id.'\'';
				
	$result_selected=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result_selected);
	return $ar['pending_review'];
}

function save_approve($link,$reviewer_id,$proposal_id,$comment)
{	
	save_comment($link,$reviewer_id,$proposal_id,$comment.' [FORWARDED]','','');
	
	$sql='update decision 
			set approval=1
			where
				proposal_id=\''.$proposal_id.'\' and
				reviewer_id=\''.$reviewer_id.'\'';


	if(!run_query($link,$GLOBALS['database'],$sql))
	{
		echo '<span class="text-danger">Your approval not saved</span>';
	}
	else
	{
		echo '<span class="text-success">Your Approval Saved</span>';
	}
}


function view_entire_application($link,$proposal_id,$who_string)
{
	echo '<ul class="nav nav-pills">
		<li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#application">Application</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#review_status">Review Status</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#comment">Comments (SRC)</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#make_comment">Make Comment (SRC)</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#approve">Approve Application (SRC)</a></li>
	</ul>';
	
	echo '<div class="tab-content">';	

		echo '<div class="jumbotron tab-pane container active" id=application>';
			list_single_application_with_all_fields($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=review_status>';
			show_review_status($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=comment>';
			display_comment($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=make_comment>';
			make_comment($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=approve>';
			approve($link,$proposal_id,$who_string);
		echo '</div>';

	echo '</div>';//for tab-content
	
}


function view_entire_application_for_applicant($link,$proposal_id)
{
	echo '<ul class="nav nav-pills">
		<li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#application">Application</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#edit">Edit</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#upload">Upload</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#review_status">Review Status</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#comment">Comments</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#make_comment">Make Comment</a></li>
	</ul>';
	
	echo '<div class="tab-content">';	

		echo '<div class="jumbotron tab-pane container active" id=application>';
			list_single_application_with_all_fields($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=edit>';
		   
			edit_application($link,$proposal_id,'yes');
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=upload>';
			upload_attachment($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=review_status>';
			show_review_status($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=comment>';
			display_comment($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=make_comment>';
			make_comment($link,$proposal_id);
		echo '</div>';
	echo '</div>';//for tab-content
	
}

function set_application_status($link,$id,$status)
{
	$sql='update proposal set status=\''.$status.'\' where id=\''.$id.'\'';
	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
	return $result;
}


function get_application_status($link,$id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where id=\''.$id.'\'');
	$ar=get_single_row($result);
	return $ar['status'];
}

function get_attachment_forwarding_status($link,$id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from attachment where id=\''.$id.'\'');
	$ar=get_single_row($result);
	return $ar['forwarded'];
}

function get_attachment_applicant_id($link,$id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from attachment where id=\''.$id.'\'');
	$ar=get_single_row($result);
	return get_applicant_id($link,$ar['proposal_id']);	
}

function forward_attachment($link,$id)
{
	$result=run_query($link,$GLOBALS['database'],'update attachment set forwarded=0 where id=\''.$id.'\'');
	return $result;
}

function get_applicant_id($link,$proposal_id)
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where id=\''.$proposal_id.'\'');
	$ar=get_single_row($result);
	return $ar['applicant_id'];
}

function get_selected_srcm_reviewer($link,$proposal_id)
{
	$applicant_id=get_applicant_id($link,$proposal_id);
	
	$sql='select * from user,decision 
			where 
				proposal_id=\''.$proposal_id.'\' and 
				user.id=decision.reviewer_id and
				user.id!=\''.$applicant_id.'\'';
				
	$result_selected=run_query($link,$GLOBALS['database'],$sql);
	
	$ret=array();
	while($ar=get_single_row($result_selected))
	{
		$ret[]=$ar['reviewer_id'];
	}
	//my_print_r($ret);
	return $ret;
}
function get_only_srcm_reviewer($link,$proposal_id)
{
	$applicant_id=get_applicant_id($link,$proposal_id);
	
	$sql='select * from user,decision 
			where 
				proposal_id=\''.$proposal_id.'\' and 
				user.id=decision.reviewer_id and
				user.id!=\''.$applicant_id.'\' and
				(type REGEXP \'srcm$\' or type REGEXP \'srcm[^s]\')';
	//echo $sql;			
	$result_selected=run_query($link,$GLOBALS['database'],$sql);
	
	$ret=array();
	while($ar=get_single_row($result_selected))
	{
		$ret[]=$ar['reviewer_id'];
	}
	//my_print_r($ret);
	return $ret;
}

function count_selected_srcm_reviewer($link,$proposal_id)
{
	$applicant_id=get_applicant_id($link,$proposal_id);
	
	$sql='select count(id) as total_selected from user,decision 
			where 
				proposal_id=\''.$proposal_id.'\' and 
				user.id=decision.reviewer_id and
				user.id!=\''.$applicant_id.'\' and 
				(type REGEXP \'srcm$\' or type REGEXP \'srcm[^s]\')';
				
	//applicant is not counted
				
	$result_selected=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result_selected);
	return $ar['total_selected'];
}

function list_srcm_reviewer($link,$proposal_id)
{
	$applicant_id=get_applicant_id($link,$proposal_id);
	//								(type=\'srcm\' or type=\'srcms\')

	$sql_eligible_reviewer='select * from user where 
								type REGEXP \'srcm$\' or type REGEXP \'srcm[^s]\'
								and
								id!=\''.$applicant_id.'\' order by name';

	$result=run_query($link,$GLOBALS['database'],$sql_eligible_reviewer);

	$selected_reviewer=get_selected_srcm_reviewer($link,$proposal_id);

	echo '<form method=post>
			<input type=hidden name=proposal_id value=\''.$proposal_id.'\'>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';

	echo '<table class="table table-striped table-success">
			<tr><th>reviewer</th><th>Reviewer id/Name/Department</th><th>Type</th></tr>';

	while($ar=get_single_row($result))
	{
		$user_info=get_user_info($link,$ar['id']);
		if(in_array($ar['id'],$selected_reviewer)){$checked='checked';}else{$checked='';}

		echo '<tr>
				<td>
						<input class=sr id=ch_'.$ar['id'].' name=ch_'.$ar['id'].' type=checkbox '.$checked.'>
				</td>
				<td><label for=ch_'.$ar['id'].'><span class="text-primary">'.$ar['id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></label></td>
				<td>'.$ar['type'].'</td>
		</tr>';
	}
	echo '<tr><td colspan="3"><button name=action onclick="return confirm(\'Do you really want to save application?\');"  value=save_srcm_reviewer class="btn btn-block btn-success">Save</button></td></tr>';
	echo '</table>';	
	echo '</form>';
}

function show_review_status($link,$proposal_id)
{
	$user=get_user_info($link,$_SESSION['login']);
	
	$applicant_id=get_applicant_id($link,$proposal_id);
	$sql='select * from decision where 
				proposal_id=\''.$proposal_id.'\'
					and
				reviewer_id!=\''.$applicant_id.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);


	echo '<table class="table table-striped table-success">
			<tr><th>Reviewer id/Name/Department</th><th>Type</th><th>Decision</th></tr>';
			
	while($ar=get_single_row($result))
	{
		//if($ar['approval']==0){$ap='approval pending';}else{$ap='approved';}
		if($ar['approval']==0)
		    {
			   $sql='SELECT count(*) FROM `comment` WHERE
			    proposal_id=\''.$proposal_id.'\' 
			    and 
			    reviewer_id=\''.$ar['reviewer_id'].'\'';
			    $result1=run_query($link,$GLOBALS['database'],$sql);
			    
			   while($ar1=get_single_row($result1))
	          { 
				// print_r($ar1);
			   foreach($ar1 as $key=> $value)
			     { 
			       if($value==0)
		           {
			          $ap=' Not Reviewed';
			        }
			       else
			       {
				     $ap='Review Started';
			       } 
			     }
		      }
		    }
		  else{			  
			    $ap='Forwarded';
			    //$ap='approved';
			   }   
		
		//echo $ar['reviewer_id'];
		$user_info=get_user_info($link,$ar['reviewer_id']);
		echo '<tr>
				<td><span class="text-primary">'.$ar['reviewer_id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></td>
				<td>'.$user_info['type'].'</td>
				<td>'.$ap.'</td>';
				//<td>';
				//if($user['type']=='srcms' || $user['type']=='ecms')
				//{
				//echo '	<form method=post>
						//<button type=submit class="btn btn-warning" name=action value=reverse_approval>Reverse<br>approval</button>

						//<input type=hidden name=proposal_id value=\''.$proposal_id.'\'>
						//<input type=hidden name=reviewer_id value=\''.$ar['reviewer_id'].'\'>
						//<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
						//<textarea name=comment placeholder="Remark for reversal"></textarea>
					//</form>				
				//</td>';
				//}
				
			echo '</tr>';
	}
	echo '</table>';	
}


function reverse_approval($link,$proposal_id,$reviewer_id,$comment)
{
	print_r($_POST);
	$sql='update decision set approval=0
	where 
		proposal_id=\''.$proposal_id.'\' and reviewer_id=\''.$reviewer_id.'\'';
	echo $sql;
	save_comment($link,$reviewer_id,$proposal_id,$comment,'','');
}


function save_srcm_reviewer($link,$post)
{
	//$result=run_query($link,$GLOBALS['database'],'select * from user where type=\'srcm\' || type=\'srcms\'');
	$result=run_query($link,$GLOBALS['database'],'select * from user where type REGEXP \'srcm$\' or type REGEXP \'srcm[^s]\'');

	$selected_reviewer=get_selected_srcm_reviewer($link,$post['proposal_id']);
		
	$applicant_id=get_applicant_id($link,$post['proposal_id']);
	while($ar=get_single_row($result))
	{
		if(in_array($ar['id'],$selected_reviewer))
		{
			if(!isset($post['ch_'.$ar['id']]))
			{
				$sql_del='delete from decision where 
						proposal_id=\''.$post['proposal_id'].'\' and 
						reviewer_id=\''.$ar['id'].'\' and
						reviewer_id!=\''.$applicant_id.'\'
						';
						
				if(!run_query($link,$GLOBALS['database'],$sql_del))
				{
					//echo $sql_del.'<br>';
					echo '<p><span class="text-danger">reviewers who already commented on proposal can not be deleted!!(No error)</span><br></p>';
				}
				else
				{
					echo '<p><span class="text-danger">...deleting user_id='.$ar['id'].' as reviewer for proposal_id='.$post['proposal_id'].'</span><br>';
				}
			}
			else
			{
				//do nothing
			}
		}
		else
		{
			if(isset($post['ch_'.$ar['id']]))
			{
				$sql='insert into decision values(
					\''.$post['proposal_id'].'\',
					\''.$ar['id'].'\',
					\'0\')';
				//echo $sql.'<br>';
				echo '<p><span class="text-danger">...adding user_id='.$ar['id'].' as reviewer for proposal_id='.$post['proposal_id'].'</span>';
				if(!run_query($link,$GLOBALS['database'],$sql))
				{
					echo '<p><span class="text-danger">'.$sql.'</span>';					
				}
				//save_comment_for_reviewer_assignment($link,$ar['id'],$post['proposal_id']);
				//save_comment($link,$ar['id'],$post['proposal_id'],'You are assigned as reviewer for this proposal');
			}
			else
			{
				//do nothing
			}			
		}
	}
	
	
	//////////Prepare comment/////////////
	$new_reviewer_list=get_only_srcm_reviewer($link,$post['proposal_id']);
$comment='AUTO-GENERATED COMMENT
current reviewers are as follows:
(Note:Donot call directly on mobile of srcms/ecms)
';
	foreach($new_reviewer_list as $value)
	{
		$user=get_user_info($link,$value);
			$comment=$comment.$user['name'].'('.$user['type'].')
';
	}
	save_comment($link,$applicant_id,$post['proposal_id'],$comment);
	
	
	///////////////////////////////////////

/*

'001.applied',
'010.srcm_assigned',
'020.srcm_approved',
'030.sent_to_ecms',
'040.ecm_assigned',
'060.ecm_approved',
'070.ecms_approved'

*/	
	$tot=count_selected_srcm_reviewer($link,$_POST['proposal_id']);
	echo '<p><span class="text-danger">Total reviewers selected='.$tot.'</span>';
	if($tot<$GLOBALS['required_srcm_reviewer'])
	{
		echo '<p><span class="text-danger">Total reviewers selected are less then required ('.$GLOBALS['required_srcm_reviewer'].')</span>';
		set_application_status($link,$_POST['proposal_id'],'001.applied');
	}
	else
	{
		set_application_status($link,$_POST['proposal_id'],'010.srcm_assigned');		
		echo '<p><span class="text-danger">Total reviewers selected are as required ('.$GLOBALS['required_srcm_reviewer'].')</span>';
		echo '<p><span class="text-danger">Done setting application status to  ('.get_application_status($link,$_POST['proposal_id']).')</span>';
	}


}


function list_researcher_application($link)
{        echo'<form method=post>

		            <input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
		             <input type=hidden name=table value="proposal">
			        <input type=hidden name=field value="application" >
			        <input type=hidden name=applicantid value=\''.$_SESSION['login'].'\'>
			        <button class="btn btn-primary"  onclick="return confirm(\'Do you really want to new application?\');" 
						type=submit
						name=action
						value=new_application>New Appliction</button>
				 </form>';
	
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where applicant_id=\''.$_SESSION['login'].'\'');
		echo '<table class="table table-striped">
			<tr><th colspan=10 class="text-center">Click respective <span class="badge badge-secondary">Proposal ID</span> to Edit and upload documents</th></tr>
			<tr>
			<th>Proposal ID</th>
			<th>Title</th>
			<th>DateTime</th>
			<th>Current Status</th>
			<th>Pending Forward?</th>
			</tr>';
	while($ar=get_single_row($result))
	{
		//if($ar['forwarded']==1){$forward_need='Yes';$class='class=bg-danger';}else{$forward_need='No';$class='';}
		$status=get_proposal_upload_comment_status($link,$ar['id']);
		//if($status['proposal_status']>0 || $status['cnf']>0 || $status['anf']>0 ){$forward_need='Yes';$class='class=bg-danger';}else{$forward_need='No';$class='';}
		if($status['proposal_status']>0 || $status['anf']>0 ){$forward_need='Yes';$class='class=bg-danger';}else{$forward_need='No';$class='';}
		
		$user_info=get_user_info($link,$ar['applicant_id']);
		echo '<tr>
			   	<td>
					<form method=post>
						<button  class="btn btn-sm btn-block btn-info" value=manage_application name=action>'.$ar['id'].'</button>
						<input type=hidden name=proposal_id value=\''.$ar['id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</form>
				</td>
				<td>'.$ar['proposal_name'].'</td>
				<td>'.$ar['date_time'].'</td>
				<td>'.$ar['status'].'</td>
				<td '.$class.'>'.$forward_need.'</td>
		</tr>';
	}
	echo '</table>';
	
}

function get_proposal_upload_comment_status($link,$proposal_id)
{
	$ret=array();
	
	$proposal_status=0;
	$proposal_info=get_proposal_info($link,$proposal_id);
	if($proposal_info['forwarded']==1){$ret['proposal_status']=1;}else{$ret['proposal_status']=0;}
	//$ret['cnf']=count_not_forwarded_comments($link,$proposal_id);
	$ret['anf']=count_not_forwarded_uploads($link,$proposal_id);
	
	//print_r($ret);
	return $ret;
}


function count_not_forwarded_comments($link,$proposal_id)
{
	$result=run_query($link,$GLOBALS['database'],'select count(id) tnf from comment where proposal_id=\''.$proposal_id.'\' and forwarded=1');
	$ar=get_single_row($result);
	//print_r($ar);
	return $ar['tnf'];
}


function count_not_forwarded_uploads($link,$proposal_id)
{
		$sql='select count(id) as anf from attachment 
				where 
					proposal_id=\''.$proposal_id.'\' 
					and
					forwarded=1'; 
					
		$result=run_query($link,$GLOBALS['database'],$sql);
		$ar=get_single_row($result);
		//print_r($ar);
		return $ar['anf'];
}

function get_application_data($link)
{
	echo'<form method=post enctype="multipart/form-data"  class="jumbotron">
					<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
				   <input type=hidden name=applicant_id value=\''.$_SESSION['login'].'\'>
	      <table class="table table-striped" width="50%"> 
	      <tr><th class="text-success rounded-top">New Application</th></tr>              
			 <tr>
				   <th>Proposal Title</th>
				   <td><textarea name=proposal_name class="form-control"  placeholder="Enter Proposal Title"></textarea></td>
				   <td>Must be same as what is uploaded in protocol</td>
			</tr>

			 <tr>
				   <th>Proposal Type</th>
				   <td>';
				   mk_select_from_array('type',$GLOBALS['proposal_type'],'','');
				   echo '</td>';
				   echo '<td>Select appropriate type of proposal</td>';
			echo '</tr>

			 <tr>
				   <th>Interventional Research</th>
				   <td>';
				   mk_select_from_array('interventional_research',['no','yes'],'','');
				   echo '</td>';
				   echo '<td>Is it Interventional Research?</td>';
			echo '</tr>


			 <tr>
				   <th>Research on vulnerable subjects</th>
				   <td>';
				   mk_select_from_array('research_on_vulnerable_subjects',$GLOBALS['vulnerable_array'],'','');
				   echo '</td>';
				   echo '<td>subjects vulnerable?<a href="https://main.icmr.nic.in/sites/default/files/guidelines/ICMR_Ethical_Guidelines_2017.pdf">ICMR Guideline(See Chapter.6)</a></td>';
			echo '</tr>

			 <tr>
				   <th>Description of study subjects</th>
				   <td><textarea name=description_of_study_subjects class="form-control"  placeholder="Enter Detailed description of study subjects"></textarea></td>
				   <td>Must be same as what is uploaded in protocol</td>
			</tr>';
			
			echo '</tr>
			
			 <tr>
				   <th>Additional_remarks</th>
				   <td><textarea name=additional_remarks class="form-control"  placeholder="Write relavent remarks"></textarea></td>
				   <td>If relavent</td>
			</tr>';
			
			echo '</tr>			 
			 
			 <tr>			 
				   <th>Researcher</th>
				   <td><input name=guide class="form-control"  placeholder="Name of Researcher"></td>
				   <td>If applying on behalf of UG/PG student, write name of UG/PG Student</td>
			</tr>	
			<tr>
			       <th>Researcher Email id</th>
			       <td><input name=emailid class="form-control"  placeholder="Enter Email Id"></td>
			       <td>If applying on behalf of UG/PG student, write Researcher Email id of UG/PG Student</td>
			</tr>	
			<tr>
			       <th>Researcher Mobile No.</th>
			       <td><input name=mobileno class="form-control"  placeholder="Enter Mobile No."></td>
			       <td>If applying on behalf of UG/PG student, write Researcher Mobile Number of UG/PG Student</td>
			</tr>	
			<tr>
				   <th>Year of Admission</th>
				   <td><input name=year pattern="^20[0-9][0-9]$"class="form-control"  placeholder="Enter Year As 20YY"></td>
				   <td>If applying on behalf of UG/PG student, write Year of Admission of UG/PG Student</td>
				  
			</tr>	
				<tr>
				   <th>Department</th>
				   <td>';
				   mk_select_from_array('dept_type',$GLOBALS['Department_Type'],'','');
				   echo'</td>
				   <td>If applying on behalf of UG/PG student, select Department of UG/PG Student</td>
				  
			</tr>	
			<tr>
					<td></td>
					<td>
						<button onclick="return confirm(\'Do you really want to save application?\');"  class="btn btn-primary"  
							type=submit
							name=action
							value=insert_application>Save</button>
					</td>
			</tr>
			<tr><th class="text-danger" colspan=10>Note: Documents can be uploaded after saving the application</th></tr>
			</table>
		</form>';
}

function file_to_str($link,$file)
{
	if($file['size']>0)
	{
		$fd=fopen($file['tmp_name'],'r');
		$size=$file['size'];
		$str=fread($fd,$size);
		return my_safe_string($link,$str);
	}
	else
	{
		return false;
	}
}

function insert_application($link,$aid,$pname,$type,
			$interventional_research,$research_on_vulnerable_subjects,$description_of_study_subjects,$additional_remarks,
			$guide,$emailid,$mobileno,$year,$dept_type)
{

	$sql='INSERT INTO proposal( 
			applicant_id, 
			proposal_name,
			type,
			
			interventional_research,
			research_on_vulnerable_subjects,
			description_of_study_subjects,
			additional_remarks,
			
			date_time,guide,
			researcher_email_id,
			researcher_mobile_no,
			year,
			Department, 
			status)
		
	 	VALUES (
			\''.$aid.'\',
			\''.$pname.'\',
			\''.$type.'\',
			\''.$interventional_research.'\',
			\''.$research_on_vulnerable_subjects.'\',
			\''.$description_of_study_subjects.'\',
			\''.$additional_remarks.'\',
			now(),
			\''.$guide.'\',
			\''.$emailid.'\',
			\''.$mobileno.'\',
			\''.$year.'\',
			\''.$dept_type.'\',
			\'001.applied\')';
	 	
	 	
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo $sql;
    if($result==false)
	{
		echo '<h3 style="color:red;">No record inserted</h3>';
	}
	else
	{
		echo '<h3 style="color:green;">'.$result.' record inserted</h3>';
		$new_proposal_id=last_autoincrement_insert($link);
		
		insert_reviewer(
					$link,
					$new_proposal_id,
					get_applicant_id($link,last_autoincrement_insert($link))
				);

	}
	$comment='AUTO-GENERATED COMMENT
Created new proposal with unique proposal_id='.$new_proposal_id.'
Login frequently and view emails to be up-to-date about this application';

	save_comment($link,$aid,$new_proposal_id,$comment);
	$sql1="select * from user where type='srcms' ";
	$result=run_query($link,$GLOBALS['database'],$sql1);
	$srcms_comment='<h2><b>HREC, GMC Surat.</b></h2>
	<h3><b>Proposal ID:- <u style=\'color: green;font-family: arial, sans-serif;font-weight: bold;\'>'.$new_proposal_id.'</u><br>
	Proposal Name:- <u style=\'color: #800080;font-family: arial, sans-serif;font-weight: bold;\'>'.$pname.'</u></b></h3>
	<h3 style=\'font-family: arial, sans-serif;font-size: 20px;font-weight: bold;\'>
	<h4>AUTO-GENERATED COMMENT<br>
New proposal with unique Proposal Id:- <u style=\'color: green;font-family: arial, sans-serif;font-weight: bold;\'>'.$new_proposal_id.' </u>  and Proposal Name:- <u style=\'color: #800080;font-family: arial, sans-serif;font-weight: bold;\'>'.$pname.'</u> has arrived.<br>
assign reviewers as early as possible.</h4>
     <a href="http://gmcsurat.edu.in:12347/hrec">REC Login from Internet</a><br>
	 <a href="http://11.207.1.2/hrec/">REC Login from College Network</a></h4>';
//echo $srcms_comment;
	while($ar=get_single_row($result))
	{
	  //echo $ar['id'];	
	  //echo $ar['email'];
	 save_email($ar['email'],$srcms_comment);
	}
	
	return TRUE;
}

function save_email_for_send_to_ecms($link,$proposal_id)
{
	$pinfo=get_proposal_info($link,$proposal_id);
	$sql1="select * from user where type='ecms' ";
	$result=run_query($link,$GLOBALS['database'],$sql1);
	$ecms_comment='<h2><b>HREC, GMC Surat.</b></h2>
	<h3><b>Proposal ID:- <u style=\'color: green;font-family: arial, sans-serif;font-weight: bold;\'>'.$proposal_id.'</u><br>
	Proposal Name:- <u style=\'color: #800080;font-family: arial, sans-serif;font-weight: bold;\'>'.$pinfo['proposal_name'].'</u></b></h3>
	<h3 style=\'font-family: arial, sans-serif;font-size: 20px;font-weight: bold;\'>
	<h4>AUTO-GENERATED COMMENT<br>
Proposal with unique Proposal Id:- <u style=\'color: green;font-family: arial, sans-serif;font-weight: bold;\'>'.$proposal_id.' </u>  and Proposal Name:- <u style=\'color: #800080;font-family: arial, sans-serif;font-weight: bold;\'>'.$pinfo['proposal_name'].'</u> has arrived.<br>
assign EC reviewers as early as possible.</h4>
     <a href="http://gmcsurat.edu.in:12347/hrec">REC Login from Internet</a><br>
	 <a href="http://11.207.1.2/hrec/">REC Login from College Network</a></h4>';
//echo $srcms_comment;
	while($ar=get_single_row($result))
	{
	  //echo $ar['id'];	
	  //echo $ar['email'];
	 save_email($ar['email'],$ecms_comment);
	}
}

function insert_reviewer($link,$proposal_id,$reviewer_id)
{
	$sql='insert into decision values(
	\''.$proposal_id.'\',
	\''.$reviewer_id.'\',
	\'0\')';
	//echo $sql.'<br>';
	if(!run_query($link,$GLOBALS['database'],$sql))
	{
		echo '<p><span class="text-danger">'.$sql.'</span>';					
	}
	else
	{
		echo '<p><span class="text-danger">...adding user_id='.$reviewer_id.' as reviewer for proposal_id='.$proposal_id.'</span>';
	}
}

function edit_application($link,$proposal_id,$readonly='')
{	
		$result=run_query($link,$GLOBALS['database'],'select * from proposal where id=\''.$proposal_id.'\' ');

		while($ar=get_single_row($result))
	   {
		   		
         if($ar['status']=="070.ecms_approved")
          { 
		   echo'<h2>No more changes this application approved</h2>';

	       }
	    else
	    {
		
		$user_info=get_user_info($link,$ar['applicant_id']);
	    echo'<form method=post enctype="multipart/form-data">
				<table class="table table-striped" width="50%">
    
					<tr>	 
					   <th>Proposal Name</th>
					   <td><textarea class="form-control" type=text name=proposal_name >'.$ar['proposal_name'].'</textarea></td>
					   <input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					   <input type=hidden name=applicantid value=\''.$_SESSION['login'].'\'>
					   <input type=hidden name=id value='.$ar['id'].'>
					</tr>

					 <tr>
						   <th>Proposal Type</th>
						   <td>';
						   mk_select_from_array('type',$GLOBALS['proposal_type'],'',$ar['type']);
						  
						   echo '</td>';
						   echo '<td>Select appropriate type of proposal</td>';



					echo '<tr>
				   <th>Interventional Research</th>
				   <td>';
				   mk_select_from_array('interventional_research',['no','yes'],'',$ar['interventional_research']);
				   echo '</td>';
				   echo '<td>Is it Interventional Research?</td>';
					echo '</tr>';

					echo '<tr>
				   <th>Research on vulnerable subjects</th>
				   <td>';
				   mk_select_from_array('research_on_vulnerable_subjects',$GLOBALS['vulnerable_array'],'',$ar['research_on_vulnerable_subjects']);
				   echo '</td>';
				   echo '<td>subjects vulnerable?<a href="https://main.icmr.nic.in/sites/default/files/guidelines/ICMR_Ethical_Guidelines_2017.pdf">ICMR Guideline(See Chapter.6)</a></td>';
   echo '<td></td>';
					echo '</tr>';

					echo '<tr>
					<th>Description of study subjects</th>
					<td><textarea name=description_of_study_subjects class="form-control"  placeholder="Enter Detailed description of study subjects">'.$ar['description_of_study_subjects'].'</textarea></td>
					<td>Must be same as what is uploaded in protocol</td>
				   </tr>';
			
			
						   
					echo '

					
					<tr>
						<th>Additional_remarks</th>
						<td><textarea name=additional_remarks class="form-control"  placeholder="Write relavent remarks">'.$ar['additional_remarks'].'</textarea></td>
						<td>If relavent</td>
					</tr>

					 <tr>
						   <th>Researcher</th>
						   <td><input name=guide class="form-control"  placeholder="Name of Researcher" value=\''.$ar['guide'].'\'></td>
						   <td>If applying on behalf of UG/PG student, write name of UG/PG Student</td>
					</tr>
					
					 <tr>
						   <th>Researcher Email id</th>
						   <td><input name=emailid class="form-control"  placeholder="Email Id of Researcher" value=\''.$ar['researcher_email_id'].'\'></td>
						   <td>If applying on behalf of UG/PG student, write Researcher Email id of UG/PG Student</td>
					</tr>
					 <tr>
						   <th>Researcher Mobile no.</th>
						   <td><input name=mobileno class="form-control"  placeholder="Mobile No. of Researcher" value=\''.$ar['researcher_mobile_no'].'\'></td>
						  <td>If applying on behalf of UG/PG student, write Researcher Mobile Number of UG/PG Student</td>
					</tr>
			    <tr>
				   <th>Year of Admission</th>
				   <td><input name=year class="form-control"  placeholder="Enter Year" value=\''.$ar['year'].'\'></td>
				  <td>If applying on behalf of UG/PG student, write Year of Admission of UG/PG Student</td>
		   	</tr>	
				<tr>
				   <th>Department</th>
				   <td>';
				   mk_select_from_array('dept_type',$GLOBALS['Department_Type'],'',$ar['Department']);
				   echo'</td>
				  <td>If applying on behalf of UG/PG student, Select Department of UG/PG Student</td>
			</tr>	
					<tr>
						<td></td>
						<td>';
					//if($readonly=='')
						//{
						echo'	<button onclick="return confirm(\'Do you really want to Update application?\');" class="btn btn-primary"  
								type=submit
								name=action
								value=update_application>Update</button>';
						//}
						//else{
						//	status($link,$proposal_id);
						//}
								
						echo '</td>
					</tr>
				</table>
		</form>';
	}
	}
	
}

function save_application_field($link,$aid,$field,$value)
{
	
	$sql='UPDATE proposal SET 
		`'.$field.'` =\''.$value.'\'
		WHERE id=\''.$aid.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
    //return TRUE;
	if($result==false)
	{
		//echo '<h5 style="color:red;">'.$field.' not updated</h5>';
	}
	else
	{
		//echo '<h5 style="color:green;">'.$field.' updated</h5>';
	}
}


function upload_attachment($link,$proposal_id)
{
	$status=get_application_status($link,$proposal_id);
    if($status=="07.ecms_approved")
    { 
		echo'<h2>No more changes this application approved</h2>';
		//return true;
		//exit();
	}
	else
	{
	echo'<form method=post enctype="multipart/form-data">
					   <input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					   <input type=hidden name=applicantid value=\''.$_SESSION['login'].'\'>
					   <input type=hidden name=proposal_id value=\''.$proposal_id.'\'>
				<table class="table table-striped">
					<tr>	 
					   <th>File to upload</th>
					   <td><input type=file name=attachment></td>
					   <td><20 MB file size is accepted</td>
					</tr>

					 <tr>
						   <th>Type</th>
						   <td>';
						   mk_select_from_array('type',$GLOBALS['attachment_type']);
						   echo '</td>';
						   echo '<td>Select appropriate type of document'.help('upload_type_help').'</td>';

					echo '<tr>
							<td colspan=3>';
							
					echo'		<button onclick="return confirm(\'Do you really want to Upload application?\');"  class="btn btn-primary"  
								type=submit
								name=action
								value=upload_attachment>Upload</button>'.help('upload_button_help').'
							</td>
						</tr>';
					echo '<tr><td class="text-success" colspan=3>See [Application] tab to view uploaded documents</td></tr>';
				echo '</table>
	</form>';	
	}
}

function help($topic)
{
	$str= '<button type=button data-toggle="modal" data-target=#'.$topic.' class="btn btn-light m-1 p-0" ><img src="img/help.png" width="30"></button>';
	$str=$str.	'<div class="modal" id='.$topic.'>
					<div class="modal-dialog modal-dialog-scrollable" >
						<div class="modal-content">
							<div class="modal-body text-info">
								'.$GLOBALS[$topic].'
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>';				
	return $str;
}

function popup($topic,$text)
{
	//$str='hi';
	$str= '<button type="button" data-toggle="collapse" data-target="#'.$topic.'" class="btn btn-light m-1 p-0" ><img src="img/eye.png" width="30"></button>';
	$str=$str.'<div class="collapse bg-info" style="position:absolute" id="'.$topic.'">'.$text.'</div>';
	return $str;
}
function save_attachement($link,$proposal_id,$type,$blob,$attachment_name)
{
	
	if(strlen($type)==0)
	{
		echo '<h5 style="color:red;">nothing uploaded. upload type is required.  Retry with selection of upload type</h5>';
		return false;
	}
       	if(strlen($blob)==0)
	{
		echo '<h5 style="color:red;">nothing uploaded. file size is 0 .  Retry with proper file.</h5>';
		return false;
	}
	
	$sql='insert into attachment ( proposal_id, 	type 	,date_time 	,attachment  ,attachment_name)
			values
				(
					\''.$proposal_id.'\',
					\''.$type.'\',
					now(),
					\''.$blob.'\',
					\''.$attachment_name.'\'
				)';
		
	$result=run_query($link,$GLOBALS['database'],$sql);
	if($result==false)
	{
		echo '<h5 style="color:red;"> nothing updated.too big??</h5>';
		return false;
	}
	else
	{
		echo '<h5 style="color:green;">uploaded. see application tab</h5>';
		$applicant_id=get_applicant_id($link,$proposal_id);
		$comment='AUTO-GENERATED COMMENT
uploaded new version of '.$type.'
Download and review';
		save_comment($link,$applicant_id,$proposal_id,$comment);
		return true;
	}
}

function mk_select_from_array($name, $select_array,$disabled='',$default='')
{	
	echo '<select  '.$disabled.' name=\''.$name.'\'>';
	foreach($select_array as $key=>$value)
	{
				//echo $default.'?'.$value;
		if($value==$default)
		{
			echo '<option  selected > '.$value.' </option>';
		}
		else
		{
			echo '<option > '.$value.' </option>';
		}
	}
	echo '</select>';	
	return TRUE;
}


function echo_applicant_info_popup($link,$applicant_id,$proposal_id)
{
	$user_info=get_user_info($link,$applicant_id);
	$user_info1=get_user_info1($link,$proposal_id);
	//echo $proposal_id;
	echo '<div>
		<span class="text-primary">'.$applicant_id.'</span><br>
		<span class="text-danger">'.$user_info['name'].'</span><br>
		<span class="text-primary">'.$user_info['department'].'</span><br>
		<span class="text-danger">'.$user_info['type'].'</span><br>
		<span class="text-primary">'.$user_info['subtype'].'</span><br>		
		<span class="text-primary">'.$user_info['Institute'].'</span></br>		
		<span class="text-danger">'.$user_info['year_of_admission'].'</span><br>
		<span class="text-primary">'.$user_info['email'].'</span><br>
		<span class="text-danger">'.$user_info['mobile'].'</span><br>
		<span class="text-primary">'.$user_info1['guide'].'</span>
			</div>';
}

///////For EC
function list_ecm_reviewer($link,$proposal_id,$ecm_string)
{
	//echo 'xxxxx';
	$applicant_id=get_applicant_id($link,$proposal_id);
	
	$sql_eligible_reviewer='select * from user where 
								(type REGEXP \''.$ecm_string.'$\' or type REGEXP \''.$ecm_string.'[^s]\')
								and
								id!=\''.$applicant_id.'\'';
	//echo 	$sql_eligible_reviewer;
	$result=run_query($link,$GLOBALS['database'],$sql_eligible_reviewer);

	$selected_reviewer=get_selected_ecm_reviewer($link,$proposal_id);

	echo '<form method=post>
			<input type=hidden name=proposal_id value=\''.$proposal_id.'\'>
			<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';

	echo '<table class="table table-striped table-success">
			<tr><th>reviwer</th><th>Reviewer id/Name/Department</th><th>Type</th></tr>';
			
	while($ar=get_single_row($result))
	{
		$user_info=get_user_info($link,$ar['id']);
		if(in_array($ar['id'],$selected_reviewer)){$checked='checked';}else{$checked='';}
		
		echo '<tr>
				<td>
						<input class=sr id=ch_'.$ar['id'].' name=ch_'.$ar['id'].' type=checkbox '.$checked.'>
				</td>
				<td><label for=ch_'.$ar['id'].'><span class="text-primary">'.$ar['id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></label></td>
				<td>'.$ar['type'].'</td>
		</tr>';
	}
	echo '<tr><td colspan="3"><button onclick="return confirm(\'Do you really want to save application?\');"  name=action value=\'save_reviewer_'.$ecm_string.'\' class="btn btn-block btn-success">Save</button></td></tr>';
	echo '</table>';	
	echo '</form>';
}

function get_selected_ecm_reviewer($link,$proposal_id)
{
	$applicant_id=get_applicant_id($link,$proposal_id);

	$sql='select * from user,decision 
			where 
				proposal_id=\''.$proposal_id.'\' and 
				user.id=decision.reviewer_id and
				user.id!=\''.$applicant_id.'\'';
				
	$result_selected=run_query($link,$GLOBALS['database'],$sql);
	
	$ret=array();
	while($ar=get_single_row($result_selected))
	{
		$ret[]=$ar['reviewer_id'];
	}
	//my_print_r($ret);
	return $ret;
}
function get_only_ecm_reviewer($link,$proposal_id)
{
	$applicant_id=get_applicant_id($link,$proposal_id);

	$sql='select * from user,decision 
			where 
				proposal_id=\''.$proposal_id.'\' and 
				user.id=decision.reviewer_id and
				user.id!=\''.$applicant_id.'\' and
				(type REGEXP \'ecm$\' or type REGEXP \'ecm[^s]\')';
				
	$result_selected=run_query($link,$GLOBALS['database'],$sql);
	
	$ret=array();
	while($ar=get_single_row($result_selected))
	{
		$ret[]=$ar['reviewer_id'];
	}
	//my_print_r($ret);
	return $ret;
}

function save_ecm_reviewer($link,$post,$ecm_status_type)
{
	$result=run_query($link,$GLOBALS['database'],'select * from user');

	$selected_reviewer=get_selected_ecm_reviewer($link,$post['proposal_id']);
	$applicant_id=get_applicant_id($link,$post['proposal_id']);
	
	while($ar=get_single_row($result))
	{
		if(in_array($ar['id'],$selected_reviewer))
		{
			if(!isset($post['ch_'.$ar['id']]))
			{
				$sql_del='delete from decision where 
						proposal_id=\''.$post['proposal_id'].'\' and 
						reviewer_id=\''.$ar['id'].'\' and
						reviewer_id!=\''.$applicant_id.'\'
						';
						
				if(!run_query($link,$GLOBALS['database'],$sql_del))
				{
					//echo $sql_del.'<br>';
					echo '<p><span class="text-danger">reviewers who already commented on proposal can not be deleted!!(Not Error)</span><br></p>';
				}
				else
				{
					echo '<p><span class="text-danger">...deleting user_id='.$ar['id'].' as reviewer for proposal_id='.$post['proposal_id'].'</span><br>';
				}
			}
			else
			{
				//do nothing
			}
		}
		else
		{
			if(isset($post['ch_'.$ar['id']]))
			{
				$sql='insert into decision values(
					\''.$post['proposal_id'].'\',
					\''.$ar['id'].'\',
					\'0\')';
				//echo $sql.'<br>';
				echo '<p><span class="text-danger">...adding user_id='.$ar['id'].' as reviewer for proposal_id='.$post['proposal_id'].'</span>';
				if(!run_query($link,$GLOBALS['database'],$sql))
				{
					echo '<p><span class="text-danger">'.$sql.'</span>';					
				}
			}
			else
			{
				//do nothing
			}			
		}
	}
 //////////Prepare comment/////////////
	$new_reviewer_list=get_only_ecm_reviewer($link,$post['proposal_id']);
$comment='AUTO-GENERATED COMMENT
current reviewers are as follows:
(Note:Donot call directly on mobile of srcms/ecms)
';
	foreach($new_reviewer_list as $value)
	{
		    $user=get_user_info($link,$value);
			$comment=$comment.$user['name'].'('.$user['type'].')
';
	}
	save_comment($link,$applicant_id,$post['proposal_id'],$comment);
	
	
	///////////////////////////////////////
 
	$tot_ecm=count_selected_ecm_reviewer($link,$_POST['proposal_id']);
	echo '<p><span class="text-danger">Total reviewers selected='.$tot_ecm.'</span>';
    if($tot_ecm<$GLOBALS['required_ecm_reviewer'])
	{
		echo '<p><span class="text-danger">Total reviewers selected are less then required ('.$GLOBALS['required_ecm_reviewer'].')</span>';
		set_application_status($link,$_POST['proposal_id'],'031.sent_to_ecms1');
	}
	else
	{
		set_application_status($link,$_POST['proposal_id'],$ecm_status_type);		
		echo '<p><span class="text-danger">Total reviewers selected are as required ('.$GLOBALS['required_ecm_reviewer'].')</span>';
		echo '<p><span class="text-danger">Done setting application status to  ('.get_application_status($link,$_POST['proposal_id']).')</span>';
	}
}


function count_selected_ecm_reviewer($link,$proposal_id)
{
	$applicant_id=get_applicant_id($link,$proposal_id);
	$sql='select count(id) as total_selected from user,decision 
			where 
				proposal_id=\''.$proposal_id.'\' and 
				user.id=decision.reviewer_id and
				user.id!=\''.$applicant_id.'\' and
				(type REGEXP \'ecm$\' or type REGEXP \'ecm[^s]\')
				';

	//applicant is not counted
				
	$result_selected=run_query($link,$GLOBALS['database'],$sql);
	$ar=get_single_row($result_selected);
	return $ar['total_selected'];
}



function view_entire_application_ecm($link,$proposal_id,$who_string)
{
	echo '<ul class="nav nav-pills">
		<li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#application">Application</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#review_status">Review Status</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#comment">Comments (ECM)</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#make_comment">Make Comment (ECM)</a></li>
		<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#approve">Forward To EC</a></li>
	</ul>';
	
	echo '<div class="tab-content">';	

		echo '<div class="jumbotron tab-pane container active" id=application>';
			list_single_application_with_all_fields($link,$proposal_id,$who_string);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=review_status>';
			show_review_status($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=comment>';
			display_comment($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=make_comment>';
			make_comment($link,$proposal_id);
		echo '</div>';
		echo '<div class="jumbotron tab-pane container" id=approve>';
			approve($link,$proposal_id,$who_string,$who_string);
		echo '</div>';

	echo '</div>';//for tab-content
	
}


function print_approval_latter($link,$status,$action='none',$message='')
{
	$result=run_query($link,$GLOBALS['database'],'select * from proposal where status=\''.$status.'\'');
	echo '<table class="table table-striped"><tr><th colspan=10>List of research application with current status of <span class=bg-danger>'.$status.'</span></th></tr>
			<tr><th>proposal id</th><th>Applicant id/Name/Department</th><th>Proposal</th><th>DateTime</th><th>Status</th></tr>';
	while($ar=get_single_row($result))
	{
		$user_info=get_user_info($link,$ar['applicant_id']);
		echo '<tr>
				<td>';
		
		if($action!='none')
		{
					echo '<form method=post>
						<button   class="btn btn-sm btn-block btn-info" formtarget=_blank name=action  value=\''.$action.'\' formaction=\''.$action.'\' >'.$ar['id'].' '.$message.'</button>
						<input type=hidden name=proposal_id value=\''.$ar['id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</form>';
		}
		else
		{
			echo $ar['id'];
		}			
		echo ' </td>
				<td><span class="text-primary">'.$ar['applicant_id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></td>
				<td>'.$ar['proposal_name'].'</td>
				<td>'.$ar['date_time'].'</td>
				<td>'.$ar['status'].'</td>
		</tr>';
	}
	echo '</table>';
}

function gate_approval_latter_pdf($link,$user_info,$ar,$action)
{	
echo'
  <table  width="100%" align="center">
	        <tr>
	          <td  width="15%" height="100%"><img src="./img/gujarat.jpg" alt="gujarat" height="100"></td>
	          <td width="70%" height="100%">
	           <table >
	            <tr height="50%" width="100%">
	               <td >
	                <img src="./img/human.png" alt="gujarat"> 
	                </td>
	            </tr>
	            <tr height="25%" width="80%">
	                <td>
	                  Office of Dean 
	                </td>
	            </tr>
	            <tr height="25%" width="80%">
	               <td >
	                    <b>Government Medical College</b>
	                </td>
	             </tr>
	             <tr height="25%" width="80%">
	                <td>
	                  Majuragate, Surat.
	                </td>
	             </tr>
	            </table>
	           </td>
	           <td  width="15%" height="100%"><img src="./img/college_logo.jpg" alt="college logo"></td>
	         </tr>
	      </table>
	      <table width="100%" ><tr><td><img src="./img/border.png" alt="border" height="5" width="700"></td></tr></table>
	      <table width="100%" height="10%">
	      <tr>
	      <td  width="60%">No.GMCS/STU/ETHICS/Approval/'.$_POST['letter_no'].'&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td><td  width="39%"  align="right">
	      Date:-  '.date("d-m-Y",strtotime($_POST['letter_date'])).'</td>
	      </tr>
	      </table>';
	      if($action=='print1')
	      {
		    echo'<br><br><table align="center"><tr><td><H3>REPORT OF ETHICS COMMITTEE</H3></td></tr></table>';
		    echo'<table border="1" ><tr width="100%"><th width="40%"><b>&nbsp; Department</b></th><td width="60%">&nbsp; '.$user_info['department'].'</td></tr>
		                        <tr><th><b>&nbsp; Candidate admitted year</b></th><td>&nbsp; '.$ar['year'].'</td></tr>
		                        <tr><th><b>&nbsp; Subject</b></th><td>&nbsp; '.$user_info['department'].'</td></tr>
		                        <tr><th><b>&nbsp; College Name & Address</b></th><td>&nbsp; '.$user_info['Institute'].'</td></tr>
		                  </table>';
		
	      }
	      else if($action=='print3')
	      {
		    echo'<br><br><table align="center"><tr><td><H3>REPORT OF ETHICS COMMITTEE</H3></td></tr></table>';
		    echo'<table border="1" ><tr width="100%"><th width="40%"><b>&nbsp; Department</b></th><td width="60%">&nbsp; '.$user_info['department'].'</td></tr>
		                        <tr><th><b>&nbsp; Candidate admitted year</b></th><td>&nbsp; '.$ar['year'].'</td></tr>
		                        <tr><th><b>&nbsp; Subject</b></th><td>&nbsp; '.$user_info['department'].'</td></tr>
		                        <tr><th><b>&nbsp; College Name & Address</b></th><td>&nbsp; '.$user_info['Institute'].'</td></tr>
		                  </table>';
		

	      }  
	    echo'<pre></pre>
	     <table>
	     <tr><td><b>To,</b></td></tr>
	      <tr><td><b>'. $ar['guide'].' </b></td></tr>
	      <tr><td><b>Department of '. $user_info['department'].',</b></td></tr>
	      <tr><td><b>'.$user_info['Institute'].'.</b></td></tr>
	      <tr><td><b> </b></td></tr>
	      <tr><td> </td></tr>
	      </table>';
	      
	    if($action=='print1')
	    {
			
			echo' <table><tr width="100%"><td align="right" width="20%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>Sub:-</b></td><td width="80%"> Research Proposal Entitled," '.$ar['proposal_name'].'. "</td></tr></table>';
			echo' <table><tr width="100%"><td align="right" width="20%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>Ref:-</b></td><td width="50%"> Online HREC Protocol No. '.$ar['id'].'</td><td width="30%">Date:- '. date("d-m-Y",strtotime($ar['date_time'])).'</td></tr></table>';
		    echo' <br><br><table><tr><td>Dear <b>'. $ar['guide'].',</b></td></tr></table>';

		    echo' <br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; The above mentioned research proposal of <b>'.$ar['guide'].'</b>, Entitled,<b> " '.$ar['proposal_name'].' " </b> was discussed in the Ethics Committee meeting held on '.date("d-m-Y",strtotime($_POST['meeting_date'])).' at our College.</td></tr></table>';
			echo'<br><br><table>
				<tr>
					<td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Ethics Committee has unanimously approved your Title & Synopsis of Dissertation. This work will be done under the guidance and supervision of your guide, <b>'.$user_info['name'].'</b></td></tr>
				</table>';
		}
	    else if($action=='print2')
	    {
			echo' <table><tr><td> &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>Sub:-</b>Approval of Research Proposal Submitted by you to HREC.</td></tr></table>';
			 echo'<br><br> <table><tr><td>Dear <b>'. $ar['guide'].',</b></td></tr></table>';
			echo' <br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;The HREC, in its meeting on <b>'. date("d-m-Y",strtotime($ar['date_time'])).' </b> had discussed the research Proposal Submitted by you.
			 The committee has decided to give approval to your Proposal.( Protocol No.'. $ar['id'].'    )  </td></tr></table>';	
		    echo'<br><br>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b> " '.$ar['proposal_name'].' "</b>';
		 echo' <br><br><table>
    	  <tr>
    	     <td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;The study was approved with following conditions. </td>
    	  </tr>
    	  </table>
    	  <table>
    	  <tr>
    	     <td width="8%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;1) </td><td width="90%">End of study report to be submitted to HREC.</td>
    	  </tr>
    	  </table>
    	  <table>
    	  <tr>
    	     <td width="8%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;2) </td><td width="90%">Publication should be notified to HREC. </td>
    	  </tr>
    	  </table>';
	    }
	 else if($action=='print3')
	    {
			echo'
			<table ><tr width="100%"><td align="right" width="20%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>Sub:-  </b></td><td width="80%"> Research Proposal Entitled,<b> "'.$ar['proposal_name'].'."</b></td></tr></table>
                  <br><table>
			          <tr width="100%"><td align="right" width="20%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>Ref:-  </b></td><td width="60%">(1):- No.MCS/online EC-Protocol No.'. $ar['id'].'</td><td width="20%">Date: '. date("d-m-Y",strtotime($ar['date_time'])).' </td></tr>
			          <tr width="100%"><td align="right" width="20%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td><td width="60%">(2):-Provisional approval letter No.MCS/STU/Ethics/'.$_POST['provisional_no'].'</td><td width="20%">Date: '.date("d-m-Y",strtotime($_POST['provisional_date'])).'</td></tr>
			      </table>';
			   echo' <br><br><table><tr><td>Dear <b>'. $ar['guide'].',</b></td></tr></table>';
			  echo'<br><br><table >
						<tr>
							<td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
									The above mentioned research proposal of <b>'. $ar['guide'].' </b> Entitled,  <b>"
									'.$ar['proposal_name'].' "</b> was discussed in the Ethics Committee 
									  meeting held on '.date("d-m-Y",strtotime($_POST['meeting_date'])).' at our College. 
							</td>
						</tr>

						<tr>
							<td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; 
							     Ethics Commitee has unanimously approved your Title & Synopsis of Dissertation.
								 	
							</td>
						</tr>
						<tr>
							<td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; 
								This work will be done under the guidance and supervision of guide, <b>'.$user_info['name'].'</b>
							</td>
						</tr>
						<tr>
						     <td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; 
						          The committee had given provisional approval for the study on '.date("d-m-Y",strtotime($_POST['provisional_date'])).' 
						     </td>
						</tr>
						<tr>
						     <td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; 
						          The Study is approved by Gujarat State AIDS Control Society/NACO.( GSACS approval letter no. '.$_POST['gsacs_no'].', Date: '.date("d-m-Y",strtotime($_POST['gsacs_date'])).' )
						     </td>
						</tr>
						<tr>
						     <td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
						          The Committee has decided to give approval to your Proposal.
						     </td>
						</tr>

					</table>';
		}
		else if($action=='print4')
	    {
			echo'<table><tr width="100%"><td align="right" width="20%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>Sub:-</b></td><td width="80%"> Approval of Research Proposal Submitted by you to HREC.</td></tr></table>';
			echo'<br><br><table><tr><td>Dear <b>'. $ar['guide'].',</b></td></tr></table>';
		     echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;The HREC,in its meeting on '.date("d-m-Y",strtotime($_POST['meeting_date'])).' had discussed the Research Proposal Submitted by you.The committee had decided to give provisional approval to your Proposal. ( Protocol No.'. $ar['id'].' )</td></tr></table>';			
		     echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>"'.$ar['proposal_name'].'"</b></td></tr></table>';			
		     echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;The study has been approved by Gujarat State AIDS Control Society.</td></tr></table>';			
		     echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;You  have  submitted  the  copy  of  Permission  from  GSACS No. GSACS/M&E/Research/'.$_POST['gsacs_no'].'   dated '.date("d-m-Y",strtotime($_POST['gsacs_date'])).'.</td></tr></table>';			
		     echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;You are here by permitted to carry out the study as per conditions motioned in permission for GSACS Letter.</td></tr></table>';			

		}
		else if($action=='print5')
	    {
			echo'<table><tr width="100%"><td align="right" width="20%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>Sub:-</b></td><td width="80%"> Provisional Approval of Research Proposal Submitted by you to HREC.</td></tr></table>';
			echo'<br><br><table><tr><td>Dear <b>'. $ar['guide'].',</b></td></tr></table>';
			echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;The HREC,in its meeting on date  '.date("d-m-Y",strtotime($_POST['meeting_date'])).' had discussed the research Proposal Submitted by you,(Online Protocol No.'. $ar['id'].')</td></tr></table>';			
		    echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>"'.$ar['proposal_name'].'"</b></td></tr></table>';			
			echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;The committee has decided to give provisional approval to your Proposal.</td></tr></table>';			
		    echo'<br><br><table><tr><td>The study is Provisionally approved with following conditions.</td></tr></table>';			
		    echo'<br><br><table><tr width="100%"><td width="8%">&nbsp;&nbsp;&nbsp; &nbsp;1)</td><td width="90%">Permission from GSACS/NACO for Data usage should be obtained and a copy should be submitted to HREC before initiating the study.</td></tr></table>';			
		    echo'<br><br><table><tr width="100%"><td width="8%">&nbsp;&nbsp;&nbsp; &nbsp;2)</td><td width="90%">Annual Progress report and end of study report to be submitted to HREC.</td></tr></table>';			
		    echo'<br><br><table><tr width="100%"><td width="8%">&nbsp;&nbsp;&nbsp; &nbsp;3)</td><td width="90%">Publication should be Notified to HREC.</td></tr></table>';			

		}
		else if($action=='print6')
	    {
			echo'<table><tr width="100%"><td align="right" width="20%">&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>Sub:-</b></td><td width="80%"> Provisional Approval of Research Proposal Submitted by you to HREC.</td></tr></table>';
			echo'<br><br><table><tr><td>Dear <b>'. $ar['guide'].',</b></td></tr></table>';
			echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;The HREC,in its meeting on date '.date("d-m-Y",strtotime($_POST['meeting_date'])).' had discussed the research Proposal Submitted by you,(Online Protocol No.'. $ar['id'].')</td></tr></table>';			
		    echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<b>"'.$ar['proposal_name'].'"</b></td></tr></table>';			
			echo'<br><br><table><tr><td>&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;The committee has decided to give provisional approval to your Proposal.</td></tr></table>';			
		    echo'<br><br><table><tr><td>The study is Provisionally approved with following conditions.</td></tr></table>';			
		    echo'<br><br><table><tr width="100%"><td width="8%">&nbsp;&nbsp;&nbsp; &nbsp;1)</td><td width="90%">Permission from GSACS/NACO for Data usage should be obtained and a copy should be submitted to HREC before initiating the study.</td></tr></table>';			
		    echo'<br><br><table><tr width="100%"><td width="8%">&nbsp;&nbsp;&nbsp; &nbsp;2)</td><td width="90%">Annual Progress report and end of study report to be submitted to HREC.</td></tr></table>';			
		    echo'<br><br><table><tr width="100%"><td width="8%">&nbsp;&nbsp;&nbsp; &nbsp;3)</td><td width="90%">Publication should be Notified to HREC.</td></tr></table>';			

		}
		
	echo'<pre></pre><pre></pre>
	<table>
	<tr align="center" width="100%"><td width="55%"></td><td width="45%"><b>Chairperson /Member secretary</b></td></tr>
	<tr align="center" width="100%"><td width="55%"></td><td width="45%">Human Research Ethics Committee</td></tr>
<tr align="center" width="100%"><td width="55%"></td ><td width="45%">Govt. Medical College,</td></tr>
<tr align="center" width="100%"><td width="55%"></td><td width="45%">Surat.</td></tr>
</table>';
}

function list_application_status_for_ecms_final($link,$status,$who_string)
{
	$sql='select * from proposal where status=\''.$status.'\'';
	$result=run_query($link,$GLOBALS['database'],$sql);
	echo '<table class="table table-striped"><tr><th colspan=10>List of research application with current status of <span class=bg-danger>'.$status.'</span></th></tr>
			<tr><th>proposal id</th><th>Applicant id/Name/Department</th><th>Title</th><th>DateTime</th><th>Status</th></tr>';
	while($ar=get_single_row($result))
	{
		//$user_info=get_user_info($link,$ar['applicant_id']);
		echo '<tr>
				<td>';

					echo '<form method=post>
						<button class="btn btn-sm btn-block btn-info" name=action value=view_'.$who_string.' >'.$ar['id'].' View</button>
						<button class="btn btn-sm btn-block btn-info" name=action value='.$who_string.'_approve >'.$ar['id'].' Approve</button>
						<button class="btn btn-sm btn-block btn-info" name=action value=ecms_sent_back >'.$ar['id'].' Send back</button>
						<input type=hidden name=proposal_id value=\''.$ar['id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</form>';
		
		
		echo ' </td>';
		
		echo '<td>';
			echo_applicant_info_popup($link,get_applicant_id($link,$ar['id']),$ar['id']);
		echo '</td>';
		
				//<td><span class="text-primary">'.$ar['applicant_id'].'</span>/<span class="text-danger">'.$user_info['name'].'</span>/<span class="text-primary">'.$user_info['department'].'</span></td>
		echo '<td>'.$ar['proposal_name'].'</td>
				<td>'.$ar['date_time'].'</td>
				<td>'.$ar['status'].'</td>
		</tr>';
		

	}
	echo '</table>';
}


function save_unapprove_ec($link,$reviewer_id,$proposal_id,$comment)
{	
	save_comment($link,$reviewer_id,$proposal_id,$comment.' [UNAPPROVED]');
	
	$sql='update decision 
			set approval=0
			where
				proposal_id=\''.$proposal_id.'\' and
				reviewer_id=\''.$reviewer_id.'\'';

	//echo $sql;
	if(!run_query($link,$GLOBALS['database'],$sql))
	{
		echo '<br><span class="text-danger">Unapproval failed</span>';
	}
	else
	{
		echo '<br><span class="text-success">Review by reviewer_id='.$reviewer_id.' for proposal_id='.$proposal_id.' is Unapproved</span>';
	}
}

function show_dashboard($link)
{
	get_sql($link);
	
}

function get_sql($link)
{
        if(!$result=run_query($link,$GLOBALS['database'],'select * from view_info_data')){return false;}

        echo '
        <table border=1 class="table-striped table-hover"><tr><th colspan=20>Select the data to view</th></tr>';

        $first_data='yes';

        while($array=get_single_row($result))
        {
                if($first_data=='yes')
                {
                        echo '<tr>';
                        foreach($array as $key=>$value)
                        {
							    if($key!='sql'){
                                echo '<th bgcolor=lightgreen>'.$key.'</th>';}
                        }
                        echo '</tr>';
                        $first_data='no';
                }

				echo'<form style="margin-bottom:0;" method=post>';
                echo '<tr>';
                foreach($array as $key=>$value)
                {
					echo'<input type=hidden name=session_name value=\''.$_SESSION['login'].'\'>';
					echo '<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';
                       if($key=='id')
                        { 
                         echo '<td>
							<input type=hidden name=action value=display_data>
							<button class="btn btn-danger" type=submit name=id value=\''.$value.'\'>'.$value.'</button></td>';
                        }
                        elseif($key=='sql'){}
                        elseif($key=='Fields')
                        {
                                echo '<td class="badge badge-warning">'.$value.'</td>';							
						}
                        else
                        {
                                echo '<td>'.$value.'</td>';
                        }
                }
				echo '</tr>';
				echo '</form>';

        }
        echo '</table>';
    
}
function prepare_result_from_view_data_id($link,$id)
{

         if(!$result_id=run_query($link,$GLOBALS['database'],'select * from view_info_data where id=\''.$id.'\''))
         {
			 //echo '<h1>Problem</h1>';
		 }
		 else
		 {
			// echo '<h1>Success</h1>';
		 }
        $array_id=get_single_row($result_id);

        $sql=$array_id['sql'].'';
        $info=$array_id['info'];

		//echo $sql.'<br>';
        ////modify sql
        //print_r($_POST);
        
        if(isset($_POST['__p1'])) 
        {
			if(strlen($_POST['__p1'])>0)
			{
				$sql=str_replace('__p1',$_POST['__p1'],$sql);			
				$p1=$_POST['__p1'];
			}
			else
			{
				$p1='';
			}
		}
		else
		{
			$p1='';
		}


        if(isset($_POST['__p2'])) 
        {
			if(strlen($_POST['__p2'])>0)
			{
				$sql=str_replace('__p2',$_POST['__p2'],$sql);			
				$p2=$_POST['__p2'];
			}
			else
			{
				$p2='';
			}
		}
		else
		{
			$p2='';
		}

        if(isset($_POST['__p3'])) 
        {
			if(strlen($_POST['__p3'])>0)
			{
				$sql=str_replace('__p3',$_POST['__p3'],$sql);			
				$p3=$_POST['__p3'];
			}
			else
			{
				$p3='';
			}
		}
		else
		{
			$p3='';
		}

        if(isset($_POST['__p4'])) 
        {
			if(strlen($_POST['__p4'])>0)
			{
				$sql=str_replace('__p4',$_POST['__p4'],$sql);			
				$p4=$_POST['__p4'];
			}
			else
			{
				$p4='';
			}
		}
		else
		{
			$p4='';
		}
        //////////////
		//echo $sql;


        if(!$result=run_query($link,$GLOBALS['database'],$sql))
        {
			 echo '<h1>Problem</h1>';
		}
		 else
		 {
			 //echo '<h1>Success</h1>';
		 }


		echo_export_button($link,$id,$p1,$p2,$p3,$p4);
		display_sql_result_data($result);

}


function echo_export_button($link,$id,$p1,$p2,$p3,$p4)
{
	echo '<form method=post class="d-inline" action=export.php>';
		echo '<input type=hidden name=session_name value=\''.$_SESSION['login'].'\'>';
		echo '<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>';			
		echo '<input type=hidden name=id value=\''.$id.'\'>';
		echo '<input type=hidden name=__p1 value=\''.$p1.'\'>		
			<input type=hidden name=__p2 value=\''.$p2.'\'>		
			<input type=hidden name=__p3 value=\''.$p3.'\'>		
			<input type=hidden name=__p4 value=\''.$p4.'\'>		
			
			<button class="btn btn-info"  
			formtarget=_blank
			type=submit
			name=action
			value=export>Export</button>
		</form>';
}
	
function display_sql_result_data($result)
{
	echo '<button data-toggle="collapse" data-target="#sql_result" class="btn btn-dark">Show/Hide Result</button>';
	echo '<div id="sql_result" class="collapse show">';		
		
	
       echo '<table border=1 class="table-striped table-hover">';
				
        $first_data='yes';

        while($array=get_single_row($result))
        {
			//print_r($array);
                if($first_data=='yes')
                {
                        echo '<tr bgcolor=lightgreen>';
                        foreach($array as $key=>$value)
                        {
                                echo '<th>'.$key.'</th>';
                        }
                        echo '</tr>';
                        $first_data='no';
                }
                foreach($array as $key=>$value)
                {
                        echo '<td>'.$value.'</td>';
                }
                echo '</tr>';

        }
        echo '</table>';	
	echo '</div>';	
	
}
//111119500892
//one

function prepare_result_for_export($link,$id)
{

         if(!$result_id=run_query($link,$GLOBALS['database'],'select * from view_info_data where id=\''.$id.'\''))
         {
			 //echo '<h1>Problem</h1>';
		 }
		 else
		 {
			// echo '<h1>Success</h1>';
		 }
        $array_id=get_single_row($result_id);

        $sql=$array_id['sql'].'';
        $info=$array_id['info'];

		//echo $sql.'<br>';
        ////modify sql
        //print_r($_POST);
        
        if(isset($_POST['__p1'])) 
        {
			if(strlen($_POST['__p1'])>0)
			{
				$sql=str_replace('__p1',$_POST['__p1'],$sql);			
				$p1=$_POST['__p1'];
			}
			else
			{
				$p1='';
			}
		}
		else
		{
			$p1='';
		}


        if(isset($_POST['__p2'])) 
        {
			if(strlen($_POST['__p2'])>0)
			{
				$sql=str_replace('__p2',$_POST['__p2'],$sql);			
				$p2=$_POST['__p2'];
			}
			else
			{
				$p2='';
			}
		}
		else
		{
			$p2='';
		}

        if(isset($_POST['__p3'])) 
        {
			if(strlen($_POST['__p3'])>0)
			{
				$sql=str_replace('__p3',$_POST['__p3'],$sql);			
				$p3=$_POST['__p3'];
			}
			else
			{
				$p3='';
			}
		}
		else
		{
			$p3='';
		}

        if(isset($_POST['__p4'])) 
        {
			if(strlen($_POST['__p4'])>0)
			{
				$sql=str_replace('__p4',$_POST['__p4'],$sql);			
				$p4=$_POST['__p4'];
			}
			else
			{
				$p4='';
			}
		}
		else
		{
			$p4='';
		}
        //////////////
		//echo $sql;


        if(!$result=run_query($link,$GLOBALS['database'],$sql))
        {
			 echo '<h1>Problem</h1>';
		}
		 else
		 {
			 //echo '<h1>Success</h1>';
		 }


		export_data($result);
}

function export_data($result)
{
	    $fp = fopen('php://output', 'w');
	    if ($fp && $result) 
	    {
		    header('Content-Type: text/csv');
		    header('Content-Disposition: attachment; filename="export.csv"');
		
	    	$first='yes';
		
		   while ($row = get_single_row($result))
		   {
			    if($first=='yes')
			    {
				  fputcsv($fp, array_keys($row));
				  $first='no';
			    }
			
			fputcsv($fp, array_values($row));
		  }
	   }
}

function is_user_type($link,$user_id,$type)
{
	//echo '<pre>'.$user_id;
	$ui=get_user_info($link,$user_id);
	//print_r($ui);
	//print_r($ui['type']);
	$ex=explode('|',$ui['type']);
	//print_r($ex);
	if(in_array($type,$ex)){return true;}
	else{return false;}
}
?>
