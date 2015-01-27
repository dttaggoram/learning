-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jan 26, 2015 at 12:39 AM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `Class`
--

CREATE TABLE `Class` (
`classid` int(11) NOT NULL,
  `classcode` varchar(3) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Class`
--

INSERT INTO `Class` (`classid`, `classcode`) VALUES
(1, 'DA1'),
(2, 'DA2');

-- --------------------------------------------------------

--
-- Table structure for table `ClassPapers`
--

CREATE TABLE `ClassPapers` (
  `classid` int(11) NOT NULL,
  `paperid` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ClassPapers`
--

INSERT INTO `ClassPapers` (`classid`, `paperid`, `date`) VALUES
(1, 1, '2015-01-19'),
(1, 2, '2015-01-24'),
(2, 1, '2015-01-25');

-- --------------------------------------------------------

--
-- Table structure for table `ClassQuestionsLearnt`
--

CREATE TABLE `ClassQuestionsLearnt` (
  `classid` int(11) NOT NULL,
  `questionid` int(11) NOT NULL,
  `paperid` int(11) NOT NULL,
  `learnt` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ClassQuestionsLearnt`
--

INSERT INTO `ClassQuestionsLearnt` (`classid`, `questionid`, `paperid`, `learnt`) VALUES
(1, 1, 1, 1),
(1, 2, 1, 1),
(2, 1, 1, 1),
(2, 2, 1, 0),
(1, 3, 2, 1),
(1, 4, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `GradeBoundaries`
--

CREATE TABLE `GradeBoundaries` (
  `paperid` int(11) NOT NULL,
  `boundary` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Marks`
--

CREATE TABLE `Marks` (
  `questionid` int(11) NOT NULL,
  `studentid` int(11) NOT NULL,
  `mark` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Marks`
--

INSERT INTO `Marks` (`questionid`, `studentid`, `mark`) VALUES
(1, 1, 3),
(2, 1, 1),
(3, 1, 3),
(4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Papers`
--

CREATE TABLE `Papers` (
`paperid` int(11) NOT NULL,
  `papername` varchar(20) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Papers`
--

INSERT INTO `Papers` (`paperid`, `papername`, `date`) VALUES
(1, 'Nov 2012 Higher', '2012-11-01'),
(2, 'March 2013 Higher', '2013-03-01');

-- --------------------------------------------------------

--
-- Table structure for table `PostSurvey`
--

CREATE TABLE `PostSurvey` (
  `paperid` int(11) NOT NULL,
  `studentid` int(11) NOT NULL,
  `bestarea` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PostSurvey`
--

INSERT INTO `PostSurvey` (`paperid`, `studentid`, `bestarea`) VALUES
(1, 1, 'algebra');

-- --------------------------------------------------------

--
-- Table structure for table `PreSurvey`
--

CREATE TABLE `PreSurvey` (
  `paperid` int(11) NOT NULL,
  `studentid` int(11) NOT NULL,
  `bestarea` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `PreSurvey`
--

INSERT INTO `PreSurvey` (`paperid`, `studentid`, `bestarea`) VALUES
(1, 1, 'algebra'),
(2, 1, 'ssm');

-- --------------------------------------------------------

--
-- Table structure for table `Questions`
--

CREATE TABLE `Questions` (
`questionid` int(11) NOT NULL,
  `questionnumber` varchar(10) NOT NULL,
  `paperid` int(11) NOT NULL,
  `topicid` int(11) NOT NULL,
  `marks` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Questions`
--

INSERT INTO `Questions` (`questionid`, `questionnumber`, `paperid`, `topicid`, `marks`) VALUES
(1, 'CALC1', 1, 0, 4),
(2, 'CALC2', 1, 0, 2),
(3, 'CALC1', 2, 5, 3),
(4, 'NCAL1', 2, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Student`
--

CREATE TABLE `Student` (
`studentid` int(11) NOT NULL,
  `user` varchar(5) NOT NULL,
  `classid` int(11) NOT NULL,
  `sen` varchar(1) DEFAULT NULL,
  `gt` varchar(1) DEFAULT NULL,
  `fsm` varchar(1) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `lastlog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Student`
--

INSERT INTO `Student` (`studentid`, `user`, `classid`, `sen`, `gt`, `fsm`, `gender`, `lastlog`) VALUES
(1, 'DA101', 1, NULL, NULL, NULL, NULL, '2015-01-19 18:54:54'),
(2, 'DA102', 1, '1', '1', '1', 'M', '2015-01-25 23:01:37');

-- --------------------------------------------------------

--
-- Table structure for table `TeacherClasses`
--

CREATE TABLE `TeacherClasses` (
  `teacherid` int(11) NOT NULL,
  `classid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `TeacherClasses`
--

INSERT INTO `TeacherClasses` (`teacherid`, `classid`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Teachers`
--

CREATE TABLE `Teachers` (
`teacherid` int(11) NOT NULL,
  `user` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Teachers`
--

INSERT INTO `Teachers` (`teacherid`, `user`) VALUES
(1, 'david.tagg-oram');

-- --------------------------------------------------------

--
-- Table structure for table `Topics`
--

CREATE TABLE `Topics` (
`topicid` int(11) NOT NULL,
  `area` varchar(20) NOT NULL,
  `supertopic` varchar(50) DEFAULT NULL,
  `topic` varchar(100) NOT NULL,
  `grade` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Topics`
--

INSERT INTO `Topics` (`topicid`, `area`, `supertopic`, `topic`, `grade`) VALUES
(1, 'Number', '', 'Factors, Multiples and Primes', 5),
(2, 'Number', NULL, 'Evaluate Powers', 5),
(3, 'Number', 'Fractions', 'Equivalent Fractions', 5),
(4, 'Number', 'Fractions', 'Simplification of Fractions', 5),
(5, 'Number', 'Fractions', 'Ordering Fractions', 5),
(6, 'Number', NULL, 'Value for Money', 5),
(7, 'Number', 'Percentages', 'Find a Percentage with a Calculator', 5),
(8, 'Number', 'Percentages', 'Find a Percentage Without a Calculator', 5),
(9, 'Number', 'Percentages', 'Change to a Percentage With a Calculator', 5),
(10, 'Number', 'Percentages', 'Change to a Percentage Without a Calculator', 5),
(11, 'Number', 'Fractions', 'Finding the Fraction of an Amount', 5),
(12, 'Number', 'Fractions', 'Addition of Fractions', 5),
(13, 'Number', 'Fractions', 'Subtraction of Fractions', 5),
(14, 'Number', 'Fractions', 'Multiplication of Fractions', 5),
(15, 'Number', 'Fractions', 'Division of Fractions', 5),
(16, 'Number', 'Fractions', 'Changing Fractions to Decimals', 5),
(17, 'Number', NULL, 'BODMAS/BIDMAS', 5),
(18, 'Number', NULL, 'Long Multiplication with Decimals', 5),
(19, 'Number', NULL, 'Ratio', 5),
(20, 'Number', NULL, 'Proportion Recipe Type Questions', 5),
(21, 'Number', NULL, 'Hard Calculator Questions', 5),
(22, 'Number', NULL, 'Money Exchange Questions', 5),
(23, 'Number', 'Percentages', 'Increase/Decrease Percentages', 4),
(24, 'Number', NULL, 'Ratio', 0),
(25, 'Number', NULL, 'Products of Prime Factors', 4),
(26, 'Number', NULL, 'HCF', 4),
(27, 'Number', NULL, 'LCM', 4),
(28, 'Number', NULL, 'Using Place Value', 4),
(29, 'Number', 'Fractions', 'Recurring Decimals into Fractions', 0),
(30, 'Number', 'Negatives', 'Addition and Subtraction of Negatives', 4),
(31, 'Number', 'Negatives', 'Multiplication and Division or Negatives', 4),
(32, 'Number', 'Decimals', 'Division by Decimals', 4),
(33, 'Number', 'Estimation', 'Rounding to Significant Figures', 4),
(34, 'Number', 'Estimation', 'Estimating Answers', 4),
(35, 'Number', 'Percentages', 'Increase/Decrease Percentages', 4),
(36, 'Number', NULL, 'Ratio', 4),
(37, 'Number', NULL, 'Products of Prime Factors', 4),
(38, 'Number', NULL, 'HCF', 4),
(39, 'Number', NULL, 'LCM', 4),
(40, 'Number', NULL, 'Using Place Value', 4),
(41, 'Number', 'Fractions', 'Recurring Decimals into Fractions', 4),
(42, 'Number', 'Negatives', 'Addition and Subtraction of Negatives', 4),
(43, 'Number', 'Negatives', 'Multiplication and Division or Negatives', 4),
(44, 'Number', 'Decimals', 'Division by Decimals', 4),
(45, 'Number', 'Estimation', 'Rounding to Significant Figures', 4),
(46, 'Number', 'Estimation', 'Estimating Answers', 4),
(47, 'Algebra', NULL, 'Generate a Sequence From the nth Term', 5),
(48, 'Algebra', NULL, 'Substitution', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Class`
--
ALTER TABLE `Class`
 ADD PRIMARY KEY (`classid`), ADD UNIQUE KEY `classcode` (`classcode`);

--
-- Indexes for table `Marks`
--
ALTER TABLE `Marks`
 ADD KEY `questionid` (`questionid`), ADD KEY `studentid` (`studentid`);

--
-- Indexes for table `Papers`
--
ALTER TABLE `Papers`
 ADD PRIMARY KEY (`paperid`);

--
-- Indexes for table `Questions`
--
ALTER TABLE `Questions`
 ADD PRIMARY KEY (`questionid`);

--
-- Indexes for table `Student`
--
ALTER TABLE `Student`
 ADD PRIMARY KEY (`studentid`), ADD UNIQUE KEY `studentid` (`studentid`), ADD UNIQUE KEY `user` (`user`);

--
-- Indexes for table `Teachers`
--
ALTER TABLE `Teachers`
 ADD PRIMARY KEY (`teacherid`);

--
-- Indexes for table `Topics`
--
ALTER TABLE `Topics`
 ADD PRIMARY KEY (`topicid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Class`
--
ALTER TABLE `Class`
MODIFY `classid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Papers`
--
ALTER TABLE `Papers`
MODIFY `paperid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Questions`
--
ALTER TABLE `Questions`
MODIFY `questionid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `Student`
--
ALTER TABLE `Student`
MODIFY `studentid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Teachers`
--
ALTER TABLE `Teachers`
MODIFY `teacherid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `Topics`
--
ALTER TABLE `Topics`
MODIFY `topicid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;