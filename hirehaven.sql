-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2025 at 06:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hirehaven`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `applied_at` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','Shortlisted','Rejected') DEFAULT 'Pending',
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `seeker_id`, `applied_at`, `status`, `note`) VALUES
(2, 2, 5, '2025-07-18 21:15:04', 'Shortlisted', 'Interview soon!'),
(3, 11, 5, '2025-07-19 21:35:28', 'Shortlisted', 'Notify Soon!'),
(4, 9, 5, '2025-07-19 21:42:32', 'Rejected', 'Limited Skills'),
(5, 8, 5, '2025-07-19 22:03:45', 'Rejected', 'limited skills'),
(6, 10, 5, '2025-07-19 22:10:04', 'Shortlisted', 'Notify Soon'),
(7, 6, 5, '2025-07-19 22:21:21', 'Pending', 'Reviewing Application'),
(8, 7, 5, '2025-07-19 22:21:47', 'Shortlisted', 'Notify Soon');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `subject` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`, `subject`) VALUES
(1, 'ASAD NOMAN', 'asadnoman@055gmail.com', 'i havent conformed my job conformation', '2025-07-04 14:23:22', 'order inquiry'),
(2, 'sohaibnoman', 'sohaibnoman786@gmail.com', 'i havent recived any responese regarding my application ', '2025-07-19 22:33:22', 'job application');

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

CREATE TABLE `employers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employers`
--

INSERT INTO `employers` (`id`, `user_id`, `company_name`, `website`, `contact_person`, `created_at`, `profile_photo`) VALUES
(1, 1, NULL, NULL, 'admin', '2025-07-04 11:06:06', NULL),
(2, 7, NULL, NULL, 'asad noman', '2025-07-18 21:03:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `employer_id`, `title`, `company`, `location`, `description`, `requirements`, `created_at`) VALUES
(2, 7, 'Network Engineer ', 'Jazz Telecom', 'Islamabad', 'A network Engineer Required with 3 years of experience in the Feild of Telecommunication sector with Education required in Masters in the Field of IT and Computer Science send your resume at asadnoman055@gmail.com', '3 years of experience in the Feild of Telecommunication sector with Education required in Masters in the Field of IT and Computer Science', '2025-07-18 21:08:39'),
(3, 7, 'Human Resource Manager ', 'Nestle ', 'Islamabad', 'Human Resource Manager Required with ', 'Human with limited age of 30 and masters or bachelors in Management', '2025-07-19 00:52:49'),
(4, 7, ' Frontend Developer Intern', 'Rabia Enterprises', 'Islamabad', 'Front End Developer with basics knowledge of HTML CSS and Javascript ', 'Bachelors of Computer science ', '2025-07-19 01:12:21'),
(5, 7, 'PHP Developer', 'Btab Group', 'Islamabad', 'We are looking for a PHP Developer responsible for managing back-end services and the interchange of data between the server and the users. Your primary focus will be the development of all server-side logic, definition and maintenance of the central database, and ensuring high performance and responsiveness to requests from the front-end. You will also be responsible for integrating the front-end elements built by your co-workers into the application. Therefore, a basic understanding of front-end technologies is necessary as well.', 'sProven experience as a PHP Developer (3+ years preferred)\r\n.Strong knowledge of PHP, MySQL, HTML, CSS, JavaScript, and AJAX\r\n.Experience with PHP frameworks like Laravel, CodeIgniter, or Symfony\r\n.Understanding of RESTful APIs and integration\r\n.Familiarity with version control systems (Git, GitHub, or Bitbucket)\r\n.Knowledge of front-end technologies like React, Vue.js, or Angular (a plus)\r\n.Experience with cloud services (AWS, Azure, or Google Cloud)\r\n.Knowledge of Docker, Kubernetes, or CI/CD pipelines\r\n.Understanding of WordPress, Magento, or other CMS platforms\r\n.Experience in unit testing and debugging PHP applications', '2025-07-19 01:16:43'),
(6, 7, 'Senior Full Stack WordPress Developer', 'X-PHY ', 'Islamabad', 'We are seeking a highly skilled Senior Full Stack WordPress Developer who possesses a deep understanding of WordPress, along with expertise in AI, automation, API integrations, and plugin development. As a Solution Architect, you will be responsible for designing, developing, and implementing complex solutions on the WordPress platform. You will manage code using Git and collaborate with cross-functional teams to deliver high-quality projects on time.', 'Full Stack Development:\r\n Design and implement WordPress themes and plugins from scratch\r\n Develop and maintain responsive front-end solutions using HTML, CSS, JavaScript, and PHP\r\n Optimize WordPress installations for speed, scalability, and performance\r\nAPI Integrations:\r\nImplement and manage API integrations between WordPress and third-party services (e.g., CRM systems, payment gateways, marketing tools)\r\nCreate and manage custom RESTful APIs as needed\r\n AI and Automation:\r\nUtilize AI technologies to enhance site functionality and user experience\r\nDevelop custom automation scripts and workflows to streamline processes and improve efficiency\r\n Solution Architecture:\r\nCollaborate with stakeholders to understand business needs and translate them into technical specifications\r\nDesign scalable, maintainable, and effective architectures for WordPress solutions\r\nPlugin Development:\r\nCreate high-quality WordPress plugins that extend the core functionality of WordPress\r\nMaintain and upgrade existing plugins and ensure they are compatible with the latest versions of WordPress\r\nVersion Control and Git Management:\r\nManage code repositories using Git, implement branching strategies, and ensure smooth code deployment\r\nConduct code reviews and ensure adherence to best practices in coding standards\r\nCollaboration and Teamwork:\r\nWork closely with designers, content creators, and other developers to deliver cohesive and high-quality digital solutions\r\nMentor junior developers and provide leadership in technical decisions\r\nTesting and Quality Assurance:\r\nPerform debugging and troubleshooting of WordPress sites and applications\r\nEnsure quality and accessibility standards are met through testing and validation processes\r\nContinuous Learning:\r\nStay up-to-date with the latest trends and technologies in web development, WordPress, AI, and industry best practices', '2025-07-19 01:20:01'),
(7, 7, 'Junior Backend Developer (Python/Django', 'Decklaration', 'Lahore', 'We are looking for a talented Backend Developer with expertise in Python and Django to join our dynamic team. The ideal candidate will design, develop, and maintain efficient, reusable, and reliable server-side logic and systems, ensuring high performance and responsiveness to requests from the front end. If you are passionate about building scalable solutions and delivering impactful software, we would love to meet you!', '\r\nBachelor\'s degree in Computer Science or Software Engineering \r\n1-2 years of professional experience as a Python/Django Developer. \r\nStrong proficiency in Python and the Django framework. \r\nExperience with RESTful API development and microservices architecture. \r\nProficiency in relational databases like PostgreSQL or MySQL. \r\nKnowledge of version control systems, particularly Git. \r\nUnderstanding of CI/CD pipelines and cloud platforms like AWS, Azure, or Google Cloud is advantageous. \r\nStrong analytical and problem-solving skills. \r\nGood communication and teamwork abilities.', '2025-07-19 01:22:10'),
(8, 7, 'Senior Frontend Developer', 'InterProbe Information Technologies ', 'Islamabad', 'INTERPROBE is an idealistic team that anticipates the needs of tomorrow and focuses on, as a principle, producing high-tech integrated security solutions to address these needs. Thanks to our experience, we are deepening our expertise day by day in next generation defense technologies, cyber threat intelligence, cyber security, cryptography and special software solutions.', '5+ years of professional frontend development experience\r\nStrong knowledge and hands-on experience with Angular and React\r\nExperience integrating and customizing map-based (GIS) components\r\nDeep knowledge of JavaScript and TypeScript\r\nExpertise with validation, transpilation, and bundling tools such as ESLint, Webpack, and Babel\r\nExperience working with CSS3 and modern CSS preprocessors (e.g., SCSS, SASS)\r\nProficiency in cross-browser compatibility and responsive design techniques\r\nAbility to contribute to technical decision-making and mentor junior developers\r\nActive involvement in testing, documentation, and code review processes to improve code quality', '2025-07-19 01:25:36'),
(9, 7, 'Associate SQA Engineer', 'Decklaration', 'Islamabad', 'We are looking for a talented Backend Developer with expertise in Python and Django to join our dynamic team. The ideal candidate will design, develop, and maintain efficient, reusable, and reliable server-side logic and systems, ensuring high performance and responsiveness to requests from the front end. If you are passionate about building scalable solutions and delivering impactful software,', 'Bachelor\'s degree in Computer Science or Software Engineering \r\n1-2 years of professional experience as a Python/Django Developer. \r\nStrong proficiency in Python and the Django framework. \r\nExperience with RESTful API development and microservices architecture. \r\nProficiency in relational databases like PostgreSQL or MySQL. \r\nKnowledge of version control systems, particularly Git. \r\nUnderstanding of CI/CD pipelines and cloud platforms like AWS, Azure, or Google Cloud is advantageous. \r\nStrong analytical and problem-solving skills. \r\nGood communication and teamwork abilities.', '2025-07-19 01:26:55'),
(10, 7, 'Back End Developer ', 'Axons Limited', 'Islamabad', 'A back-end developer is responsible for building and maintaining the server-side logic and infrastructure of a website or application. This involves tasks such as designing and implementing databases, writing server-side code, developing APIs, and ensuring the application is secure, scalable, and reliable. They work closely with front-end developers and other team members to deliver a seamless user experience', 'A back-end developer typically needs proficiency in server-side programming languages, database management, API development, and familiarity with frameworks and cloud platforms. Strong problem-solving, communication, and organizational skills are also essentia', '2025-07-19 21:11:39'),
(11, 7, 'back-end developer', 'Axons Limited', 'Islamabad', 'AI Overview\r\nA back-end developer is responsible for building and maintaining the server-side logic and infrastructure of a website or application. This involves tasks such as designing and implementing databases, writing server-side code, developing APIs, and ensuring the application is secure, scalable, and reliable. They work closely with front-end developers and other team members to deliver a seamless user experience.', 'A back-end developer typically needs proficiency in server-side programming languages, database management, API development, and familiarity with frameworks and cloud platforms. Strong problem-solving, communication, and organizational skills are also essentia', '2025-07-19 21:12:26');

-- --------------------------------------------------------

--
-- Table structure for table `seekers`
--

CREATE TABLE `seekers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `experience` varchar(255) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `profile_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seekers`
--

INSERT INTO `seekers` (`id`, `user_id`, `full_name`, `education`, `experience`, `resume`, `created_at`, `profile_photo`) VALUES
(2, 5, 'ibrahim', 'Bachelor Of Computer Science', '3 years of Experience ', '1752855256_Asad Noman  CV.pdf', '2025-07-05 21:37:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employer','seeker') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'asadnoman@055gmail.com', '$2y$10$agcnT4KipmaM0q7jO9qJuO.cg9Iq6UqNTwD5CJ/7GxLw9nzb1m9OS', 'employer', '2025-07-04 11:06:06'),
(4, 'Admin', 'admin@hirehaven.com', '$2y$10$0U7kqc2Pzc24.ew/FiEez.8EP64qmjJa9emXVLD9CoxCZbVstv8Wa', 'admin', '2025-07-04 11:37:50'),
(5, 'ibrahim', 'ibrahimnoon576@gmail.com', '$2y$10$fvGWetAfqc9aVCOixBZFTe8UJ13LLqir.W4wizu6rIFq9Rx9Q.ELi', 'seeker', '2025-07-05 21:37:59'),
(7, 'asad noman', 'asadnoman055@gmail.com', '$2y$10$wp0lWdWld98ilkaa8ppEx.QOpgJ9j.uFg6wPZH2MvlOX43kGw1TM2', 'employer', '2025-07-18 21:03:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `seeker_id` (`seeker_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employers`
--
ALTER TABLE `employers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employer_id` (`employer_id`);

--
-- Indexes for table `seekers`
--
ALTER TABLE `seekers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employers`
--
ALTER TABLE `employers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `seekers`
--
ALTER TABLE `seekers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`seeker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employers`
--
ALTER TABLE `employers`
  ADD CONSTRAINT `employers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seekers`
--
ALTER TABLE `seekers`
  ADD CONSTRAINT `seekers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
