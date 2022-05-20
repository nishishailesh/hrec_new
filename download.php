<?php
$GLOBALS['nojunk']='';
require_once 'base/verify_login.php';
require_once 'research_common.php';

//my_print_r($_POST);
////////User code below/////////////////////	
	if(isset($_POST['fname_postfix']))
	{
		$postfix=$_POST['fname_postfix'];
	}
	else
	{
		$postfix='';
	}

	$link=get_link($GLOBALS['main_user'],$GLOBALS['main_pass']);
	download($link,'research',$_POST['table'],$_POST['field'],$_POST['primary_key'],$_POST['primary_key_value'],$postfix);

function download($link,$d,$t,$f,$pk,$pkv,$postfix='')
{
	$sql='select * 
			from `'.$t.'`
			where `'.$pk.'`=\''.$pkv.'\'';
			
	$result=run_query($link,$d,$sql);
	$ar=get_single_row($result);
//	$filename=$t.'_'.$f.'_'.$pkv.$postfix;
	$filename=$postfix;
	$h='Content-Disposition: attachment; filename="'.$filename.'"';
	header($h);
	echo $ar[$f];
}

//////////////user code ends////////////////
tail();
?>
