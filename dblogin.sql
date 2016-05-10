-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2016 at 03:05 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dblogin`
--
CREATE DATABASE `rollcall`;

USE `rollcall`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `rollcall`.`users` (
  `user_id` int(15) NOT NULL AUTO_INCREMENT,
  `id_number` int(15) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `bday` DATE NOT NULL,
  `begin_studying` DATE NOT NULL,
  `department` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `joining_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lecturer` BOOLEAN NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnonDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rollcall`.`admins` (
  `admin_id` int(15) NOT NULL AUTO_INCREMENT,
  `email` varchar(40) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `joining_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnonDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rollcall`.`courses`(
  `course_id` INT(15) NOT NULL AUTO_INCREMENT,
  `course_name` VARCHAR( 255 ) NOT NULL ,
  `lecturer` VARCHAR( 255 ) NOT NULL,
  `day_of_week` INT( 1 ) NOT NULL ,
  `start` TIME NOT NULL,
  `end` TIME NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnonDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `rollcall`.`cameras`(
  `camera` INT(10) NOT NULL,
  `day_of_week` int(10) NOT NULL ,
  `course_id` int(10) NOT NULL,
  `open_time` TIME NOT NULL,
  `close_time` TIME NOT NULL
) ENGINE=InnonDB DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `rollcall`.`students_courses`(
  `student` INT(10) NOT NULL,
  `course` INT(10) NOT NULL,
  `day_of_week` INT(10) NOT NULL,
  `start` TIME NOT NULL,
  `end` TIME NOT NULL,
  FOREIGN KEY (student) REFERENCES users(user_id),
  FOREIGN KEY (course) REFERENCES courses(course_id),
  ) ENGINE=InnonDB CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `rollcall`.`presence`(
  `student` INT(10) NOT NULL,
  `course` INT(10) NOT NULL,
  FOREIGN KEY (student) REFERENCES users(user_id),
  FOREIGN KEY (course) REFERENCES courses(course_id),
  `date` timestamp NOT NULL
) ENGINE=InnonDB CHARSET=latin1;

INSERT INTO `rollcall`.`users` (
`user_id`,
`id_number`,
`first_name`,
`last_name`,
`email`,
`bday`,
`begin_studying`,
`department`,
`pass`,
`joining_date`,
`admin`
)
VALUES
(NULL,'300804671','Al','Sade','alsade15@gmail.com','2004-03-03','2004-03-03','Economics','$2y$10$V5LZM6mbjNO545dBjSK7WuYyLLgGt83tqfEz/zbTZYTQHYl.KAct.',CURRENT_TIMESTAMP,0),
(NULL,'38473987','Alon','Cohen','alon@gmail.com','1994-11-04','2004-03-05','Arts','373923hdf',CURRENT_TIMESTAMP,0)
 ;

INSERT INTO `rollcall`.`courses` (
  `course_id`,
  `course_name`,
  `lecturer`,
  `day_of_week`,
  `start`,
  `end`
 )
  VALUES
 ('1111', 'Mathematics', '1000', '2', '10:00:00', '12:00:00'),
 ('2222', 'Physics', '1100', '1', '11:00:00', '15:00:00'),
 ('3333', 'Computer Science', '1122', '1', '14:00:00', '16:00:00'),
 ('4444', 'English', '1123', '4', '10:00:00', '12:00:00');


INSERT INTO  `rollcall`.`cameras` (
`camera` ,
`day_of_week` ,
`course_id` ,
`open_time` ,
`close_time`
)
VALUES
('101',  '1',  '1111',  '10:00:00',  '14:00:00'),
('101',  '1',  '2222',  '15:00:00',  '16:30:00'),
('101',  '1',  '3333',  '17:00:00',  '19:00:00'),
('101',  '2',  '1111',  '10:00:00',  '12:00:00'),
('101',  '2',  '2222',  '15:00:00',  '18:00:00'),
('101',  '2',  '4444',  '19:00:00',  '20:30:00'),
('101',  '4',  '1111',  '08:00:00',  '10:00:00'),
('101',  '4',  '2222',  '11:00:00',  '14:00:00'),
('101',  '4',  '3333',  '15:00:00',  '16:30:00'),
('101',  '4',  '4444',  '17:00:00',  '19:00:00'),
('101',  '5',  '1111',  '10:00:00',  '11:30:00'),
('101',  '5',  '2222',  '12:00:00',  '13:30:00'),
('101',  '5',  '4444',  '14:00:00',  '17:00:00'),
('101',  '5',  '3333',  '17:00:00',  '20:00:00'),
('101',  '6',  '1111',  '08:00:00',  '10:00:00');



INSERT INTO `rollcall`.`students_courses` (
  `student`,
   `course`
 )
VALUES
('1', '1111'),
('1', '2222'),
('1', '3333'),
('1', '4444'),
('2', '1111'),
('2', '3333'),
('2', '4444');

INSERT INTO  `rollcall`.`presence` (
`student` ,
`course` ,
`date`
)
VALUES
('1','1111','2016-03-17 10:25:00'),
('1',  '2222',  '2016-03-14 12:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
