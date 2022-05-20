<?php
//session_start();
//session_name($_POST['session_name']);
//session_start();

//$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'research_common.php';

$proposal_id=$_POST['proposal_id'];
//print_r($_POST);

////////User code below/////////////////////	

	$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
$user_info=get_user_info($link,$_SESSION['login']);
	//my_print_r($user_info);
	echo'';
	echo '<form method=post><table class="table table-dark">
			<tr>
				<td>'.$user_info['id'].'</td>
				<td>'.$user_info['name'].'</td>
				<td>'.$user_info['type'].'</td>
				<td>'.$user_info['subtype'].'</td>
				<td>'.$user_info['Institute'].'</td>
				<td>'.$user_info['year_of_admission'].'</td>
				<td>'.$user_info['department'].'</td>
				<td>'.$user_info['email'].'</td>
				<td>'.$user_info['mobile'].'</td>
				<td><button class="btn btn-info" type=submit formtarget=_blank name=action value=help formaction="http://11.207.1.2/dokuwiki/doku.php?id=src_help">Help</button></td>
			</tr>
		</table></form>';
	//$user_info=get_user_info($link,$_SESSION['login']);

            echo'<div class="container-fluid">
		         <div class="row">
		         <div class="col-md-2 col-offset-2"></div>
			     <div class="col-md-8 col-offset-3">
            <form method=post>		
               <table border="1">
               <tr>
               <td>Letter No.<input type=text name=letter_no placeholder="Enter Letter No"></td>
               <td> Letter Date<input type=date name=letter_date></td>
               </tr>
               <tr>
               <td>Provisional Approval No.<input type=text name=provisional_no placeholder="Enter Provisional Approval No."></td>
               <td>Provisional Approval Date.<input type=date name=provisional_date ></td>
               </tr>
               <tr>
               <td>GSACS Approval No.<input type=text name=gsacs_no placeholder="Enter GSACS Approval No."></td>
               <td>GSACS  Approval Date.<input type=date name=gsacs_date></td>
               </tr>
               <tr>
               <td> Meeting Date<input type=date name=meeting_date plceholder="Enter Letter No"></td>
               <td></td>
               </tr>
              <tt><td><input type=hidden name=proposal_id value=\''.$_POST['proposal_id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'></td></tt>
               </table>
               </div>
               <div class="col-md-2 col-offset-2"></div>
               </div>
               </div>
           
               
               
               
		         <div class="row">
		         <div class="col-md-3 col-offset-3"></div>
			     <div class="col-md-6 col-offset-3">

					<div class="form-group">
					   <button   class="btn btn-lm btn-block btn-success" formtarget=_blank
					    name=action  value=print1 formaction=print_approval_latter.php >Print Dissertation (Final) Approval letter</button>
					    <input type=hidden name=proposal_id value=\''.$_POST['proposal_id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</div>
				
					
					
					<div class="form-group">						
					    <button class="btn btn-lm btn-block btn-success" formtarget=_blank 
					    name=action  value=print2 formaction=print_approval_latter.php>Print Non-Dissertation (Final) Approval Letter</button>
					      <input type=hidden name=proposal_id value=\''.$_POST['proposal_id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</div>
					
					
					<div class="form-group">						
						<button class="btn btn-lm btn-block btn-info" formtarget=_blank 
						name=action value=print3 formaction=print_approval_latter.php>Print HIV Dissertation (Final) Approval Letter</button>
						  <input type=hidden name=proposal_id value=\''.$_POST['proposal_id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</div>
					
					<div class="form-group">
					   <button   class="btn btn-lm btn-block btn-info" formtarget=_blank
					    name=action  value=print4 formaction=print_approval_latter.php >Print HIV Non-Dissertation (Final) Approval Letter</button>
					   <input type=hidden name=proposal_id value=\''.$_POST['proposal_id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</div>
					
					
					
					<div class="form-group">						
					    <button class="btn btn-lm btn-block btn-success" formtarget=_blank 
					    name=action  value=print5 formaction=print_approval_latter.php>Print HIV Dissertation Provisional Approval Letter</button>
					      <input type=hidden name=proposal_id value=\''.$_POST['proposal_id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</div>
				
				<div class="form-group">						
					    <button class="btn btn-lm btn-block btn-success" formtarget=_blank 
					    name=action  value=print6 formaction=print_approval_latter.php>Print HIV Non-Dissertation Provisional Approval Letter</button>
					      <input type=hidden name=proposal_id value=\''.$_POST['proposal_id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</div>
					
					<div class="form-group">						
						<button class="btn btn-lm btn-block btn-success" formtarget=_blank
						name=action  value=print6 formaction=print_approval_latter.php>Print HIV Non-Dissertation Provisional Approval Letter</button>
						  <input type=hidden name=proposal_id value=\''.$_POST['proposal_id'].'\'>
						<input type=hidden name=session_name value=\''.$_POST['session_name'].'\'>
					</div>
					<div class="col-md-3 col-offset-3"></div>
				    </form>
				    </div>
				    </div>
				    </div>';
?>
