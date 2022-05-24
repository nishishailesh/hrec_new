<?php
//$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'research_common.php';


////////User code below/////////////////////	
//print_r($_POST);

	$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

	$user_info=get_user_info($link,$_SESSION['login']);
	//my_print_r($user_info);
	echo '<form method=post><table class="table table-dark">
			<tr><td><button class="btn btn-info m-2" type=submit  name=action value=home><img src="img/home.png"></button>
					<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'></td>
				<td>'.$user_info['id'].'</td>
				<td>'.$user_info['name'].'</td>
				<td>'.$user_info['type'].'</td>
				<td>'.$user_info['subtype'].'</td>
				<td>'.$user_info['Institute'].'</td>
				<td>'.$user_info['year_of_admission'].'</td>
				<td>'.$user_info['department'].'</td>
				<td>'.$user_info['email'].'</td>
				<td>'.$user_info['mobile'].'</td>
				<td>
				<button class="btn btn-info" type=submit formtarget=_blank name=action value=help 
		formaction="http:\/\/'.$_SERVER['HTTP_HOST'].'/dokuwiki/doku.php?id=hrec_help:start">Help</button>				
		</td>
			</tr>
		</table></form>';
		
	echo '<h3 data-toggle="collapse" data-target="#recent_activity" class="bg-warning">Recent Activity related to you <font size="4" color="blue">(Click to Expand)</font></h3>';
	echo '<div id="recent_activity" class="collapse">';		
		echo '<div id="recent_activity">Messages</div>';
	echo '</div>';

/////Actions//////
	if($_POST['action']=='save_comment')
	{ 
		if(isset($_FILES['attachment']))
		{
			$blob=file_to_str($link,$_FILES['attachment']);
			$upload_fname=$_FILES['attachment']['name'];
		}
		else
		{
			$blob='';
			$upload_fname='';
		}
		save_comment($link,$_SESSION['login'],$_POST['proposal_id'],$_POST['comment'],$blob,$upload_fname);
	}	

	if($_POST['action']=='forward_proposal')
	{ 
		save_application_field($link,$_POST['proposal_id'],'forwarded',0);
	}

	if($_POST['action']=='forward_attachment')
	{ 
		forward_attachment($link,$_POST['attachment_id']);
	}
	
    if(is_user_type($link,$_SESSION['login'],'researcher'))
	 {
	    
	    if($_POST['action']=='insert_application')
        {
			insert_application($link,
						$_POST['applicant_id'],$_POST['proposal_name'],$_POST['type'],
						$_POST['interventional_research'],
						$_POST['research_on_vulnerable_subjects'],
						$_POST['description_of_study_subjects'],
						$_POST['additional_remarks'],
						$_POST['guide'],$_POST['emailid'],$_POST['mobileno'],$_POST['year'],$_POST['dept_type']);
			$_SESSION['dsp']='researcher';
	    }


	    if($_POST['action']=='update_application')
        {
			save_application_field($link,$_POST['id'],'proposal_name',$_POST['proposal_name']); 
			save_application_field($link,$_POST['id'],'type',$_POST['type']); 

			save_application_field($link,$_POST['id'],'interventional_research',$_POST['interventional_research']); 
			save_application_field($link,$_POST['id'],'research_on_vulnerable_subjects',$_POST['research_on_vulnerable_subjects']); 
			save_application_field($link,$_POST['id'],'description_of_study_subjects',$_POST['description_of_study_subjects']); 
			save_application_field($link,$_POST['id'],'additional_remarks',$_POST['additional_remarks']); 

			save_application_field($link,$_POST['id'],'guide',$_POST['guide']); 
			save_application_field($link,$_POST['id'],'researcher_email_id',$_POST['emailid']);
			save_application_field($link,$_POST['id'],'researcher_mobile_no',$_POST['mobileno']);
            save_application_field($link,$_POST['id'],'year',$_POST['year']); 
			save_application_field($link,$_POST['id'],'Department',$_POST['dept_type']); 
			//view_entire_application_for_applicant($link,$_POST['id']);
			$_SESSION['dsp']='researcher';
	    }

	    if($_POST['action']=='upload_attachment')
        {   
			//status($link,$_POST['proposal_id']);
			$blob=file_to_str($link,$_FILES['attachment']);
			save_attachement($link,$_POST['proposal_id'],$_POST['type'],$blob,$_FILES['attachment']['name']);		
			//view_entire_application_for_applicant($link,$_POST['proposal_id']);
			$_SESSION['dsp']='researcher';
			
	    }
	    	    
		if($_POST['action']=='delete_appication')
        	{
				delete($link,'research',$_POST['table'],$_POST['primary_key'],$_POST['primary_key_value']);
				$_SESSION['dsp']='researcher';
			}
       
       //list_researcher_application($link);
	
		//echo '</div><div></div>'; //for researcher collapse
	}

	if(is_user_type($link,$_SESSION['login'],'srcm'))
	{	

		/////
		//3//
		/////
		//echo '<h3 data-toggle="collapse" data-target="#srcm" class="bg-warning">Activity as SRCM <font size="4" color="blue">(Click to Expand)</font></h3>';

		//echo '<div class=collapse id=srcm>';
		

			
		/////
		//4//
		/////		
		if($_POST['action']=='approve')
		{
			save_approve($link,$_SESSION['login'],$_POST['proposal_id'],$_POST['comment']);
			if(pending_review($link,$_POST['proposal_id'],'srcm')==0)
			{
					set_application_status($link,$_POST['proposal_id'],'020.srcm_approved');
			}
			$_SESSION['dsp']='srcm';
		}	
						
		//if($_POST['action']=='view_application' || $_POST['action']=='save_comment' ||$_POST['action']=='approve')
		if($_POST['action']=='view_application' ||$_POST['action']=='approve')
		{
		//	view_entire_application($link,$_POST['proposal_id']);
			//$_SESSION['dsp']='srcm';
		}		
		

			//echo '<div class="jumbotron tab-pane container active" id=assigned>';
			//	list_application_for_reviewer($link,'010.srcm_assigned','view_application',$_SESSION['login']);
			//echo '</div>';
			
			//echo '</div>'; //for srcm collapse
			
			
	}
	
	if(is_user_type($link,$_SESSION['login'],'srcms'))
	{	
		/////
		//1//
		/////
		if($_POST['action']=='assign_reviewer')
		{

		}

		/////
		//2//
		/////
		if($_POST['action']=='save_reviewer')
		{
			save_srcm_reviewer($link,$_POST);
		}

		/////
		//3//
		/////		
		if(in_array($_POST['action'],['send_to_ecms1','send_to_ecms2','send_to_ecms3']))
		{
			if($_POST['action']=='send_to_ecms1')
			{
				set_application_status($link,$_POST['proposal_id'],'031.sent_to_ecms1');
			}
			if($_POST['action']=='send_to_ecms2')
			{
				set_application_status($link,$_POST['proposal_id'],'032.sent_to_ecms2');
			}
			if($_POST['action']=='send_to_ecms3')
			{
				set_application_status($link,$_POST['proposal_id'],'033.sent_to_ecms3');
			}
			
			save_email_for_send_to_ecms($link,$_POST['proposal_id']);
			$_SESSION['dsp']='srcms';
		}


		/////
		//4//
		/////		
		if($_POST['action']=='reverse_approval')
		{
			reverse_approval($link,$_POST['proposal_id'],$_POST['reviewer_id'],$_POST['comment']);
		}
						

	}

	if(is_user_type($link,$_SESSION['login'],'ecm1'))
	{	
		if($_POST['action']=='approve')
		{
			save_approve($link,$_SESSION['login'],$_POST['proposal_id'],$_POST['comment']);
			if(pending_review($link,$_POST['proposal_id'])==0)
			{
					set_application_status($link,$_POST['proposal_id'],'061.sent_to_committee1');
			}
			$_SESSION['dsp']='ecm';
		}
		if($_POST['action']=='view_application' ||$_POST['action']=='approve')
		{

		}
	}

	if(is_user_type($link,$_SESSION['login'],'ecms1'))
	{	
		if($_POST['action']=='assign_reviewer')
		{

		}

		if($_POST['action']=='ecms_approve')
		{
			set_application_status($link,$_POST['proposal_id'],'071.ecms1_approved');
			$_SESSION['dsp']='ecms';
		}
				
		if($_POST['action']=='ecms_sent_back')
		{
			$list_of_reviewer=get_only_ecm_reviewer($link,$_POST['proposal_id']);
			foreach($list_of_reviewer as $value)
			{
				$comment='Sent back for re-review
				Reviewers are requested to make comment on online proposal about suggestions made in meeting';
				save_unapprove_ec($link,$value,$_POST['proposal_id'],$comment);
			}
			
			set_application_status($link,$_POST['proposal_id'],'041.ecm1_assigned');
			$_SESSION['dsp']='ecms';
		}

		if($_POST['action']=='save_reviewer')
		{
			save_ecm_reviewer($link,$_POST,'041.ecm1_assigned');
		}

		if($_POST['action']=='view')
		{

		}

	}

	if(is_user_type($link,$_SESSION['login'],'ecm2'))
	{	
		if($_POST['action']=='approve')
		{
			save_approve($link,$_SESSION['login'],$_POST['proposal_id'],$_POST['comment']);
			if(pending_review($link,$_POST['proposal_id'])==0)
			{
					set_application_status($link,$_POST['proposal_id'],'062.sent_to_committee2');
			}
			$_SESSION['dsp']='ecm';
		}
		if($_POST['action']=='view_application' ||$_POST['action']=='approve')
		{

		}
	}

	if(is_user_type($link,$_SESSION['login'],'ecms2'))
	{	
		if($_POST['action']=='assign_reviewer')
		{

		}

		if($_POST['action']=='ecms_approve')
		{
			set_application_status($link,$_POST['proposal_id'],'072.ecms2_approved');
			$_SESSION['dsp']='ecms';
		}
				
		if($_POST['action']=='ecms_sent_back')
		{
			$list_of_reviewer=get_only_ecm_reviewer($link,$_POST['proposal_id']);
			foreach($list_of_reviewer as $value)
			{
				$comment='Sent back for re-review
				Reviewers are requested to make comment on online proposal about suggestions made in meeting';
				save_unapprove_ec($link,$value,$_POST['proposal_id'],$comment);
			}
			
			set_application_status($link,$_POST['proposal_id'],'042.ecm2_assigned');
			$_SESSION['dsp']='ecms';
		}

		if($_POST['action']=='save_reviewer')
		{
			save_ecm_reviewer($link,$_POST,'042.ecm2_assigned');
		}

		if($_POST['action']=='view')
		{

		}

	}

	if(is_user_type($link,$_SESSION['login'],'ecm3'))
	{	
		if($_POST['action']=='approve')
		{
			save_approve($link,$_SESSION['login'],$_POST['proposal_id'],$_POST['comment']);
			if(pending_review($link,$_POST['proposal_id'])==0)
			{
					set_application_status($link,$_POST['proposal_id'],'063.sent_to_committee3');
			}
			$_SESSION['dsp']='ecm';
		}
		if($_POST['action']=='view_application' ||$_POST['action']=='approve')
		{

		}
	}

	if(is_user_type($link,$_SESSION['login'],'ecms3'))
	{	
		if($_POST['action']=='assign_reviewer')
		{

		}

		if($_POST['action']=='ecms_approve')
		{
			set_application_status($link,$_POST['proposal_id'],'073.ecms3_approved');
			$_SESSION['dsp']='ecms';
		}
				
		if($_POST['action']=='ecms_sent_back')
		{
			$list_of_reviewer=get_only_ecm_reviewer($link,$_POST['proposal_id']);
			foreach($list_of_reviewer as $value)
			{
				$comment='Sent back for re-review
				Reviewers are requested to make comment on online proposal about suggestions made in meeting';
				save_unapprove_ec($link,$value,$_POST['proposal_id'],$comment);
			}
			
			set_application_status($link,$_POST['proposal_id'],'043.ecm3_assigned');
			$_SESSION['dsp']='ecms';
		}

		if($_POST['action']=='save_reviewer')
		{
			save_ecm_reviewer($link,$_POST,'043.ecm3_assigned');
		}

		if($_POST['action']=='view')
		{

		}

	}



////display////
	if(is_user_type($link,$_SESSION['login'],'researcher'))
	{
		echo '<h3 data-toggle="collapse" data-target="#researcher" class="bg-warning">Activity as PG Guide/RESEARCHER <font size="4" color="blue">(Click to Expand)</font></h3>';
		 
		echo '<div class=collapse id=researcher>';
		
		if($_POST['action']=='manage_application')
        {	
			view_entire_application_for_applicant($link,$_POST['proposal_id']);
			
			$_SESSION['dsp']='researcher';
		}
			
		if($_POST['action']=='new_application')
        {		
		    get_application_data($link);  
			$_SESSION['dsp']='researcher';
	    }
	    
	    if($_POST['action']=='insert_application')
        {
			
			//insert_application($link,$_POST['applicant_id'],$_POST['proposal_name'],$_POST['type'],$_POST['guide'],$_POST['year'],$_POST['dept_type']);
			//$_SESSION['dsp']='researcher';
	    }


	    if($_POST['action']=='update_application')
        {
		
			//save_application_field($link,$_POST['id'],'proposal_name',$_POST['proposal_name']); 
			//save_application_field($link,$_POST['id'],'type',$_POST['type']); 
			//save_application_field($link,$_POST['id'],'guide',$_POST['guide']); 
           // save_application_field($link,$_POST['id'],'year',$_POST['year']); 
			//save_application_field($link,$_POST['id'],'Department',$_POST['dept_type']); 
			view_entire_application_for_applicant($link,$_POST['id']);
			$_SESSION['dsp']='researcher';
	    }

	    if($_POST['action']=='upload_attachment')
        {   
			//status($link,$_POST['proposal_id']);
			//$blob=file_to_str($link,$_FILES['attachment']);
			//save_attachement($link,$_POST['proposal_id'],$_POST['type'],$blob,$_FILES['attachment']['name']);		
			view_entire_application_for_applicant($link,$_POST['proposal_id']);
			$_SESSION['dsp']='researcher';
			
	    }
	    	    
		if($_POST['action']=='delete_appication')
        	{
				//delete($link,'research',$_POST['table'],$_POST['primary_key'],$_POST['primary_key_value']);
				//$_SESSION['dsp']='researcher';
			}
       
       list_researcher_application($link);
	
		echo '</div><div></div>'; //for researcher collapse
	}

	if(is_user_type($link,$_SESSION['login'],'srcm'))
	{	

		/////
		//3//
		/////
		echo '<h3 data-toggle="collapse" data-target="#srcm" class="bg-warning">Activity as SRCM <font size="4" color="blue">(Click to Expand)</font></h3>';

		echo '<div class=collapse id=srcm>';
		

			
		/////
		//4//
		/////		
		if($_POST['action']=='approve')
		{
			//save_approve($link,$_SESSION['login'],$_POST['proposal_id'],$_POST['comment']);
			//if(pending_review($link,$_POST['proposal_id'],'srcm')==0)
			//{
			//		set_application_status($link,$_POST['proposal_id'],'020.srcm_approved');
			//}
			//save_comment$_SESSION['dsp']='srcm';
		}	
						
		//if($_POST['action']=='view_application' || $_POST['action']=='save_comment' ||$_POST['action']=='approve')
		if($_POST['action']=='view_application' ||$_POST['action']=='approve')
		{
			view_entire_application($link,$_POST['proposal_id']);
			$_SESSION['dsp']='srcm';
		}		
		

			echo '<div class="jumbotron tab-pane container active" id=assigned>';
				list_application_for_reviewer($link,'010.srcm_assigned','view_application',$_SESSION['login']);
			echo '</div>';
			
			echo '</div>'; //for srcm collapse
			
			
	}
	
	if(is_user_type($link,$_SESSION['login'],'srcms'))
	{	
		/////
		//1//
		/////
		echo '<h3 data-toggle="collapse" data-target="#srcms" class="bg-warning">Activity as SRCMS <font size="4" color="blue">(Click to Expand)</font></h3>';
		echo '<div id="srcms" class="collapse">';
		if($_POST['action']=='assign_reviewer')
		{
			echo '<div class="jumbotron">';
				list_single_application($link,$_POST['proposal_id']);
			//echo '</div>';
			//echo '<div class="jumbotron">';
			echo '<h3 data-toggle="collapse" data-target="#listr" class="bg-info">List Reviewers <font size="4" color="blue">(Click to Expand)</font></h3>';
		    echo '<div id="listr" class="collapse">';
				list_srcm_reviewer($link,$_POST['proposal_id']);
			echo '</div></div>';
			$_SESSION['dsp']='srcms';
		}


		/////
		//2//
		/////
		if($_POST['action']=='save_reviewer')
		{
			//save_srcm_reviewer($link,$_POST);
			//$applicant_id=get_applicant_id($link,$_POST['proposal_id']);
			
			//insert_reviewer($link,$_POST['proposal_id'],$applicant_id);
			
			echo '<div class="jumbotron">';
				list_single_application($link,$_POST['proposal_id']);
			echo '<h3 data-toggle="collapse" data-target="#listr" class="bg-info">List Reviewers <font size="4" color="blue">(Click to Expand)</font></h3>';
		    echo '<div id="listr" class="collapse">';
				list_srcm_reviewer($link,$_POST['proposal_id']);
			echo '</div></div>';
			$_SESSION['dsp']='srcms';
		}

		/////
		//3//
		/////		
		if($_POST['action']=='send_to_ecms')
		{
			//set_application_status($link,$_POST['proposal_id'],'030.sent_to_ecms');
			//$_SESSION['dsp']='srcms';
		}


		/////
		//4//
		/////		
		if($_POST['action']=='reverse_approval')
		{
			//reverse_approval($link,$_POST['proposal_id'],$_POST['reviewer_id'],$_POST['comment']);
		}
						
			echo '<ul class="nav nav-pills">
			<li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#dashboard">Dashboard</a></li>
			<li class="nav-item"><a class="nav-link " data-toggle="pill" href="#applied">Applied</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#assi">Reviewers Assigned (SRC)</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#reviewed">Reviewed (SRC)</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#sent_to_ecms1">Sent to ECMS1</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#sent_to_ecms2">Sent to ECMS2</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#sent_to_ecms3">Sent to ECMS3</a></li>
			
			</ul>
		
			<div class="tab-content">';
		
			echo '<div class="jumbotron tab-pane container" id=applied>';
				list_application_status($link,'001.applied','assign_reviewer');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=assi>';
				list_application_status($link,'010.srcm_assigned','assign_reviewer');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=reviewed>';
				list_application_status_multiple($link,'020.srcm_approved',['send_to_ecms1','send_to_ecms2','send_to_ecms3']);
			echo '</div>';

			echo '<div class="jumbotron tab-pane container" id=sent_to_ecms1>';
				list_application_status($link,'031.sent_to_ecms1');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=sent_to_ecms2>';
				list_application_status($link,'032.sent_to_ecms2');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=sent_to_ecms3>';
				list_application_status($link,'033.sent_to_ecms3');
			echo '</div>';

			echo '<div class="jumbotron tab-pane container  active" id=dashboard>';
				if(isset($_POST['action']))
				{
					if( $_POST['action']=='display_data')
					{
					//echo 'ssss';
					$result=prepare_result_from_view_data_id($link,$_POST['id']);
					
					$_SESSION['dsp']='srcms';
					}
				}
				show_dashboard($link);
				
			echo '</div>';						
			echo '</div>';//for tab-content
			
			echo '</div>';//for srcms collapsible
			echo '
					<form action=today_activity.php method=post target=_blank>
					<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
							<button name=today_activity> Today Activity</button>
					</form>';
		
		}
	
	if(is_user_type($link,$_SESSION['login'],'ecm1'))
	{	

		/////
		//3//
		/////
		echo '<h3 data-toggle="collapse" data-target="#ecm" class="bg-warning">Activity as ECM1 <font size="4" color="blue">(Click to Expand)</font></h3>';

		echo '<div class=collapse id=ecm>';
		

			
		/////
		//4//
		/////		
		if($_POST['action']=='approve')
		{
		}

		if($_POST['action']=='view_application' ||$_POST['action']=='approve')
		{
			view_entire_application_ecm($link,$_POST['proposal_id']);
			$_SESSION['dsp']='ecm';
		}		
			echo '<div class="jumbotron tab-pane container active" id=assigned>';
				list_application_for_reviewer($link,'041.ecm1_assigned','view_application',$_SESSION['login']);
			echo '</div>';
			
			echo '</div>'; //for ecm collapse
			
			
	}

	if(is_user_type($link,$_SESSION['login'],'ecms1'))
	{	
		/////
		//1//
		/////
		echo '<h3 data-toggle="collapse" data-target="#ecms" class="bg-warning">Activity as ECMS1 <font size="4" color="blue">(Click to Expand)</font></h3>';
		
		echo '<div id="ecms" class="collapse">';
		if($_POST['action']=='assign_reviewer')
		{
			echo '<div class="jumbotron">';
				list_single_application($link,$_POST['proposal_id']);
			//echo '</div>';
			//echo '<div class="jumbotron">';
			echo '<h3 data-toggle="collapse" data-target="#listr" class="bg-info">List Reviewers <font size="4" color="blue">(Click to Expand)</font></h3>';
		    echo '<div id="listr" class="collapse">';
				list_ecm_reviewer($link,$_POST['proposal_id'],'ecm1');
			echo'</div>';
			echo '</div>';
			$_SESSION['dsp']='ecms';
		}
		
		if($_POST['action']=='ecms_approve')
		{
		}
			
		if($_POST['action']=='ecms_sent_back')
		{
		}

		if($_POST['action']=='save_reviewer')
		{
			echo '<div class="jumbotron">';
	
				list_single_application($link,$_POST['proposal_id']);
			echo '<h3 data-toggle="collapse" data-target="#listr" class="bg-info">List Reviewers <font size="4" color="blue">(Click to Expand)</font></h3>';
		    echo '<div id="listr" class="collapse">';
				list_ecm_reviewer($link,$_POST['proposal_id'],'ecm1');
			echo '</div></div>';
			$_SESSION['dsp']='ecms';
		}
		
		
		if($_POST['action']=='view')
		{
			echo '<ul class="nav nav-pills">
			<li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#application">Application</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#review_status">Review Status</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#comment">Comments (ECM)</a></li>
			</ul>';
		
			echo '<div class="tab-content">';	

			echo '<div class="jumbotron tab-pane container active" id=application>';
				list_single_application_with_all_fields($link,$_POST['proposal_id']);
				$_SESSION['dsp']='ecms';
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=review_status>';
				show_review_status($link,$_POST['proposal_id']);
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=comment>';
				display_comment($link,$_POST['proposal_id']);
			echo '</div>';
		 echo '</div>';//for tab-content
		}

	
		
		
	echo '<ul class="nav nav-pills">
	        <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#dashboard">Dashboard</a></li>
			<li class="nav-item"><a class="nav-link " data-toggle="pill" href="#applied">Assign EC1 Reviewer</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#assi">EC1 Reviewers Assigned</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#ecms_approve">Require EC1 approval</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#print">EC1 Approved</a></li>
		</ul>
		
		<div class="tab-content">';
		
			echo '<div class="jumbotron tab-pane container " id=applied>';
				list_application_status($link,'031.sent_to_ecms1','assign_reviewer');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=assi>';
				list_application_status($link,'041.ecm1_assigned','assign_reviewer');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=ecms_approve><p>';		
				list_application_status_for_ecms_final($link,'061.sent_to_committee1');
			echo '</p></div>';
			echo '<div class="jumbotron tab-pane container" id=print>';
			//echo 'Print approval here';
				print_approval_latter($link,'071.ecms1_approved','print.php','Print Approval Letter');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container  active" id=dashboard>';
				if(isset($_POST['action']))
				{
					if( $_POST['action']=='display_data')
					{
					//echo 'ssss';
					$result=prepare_result_from_view_data_id($link,$_POST['id']);
					
					$_SESSION['dsp']='ecms';
					}
				}
				show_dashboard($link);
				
			echo '</div>';				
		echo '</div>';//for tab-content
		
		echo '</div>';//for ecms collapsible
	}


	
	if(is_user_type($link,$_SESSION['login'],'ecm2'))
	{	

		/////
		//3//
		/////
		echo '<h3 data-toggle="collapse" data-target="#ecm" class="bg-warning">Activity as ECM2 <font size="4" color="blue">(Click to Expand)</font></h3>';

		echo '<div class=collapse id=ecm>';
		

			
		/////
		//4//
		/////		
		if($_POST['action']=='approve')
		{
		}

		if($_POST['action']=='view_application' ||$_POST['action']=='approve')
		{
			view_entire_application_ecm($link,$_POST['proposal_id']);
			$_SESSION['dsp']='ecm';
		}		
			echo '<div class="jumbotron tab-pane container active" id=assigned>';
				list_application_for_reviewer($link,'042.ecm2_assigned','view_application',$_SESSION['login']);
			echo '</div>';
			
			echo '</div>'; //for ecm collapse
			
			
	}

	if(is_user_type($link,$_SESSION['login'],'ecms2'))
	{	
		/////
		//1//
		/////
		echo '<h3 data-toggle="collapse" data-target="#ecms" class="bg-warning">Activity as ECMS2 <font size="4" color="blue">(Click to Expand)</font></h3>';
		
		echo '<div id="ecms" class="collapse">';
		if($_POST['action']=='assign_reviewer')
		{
			echo '<div class="jumbotron">';
				list_single_application($link,$_POST['proposal_id']);
			//echo '</div>';
			//echo '<div class="jumbotron">';
			echo '<h3 data-toggle="collapse" data-target="#listr" class="bg-info">List Reviewers <font size="4" color="blue">(Click to Expand)</font></h3>';
		    echo '<div id="listr" class="collapse">';
				list_ecm_reviewer($link,$_POST['proposal_id'],'ecm2');
			echo'</div>';
			echo '</div>';
			$_SESSION['dsp']='ecms';
		}
		
		if($_POST['action']=='ecms_approve')
		{
		}
			
		if($_POST['action']=='ecms_sent_back')
		{
		}

		if($_POST['action']=='save_reviewer')
		{
			echo '<div class="jumbotron">';
	
				list_single_application($link,$_POST['proposal_id']);
			echo '<h3 data-toggle="collapse" data-target="#listr" class="bg-info">List Reviewers <font size="4" color="blue">(Click to Expand)</font></h3>';
		    echo '<div id="listr" class="collapse">';
				list_ecm_reviewer($link,$_POST['proposal_id'],'ecm2');
			echo '</div></div>';
			$_SESSION['dsp']='ecms';
		}
		
		
		if($_POST['action']=='view')
		{
			echo '<ul class="nav nav-pills">
			<li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#application">Application</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#review_status">Review Status</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#comment">Comments (ECM2)</a></li>
			</ul>';
		
			echo '<div class="tab-content">';	

			echo '<div class="jumbotron tab-pane container active" id=application>';
				list_single_application_with_all_fields($link,$_POST['proposal_id']);
				$_SESSION['dsp']='ecms';
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=review_status>';
				show_review_status($link,$_POST['proposal_id']);
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=comment>';
				display_comment($link,$_POST['proposal_id']);
			echo '</div>';
		 echo '</div>';//for tab-content
		}

	
		
		
	echo '<ul class="nav nav-pills">
	        <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#dashboard">Dashboard</a></li>
			<li class="nav-item"><a class="nav-link " data-toggle="pill" href="#applied">Assign EC1 Reviewer</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#assi">EC1 Reviewers Assigned</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#ecms_approve">Require EC1 approval</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#print">EC1 Approved</a></li>
		</ul>
		
		<div class="tab-content">';
		
			echo '<div class="jumbotron tab-pane container " id=applied>';
				list_application_status($link,'032.sent_to_ecms2','assign_reviewer');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=assi>';
				list_application_status($link,'042.ecm2_assigned','assign_reviewer');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=ecms_approve><p>';		
				list_application_status_for_ecms_final($link,'062.sent_to_committee2');
			echo '</p></div>';
			echo '<div class="jumbotron tab-pane container" id=print>';
			//echo 'Print approval here';
				print_approval_latter($link,'072.ecms2_approved','print.php','Print Approval Letter');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container  active" id=dashboard>';
				if(isset($_POST['action']))
				{
					if( $_POST['action']=='display_data')
					{
					//echo 'ssss';
					$result=prepare_result_from_view_data_id($link,$_POST['id']);
					
					$_SESSION['dsp']='ecms';
					}
				}
				show_dashboard($link);
				
			echo '</div>';				
		echo '</div>';//for tab-content
		
		echo '</div>';//for ecms collapsible
	}


	
	if(is_user_type($link,$_SESSION['login'],'ecm3'))
	{	

		/////
		//3//
		/////
		echo '<h3 data-toggle="collapse" data-target="#ecm" class="bg-warning">Activity as ECM3 <font size="4" color="blue">(Click to Expand)</font></h3>';

		echo '<div class=collapse id=ecm>';
		

			
		/////
		//4//
		/////		
		if($_POST['action']=='approve')
		{
		}

		if($_POST['action']=='view_application' ||$_POST['action']=='approve')
		{
			view_entire_application_ecm($link,$_POST['proposal_id']);
			$_SESSION['dsp']='ecm';
		}		
			echo '<div class="jumbotron tab-pane container active" id=assigned>';
				list_application_for_reviewer($link,'043.ecm3_assigned','view_application',$_SESSION['login']);
			echo '</div>';
			
			echo '</div>'; //for ecm collapse
			
			
	}

	if(is_user_type($link,$_SESSION['login'],'ecms3'))
	{	
		/////
		//1//
		/////
		echo '<h3 data-toggle="collapse" data-target="#ecms" class="bg-warning">Activity as ECMS3 <font size="4" color="blue">(Click to Expand)</font></h3>';
		
		echo '<div id="ecms" class="collapse">';
		if($_POST['action']=='assign_reviewer')
		{
			echo '<div class="jumbotron">';
				list_single_application($link,$_POST['proposal_id']);
			//echo '</div>';
			//echo '<div class="jumbotron">';
			echo '<h3 data-toggle="collapse" data-target="#listr" class="bg-info">List Reviewers <font size="4" color="blue">(Click to Expand)</font></h3>';
		    echo '<div id="listr" class="collapse">';
				list_ecm_reviewer($link,$_POST['proposal_id'],'ecm3');
			echo'</div>';
			echo '</div>';
			$_SESSION['dsp']='ecms';
		}
		
		if($_POST['action']=='ecms_approve')
		{
		}
			
		if($_POST['action']=='ecms_sent_back')
		{
		}

		if($_POST['action']=='save_reviewer')
		{
			echo '<div class="jumbotron">';
	
				list_single_application($link,$_POST['proposal_id']);
			echo '<h3 data-toggle="collapse" data-target="#listr" class="bg-info">List Reviewers <font size="4" color="blue">(Click to Expand)</font></h3>';
		    echo '<div id="listr" class="collapse">';
				list_ecm_reviewer($link,$_POST['proposal_id'],'ecm3');
			echo '</div></div>';
			$_SESSION['dsp']='ecms';
		}
		
		
		if($_POST['action']=='view')
		{
			echo '<ul class="nav nav-pills">
			<li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#application">Application</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#review_status">Review Status</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#comment">Comments (ECM3)</a></li>
			</ul>';
		
			echo '<div class="tab-content">';	

			echo '<div class="jumbotron tab-pane container active" id=application>';
				list_single_application_with_all_fields($link,$_POST['proposal_id']);
				$_SESSION['dsp']='ecms';
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=review_status>';
				show_review_status($link,$_POST['proposal_id']);
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=comment>';
				display_comment($link,$_POST['proposal_id']);
			echo '</div>';
		 echo '</div>';//for tab-content
		}

	
		
		
	echo '<ul class="nav nav-pills">
	        <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#dashboard">Dashboard</a></li>
			<li class="nav-item"><a class="nav-link " data-toggle="pill" href="#applied">Assign EC1 Reviewer</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#assi">EC1 Reviewers Assigned</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#ecms_approve">Require EC1 approval</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#print">EC1 Approved</a></li>
		</ul>
		
		<div class="tab-content">';
		
			echo '<div class="jumbotron tab-pane container " id=applied>';
				list_application_status($link,'033.sent_to_ecms3','assign_reviewer');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=assi>';
				list_application_status($link,'043.ecm3_assigned','assign_reviewer');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container" id=ecms_approve><p>';		
				list_application_status_for_ecms_final($link,'063.sent_to_committee3');
			echo '</p></div>';
			echo '<div class="jumbotron tab-pane container" id=print>';
			//echo 'Print approval here';
				print_approval_latter($link,'073.ecms3_approved','print.php','Print Approval Letter');
			echo '</div>';
			echo '<div class="jumbotron tab-pane container  active" id=dashboard>';
				if(isset($_POST['action']))
				{
					if( $_POST['action']=='display_data')
					{
					//echo 'ssss';
					$result=prepare_result_from_view_data_id($link,$_POST['id']);
					
					$_SESSION['dsp']='ecms';
					}
				}
				show_dashboard($link);
				
			echo '</div>';				
		echo '</div>';//for tab-content
		
		echo '</div>';//for ecms collapsible
	}

//////////////user code ends////////////////
tail();

//print_r($_POST);
//my_print_r($_FILES);
//my_print_r($_SESSION);
//my_print_r($_SERVER);

if(isset($_POST['session_name'])){$post='session_name='.$_POST['session_name'];}else{$post=session_name();}
if(isset($_SESSION['dsp'])){$dsp='\'#'.$_SESSION['dsp'].'\'';}else{$dsp='';}

?>

<script>

jQuery(document).ready(
	function() 
	{
		jQuery(<?php echo $dsp;?>).addClass("show");
		start();
	}
);

function start()
{
	setTimeout(callServer, 2000);
}
	
function callServer()
{
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
		document.getElementById('recent_activity').innerHTML = xhttp.responseText;
		}
	};
	xhttp.open('POST', 'response.php', true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.send(<?php echo '\''.$post.'\'';?>);
	setTimeout(callServer, <?php echo $GLOBALS['recent_activity_refresh_period']; ?>);
}
</script>

