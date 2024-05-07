-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2023 at 02:57 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
(1, 4, '2023-12-09', 'Kevin Carl Lloren', 'llorenkevs@gmail.com', '7801128587 '),
(2, 2, '2024-12-10', 'Claire Bulalaque', 'bulalaqueclaire21@gmail.com', '2302110634 '),
(3, 2, '2023-12-10', 'Carl Lloren', 'llorenkevs@gmail.com', '7856137002 '),
(4, 1, '2023-12-10', 'Beverly Lloren', 'beverlylloren03@gmail.com', '0544152885'),
(6, 1, '2023-12-10', 'Carl Toreta', 'llorenkevincarl@gmail.com', '2992732652'),
(7, 1, '2023-12-10', 'Ritz Larano', 'ritzlarano@gmail.com', '3843744826'),
(9, 4, '2023-12-10', 'Jane Lloren', 'llorenkevs@gmail.com', '1016064415'),
(10, 1, '2023-12-10', 'Kristle Shane', 'llorenkevs@gmail.com', '0788042619'),
(11, 4, '2023-12-10', 'Kevin Carl Lloren', 'llorenkevs@gmail.com', '2942187897'),
(12, 2, '2023-12-10', 'Krishan Aea Dela Cruz', 'delacruzkrishanaea@gmail.com', '6683515833'),
(13, 4, '2023-12-10', 'Yuno baellery', 'llorenkevs@gmail.com', '4240638944'),
(14, 9, '2023-12-11', 'Kevin Carl Lloren', 'llorenkevincarl@gmail.com', '2185395365'),
(15, 9, '2023-12-11', 'Kevin Carl Lloren', 'llorenkevincarl@gmail.com', '7490378091');

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
(1, 'Kevin Carl', 'Lloren', 'llorenkevincarl@gmail.com', 'Hiiii', '2023-12-10 18:39:46'),
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
(1, 1, 199, 199, 198, 198),
(2, 2, 300, 299, 298, 297),
(4, 4, 198, 197, 196, 197),
(9, 9, 248, 250, 250, 250),
(10, 10, 300, 300, 300, 300),
(11, 11, 200, 200, 200, 200);

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
(1, 4, '2023-12-09', 4, 14000, 1, 1, 1, 1),
(2, 2, '2023-12-10', 2, 8000, 0, 0, 1, 1),
(3, 2, '2023-12-10', 2, 11000, 0, 1, 1, 0),
(4, 1, '2023-12-10', 1, 3000, 0, 0, 1, 0),
(6, 1, '2023-12-10', 2, 9000, 1, 1, 0, 0),
(7, 1, '2023-12-10', 1, 1500, 0, 0, 0, 1),
(9, 4, '2023-12-10', 4, 14000, 1, 1, 1, 1),
(10, 1, '2023-12-10', 2, 4500, 0, 0, 1, 1),
(11, 4, '2023-12-10', 2, 5000, 0, 0, 1, 1),
(12, 2, '2023-12-10', 2, 6000, 0, 0, 0, 2),
(13, 4, '2023-12-10', 2, 7000, 0, 1, 1, 0),
(14, 9, '2023-12-11', 1, 3500, 1, 0, 0, 0),
(15, 9, '2023-12-11', 1, 3500, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_ID` int(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_ID`, `fullname`, `username`, `password`) VALUES
(1, 'Kevin Carl Lloren', 'Kevins', '123'),
(2, 'Rose Banaybanay', 'admin', 'admin123'),
(3, 'Krishan Aea Dela Cruz', 'aea', 'a123');

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
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_ID`);

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
  MODIFY `customer_ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `Transaction_ID` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
