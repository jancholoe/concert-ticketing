-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2024 at 07:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `concert`
--

-- --------------------------------------------------------

--
-- Table structure for table `concerts`
--

CREATE TABLE `concerts` (
  `concert_ID` int(255) NOT NULL,
  `concert_name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `seat` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `concerts`
--

INSERT INTO `concerts` (`concert_ID`, `concert_name`, `price`, `seat`, `description`, `schedule`, `banner`) VALUES
(1, 'Bruno Mars', '1000', '200', 'Peter Gene Hernandez, known professionally as Bruno Mars, is an American singer-songwriter and record producer. He is known for his stage performances, retro showmanship, and for performing in a wide range of musical styles, including pop, R&B, funk, soul, reggae, disco, and rock. ', '2023/11/11 05:00', 'bruno.jpg'),
(2, '5 Seconds of Summer', '5000', '200', '5 Seconds of Summer, often shortened to 5SOS, are an Australian pop rock band from Sydney, New South Wales, formed in late 2011. The group consists of lead vocalist and rhythm guitarist Luke Hemmings, lead guitarist Michael Clifford, bassist Calum Hood, and drummer Ashton Irwin.', '2023/11/12 21:00', '5sos.jpg'),
(3, 'Taylor Swift', '8000', '200', 'Taylor Alison Swift is an American singer-songwriter. Recognized for her songwriting, musical versatility, artistic reinventions, and influence on the music industry, she is a prominent cultural figure of the 21st century. ', '2023/12/18 20:00', 'taylor.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `concert_details`
--

CREATE TABLE `concert_details` (
  `concert_ID` int(30) NOT NULL,
  `concert_name` varchar(50) NOT NULL,
  `schedule` date NOT NULL,
  `time` time NOT NULL,
  `description` text NOT NULL,
  `poster` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `concert_details`
--

INSERT INTO `concert_details` (`concert_ID`, `concert_name`, `schedule`, `time`, `description`, `poster`) VALUES
(1, 'Bruno Mars', '2024-01-30', '17:37:00', 'Peter Gene Hernandez, known professionally as Bruno Mars, is an American singer-songwriter and record producer. He is known for his stage performances, retro showmanship, and for performing in a wide range of musical styles, including pop, R&B, funk, soul, reggae, disco, and rock.', 'bruno.jpg'),
(2, 'Taylor Swift', '2024-11-30', '21:38:00', 'Taylor Alison Swift is an American singer-songwriter. Recognized for her songwriting, musical versatility, artistic reinventions, and influence on the music industry, she is a prominent cultural figure of the 21st century.', 'taylor.jpg'),
(4, '5 Seconds of Summer', '2024-11-30', '19:39:00', '	5 Seconds of Summer, often shortened to 5SOS, are an Australian pop rock band from Sydney, New South Wales, formed in late 2011. The group consists of lead vocalist and rhythm guitarist Luke Hemmings, lead guitarist Michael Clifford, bassist Calum Hood, and drummer Ashton Irwin.d', '5Ssos.jpg'),
(9, 'The Weeknd', '2024-06-11', '17:00:00', 'Abel Makkonen Tesfaye, known professionally as the Weeknd, is a Canadian singer, songwriter, and record producer. He is noted for his unconventional music production, artistic reinventions, and his signature use of the falsetto register.', 'the-weeknd.jpg'),
(10, 'Niall Horan', '2024-05-14', '17:25:00', 'Niall James Horan is an Irish singer-songwriter. He rose to prominence as a member of the boy band One Direction, formed in 2010 on the singing competition The X Factor.', 'portrait_15102023163147.jpg'),
(11, 'Rivermaya', '2024-07-11', '18:10:00', 'Rivermaya is a Filipino alternative rock band. Formed in 1994, it is one of the several bands who spearheaded the 1990s Philippine alternative rock explosion.', 'portrait_16112023132230.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `concert_price`
--

CREATE TABLE `concert_price` (
  `price_ID` int(30) NOT NULL,
  `date_created` date NOT NULL DEFAULT current_timestamp(),
  `vip` int(50) NOT NULL,
  `lower_box` int(50) NOT NULL,
  `upper_box` int(50) NOT NULL,
  `gen_add` int(50) NOT NULL,
  `vipSeat` int(50) NOT NULL,
  `lower_boxSeat` int(50) NOT NULL,
  `upper_boxSeat` int(50) NOT NULL,
  `gen_addSeat` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `concert_price`
--

INSERT INTO `concert_price` (`price_ID`, `date_created`, `vip`, `lower_box`, `upper_box`, `gen_add`, `vipSeat`, `lower_boxSeat`, `upper_boxSeat`, `gen_addSeat`) VALUES
(1, '2023-12-06', 5000, 4000, 3000, 1500, 200, 200, 200, 200),
(2, '2023-12-06', 8000, 6000, 5000, 3000, 300, 300, 300, 300),
(4, '2023-12-06', 5000, 4000, 3000, 2000, 200, 200, 200, 200),
(9, '2023-12-10', 3500, 2500, 1500, 800, 250, 250, 250, 250),
(10, '2023-12-10', 4000, 3000, 2000, 1000, 300, 300, 300, 300),
(11, '2023-12-10', 6000, 5000, 4000, 3000, 200, 200, 200, 200);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_ID` int(30) NOT NULL,
  `concert_ID` int(30) NOT NULL,
  `payment_date` date NOT NULL DEFAULT current_timestamp(),
  `customer_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `transaction_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_ID`, `concert_ID`, `payment_date`, `customer_name`, `email`, `transaction_code`) VALUES
(25, 2, '2024-05-07', 'Keyan Andy Delgado', 'keyanandydelgado@gmail.com', '4096217444'),
(26, 9, '2024-05-07', 'Keyan Andy Delgado', 'keyanandydelgado@gmail.com', '1796282805'),
(27, 10, '2024-05-07', 'Keyan Andy Delgado', 'kevinjensitago112@gmail.com', '6863681679'),
(28, 10, '2024-05-07', 'Keyan Andy Delgado', 'kevinjensitago112@gmail.com', '6495640074'),
(29, 4, '2024-05-07', 'Keyan Andy Delgado', 'keyanandydelgado@gmail.com', '1348780248'),
(30, 11, '2024-05-08', 'Keyan Andy Delgado', 'keyanandydelgado@gmail.com', '1013536099');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `user_id`, `event_type`, `description`, `created_at`) VALUES
(3, 7, 'Login Success', 'User logged in successfully', '2024-05-07 17:28:09'),
(4, 7, 'Login Success', 'User logged in successfully', '2024-05-07 17:28:23'),
(5, 7, 'Login Success', 'User logged in successfully', '2024-05-07 17:29:16'),
(6, 7, 'Login Success', 'User logged in successfully', '2024-05-07 17:31:59'),
(7, 7, 'Login Success', 'User logged in successfully', '2024-05-07 17:38:51'),
(8, 7, 'Payment Initiated', 'User  initiated payment with masked card: ****', '2024-05-07 17:39:14');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_ID` int(50) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `MSG` text NOT NULL,
  `message_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`message_ID`, `firstname`, `lastname`, `email`, `MSG`, `message_date`) VALUES
(3, 'Rose', 'Banaybanay', 'ritzlarano@gmail.com', 'Test', '2023-12-10 19:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sales_ID` int(30) NOT NULL,
  `price_ID` int(30) NOT NULL,
  `vipSeatS` int(50) NOT NULL,
  `lower_boxSeatS` int(50) NOT NULL,
  `upper_boxSeatS` int(50) NOT NULL,
  `gen_addSeatS` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sales_ID`, `price_ID`, `vipSeatS`, `lower_boxSeatS`, `upper_boxSeatS`, `gen_addSeatS`) VALUES
(1, 1, 193, 199, 198, 198),
(2, 2, 299, 297, 297, 296),
(4, 4, 194, 196, 195, 196),
(9, 9, 247, 250, 250, 250),
(10, 10, 298, 298, 298, 298),
(11, 11, 199, 200, 200, 200);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `Transaction_ID` int(30) NOT NULL,
  `concert_ID` int(30) NOT NULL,
  `date_purchased` date NOT NULL DEFAULT current_timestamp(),
  `total_tickets` int(30) NOT NULL,
  `total_amount` int(30) NOT NULL,
  `vipT` int(30) NOT NULL,
  `lower_boxT` int(30) NOT NULL,
  `upper_boxT` int(30) NOT NULL,
  `gen_addT` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`Transaction_ID`, `concert_ID`, `date_purchased`, `total_tickets`, `total_amount`, `vipT`, `lower_boxT`, `upper_boxT`, `gen_addT`) VALUES
(26, 2, '2024-05-07', 4, 22000, 1, 1, 1, 1),
(27, 9, '2024-05-07', 1, 3500, 1, 0, 0, 0),
(28, 10, '2024-05-07', 4, 10000, 1, 1, 1, 1),
(29, 10, '2024-05-07', 4, 10000, 1, 1, 1, 1),
(30, 4, '2024-05-07', 4, 14000, 1, 1, 1, 1),
(31, 11, '2024-05-08', 1, 6000, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `FirstName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `Username` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `usertype` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `FirstName`, `LastName`, `Username`, `Email`, `Age`, `Password`, `usertype`) VALUES
(2, 'Admin', 'Nigga', 'adminigga', 'admin@gmail.com', 20, 'admin123', 'admin'),
(7, 'Nigga', 'Man', 'niggaman', 'nigga@gmail.com', 22, 'nigga123', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `concerts`
--
ALTER TABLE `concerts`
  ADD PRIMARY KEY (`concert_ID`);

--
-- Indexes for table `concert_details`
--
ALTER TABLE `concert_details`
  ADD PRIMARY KEY (`concert_ID`);

--
-- Indexes for table `concert_price`
--
ALTER TABLE `concert_price`
  ADD PRIMARY KEY (`price_ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_ID`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_ID`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sales_ID`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`Transaction_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `concerts`
--
ALTER TABLE `concerts`
  MODIFY `concert_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `concert_details`
--
ALTER TABLE `concert_details`
  MODIFY `concert_ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `concert_price`
--
ALTER TABLE `concert_price`
  MODIFY `price_ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_ID` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sales_ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `Transaction_ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
