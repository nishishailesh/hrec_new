<?php
//session_start();\
$nojunk='';
require_once 'base/verify_login.php';
require_once 'research_common.php';
require_once('tcpdf/tcpdf.php');
//require_once('Numbers/Words.php');

class ACCOUNT1 extends TCPDF {

	public function Header() 
	{
	}
	
	public function Footer() 
	{
	    $this->SetY(-10);
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}	
}	

$proposal_id=$_POST['proposal_id'];
$action=$_POST['action'];
//print_r($_POST);
//exit(0);
//print_r($action);
	$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);

	$user_info=get_user_info($link,get_applicant_id($link,$proposal_id));

	$result=run_query($link,'research','select * from proposal where id=\''.$proposal_id.'\'');
	$ar=get_single_row($result);
	
ob_start();
	gate_approval_latter_pdf($link,$user_info,$ar,$action);
  $myStr = ob_get_contents();
  ob_end_clean();
  
 //echo $myStr;
 // exit(0);
    

	
	     $pdf = new ACCOUNT1('P', 'mm', 'A4', true, 'UTF-8', false);
//	     $pdf->SetFont('dejavusans', '', 9);
	     //$pdf->SetFont('dejavusans', '', $_POST['fontsize']);
//	     $pdf->SetFont('courier', '', 8);
	     $pdf->SetMargins(10, 10, 10);
	     $pdf->AddPage();
	     $pdf->writeHTML($myStr, true, false, true, false, '');
	    $pdf->Output('print_approval_latter.pdf', 'I');

?>
