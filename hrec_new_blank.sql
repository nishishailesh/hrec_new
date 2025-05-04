-- MariaDB dump 10.19  Distrib 10.11.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: hrec_new
-- ------------------------------------------------------
-- Server version	10.11.6-MariaDB-0+deb12u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` bigint(20) NOT NULL,
  `name` varchar(15) NOT NULL,
  `password` varchar(300) NOT NULL,
  `expirydate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachment`
--

DROP TABLE IF EXISTS `attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposal_id` int(11) NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `date_time` datetime NOT NULL,
  `attachment` mediumblob NOT NULL,
  `attachment_name` varchar(300) NOT NULL,
  `forwarded` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`),
  CONSTRAINT `attachment_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17332 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proposal_id` int(11) NOT NULL,
  `reviewer_id` bigint(15) NOT NULL,
  `comment` varchar(5000) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `attachment` mediumblob DEFAULT NULL,
  `attachment_name` varchar(300) DEFAULT NULL,
  `forwarded` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_id` (`proposal_id`,`reviewer_id`),
  KEY `reviewer_id` (`reviewer_id`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`proposal_id`, `reviewer_id`) REFERENCES `decision` (`proposal_id`, `reviewer_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28575 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `decision`
--

DROP TABLE IF EXISTS `decision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `decision` (
  `proposal_id` int(11) NOT NULL,
  `reviewer_id` bigint(15) NOT NULL,
  `approval` int(11) DEFAULT NULL,
  PRIMARY KEY (`proposal_id`,`reviewer_id`),
  KEY `reviewer_id` (`reviewer_id`),
  CONSTRAINT `decision_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposal` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `decision_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dept_user`
--

DROP TABLE IF EXISTS `dept_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dept_user` (
  `id` varchar(40) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(500) NOT NULL,
  `expirydate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_content` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `proposal`
--

DROP TABLE IF EXISTS `proposal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proposal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` bigint(15) NOT NULL,
  `proposal_name` varchar(500) NOT NULL,
  `type` varchar(20) NOT NULL,
  `interventional_research` varchar(20) DEFAULT NULL,
  `research_on_vulnerable_subjects` varchar(200) DEFAULT NULL,
  `applicable_tags` varchar(200) DEFAULT NULL,
  `description_of_study_subjects` varchar(1000) DEFAULT NULL,
  `additional_remarks` varchar(500) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `guide` varchar(50) DEFAULT NULL,
  `researcher_email_id` varchar(100) DEFAULT NULL,
  `researcher_mobile_no` varchar(30) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `Department` varchar(25) DEFAULT NULL,
  `status` enum('001.applied','010.srcm_assigned','020.srcm_approved','031.sent_to_ecms1','041.ecm1_assigned','061.sent_to_committee1','071.ecms1_approved','032.sent_to_ecms2','042.ecm2_assigned','062.sent_to_committee2','072.ecms2_approved','033.sent_to_ecms3','043.ecm3_assigned','063.sent_to_committee3','073.ecms3_approved') DEFAULT NULL,
  `status2` enum('001.applied','010.srcm_assigned','020.srcm_approved','030.sent_to_ecms','040.ecm_assigned','060.sent_to_committee','070.ecms_approved') DEFAULT NULL,
  `forwarded` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `applicant_id` (`applicant_id`),
  CONSTRAINT `proposal_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1418 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(15) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `expirydate` date DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `subtype` enum('','UG','PG','Faculty','Intern','PHD') DEFAULT NULL,
  `Institute` varchar(50) DEFAULT NULL,
  `year_of_admission` varchar(4) DEFAULT NULL,
  `department` enum('N/A','Anatomy','Anesthesiology','Biochemistry','Burns and Plastic Surgery','Cardiology','Community Medicine','Dentistry','Dermatology','Emergency Medicine','Forensic Medicine','General Surgery','Immunohematology and Blood Transfusion','Infectious Disease','Medicine','Microbiology','Nephrology','Neurology','Neurosurgery','Obstetrics and Gynacology','Opthalmology','Orthopaedics','Otorhinolaryngiology','Paediatrics','Pathology','Peadiatric Surgery','Pharmacology','Physiology','Psychiatry','Pulmonary Medicine','Radiology','Radiotherapy','Urology','PSM','ENT','Other') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `group` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user.notuse`
--

DROP TABLE IF EXISTS `user.notuse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user.notuse` (
  `id` bigint(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `expirydate` date NOT NULL,
  `type` enum('researcher','srcms','srcm','ecms','ecm') NOT NULL,
  `subtype` enum('','UG','PG','Faculty','Intern','PHD') NOT NULL,
  `Institute` varchar(50) NOT NULL,
  `year_of_admission` int(11) DEFAULT NULL,
  `department` enum('N/A','Anatomy','Anesthesiology','Biochemistry','Burns and Plastic Surgery','Cardiology','Community Medicine','Dentistry','Dermatology','Emergency Medicine','Forensic Medicine','General Surgery','Immunohematology and Blood Transfusion','Infectious Disease','Medicine','Microbiology','Nephrology','Neurology','Neurosurgery','Obstetrics and Gynacology','Opthalmology','Orthopaedics','Otorhinolaryngiology','Paediatrics','Pathology','Peadiatric Surgery','Pharmacology','Physiology','Psychiatry','Pulmonary Medicine','Radiology','Radiotherapy','Urology','PSM','Skin & V.D.','ENT','Surgery','Other') NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `view_info_data1`
--

DROP TABLE IF EXISTS `view_info_data1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view_info_data1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info` varchar(100) NOT NULL,
  `Fields` varchar(2000) NOT NULL,
  `sql` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-04 22:20:48
