<?php

require_once '/var/gmcs_config/staff.conf';
require_once 'research_common.php';
require_once 'base/common.php';
$con=mysqli_connect("127.0.0.1",$GLOBALS['main_user'],$GLOBALS['main_pass']);

$db_success=mysqli_select_db($con,'research');

$sql = "select * from comment order by date_time DESC limit 0,25";

$query = mysqli_query($con,$sql);

header( "Content-type: text/xml");
 
 echo "<?xml version='1.0' encoding='UTF-8'?>
 <rss version='2.0'>
 <channel>
 <title>Comment</title>
 <link>http://gmcsurat.edu.in</link>
 <description>HREC Comment</description>
 <language>en-us</language>";
 
 while($row = mysqli_fetch_assoc($query))
{
	$link='http://'.$_SERVER['HTTP_HOST'].'/hrec';
	//$title=print_r($row,true);
	$reviewer_info=get_user_info($con,$row['reviewer_id']);

	$applicant_id=get_applicant_id($con,$row['proposal_id']);
	$applicant_info=get_user_info($con,$applicant_id);

	$title=$reviewer_info['name'].' commented at '.$row['date_time'].' on praposal by '.$applicant_info['name'];
            echo "<item>
             <title>$title</title>
             <link>$link</link>
             <description></description>
              </item>";
}
 
 echo "</channel></rss>";
 
?>

