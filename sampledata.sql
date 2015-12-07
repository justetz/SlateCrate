SET FOREIGN_KEY_CHECKS = 0; 
TRUNCATE TABLE `categories`;
TRUNCATE TABLE `users`;
TRUNCATE TABLE `links`;
SET FOREIGN_KEY_CHECKS = 1;

--
-- Sample data for `categories`
--

INSERT INTO `categories` (`category_id`, `title`, `links`, `prefix`, `rcs_id`, `creation_date`) VALUES
(1, 'Web Systems Development', 0, 'CSCI', 'PLOTKR2', '2015-08-31'),
(2, 'Introduction to ITWS', 0, 'ITWS', 'PLOTKR2', '2015-12-06'),
(3, 'Data Structures', 0, 'CSCI', 'CUTLEB', '2015-12-06'),
(4, 'Intro to Management', 0, 'MGMT', 'WRIGHF', '2015-12-06'),
(5, 'Calculus I', 0, 'MATH', 'MCLAUGH', '2015-12-06'),
(6, 'Intro to Differential Equations', 0, 'MATH', 'BOUDJM', '2015-12-06'),
(7, 'Sport Psychology', 0, 'PSYC', 'HUBBEC', '2015-12-06'),
(8, 'Intro to Visual Communications', 0, 'COMM', 'MIYAMP', '2015-12-06'),
(9, 'Principles of Software', 0, 'CSCI', 'MILANA', '2015-12-06'),
(10, 'Physics I', 0, 'PHYS', 'WASHIM', '2015-12-06'),
(11, 'Electric Circuits', 0, 'ECSE', 'PROFTEST', '2015-12-06'),
(12, 'Rhetoric and Writing', 0, 'WRIT', 'PROFTEST', '2015-12-06'),
(13, 'Science, Tech and Society', 0, 'STSS', 'PROFTEST', '2015-12-06'),
(14, 'Stress and the Brain', 0, 'PSYC', 'PROFTEST', '2015-12-06'),
(15, 'Mech Properties of Materials', 0, 'MTLE', 'ETZINJ', '2015-12-06'),
(16, 'Fundamentals of Flight', 0, 'MANE', 'PROFTEST', '2015-12-06'),
(17, 'Intro to Geochemistry', 0, 'ERTH', 'PROFTEST', '2015-12-06'),
(18, 'Engineering Dynamics', 0, 'ENGR', 'PROFTEST', '2015-12-06'),
(19, 'Intro to Engineering Design', 0, 'ENGR', 'ETZINJ', '2015-12-06'),
(20, 'Game Mechanics', 0, 'COGS', 'PROFTEST', '2015-12-06'),
(21, 'Experimental Soil Mechanics', 0, 'CIVL', 'PROFTEST', '2015-12-06'),
(22, 'Transport Phenomena I', 0, 'CHME', 'PROFTEST', '2015-12-06');


--
-- Test data for `links`
--

INSERT INTO `links` (`link_id`, `link`, `rcs_id`, `category_id`, `creation_date`, `title`, `score`) VALUES
(1, 'http://www.cs.rpi.edu/academics/courses/fall14/csci1200/hw/01_moire_strings/hw.pdf', 'ETZINJ', 3, '2015-12-07', 'Homework 1, Fall 2014', 2),
(2, 'http://www.cs.rpi.edu/academics/courses/fall14/csci1200/hw/02_bowling_classes/hw.pdf', 'TESTSTUDENT', 3, '2015-12-07', 'Homework 2, Fall 2014', 8),
(3, 'http://www.cs.rpi.edu/academics/courses/fall14/csci1200/lectures/10_linked_lists_I.pdf', 'TESTSTUDENT', 3, '2015-12-07', 'Lecture 10: Linked Lists', -2),
(6, 'http://www.cs.rpi.edu/academics/courses/fall15/csci1200/tests/test_solutions3.pdf', 'ETZINJ', 3, '2015-12-07', 'Fall 2015, Test 3 Solutions', 11),
(7, 'http://rpistudygroup.org/MATH%20-%20Mathematics/MATH%201010%20Calculus%20I/Exams/MATH_1010-_Exam_1-_Fall_2010.pdf', 'TESTSTUDENT', 5, '2015-12-07', 'Fall 2010 Exam 1 Solutions', 0),
(8, 'http://rpistudygroup.org/MATH%20-%20Mathematics/MATH%201010%20Calculus%20I/Exams/MATH_1010-_Quiz_2-_Fall_2010.pdf', 'ETZINJ', 5, '2015-12-07', 'Fall 2010 Quiz 2 Solutions', 0),
(9, 'http://tutorial.math.lamar.edu/getfile.aspx?file=B,42,N', 'TESTSTUDENT', 5, '2015-12-07', 'Limits Cheat Sheet (dl)', 0),
(10, 'http://rpistudygroup.org/ECSE%20-%20Electrical%2C%20Computer%2C%20and%20Systems%20Engineering/ECSE%202010%20Electric%20Circuits/Misc/Exam_1_Crib_Sheet.pdf', 'TESTSTUDENT', 11, '2015-12-07', 'Exam 1 Crib Sheet', 0),
(11, 'http://rpistudygroup.org/ECSE%20-%20Electrical%2C%20Computer%2C%20and%20Systems%20Engineering/ECSE%202010%20Electric%20Circuits/Misc/Exam_2_Crib_Sheet.pdf', 'TESTSTUDENT', 11, '2015-12-07', 'Exam 2 Crib Sheet', 0),
(12, 'http://www.htmlgoodies.com/beyond/php/article.php/3907521', 'ETZINJ', 2, '2015-12-07', 'Tips on using PHP', 1),
(13, 'http://rpistudygroup.org/ENGR%20-%20Core%20Engineering/ENGR%202090%20Engineering%20Dynamics/Exams/exam1s10.pdf', 'TESTSTUDENT', 18, '2015-12-07', 'Spring ''10 Exam 1 Solution', 0),
(14, 'http://rpistudygroup.org/ENGR%20-%20Core%20Engineering/ENGR%202090%20Engineering%20Dynamics/Exams/Fall2015-Test1.pdf', 'ETZINJ', 18, '2015-12-07', 'Fall 2015 Test 1 Solutions', 0);


--
-- Test data for `users`
--

INSERT INTO `users` (`rcs_id`, `isadmin`) VALUES ('VILLAT2', 1);
INSERT INTO `users` (`rcs_id`, `isadmin`) VALUES ('ETZINJ', 1);
INSERT INTO `users` (`rcs_id`, `isadmin`) VALUES ('LIMAA', 1);
INSERT INTO `users` (`rcs_id`, `isadmin`) VALUES ('PLOTKR2', 1);
