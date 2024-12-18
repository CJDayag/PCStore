-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 03:32 PM
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
-- Database: `pcstore1`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `aid` int(11) NOT NULL,
  `afname` varchar(100) NOT NULL,
  `alname` varchar(100) NOT NULL,
  `phone` char(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `username` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`aid`, `afname`, `alname`, `phone`, `email`, `dob`, `username`, `gender`, `password`, `code`) VALUES
(31, 'CJ', 'Dayag', '09355498379', 'dayagcj491@gmail.com', '2003-04-08', 'Sijeyy', 'M', 'Dayagcj491', 140045);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(120) NOT NULL,
  `password` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin1', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `aid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `cqty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`aid`, `pid`, `cqty`) VALUES
(31, 58, 1),
(31, 64, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order-details`
--

CREATE TABLE `order-details` (
  `oid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order-details`
--

INSERT INTO `order-details` (`oid`, `pid`, `qty`) VALUES
(25, 41, 2),
(25, 45, 1),
(25, 57, 1),
(26, 45, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `oid` int(11) NOT NULL,
  `dateod` date NOT NULL,
  `datedel` varchar(255) DEFAULT NULL,
  `aid` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(100) NOT NULL,
  `account` char(16) DEFAULT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`oid`, `dateod`, `datedel`, `aid`, `address`, `city`, `country`, `account`, `total`) VALUES
(25, '2023-12-01', 'Order delivered', 31, 'Ilang-ilang Street Payatas A', 'Quezon City', 'Philippines', NULL, 1500),
(26, '2023-12-01', NULL, 31, 'Ilang-ilang Street Payatas A', 'laguna', 'Philippines', NULL, 8350);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `pid` int(11) NOT NULL,
  `pname` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `price` int(11) NOT NULL,
  `qtyavail` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `brand` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`pid`, `pname`, `category`, `description`, `price`, `qtyavail`, `img`, `brand`) VALUES
(30, 'Razer BlackWidow V4 Pro', 'keyboard', ' Take your gaming experience to the next level with the Razer BlackWidow V4 Pro! This mechanical gaming keyboard features Razer signature green switches, providing tactile feedback and optimized actua', 2000, 15, 'x3.jpeg', 'Razor'),
(33, 'Ryzen 7 3700x ', 'cpu', 'Experience lightning-fast performance with the AMD Ryzen 7 3700X processor! With 8 cores and 16 threads, this processor delivers unrivaled speed and processing power for demanding tasks, including gam', 1700, 7, 'x6.jpeg', 'Ryzen'),
(34, 'Nvidia GTX 1660Ti GPU', 'gpu', 'Take your PC experience to the next level with the NVIDIA GeForce GTX 1660 Ti graphics card! This high-performance graphics card features NVIDIA Turing architecture and 6GB of GDDR6 memory, providing ', 1500, 5, 'x9.jpeg', 'Nvidia'),
(35, 'HyperX Fury Ram 16GB', 'ram', 'Upgrade your PC performance with HyperX Fury RAM! With speeds of up to 3200MHz and capacities ranging from 8GB to 64GB, HyperX Fury RAM is the perfect choice for anyone looking to improve their PC mul', 1000, 3, '71GJY5+c14L._SY450_.jpg', 'HyperX'),
(36, 'Geforce RTX 4080 16GB', 'gpu', 'The NVIDIA GeForce RTX 4080 delivers the ultra performance and features that enthusiast gamers and creators demand. Bring your games and creative projects to life with ray tracing and AI-powered graph', 2500, 12, 'lol.jpeg', 'Nvidia'),
(37, 'Asus Rog Strix B550-E', 'motherboard', 'Gamers and PC enthusiasts, elevate your build with the ASUS ROG Strix B550-E Gaming motherboard! Designed with performance in mind, this high-end motherboard features the latest PCIe 4.0 technology, a', 4425, 1, 'rog.jpeg', 'Asus'),
(38, 'MageGee Mechanical Gaming Keyboard', 'keyboard', 'Upgrade your gaming setup with the MageGee Mechanical Gaming Keyboard. Built with high-quality and durable materials, this keyboard features mechanical switches that provide a tactile and satisfying t', 1499, 6, 'no.jpeg', 'MageGee'),
(39, 'Intel Core i9-10900K 3.7 GHz ', 'cpu', 'Experience the ultimate performance with the Intel Core i9-10900K 3.7 GHz processor. With 10 cores and 20 threads, this high-end processor delivers blazing-fast speeds and unparalleled multitasking ca', 19000, 14, 'i.jpeg', 'Intel'),
(40, 'Redragon Gaming Mouse', 'mouse', ' Take your gaming to the next level with the RedDragon gaming mouse. This high-performance gaming mouse features an ergonomic design with customizable RGB lighting, making it not only comfortable to u', 1000, 5, 'red.jpeg', 'Redragon'),
(41, 'Razer Cynosa V2 RGB Gaming Keyboard ', 'keyboard', ' The Razer Cynosa V2 RGB Gaming Keyboard is a must-have accessory for any avid gamer looking to take their gaming experience to the next level. With its fully customizable RGB lighting, you can create', 2900, 5, 'r.jpeg', 'Razor'),
(42, 'Glorious Model O Gaming Mouse', 'mouse', 'The Glorious Model O is a gaming mouse that is built to deliver superior performance, accuracy, and speed to gamers of all levels. With its sleek and ergonomic design, this mouse is designed to fit co', 2450, 8, 'g.jpeg', 'Glorious'),
(43, 'Geforce RTX 3080 12GB Zotac', 'gpu', 'The GeForce RTX 3080 12GB Zotac is a high-performance graphics card designed for gamers and professionals who require the best in graphical processing power. This graphics card is powered by the NVIDI', 61000, 3, 'Rtx.jpeg', 'Nvidia'),
(45, 'NZXT H510 Elite White - Premium Mid-Tower ATX Case PC Gaming Case', 'chassis', 'The H510 Elite compact ATX mid-tower is perfect for your RGB build. Behind the flush-mounted, tempered glass front panel, you’ll discover our renowned Aer RGB 2 fans keeping your components brilliantl', 8350, 1, 'pccase.jpg', 'NZXT'),
(47, 'Razer F05 stereo gaming head set headset with microphone 3.5mm', 'headset', 'High precision 40mm magnetic driver unit, bring you vivid sound field, sound clarity, sound shock feeling, capable of various games. Adopt unique 3D virtual speaker displacement technology, provides m', 260, 3, 'headset.jpg', 'Razer'),
(48, 'Plextone Cooling Fan EX3 Mark II Semiconductor ICE Cooling Cooler Fan Gaming', 'coolingfan', '27W overclocking Cooling 93mm large diameter Larger cooling area Blue Light Funcoole Lightweight design Fist size radiator, Experience extreme low temperature Blue light heat sink, innovative air duct', 500, 2, 'coolingfan.jpg', 'Plextone'),
(49, 'Apevia SPIRIT600W Spirit 600W ATX Power Supply with Auto', 'Powersupply', 'Apevia 600W Spirit ATX Gaming Power Supply with black sandblasted casing Supports Dual/Quad/Multi-core CPUs. Supports single 12V output for higher power usage. Connectors : 1 x 20/24pin Main Power, 1 ', 2000, 2, 'powersupply.jpg', 'Apevia'),
(51, 'Desktop Set A', 'set', 'Desktop PC Setup for Students! Freebies:  Keyboard Mouse Monitor Headset  Specs:   Intel i5-3470 8GB 1600Mhz Memory  128GB SSD GTX 960 2GB 128 Bit  Keytech T100 PC Case  Jonsbo CR1200', 12000, 2, 'SetA.jpg', '\''),
(52, 'Desktop Set B', 'set', 'Desktop Setup for Gamers! Freebies:  Keyboard Mouse Monitor Headset  Specs:   Intel Core i7-3770 16GB 1600Mhz Memory  512GB SSD GTX 960 2GB 128 Bit  Keytech T100 PC Case  Jonsbo CR1200 Tower CPU Heat ', 21000, 2, 'Set B.jpg', '\''),
(54, 'SWAFAN EX12 RGB PC Cooling Fan', 'coolingfan', 'SWAFAN EX incorporates a new magnetic force design for quick connection among fans with its 12V cable and at the same time keeping the swappable fan blade design. Daisy-chaining up to 3 fans per cable', 6000, 2, 'swafan_ex12_rgb_01.jpg', 'Swafan'),
(55, 'CORSAIR iCUE SP120 RGB Single Fan', 'coolingfan', 'CORSAIR AirGuide technology utilizes anti-vortex vanes to direct airflow and concentrate cooling, improving cooling whether used as intake, exhaust, or mounted to a liquid cooling radiator or heatsink', 1200, 2, '71RkOPwLCgL._SL1500_.jpg', 'CORSAIR'),
(56, 'M908 Impact RGB LED MMO Gaming Mouse', 'mouse', 'Professional Gaming Mouse - Redragon M908 optical gaming mouse is designed with up to 12400 DPI, 5 adjustable DPI levels (500/1000/2000/3000/6200 DPI) meet your multiple needs, either for daily work o', 1300, 2, '61kI0PIuXVL._AC_SL1500_.jpg', 'Redragon'),
(57, 'ZIUMIER Gaming Headset with Microphone', 'headset', 'PROFESSIONAL GAMING HEADPHONE. with 50mm drivers that deliver superior audio performance, ZIUMIER PS4 headset can generate a virtual surround sound experience to create distance and depth that enhance', 1500, 1, '71nh1VxLzuL._AC_SL1500_.jpg', 'ZIUMIER'),
(58, 'EKSA E900 Headset with Microphone for PC, PS4,PS5, Xbox', 'headset', '【Detachable Noise-Cancelling Microphone】E900 gaming headset has a highly sensitive microphone with omnidirectional noise reduction technology, which can minimize the background noise to capture your v', 2000, 2, '71taQaDHq0L._AC_SL1500_.jpg', 'EKSA'),
(59, '450M DS3H WiFi (AM4//AMD/B450/mATX/SATA 6GB/s/USB 3.1/HDMI/Wifi/DDR4/Motherboard)', 'motherboard', 'Supports AMD 3rd Gen Ryzen/ 2nd Gen Ryzen/ 1st Gen Ryzen/ 2nd Gen Ryzen with Radeon Vega Graphics/ 1st Gen Ryzen with Radeon Vega Graphics/ Athlon with Radeon Vega Graphics Processors Dual Channel Non', 4500, 2, '71INC58HfuL._AC_SL1000_.jpg', 'Gigabyte'),
(60, 'ASUS Pro Q670M-C-CSM LGA 1700 Micro-ATX', 'motherboard', 'Enhance your business with a 12th generation Intel computer system using the PRO Q670M-C-CSM LGA 1700 Micro-ATX Commercial Motherboard from ASUS. Powered by the Intel Q670 chipset, this motherboard su', 9500, 2, '1648729340_1698668.jpg', 'ASUS'),
(61, 'NZXT H5 Flow Compact ATX Mid-Tower PC Gaming Case', 'chassis', 'READY FOR PERFORMANCE: Fits most NVIDIA GeForce RTX 40 Series graphics cards with its 365mm max GPU clearance. Display your GPU vertically using the NZXT Vertical GPU Mounting Kit (sold separately). E', 4500, 2, '71SIs5kxpYL._AC_SL1500_.jpg', 'NZXT'),
(62, 'Novus Ascend 1130 PC Chassis', 'chassis', 'M/B Type: Micro-ATX/Mini-TX  PSU Form Factor: Standard ATX  Fan Cooling System: SIDE: 1 x 120MM (NOT INCLUDED)  Rear: 1 x 80MM (not included)  Case: L275*W150*H350  Material: 0.35mm SPCC Black  Drive ', 500, 2, 'ph-11134201-23030-3i801wi59xovf2.jpg', 'NOVUS'),
(63, 'Apevia ATX-PM1000W Premier 1000W Gaming Power Supply', 'Powersupply', '1000W 80+ Gold Certified Active PFC ATX Gaming Semi-Modular Power Supply. Semi Modular Design Supports Cable Management. Connectors: 1 x 20/24pin Main Power, 2 x P8(4+4P) ESP 12V, 4 x SATA + 4 x 8(6+2', 5000, 2, '71wdbgPFo9S._AC_SL1188_.jpg', 'Apevia'),
(64, 'Inplay True Rated 450W/550W/650W/750W 80Plus ATX Power Supply', 'Powersupply', 'INPLAY GP450-PRO | Rated 450W Power Supply | 80 Plus Bronze Certified', 700, 2, '7b3e3f47c647c41e7dd4d2e5ef27517a.png_2200x2200q80.png_.webp', 'INPLAY'),
(65, 'Intel® Core™ 12th Gen i3-12100F desktop processor', 'cpu', 'Intel Core i3-12100F Desktop Processor 4 (4P-0E) Cores Up to 4.3 GHz Turbo Frequency LGA1700 600 Series Chipset 58W Processor Base Power', 5000, 2, '51C8njBn7mL._AC_SL1000_.jpg', 'Intel'),
(66, 'Vengeance RGB Pro 32GB (2x16GB) DDR4 3600 (PC4-28800) C18 AMD Optimized Memory', 'ram', 'High performance DDR4 memory illuminates your system with vivid, animated lighting from ten ultra-bright, individually addressable RGB LEDs per module. Take control with CORSAIR iCUE software and sync', 5000, 2, '61GpY38PAWL._AC_SL1200_.jpg', 'CORSAIR'),
(67, 'Ram . TForce Delta RGB 16GB (8GBx2)', 'ram', 'Product Specifications Series	DELTA RGB Module Type	 288 Pin Unbuffered DIMM Non ECC', 3900, 2, '4753e765f9d0bd09570777781ca37c3d.jpg', 'TForce'),
(68, 'Set D', 'set', 'AASDAS', 20000, 1, 'SetA.jpg', 'ROG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`aid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`aid`,`pid`),
  ADD KEY `cartfk2` (`pid`);

--
-- Indexes for table `order-details`
--
ALTER TABLE `order-details`
  ADD PRIMARY KEY (`oid`,`pid`),
  ADD KEY `orderdtfk2` (`pid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`oid`),
  ADD KEY `ordersfk` (`aid`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cartfk1` FOREIGN KEY (`aid`) REFERENCES `accounts` (`aid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cartfk2` FOREIGN KEY (`pid`) REFERENCES `products` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order-details`
--
ALTER TABLE `order-details`
  ADD CONSTRAINT `orderdtfk1` FOREIGN KEY (`oid`) REFERENCES `orders` (`oid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderdtfk2` FOREIGN KEY (`pid`) REFERENCES `products` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `ordersfk` FOREIGN KEY (`aid`) REFERENCES `accounts` (`aid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
