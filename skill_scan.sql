-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 06:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skill_scan`
--

-- --------------------------------------------------------

--
-- Table structure for table `extracted_information`
--

CREATE TABLE `extracted_information` (
  `Id` int(11) NOT NULL,
  `name` text NOT NULL,
  `skills` text NOT NULL,
  `project` text NOT NULL,
  `education` text NOT NULL,
  `experience` text NOT NULL,
  `ats` int(3) NOT NULL,
  `contactinfo` varchar(200) NOT NULL,
  `username` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `extracted_information`
--

INSERT INTO `extracted_information` (`Id`, `name`, `skills`, `project`, `education`, `experience`, `ats`, `contactinfo`, `username`) VALUES
(2, 'TEJASWI DUBAL', '[\"Ability to communicate effectively in Marathi and Hindi\",\"Knowledge of basic child care and hygiene practices\",\"Skilled in maintaining cleanliness and orderliness in school premises\",\"Punctual and reliable with a strong work ethic\"]', '[]', '[{\"Degree\":\"7th Standard (Passed)\",\"Dates\":\"Year of Completion: 1996\",\"Institution\":\"ZP School, Lodhawade, Satara District\"}]', '[{\"Title\":\"Executive Secretary\",\"Company\":\"Ingoude Company\",\"Description\":\"Manage the schedules and calendars of the CEO and other senior executives; Coordinate and schedule meetings and conferences with internal and external stakeholders; Prepare and distribute meeting agendas, minutes, and other relevant materials; Handle confidential information and documents with discretion and maintain their proper organization\",\"Dates\":\"2018 - Present\"},{\"Title\":\"Executive Assistant\",\"Company\":\"Wardiere Inc.\",\"Description\":\"Managed calendars, scheduled appointments, and arranged meetings and conferences; Prepared and distributed reports, presentations, and other materials; Handled confidential documents and maintained their proper organization; Coordinated travel arrangements and accommodations for executives and guests\",\"Dates\":\"2016 - 2018\"},{\"Title\":\"Executive Secretary Intern\",\"Company\":\"Aldenaire & Partners\",\"Description\":\"Assisted executive secretary in managing and coordinating schedules, meetings, and travel arrangements for senior executives; Conducted research and prepared reports on various topics related to the company\'s operations and industry trends; Provided administrative support, including answering phone calls, responding to emails, and preparing correspondence\",\"Dates\":\"2015 - 2016\"}]', 70, '\"+91 9326863200\"', 'tejaswi'),
(3, 'Amit Dubal', '[\"HTML5\",\"CSS\",\"PHP\",\"Tailwind CSS\",\"Microsoft Word\",\"MYSQL\",\"Teamwork\",\"Collaboration\",\"Marathi\",\"Hindi\",\"English (Basic)\"]', '[]', '[{\"Degree\":\"BSc. Computer Science\",\"Dates\":\"2023-26\",\"Institution\":\"MGM Collage of CS & IT Kamothe\"},{\"Degree\":\"Higher Secondary School Certification (Percentage: 64.67%)\",\"Dates\":\"2022-23\",\"Institution\":\"MPASC Collage, Panvel\"},{\"Degree\":\"Secondary School Certification (percentage: 77.80%)\",\"Dates\":\"2020-21\",\"Institution\":\"Sudhagad Education Society\'s Secondary & Higher Secondary School, Kalamboli\"}]', '[{\"Title\":\"web developer\",\"Company\":\"Unified Mentor\",\"Description\":\"Completed a solo internship focused on front-end development using HTML5, CSS, and basic PHP.\",\"Dates\":\"Aug 2024 - Oct 2024\"},{\"Title\":\"Student Council Co-Head\",\"Company\":\"MGM Collage of CS & IT\",\"Description\":\"Led student initiatives, coordinated events, and collaborated with faculty to support student activities and development.\",\"Dates\":\"March 2024 - Present\"},{\"Title\":\"Finance Department Head\",\"Company\":\"MGM Collage of CS & IT\",\"Description\":\"Managed budgeting and financial planning for student events, ensuring efficient use of funds and transparent reporting.\",\"Dates\":\"March 2024 - Present\"}]', 80, '\"8879325268\"', 'amit'),
(4, 'Atul Dattatray Dubal', '[\"HTML\",\"CSS\",\"Bootstrap\",\"Tailwind CSS\",\"JavaScript\",\"React JS\",\"Node JS\",\"PHP\",\"Android Java\",\"Python\",\"MySQL\",\"MongoDB\",\"Firebase\",\"Machine Learning\",\"Teamwork\",\"Multitasking\",\"Problem-Solving\",\"Collaboration\",\"Marathi (Fluent)\",\"Hindi (Intermediate)\",\"English (Basic)\"]', '[{\"Title\":\"E-Books Library Android Application\",\"Description\":\"It\'s an Android application. Where we can see various types of books such as all education books, engineering books, bsc, medical and so on. Also on this application Author\\/Publishers can make account by choosing some plan and paying some amount and can publish books free or paid category. Published(live) on Amazon AppStore. Skills: Java Android Development \\u00b7 Android Studio \\u2022 XML \\u00b7 Firebase \\u00b7 Mobile Application Development\"},{\"Title\":\"Advanced Notepad++\",\"Description\":\"Android Notepad Application which gives ability to user to create account on app and also access their notes on website. Published(live) on Amazon AppStore. Skills: Android Development \\u00b7 MySQL \\u00b7 JavaScript \\u00b7 XML\\u30fbHTML\\u30fbCascading Style Sheets (CSS) \\u00b7 HTML5 Java. PHP\"},{\"Title\":\"Maths Puzzles\",\"Description\":\"Android Application which is Developed By using #java & #xml. it helps you to check your Mathematical knowledge. It provides automatically generated quizzes. Published(live) on Amazon AppStore. Skills: Android Development Mobile Application Development XML Java\"},{\"Title\":\"Personal Portfolio Website\",\"Description\":\"personal portfolio website developed by using MERN stack. Skills: MySQL Bootstrap (Framework) \\u00b7 JavaScript \\u00b7 Cascading Style Sheets (CSS) \\u00b7 HTML5\\u00b7 React Js Tailwind CSS\"},{\"Title\":\"Hybrid Deep Learning Model for Phishing Website Detection Using Transfer Learning\",\"Description\":\"Developed a Hybrid Deep Learning Model for Phishing Website Detection using Transfer Learning, enhancing accuracy and security in identifying malicious sites.\"}]', '[{\"Degree\":\"Bachelor Of Engineering Electronics & Computer Science\",\"Dates\":\"2022-2026\",\"Institution\":\"PHCET | University of Mumbai\"},{\"Degree\":\"Higher Secondary School Certificate\",\"Dates\":\"2020-2022\",\"Institution\":\"Sudhagad Education Society\'s Secondary & Higher Secondary School, Mumbai, Maharashtra\"},{\"Degree\":\"Secondary School Certificate\",\"Dates\":\"2019-2020\",\"Institution\":\"Sudhagad Education Society\'s Secondary & Higher Secondary School, Mumbai, Maharashtra\"}]', '[{\"Title\":\"Research Intern\",\"Company\":\"India Internet Foundation\",\"Description\":\"Contributed to the development of the AIORI App and Website, leveraging Kotlin for Android and Python Django for backend services.\",\"Dates\":\"July 2025 - PRESENT\"},{\"Title\":\"Research Intern\",\"Company\":\"ISEA Summer School Internship - IIT Guwahati\",\"Description\":\"Worked on enhancing the SELAP IoT routing protocol using the NS-3 simulator. Gained hands-on experience in simulating wireless sensor networks (WSNs) and analyzing IoT security aspects like routing efficiency and secure data transmission.\",\"Dates\":\"Jun 2025 - July 2025\"},{\"Title\":\"Technical Coordinator\",\"Company\":\"Training & Placement Cell-PHCET\",\"Description\":\"This opportunity will allow me to contribute to shaping the placement landscape at PHCET, helping students connect with better career opportunities.\",\"Dates\":\"Feb 2025 - PRESENT\"},{\"Title\":\"Technical Head\",\"Company\":\"IETE Student Forum (ISF), PHCET\",\"Description\":\"Organized and managed the Tech Aspiria 2025 event under the IETE ISF Forum, overseeing event planning, coordination, and execution.\",\"Dates\":\"Feb 2024 - PRESENT\"},{\"Title\":\"Full Stack Developer\",\"Company\":\"Techplement\",\"Description\":\"Learned Various technologies like ReactJs, NodeJs, TailwindCSS etc. Make Group Project and Due to this, I know how to work as a team.\",\"Dates\":\"Jun 2024 - Jul 2024\"},{\"Title\":\"Android Developer Intern\",\"Company\":\"NullClass (Training + Internship)\",\"Description\":\"Learned Various technologies related to android development and develop mobile applications. During this Internship I developed a Netflix Clone Application With all features of real Netflix.\",\"Dates\":\"Dec 2023 - Feb 2024\"}]', 90, '\"+91 8928333079\"', 'atul');

-- --------------------------------------------------------

--
-- Table structure for table `registered_users`
--

CREATE TABLE `registered_users` (
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registered_users`
--

INSERT INTO `registered_users` (`username`, `email`, `password`) VALUES
('amit', 'amitdubal2005@gmail.com', '$2y$10$CDCIkwhILLFMEPxkl/Hl8eV1MBGFXrgatEZ8jRPWsj4UjgwTee4Ji'),
('atul', 'atul@gmail.com', '$2y$10$f0okYuqiSacfh8xG7AJlWOg26H3P65Wn.eS2yXWbvLYP7PwCXf2ne'),
('tejaswi', 'tejaswi@gmail.com', '$2y$10$2oBfUEqAr1PSFRYb7HkmkeSnWiiMTMbmimnhprxKBGaGfuhZpIZJG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `extracted_information`
--
ALTER TABLE `extracted_information`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `username` (`username`) USING HASH;

--
-- Indexes for table `registered_users`
--
ALTER TABLE `registered_users`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `extracted_information`
--
ALTER TABLE `extracted_information`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
