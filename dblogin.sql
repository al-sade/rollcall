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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(15) NOT NULL AUTO_INCREMENT,
  `id_number` int(15) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `joining_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnonDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `lecturers` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_number` int(15) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `joining_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnonDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `courses`(
  `course_id` INT(15) NOT NULL AUTO_INCREMENT,
  `course_name` VARCHAR( 255 ) NOT NULL ,
  `lecturer` VARCHAR( 255 ) NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnonDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `cameras`(
  `camera` INT(10) NOT NULL,
  `day_of_week` int(10) NOT NULL ,
  `course_id` int(10) NOT NULL,
  `open_time` TIME NOT NULL,
  `close_time` TIME NOT NULL
) ENGINE=InnonDB DEFAULT CHARSET=latin1;


  CREATE TABLE IF NOT EXISTS `students_courses`(
    `student` INT(10) NOT NULL,
    `course` INT(10) NOT NULL,
    FOREIGN KEY (student) REFERENCES users(user_id),
    FOREIGN KEY (course) REFERENCES courses(course_id)
  ) ENGINE=InnonDB CHARSET=latin1;



INSERT INTO `courses` (
  `course_id`,
  `course_name`,
  `lecturer`
 )
  VALUES
 ('1111', 'Mathematics', '1000'),
 ('2222', 'Physics', '1100'),
 ('3333', 'Computer Science', '1122'),
 ('4444', 'English', '1123');


INSERT INTO  `cameras` (
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



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
