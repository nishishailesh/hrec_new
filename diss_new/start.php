<?php
//$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'research_common.php';


////////User code below/////////////////////	
//for disseration application by student, 
//create dept user in mysql
//use start.php, config.php, index.php
//rest of the file are same as main hrec (ln -s)
	$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

	$user_info=get_user_info($link,$_SESSION['login']);
	//my_print_r($user_info);
	echo '<form method=post><table class="table table-dark">
			<tr><td>
					<button class="btn btn-info m-2" type=submit  name=action value=home><img src="img/home.png"></button>
					<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
				</td>
				<td>
				click HOME-BUTTON to return to first screen. Search by entering proposal ID and PG teacher.
				</td>
			</tr>
		</table></form>';
		

	
	if($_POST['action']=='insert_application')
	{
		$new_application_id=insert_application_diss($link);
		view_entire_application_for_applicant($link,$new_application_id);
	}
	elseif($_POST['action']=='show_proposal')
	{
		$real_proposal_sql='select * from proposal where id=\''.$_POST['proposal_id'].'\' and applicant_id=\''.$_POST['pg_teacher'].'\'';
		$real_proposal_result=run_query($link,$GLOBALS['database'],$real_proposal_sql);
		$ar=get_single_row($real_proposal_result);
		//print_r($ar);
		if($ar)
		{
			view_entire_application_for_applicant($link,$ar['id']);
		}
		else
		{
			echo '<span class="text-danger">No proposal with given combination of proposal ID and PG Teacher exist</span><br>';
		}
	}	

    elseif($_POST['action']=='upload_attachment')
	{   
		//status($link,$_POST['proposal_id']);
		$blob=file_to_str($link,$_FILES['attachment']);
		save_attachement_diss($link,$_POST['proposal_id'],$_POST['type'],$blob,$_FILES['attachment']['name']);		
		view_entire_application_for_applicant($link,$_POST['proposal_id']);
    }	

    elseif($_POST['action']=='update_application')
	{
		save_application_field($link,$_POST['id'],'proposal_name',$_POST['proposal_name']); 
		save_application_field($link,$_POST['id'],'type',$_POST['type']); 
		save_application_field($link,$_POST['id'],'guide',$_POST['guide']); 
		save_application_field($link,$_POST['id'],'researcher_email_id',$_POST['emailid']);
		save_application_field($link,$_POST['id'],'researcher_mobile_no',$_POST['mobileno']);
		save_application_field($link,$_POST['id'],'year',$_POST['year']); 
		save_application_field($link,$_POST['id'],'Department',$_POST['dept_type']); 
		view_entire_application_for_applicant($link,$_POST['id']);
    }
  
	elseif($_POST['action']=='save_comment')
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
		$applicant_id=get_applicant_id($link,$_POST['proposal_id']);
		save_comment($link,$applicant_id,$_POST['proposal_id'],'comment from Student(via Department Login):'.$_POST['comment'],$blob,$upload_fname);
		view_entire_application_for_applicant($link,$_POST['proposal_id']);
	}	
  
	else
	{
		read_proposal_id($link);
		get_application_data_diss($link);
	}  
function read_proposal_id($link)
{
	echo '<form method=post><table class="table table-dark">
			<tr>
				<th>Proposal ID</th>
				<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
				<td><input type=number name=proposal_id></td>';
			   echo '<td>Give proposal ID given to you when applied first time.';
				echo help('diss_proposal_id_help');
				echo '</td>
			</tr>
			<tr>
			   <th>PG Teacher</th>
			   <td>';
			   $pg_teacher=pg_teacher_array($link);
			   mk_select_from_array_with_key('pg_teacher',$pg_teacher,'','');
			   echo '</td>';
			   echo '<td>Select PG teacher</td>';
			echo '</tr>
			<tr>
				<td><button class="btn btn-sm btn-block btn-info" name=action value=show_proposal>Show Proposal</button></td>			
			</tr>
		</table></form>';
}	


function pg_teacher_array($link)
{

	$sql='select id,name from user order by name';
							
	$result_selected=run_query($link,$GLOBALS['database'],$sql);
	
	$ret=array();
	while($ar=get_single_row($result_selected))
	{
		$ret[$ar['id']]=$ar['name'];
	}
	//my_print_r($ret);
	return $ret;
}

function mk_select_from_array_with_key($name, $select_array,$disabled='',$default='')
{	
	echo '<select  '.$disabled.' name=\''.$name.'\'>';
	foreach($select_array as $key=>$value)
	{
		if($value==$default)
		{
			echo '<option  selected value=\''.$key.'\'> '.$value.' </option>';
		}
		else
		{
			echo '<option  value=\''.$key.'\'> '.$value.' </option>';
		}
	}
	echo '</select>';	
	return TRUE;
}


function get_application_data_diss($link)
{
	echo'<form method=post enctype="multipart/form-data"  class="jumbotron">
					<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
				   <input type=hidden name=applicant_id value=\''.$_SESSION['login'].'\'>
	      <table class="table table-striped" width="50%"> 
	      <tr><th class="text-success rounded-top"><span class="badge badge-warning"><h3>New Proposal<h3></span></th></tr>              
	      <tr><th class="text-danger" colspan=10><h5>When saved, unique proposal ID will be displayed. Note it down.</h5></th></tr>
	      <tr><th class="text-danger" colspan=10><h5>Do not repeat application if proposal id is forgotten. Contact office.</h5></th></tr>

                        <tr><th class="text-danger" colspan=10>Note: Documents can be uploaded after saving the application</th></tr>
                        <tr><th class="text-danger" colspan=10><span class="badge badge-danger"><h4>Important Note:</h4></span> <span class="text-success">details of Interventional Research </span>MUST be added after saving the application</th></t>
                        <tr><th class="text-danger" colspan=10><span class="badge badge-danger"><h4>Important Note:</h4></span> <span class="text-success">details of Research on vulnerable subjects</span> MUST be added after saving the application</th></tr>
                        <tr><th class="text-danger" colspan=10>Note: Description of study subjects can be added after saving the application</th></tr>
                        <tr><th class="text-danger" colspan=10>Note: Additional Remarks can be added after saving the application</th></tr>


			<tr>
				   <th>Name of PG student</th>
				   <td><input name=pg_student_name class="form-control"  placeholder="Enter PG Student Name"></td>				  
			</tr>	
			<tr>
				   <th>Email of PG student</th>
				   <td><input name=pg_student_email class="form-control"  placeholder="Enter PG Student Email"></td>				  
			</tr>	
			<tr>
				   <th>Mobile of PG student</th>
				   <td><input name=pg_student_mobile class="form-control"  placeholder="Enter PG Student Mobile"></td>				  
			</tr>				
			 <tr>
				   <th>Proposal Title</th>
				   <td><textarea name=proposal_name class="form-control"  placeholder="Enter Proposal Title"></textarea></td>
				   <td>Must be same as what is uploaded in protocol</td>
			</tr>
			
			<tr>
				   <th>PG Teacher</th>
				   <td>';
				   $pg_teacher=pg_teacher_array($link);
				   mk_select_from_array_with_key('pg_teacher',$pg_teacher,'','');
				   echo '</td>';
				   echo '<td>Select PG teacher</td>';
			echo '</tr>

			</tr>			
			<tr>
				   <th>Year of Admission</th>
				   <td><input name=year class="form-control"  placeholder="Enter Year"></td>				  
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

			</table>
		</form>';
}

function insert_application_diss($link)
{
$pg_teacher_info=get_user_info($link,$_POST['pg_teacher']);	

$applicant_id=$pg_teacher_info['id'];
$proposal_name=$_POST['proposal_name'];
$type='PG Dissertation';
$guide=$_POST['pg_student_name'];
$researcher_email_id=$_POST['pg_student_email'];
$researcher_mobile_no=$_POST['pg_student_mobile'];
$year=$_POST['year'];
$department=$pg_teacher_info['department']; //in proposal D is capital
$status='001.applied';
$forwarded=1;

$sql='insert into proposal (applicant_id,proposal_name,type,date_time,guide,researcher_email_id,
							researcher_mobile_no,year,Department,status,forwarded)
values(
\''.$applicant_id.'\',
\''.$proposal_name.'\',
\''.$type.'\',
now(),
\''.$guide.'\',
\''.$researcher_email_id.'\',
\''.$researcher_mobile_no.'\',
\''.$year.'\',
\''.$department.'\',
\''.$status.'\',
\''.$forwarded.'\'
)';

	//echo $sql;
	$result=run_query($link,$GLOBALS['database'],$sql);
    if($result==false)
	{
		echo '<h3 style="color:red;">No record inserted</h3>';
	}
	else
	{
		echo '<h3 style="color:green;">'.$result.' record inserted</h3>';
		$new_proposal_id=last_autoincrement_insert($link);
		echo '<h3 style="color:green;">Your proposal ID is '.$new_proposal_id.'</h3>';
		echo '<h3 style="color:green;">Remember it for future access to your proposal</h3>';
		echo '<h3 style="color:green;">Next Step: retrive application and start uploading documents</h3>';
		
		insert_reviewer(
					$link,
					$new_proposal_id,
					get_applicant_id($link,$new_proposal_id)
				);
	}
	$comment='AUTO-GENERATED COMMENT
PG dissertation proposal with unique proposal_id='.$new_proposal_id.' is created.
However, it is yet to be forwarded by PG teacher to SRCM.
PG student are requested to contact PG teacher and ask to forward it.
PG teachers are requested to login, review and forward it at the earliest';

	save_comment($link,$applicant_id,$new_proposal_id,$comment);
	
//echo '<pre>';
//print_r($_POST);
//print_r($pg_teacher_info);
//echo $sql;
//echo '</pre>';	
	return $new_proposal_id;
}	

function save_attachement_diss($link,$proposal_id,$type,$blob,$attachment_name)
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
	
	$sql='insert into attachment ( proposal_id, 	type 	,date_time 	,attachment  ,attachment_name,forwarded)
			values
				(
					\''.$proposal_id.'\',
					\''.$type.'\',
					now(),
					\''.$blob.'\',
					\''.$attachment_name.'\',
					1
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


//////////user code ends////////////////
tail();
//echo '<pre>';
//print_r($_POST);
//my_print_r($_FILES);
//my_print_r($_SESSION);
//my_print_r($_SERVER);
//echo '</pre>';
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

