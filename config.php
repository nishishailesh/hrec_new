<?php
//$GLOBALS['main_user']='root';
//$GLOBALS['main_pass']='root';
$GLOBALS['main_user_location']='/var/gmcs_config/staff.conf';
$GLOBALS['user_database']='hrec_new';
$GLOBALS['database']='hrec_new';
$GLOBALS['user_table']='user';
$GLOBALS['user_id']='id';
$GLOBALS['user_pass']='password';
$GLOBALS['expiry_period']='+ 6 months';
$GLOBALS['expirydate_field']='expirydate';
$GLOBALS['title']='HREC GMCS';
//////Project specific globals
$GLOBALS['required_srcm_reviewer']=1;
$GLOBALS['required_ecm_reviewer']=1;
$GLOBALS['recent_activity_refresh_period']=10000;
$GLOBALS['recent_activity_data_count']=5;
$GLOBALS['send_email']=0;
//in development environment, if email can not be sent, script takes long wait. so, disable if required
//$GLOBALS['send_email']=1;
$GLOBALS['email_database_server']='11.207.1.2';
$GLOBALS['proposal_type']=array(
                  'PG Dissertation',
                  'ICMR STS',
                  'Research Project',
                  'Poster/Paper',
                  'PhD',
                  'Clinical Trial',
                  'Other'
                );


$GLOBALS['attachment_type']=array(
                  '',
                  'Covering Letter',
                  'Permission from MS',
                  'Permission from Dean',
                  'Permission from collaborator',
                  'Permission from resource-site',
                  'Departmental Minutes',
                  'Protocol',
                  'Data collection questionnaire',
                  'Assesment tools',
                  'Patient information sheet',
                  'Patient informed consent form',
                  'References',
                  'Undertakings',               
                  'Other'
                );
$GLOBALS['Department_Type']=array(
                  '',
                  'Anatomy',
                  'Anesthesiology',
                  'Biochemistry',
                  'Burns and Plastic Surgery',
                  'Community Medicine',
                  'Dentistry',
                  'Emergency Medicine',
                  'ENT',
                  'Forensic Medicine',
                  'General surgery',
                  'IHBT',
                  'Medicine',
                  'Microbiology',
                  'Neurology',
                  'Neurosurgery',
                  'Obstetrics and Gynecology',
                  'Ophthalmology',
                  'Orthopedics',
                  'Paediatrics',
                  'Pathology',
                  'Pharmacology',
                  'Physiology',
                  'Psychiatry',
                  'Radiology',
                  'Respiratory Medicine',
                  'Skin and VD'               
                );
                
$GLOBALS['applicable_tags']=array(
                  'funded',
                  'educational',
                  'research_at_other_institutes',
                  'researcher_from_other_institutes',
                  'expediate_request',
                  'review_exemption_request'
                  );
                  
$GLOBALS['upload_type_help']=
'<b>Following documents in only signed PDF</b><br>
<hr>
Covering Letter<br>
Permission from MS<br>
Permission from Dean<br>
Permission from collaborator<br>
Permission from resource-site<br>
Departmental Minutes<br>
References<br>
Undertakings<br><hr>
<b>Following documents preferably in word processor/ spreadsheet / presentation format</b><br>
<hr>
Protocol<br>
Data collection questionnaire<br>
Assesment tools<br>
Patient information sheet<br>
Patient informed consent form<br>
(To write in ગુજરાતી and हिन्दी Link:-<a target=_blank href="http:\/\/'.$_SERVER['HTTP_HOST'].'/ucode">click here</a>)<br>
<hr>
<b>Following documents in any Suitable format</b><br>
<hr>
Others<br>';
$GLOBALS['upload_button_help']='data can not be saved if ...<br>1)file is not given<br>2)file size is zero <br> 3)Type is not selected ';


$GLOBALS['vulnerable_array']=['Non-vulnerable','pregnant woman','children','student','institutionalized person','elderly','person with HIV','person with psychiatric diseases','none of the above'];

?>
