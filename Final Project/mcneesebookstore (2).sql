-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 06:32 AM
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
-- Database: `mcneesebookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CartID`, `UserID`) VALUES
(17, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `CartItemID` int(11) NOT NULL,
  `CartID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cartitems`
--

INSERT INTO `cartitems` (`CartItemID`, `CartID`, `ProductID`, `Quantity`) VALUES
(50, 17, 7, 1),
(52, 17, 6, 1),
(53, 17, 22, 1),
(54, 17, 13, 1),
(55, 17, 18, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(50) NOT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `Description`) VALUES
(1, 'Writing Supplies', 'Pens, pencils, erasers, markers, etc.'),
(2, 'Paper Products & Notebooks', 'Notebooks, loose paper, journals, and sketchbooks'),
(3, 'Organization & Storage', 'Binders, folders, desk organizers, and storage boxes'),
(4, 'Calculation & Measurement', 'Calculators, rulers, protractors, and measurement tools'),
(5, 'Planner', 'Calendars, planners, and scheduling accessories'),
(6, 'Art & Craft Supplies', 'Paints, brushes, canvases, and crafting tools'),
(7, 'Merchandise', 'McNeese branded clothing, accessories, and memorabilia');

-- --------------------------------------------------------

--
-- Table structure for table `orderhistory`
--

CREATE TABLE `orderhistory` (
  `HistoryID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `Status` enum('Package Shipped','Delayed','Delivery On Its Way','Delivered','Cancelled') NOT NULL,
  `Comment` text DEFAULT NULL,
  `ChangeDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderhistory`
--

INSERT INTO `orderhistory` (`HistoryID`, `OrderID`, `Status`, `Comment`, `ChangeDate`) VALUES
(1, 7, '', 'Initial order placed', '2025-03-27 15:52:40'),
(2, 8, '', 'Initial order placed', '2025-03-27 16:10:06'),
(3, 9, '', 'Initial order placed', '2025-03-27 16:14:37'),
(4, 10, '', 'Initial order placed', '2025-03-27 16:24:38'),
(5, 11, '', 'Initial order placed', '2025-03-27 16:38:34'),
(6, 12, '', 'Initial order placed', '2025-03-27 16:38:59'),
(7, 13, '', 'Initial order placed', '2025-03-27 16:41:53'),
(8, 14, '', 'Initial order placed', '2025-03-27 16:43:14'),
(9, 15, '', 'Initial order placed', '2025-03-27 16:45:41'),
(10, 16, '', 'Initial order placed', '2025-03-27 16:47:05'),
(11, 17, '', 'Initial order placed', '2025-03-27 16:49:44'),
(12, 18, '', 'Initial order placed', '2025-03-27 16:52:00'),
(13, 19, '', 'Initial order placed', '2025-04-10 20:12:06'),
(14, 20, '', 'Initial order placed', '2025-04-10 20:13:16'),
(15, 21, '', 'Initial order placed', '2025-04-10 21:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `OrderItemID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 1,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`OrderItemID`, `OrderID`, `ProductID`, `Quantity`, `Price`) VALUES
(3, 3, 5, 1, 19.99),
(4, 3, 7, 1, 89.99),
(6, 4, 7, 1, 89.99),
(8, 5, 7, 1, 89.99),
(10, 6, 7, 2, 89.99),
(11, 7, 6, 1, 14.99),
(12, 7, 7, 1, 89.99),
(14, 8, 13, 1, 3.49),
(15, 8, 20, 1, 12.99),
(16, 8, 21, 1, 6.49),
(17, 9, 5, 1, 19.99),
(18, 9, 6, 1, 14.99),
(19, 10, 7, 3, 89.99),
(20, 11, 6, 2, 14.99),
(21, 12, 6, 2, 14.99),
(25, 16, 14, 2, 7.99),
(26, 17, 6, 1, 14.99),
(28, 17, 16, 1, 29.99),
(29, 18, 5, 1, 19.99),
(30, 18, 6, 1, 14.99),
(31, 18, 7, 1, 89.99),
(34, 18, 12, 1, 5.29),
(35, 18, 13, 1, 3.49),
(36, 18, 14, 1, 7.99),
(37, 19, 7, 1, 89.99),
(38, 19, 13, 1, 3.49),
(39, 19, 16, 1, 29.99),
(40, 20, 6, 1, 14.99),
(43, 20, 15, 1, 4.99),
(44, 20, 21, 1, 6.49);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalPrice` decimal(10,2) NOT NULL,
  `Status` enum('Package Shipped','Delayed','Delivery On Its Way','Delivered','Cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `OrderDate`, `TotalPrice`, `Status`) VALUES
(3, 3, '2025-03-27 12:41:43', 118.47, ''),
(4, 3, '2025-03-27 14:40:59', 98.48, ''),
(5, 3, '2025-03-27 14:48:12', 98.48, ''),
(6, 3, '2025-03-27 15:37:06', 179.98, ''),
(7, 3, '2025-03-27 15:52:40', 104.98, ''),
(8, 7, '2025-03-27 16:10:06', 29.96, ''),
(9, 7, '2025-03-27 16:14:37', 34.98, ''),
(10, 7, '2025-03-27 16:24:38', 269.97, ''),
(11, 7, '2025-03-27 16:38:34', 29.98, ''),
(12, 7, '2025-03-27 16:38:59', 29.98, ''),
(13, 7, '2025-03-27 16:41:53', 16.98, ''),
(14, 7, '2025-03-27 16:43:14', 16.98, ''),
(15, 7, '2025-03-27 16:45:41', 16.98, ''),
(16, 7, '2025-03-27 16:47:05', 15.98, ''),
(17, 3, '2025-03-27 16:49:44', 53.47, ''),
(18, 3, '2025-03-27 16:52:00', 157.22, ''),
(19, 7, '2025-04-10 20:12:06', 123.47, ''),
(20, 7, '2025-04-10 20:13:16', 41.95, ''),
(21, 7, '2025-04-10 21:39:25', 8.49, '');

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `PaymentMethodID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PaymentType` enum('CreditCard','DebitCard') NOT NULL,
  `CardHolderName` varchar(50) DEFAULT NULL,
  `EncryptedCardNumber` varbinary(255) NOT NULL,
  `ExpirationMonth` int(11) NOT NULL,
  `ExpirationYear` int(11) NOT NULL,
  `BillingAddress` text DEFAULT NULL,
  `Token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paymentmethods`
--

INSERT INTO `paymentmethods` (`PaymentMethodID`, `UserID`, `PaymentType`, `CardHolderName`, `EncryptedCardNumber`, `ExpirationMonth`, `ExpirationYear`, `BillingAddress`, `Token`) VALUES
(1, 7, '', 'Credit', 0x4c616479204c656461, 0, 10, '2030', NULL),
(2, 7, '', 'Lady Leda', 0x24327924313024487564345167544d4f3454676a314d7541754c2e4d4f4f7a4b3178576a4f66497157596339474f6a3657664d37547749374b396a32, 10, 2030, '4305 St.', NULL),
(3, 7, 'DebitCard', 'Lady Leda', 0x2432792431302462704744656c736d446143684645493845624876474f444639544a794c39793662566f6a744c6a65466c4f70544a78334a61696e75, 5, 2030, '4305 St.', NULL),
(4, 7, 'CreditCard', 'Lady Leda', 0x24327924313024587a6e65777976465462486a667763364d434f65522e5573444879385a66533936426572557472315a76752e3666496676357a6e69, 10, 2030, '4305 St.', NULL),
(5, 7, 'CreditCard', 'Lady Leda', 0x243279243130242e4e37657a795942797a326d4833786e553856356f7553486f376d6134797a3047376f4d356c2f305655566f757571767053507843, 10, 2030, '4305 St.', 'f50a5736866ed8d8362e72364743d25e93ee86991a44c56330e92727cc2b7bea'),
(6, 3, 'CreditCard', 'Kid Kad', 0x243279243130247249575a6e72755745657270314b67706f76655836754c482e754a716e585938426d474259564f755a616e3945636d62586a444e57, 6, 2029, '4124 St.', '87ec8a879ea226a4209d42433bea7db03d157618645e89c9dd23c29b888c220e'),
(7, 7, 'CreditCard', 'Lady Leda', 0x2432792431302476726f454566594267754e53664250382f596c5541752f574e5a6632515742343634492e5449746e30566d30316a64666536417661, 5, 2029, '3432 Something Street', 'b891ba6894823d98994d1228b335da5a9bce2db0e09a5c84904cbaedc337a0a9'),
(8, 7, 'CreditCard', 'Lady Leda', 0x243279243130246a415346364e7565677242307952665648677a36732e6f45707961616a544e496b6a61326365654f44656c687437436f4870312f6d, 5, 2029, '2342 something street', '57778222fd6dfe3a44d9b81ee955b9ae3346c12910f9968bc2774865669e8125');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `PaymentMethodID` int(11) NOT NULL,
  `PaymentAmount` decimal(10,2) NOT NULL,
  `PaymentDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `PaymentStatus` enum('Pending','Completed','Failed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`PaymentID`, `OrderID`, `PaymentMethodID`, `PaymentAmount`, `PaymentDate`, `PaymentStatus`) VALUES
(1, 8, 1, 29.96, '2025-03-27 16:10:06', 'Completed'),
(2, 9, 2, 34.98, '2025-03-27 16:14:37', 'Completed'),
(3, 10, 3, 269.97, '2025-03-27 16:24:38', 'Completed'),
(4, 12, 4, 29.98, '2025-03-27 16:38:59', 'Completed'),
(5, 15, 5, 16.98, '2025-03-27 16:45:41', 'Completed'),
(6, 16, 5, 15.98, '2025-03-27 16:47:05', 'Completed'),
(7, 17, 6, 53.47, '2025-03-27 16:49:45', 'Completed'),
(8, 18, 6, 157.22, '2025-03-27 16:52:00', 'Completed'),
(9, 19, 7, 123.47, '2025-04-10 20:12:06', 'Completed'),
(10, 20, 8, 41.95, '2025-04-10 20:13:16', 'Completed'),
(11, 21, 5, 8.49, '2025-04-10 21:39:25', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Stock` int(11) NOT NULL DEFAULT 0,
  `CategoryID` int(11) NOT NULL,
  `ImagePath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `Description`, `Price`, `Stock`, `CategoryID`, `ImagePath`) VALUES
(5, 'McNeese T-Shirt', 'Official McNeese branded t-shirt', 19.99, 50, 7, 'images/mcneese-tshirt.jpeg'),
(6, 'McNeese Hat', 'Adjustable McNeese hat', 14.99, 30, 7, 'images/hat.jpg'),
(7, 'Introduction to Programming with C++', 'For undergraduate students in Computer Science and Computer Programming courses\r\n\r\nA solid foundation in the basics of C++ programming will allow students to create efficient, elegant code ready for any production environment.', 89.99, 20, 2, 'images/introduction-to-programming.jpg'),
(12, 'Five Star Spiral Notebook (3-subject)', 'Durable cover, college ruled.', 5.29, 50, 2, 'images/five-star-notebook.jpg'),
(13, 'McNeese Notepad with Logo', '50-sheet custom notepad.', 3.49, 45, 2, 'images/mcneese-notepad.jpg'),
(14, 'Plastic Accordion File Organizer', '13-pocket expanding folder.', 7.99, 25, 3, 'images/file-organizer.jpg'),
(15, 'Desk Organizer Tray', 'Multi-compartment for office/school.', 4.99, 30, 3, 'images/desk-organizer.jpeg'),
(16, 'Casio FX-991EX Scientific Calculator', 'Recommended for engineering & science.', 29.99, 15, 4, 'images/scientific-calculator.jpg'),
(17, '12” Transparent Ruler (2-pack)', 'Flexible and shatter-resistant.', 2.99, 60, 4, 'images/ruler.jpg'),
(18, '2024 Weekly Planner', 'Hardcover with calendar view.', 9.99, 25, 5, 'images/weekly-planner.jpg'),
(19, 'Pocket Academic Planner', 'Compact for students on the go.', 5.99, 35, 5, 'images/pocket-planner.jpeg'),
(20, 'Acrylic Paint Set (12 colors)', 'Vibrant, beginner-friendly set.', 12.99, 20, 6, 'images/acrylic-paint.jpg'),
(21, 'Sketch Pad (100 Sheets)', 'Ideal for pencils, charcoal, and markers.', 6.49, 28, 6, 'images/sketch-pad.jpg'),
(22, 'McNeese State Cowboys Men\'s Basketball T-Shirt', 'Official McNeese branded t-shirt', 19.99, 50, 7, 'images/mcneese-basketball-tshirt.jpg'),
(23, 'McNeese Knit Beanie Hat Unisex for Men and Women', 'Campus Lab Official Collegiate Primary Logo Sublimated Patch Knit Beanie Hat Unisex for Men and Women', 14.99, 30, 7, 'images/mcneese-beanie.jpg'),
(24, 'McNeese Ceramic Coffee Mug', 'McNeese State University Cowboys Logo Ceramic Coffee Mug, Novelty Gift Mugs for Coffee, Tea and Hot Drinks, 11oz, White ', 9.99, 40, 7, 'images/mcneese-ceramic-mug.jpg'),
(25, 'Introduction to Web Development', 'A beginner-friendly guide to HTML, CSS, and JavaScript.', 74.99, 20, 2, 'images/intro-to-webdev.jpg'),
(26, 'Pentel Hi-Polymer Super Eraser', 'High-quality eraser for clean and precise erasing.', 2.99, 100, 2, 'images/eraser.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `Birthday` date DEFAULT '2000-01-01',
  `Password` varchar(255) NOT NULL,
  `Address` text DEFAULT NULL,
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `Role` enum('Admin','User') NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `ProfilePicture` varchar(255) DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Email`, `Gender`, `Birthday`, `Password`, `Address`, `PhoneNumber`, `Role`, `CreatedAt`, `ProfilePicture`, `Status`) VALUES
(2, 'Kat', 'Kim', 'kat@mcneese.edu', 'Male', NULL, '$2y$10$IRorx8ASv1R4tlSGsFi1SeXZczS8kraJzBYjo4yNUjjh0d7G08tfa', NULL, NULL, '', '2025-03-13 19:17:13', NULL, 'Active'),
(3, 'Kid', 'kad', 'kid@mcneese.edu', 'Male', NULL, '$2y$10$c2msvP8v6S9ya17SgAXiR.zkY9xOhm0Hf9U9Wupk/zTMTUCYtjaoK', NULL, NULL, '', '2025-03-13 19:27:48', NULL, 'Active'),
(4, 'Hero', 'June', 'june@mcneese.edu', 'Male', '2025-04-04', '$2y$10$m866iEae.l6PTQW3EDFjsu/dIfpU0U.fM5hGX.VtaadjRUn8NdlPy', '4323 Harvard Street', '3464774532', 'Admin', '2025-03-13 19:41:44', '/bookstore/userImage/user_4_1744313580.jpg', 'Active'),
(5, 'sunny', 'sunshine', 'sunny@mcneese.edu', 'Male', NULL, '$2y$10$9OWcOKpg6sw4w2bsPGkDI.gT4OHpoV6xVQJRzXZosGfauW.37T87e', NULL, NULL, 'Admin', '2025-03-13 21:08:29', NULL, 'Active'),
(6, 'lad', 'lady', 'lad@mcneese.edu', 'Male', NULL, '$2y$10$gBjQ1AfHNIFLofXX1NjNd./0acPRGvw1dAyW5FCFL7iLjGszqK1ga', NULL, NULL, '', '2025-03-13 21:09:32', NULL, 'Active'),
(7, 'Lady', 'Leda', 'leda@mcneese.edu', 'Female', '1998-06-25', '$2y$10$8y.i9Riw9cRRqkbmmR9RRuzx5iSvbrwyyeHrgNDjQ0NivP.0ZnST.', '4000 Someting Street, Somewhere, Someplace', '1234567878', '', '2025-03-27 16:07:48', '/bookstore/userImage/user_7_1744320906.jpg', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `WishlistID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`WishlistID`, `UserID`, `ProductID`) VALUES
(47, 7, 5),
(45, 7, 6),
(49, 7, 21),
(50, 7, 22),
(51, 7, 23);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD PRIMARY KEY (`CartItemID`),
  ADD KEY `CartID` (`CartID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `orderhistory`
--
ALTER TABLE `orderhistory`
  ADD PRIMARY KEY (`HistoryID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`OrderItemID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`PaymentMethodID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `PaymentMethodID` (`PaymentMethodID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`WishlistID`),
  ADD UNIQUE KEY `unique_user_product` (`UserID`,`ProductID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cartitems`
--
ALTER TABLE `cartitems`
  MODIFY `CartItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orderhistory`
--
ALTER TABLE `orderhistory`
  MODIFY `HistoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `OrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  MODIFY `PaymentMethodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `WishlistID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `cartitems_ibfk_1` FOREIGN KEY (`CartID`) REFERENCES `cart` (`CartID`) ON DELETE CASCADE,
  ADD CONSTRAINT `cartitems_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `orderhistory`
--
ALTER TABLE `orderhistory`
  ADD CONSTRAINT `orderhistory_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD CONSTRAINT `paymentmethods_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`PaymentMethodID`) REFERENCES `paymentmethods` (`PaymentMethodID`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
