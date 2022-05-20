-- MySQL dump 10.16  Distrib 10.1.26-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: research
-- ------------------------------------------------------
-- Server version	10.1.26-MariaDB-0+deb9u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `view_info_data`
--

DROP TABLE IF EXISTS `view_info_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view_info_data` (
  `id` int(11) NOT NULL,
  `info` varchar(100) NOT NULL,
  `Fields` varchar(2000) NOT NULL,
  `sql` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `view_info_data`
--

LOCK TABLES `view_info_data` WRITE;
/*!40000 ALTER TABLE `view_info_data` DISABLE KEYS */;
INSERT INTO `view_info_data` VALUES (0,'total  all proposal','','SELECT\r\n    Type,\r\n    COUNT(*) AS \"COUNT(*)\"\r\n  FROM proposal\r\n  GROUP BY type\r\n  ORDER BY id ASC, \"COUNT(*)\" DESC'),(1,'Get Metting Agenda','','select * from proposal where status=\'060.ecm_approved\' order by date_time Asc'),(2,'Application By Status','Status:<select name=__p1> <option>001.applied</option> <option>010.srcm_assigned</option> <option>020.srcm_approved</option> <option>030.sent_to_ecms</option> <option>040.ecm_assigned</option> <option>060.sent_to_committee</option> <option>070.ecms_approved</option> </select>','select proposal.id proposal_id, proposal_name,applicant_id Guide_ID, name Guide_Name, guide researcher, user.department,if(forwarded=0,\'Forwarded\',\'Not Forwarded\') Forward_Status,status,proposal.type, date_time from proposal,user where status =\'__p1\'  and proposal.applicant_id=user.id\r\nORDER BY user.department'),(3,'Number of Application with status','','(SELECT STATUS,count(id) as Total FROM proposal group by status order by status )\r\nunion all\r\nSELECT \'<span class=\"text-primary\">Grand Total&#128531;</span>\',\r\nconcat(\'<span class=\"text-primary\">\',count(id),\'</span>\') from proposal'),(4,'Number of Application with status (PG Dissertation)','','select\nCOUNT(if(status=\'001.applied\',1,NULL)) as applied,\nCOUNT(if(status=\'010.srcm_assigned\',1,NULL)) as srcm_assigned,\nCOUNT(if(status=\'020.srcm_approved\',1,NULL)) as srcm_approved ,\nCOUNT(if(status=\'030.sent_to_ecms\',1,NULL)) as sent_to_ecms,\nCOUNT(if(status=\'040.ecm_assigned\',1,NULL)) as ecm_assigned,\nCOUNT(if(status=\'060.sent_to_committee\',1,NULL)) as sent_to_committee,\nCOUNT(if(status=\'070.ecms_approved\',1,NULL)) as ecms_approved,\nCOUNT(id) as Total\nfrom proposal where type=\'PG Dissertation\''),(5,'Bird\'s Eye View (Reviewer Context)','<input type=text name=__p1 placeholder=reviewer_name title=\'write few letters of name\'>','select proposal.id proposal_id, concat(substr(proposal.proposal_name,1,40),\"...\") title, guide Researcher, year admission, status, user.name Reviewer_Name, user.department Reviewer_Dept, user.type Reviewer_Type,decision.approval ,if(count(comment.id)=0,\"<h2 style=\'color:red\'>0</h2>\",count(comment.id)) commentNo from proposal left join decision on proposal.id=decision.proposal_id left join comment on decision.proposal_id=comment.proposal_id and decision.reviewer_id=comment.reviewer_id left join user on decision.reviewer_id=user.id where proposal.applicant_id!=decision.reviewer_id and user.name like \'%__p1%\' group by proposal.id,decision.reviewer_id'),(6,'reviewer load (SRCM)','','select count(name) total ,name from user,decision where user.type=\"srcm\" and user.id=decision.reviewer_id group by user.name order by total'),(7,'Bird\'s Eye View','','select proposal.id proposal_id, concat(substr(proposal.proposal_name,1,40),\"...\") title, guide Researcher, year admission, status, user.name Reviewer_Name, user.department Reviewer_Dept, user.type Reviewer_Type,decision.approval ,if(count(comment.id)=0,\"<h2 style=\'color:red\'>0</h2>\",count(comment.id)) commentNo from proposal left join decision on proposal.id=decision.proposal_id left join comment on decision.proposal_id=comment.proposal_id and decision.reviewer_id=comment.reviewer_id left join user on decision.reviewer_id=user.id where proposal.applicant_id!=decision.reviewer_id group by proposal.id,decision.reviewer_id'),(8,'List of reviewers','','select id,name,department,type from user where user.type!=\'researcher\' order by type ,name'),(9,'Bird\'s Eye View (detailed)','','select proposal.id proposal_id, concat(substr(proposal.proposal_name,1,40),\"...\") title,proposal.date_time Proposal_Date, guide Researcher, year, user.department , status, user.name Reviewer_Name, user.id Reviewer_ID, user.type Reviewer_Type,decision.approval ,count(comment.id) commentNo from proposal left join decision on proposal.id=decision.proposal_id left join comment on decision.proposal_id=comment.proposal_id and decision.reviewer_id=comment.reviewer_id left join user on decision.reviewer_id=user.id where proposal.applicant_id!=decision.reviewer_id group by proposal.id,decision.reviewer_id'),(10,'All PG Dissertation  (Year wise and Department wise)Count','<input type=text name=__p1 placeholder=year title=\'write year\'>','select  user.department, proposal.year, count(proposal.id) as Total_Proposal from proposal,user where proposal.applicant_id=user.id and proposal.type=\'PG Dissertation\' and proposal.year like \'%__p1%\' group by user.department,proposal.year order by user.department'),(11,'Year wise All PG Dissertation','<input type=text name=__p1 placeholder=year title=\'write year\'>','SELECT user.department as Department,proposal.year as Year,proposal.id as id,proposal.guide as \"PG Researcher\" ,user.name as \"PG Guide\",proposal.status as Status FROM `proposal`,`user` WHERE proposal.applicant_id=user.id and proposal.type=\'PG Dissertation\' and proposal.year like \'%__p1%\'  ORDER by user.department'),(12,'PG Dissertation Proposal Name','','SELECT proposal.id,	UCASE(proposal.proposal_name) AS Proposal_Name,proposal.type,proposal.guide,user.Department,status FROM `proposal`,`user` WHERE  proposal.applicant_id=user.id and proposal.type =\'PG Dissertation\' ORDER by Department ASC'),(13,'','','SELECT u.email,GROUP_CONCAT(c.reviewer_id), GROUP_CONCAT(concat(\'(\',c.proposal_id,\')\',concat(\'(\',p.proposal_Name,\')\\n\',c.comment,\'\\n\'))) as comment\r\nfrom \r\ncomment c,proposal p,user u\r\nwhere c.proposal_id=p.id and u.id=p.applicant_id  and c.date_time like concat(\'%\',substring(now(),1,10),\'%\') \r\ngroup by c.proposal_id'),(14,'Proposal where non-SRCMS have approved','','SELECT proposal.id,proposal.proposal_name,sum(decision.approval)\r\nfrom proposal,decision,user\r\nWHERE\r\n    proposal.id=decision.proposal_id AND\r\n    decision.reviewer_id=user.id AND\r\n    proposal.status=\'010.srcm_assigned\' \r\n    \r\n    \r\nGROUP by proposal.id\r\nhaving sum(decision.approval)>1'),(15,'ICMR STS  Proposal Name','','SELECT proposal.id,	UCASE(proposal.proposal_name) AS Proposal_Name,proposal.type,proposal.guide,user.Department,status FROM `proposal`,`user` WHERE  proposal.applicant_id=user.id and proposal.type =\'ICMR STS\' ORDER by Department ASC');
/*!40000 ALTER TABLE `view_info_data` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-13 22:29:59
