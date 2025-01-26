-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: fvs
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `buyer_cart`
--

DROP TABLE IF EXISTS `buyer_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buyer_cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `buyer_cart_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer_registration` (`buyer_id`) ON DELETE CASCADE,
  CONSTRAINT `buyer_cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_management` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buyer_cart`
--

LOCK TABLES `buyer_cart` WRITE;
/*!40000 ALTER TABLE `buyer_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `buyer_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buyer_registration`
--

DROP TABLE IF EXISTS `buyer_registration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buyer_registration` (
  `buyer_id` int NOT NULL AUTO_INCREMENT,
  `buyer_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` bigint NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`buyer_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone_number` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buyer_registration`
--

LOCK TABLES `buyer_registration` WRITE;
/*!40000 ALTER TABLE `buyer_registration` DISABLE KEYS */;
INSERT INTO `buyer_registration` VALUES (1,'test','test@gmail.com',1234567890,'Pune','$2y$10$hfrqercnPaqUeC6aJGRPTeH4mAkbXvB/ye8EfG1dxG/E/UBQE9oSO','2025-01-21 18:15:53','2025-01-25 19:18:54'),(2,'rohit','rohit@gmail.com',9067792489,'latur','$2y$10$rhCxrwVFwQo85uN5jYDzf.RHCIt.2Ewr4H001bMR/wTv5SyLdcW7e','2025-01-22 07:21:01','2025-01-22 07:21:01'),(3,'ganesh s','ganeshs@gmail.com',7414978082,'Nilanga','$2y$10$4wIYfASvDjoGvFbVOhhCEOqMzw.u7fSsV0cC1ivxWRD8OPQ.5NgGu','2025-01-26 06:55:48','2025-01-26 06:55:48');
/*!40000 ALTER TABLE `buyer_registration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `farmer_registration`
--

DROP TABLE IF EXISTS `farmer_registration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `farmer_registration` (
  `farmer_id` int NOT NULL AUTO_INCREMENT,
  `farmer_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` bigint NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`farmer_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone_number` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `farmer_registration`
--

LOCK TABLES `farmer_registration` WRITE;
/*!40000 ALTER TABLE `farmer_registration` DISABLE KEYS */;
INSERT INTO `farmer_registration` VALUES (1,'test','test@gmail.com',1234567890,'latur','$2y$10$0wtRp4Suz23eySzP0SIVhOfGl/e686y8RFK/eeZIIuj428JyuYgxu','2025-01-21 17:05:38','2025-01-25 19:32:44'),(3,'test2','test2@gmail.com',1122334455,'latur','$2y$10$Vcs/fvchjUF9zSpjjZTa7es3THeYo12R535eaxMB6o0NQgeNEQI5y','2025-01-21 17:11:14','2025-01-21 17:35:25'),(4,'test3','test3@gmail.com',1112223330,'latur','1234','2025-01-21 17:13:02','2025-01-21 17:13:02'),(5,'test','test4@gmail.com',1122112211,'latur','$2y$10$XT6pUeRQjTbb0DlvUctyQuiZUoVD2w5Fr5y9CLMvGVbKP2vd01DFe','2025-01-21 17:43:40','2025-01-21 17:45:52'),(7,'test5','test5@gmail.com',1100110011,'latur','$2y$10$o.ySGQwmezm2sbMdNjSGEulrhcD4guWVJqG3F4hFEFq712nlqS.je','2025-01-21 17:51:00','2025-01-21 17:51:00'),(8,'amol','amol@gmail.com',1010101010,'latur','$2y$10$R.T.Q82UkfrO2aI0bVcDlOe0e4v32R/zow8kDB4ZrIaM.nIwllmhC','2025-01-23 12:57:22','2025-01-23 12:57:22'),(10,'ganesh','ganesh@gmail.com',7414978080,'Nilanga','$2y$10$wVMUz.m9ip.VaEVuRR14F.Qx1Mw/GpDpgy45ZxbvLe/sXHzjimJbO','2025-01-26 06:52:23','2025-01-26 06:52:23');
/*!40000 ALTER TABLE `farmer_registration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `order_detail_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  `total_price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_detail_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_management` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_management` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,1,2,100,200,'2025-01-23 18:33:59','2025-01-23 18:33:59'),(2,2,1,2,100,200,'2025-01-23 18:38:21','2025-01-23 18:38:21'),(3,3,1,2,100,200,'2025-01-23 18:40:57','2025-01-23 18:40:57'),(4,4,1,2,100,200,'2025-01-23 18:42:17','2025-01-23 18:42:17'),(5,5,1,2,100,200,'2025-01-23 18:44:31','2025-01-23 18:44:31'),(6,6,2,2,250,500,'2025-01-23 20:01:12','2025-01-23 20:01:12'),(7,6,1,2,100,200,'2025-01-23 20:01:12','2025-01-23 20:01:12'),(8,7,2,2,250,500,'2025-01-25 17:51:06','2025-01-25 17:51:06'),(9,7,1,2,100,200,'2025-01-25 17:51:06','2025-01-25 17:51:06'),(10,8,2,2,250,500,'2025-01-25 17:51:23','2025-01-25 17:51:23'),(11,8,1,2,100,200,'2025-01-25 17:51:23','2025-01-25 17:51:23'),(12,9,2,2,250,500,'2025-01-25 17:57:56','2025-01-25 17:57:56'),(13,9,1,2,100,200,'2025-01-25 17:57:56','2025-01-25 17:57:56'),(14,10,1,2,100,200,'2025-01-25 18:07:25','2025-01-25 18:07:25'),(15,11,1,2,100,200,'2025-01-25 19:03:16','2025-01-25 19:03:16'),(16,11,2,1,250,250,'2025-01-25 19:03:16','2025-01-25 19:03:16'),(17,11,5,2,50,100,'2025-01-25 19:03:16','2025-01-25 19:03:16'),(18,12,1,2,100,200,'2025-01-25 19:35:23','2025-01-25 19:35:23'),(19,14,7,3,500,1500,'2025-01-26 06:57:35','2025-01-26 06:57:35'),(20,14,1,2,100,200,'2025-01-26 06:57:35','2025-01-26 06:57:35'),(21,15,5,2,50,100,'2025-01-26 07:24:03','2025-01-26 07:24:03'),(22,16,7,1,500,500,'2025-01-26 07:33:15','2025-01-26 07:33:15'),(23,16,2,1,250,250,'2025-01-26 07:33:15','2025-01-26 07:33:15');
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_management`
--

DROP TABLE IF EXISTS `order_management`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_management` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL,
  `farmer_id` int NOT NULL,
  `total_amount` int NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `order_status` varchar(50) NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_placed` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `farmer_id` (`farmer_id`),
  CONSTRAINT `order_management_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer_registration` (`buyer_id`) ON DELETE CASCADE,
  CONSTRAINT `order_management_ibfk_2` FOREIGN KEY (`farmer_id`) REFERENCES `farmer_registration` (`farmer_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_management`
--

LOCK TABLES `order_management` WRITE;
/*!40000 ALTER TABLE `order_management` DISABLE KEYS */;
INSERT INTO `order_management` VALUES (1,1,1,200,'credit_card','Pending','latur','2025-01-23 18:33:59','2025-01-23 18:33:59','2025-01-24 00:22:07'),(2,1,1,200,'credit_card','Pending','latur','2025-01-23 18:38:21','2025-01-23 18:38:21','2025-01-24 00:22:07'),(3,1,1,200,'credit_card','Pending','latur','2025-01-23 18:40:57','2025-01-23 18:40:57','2025-01-24 00:22:07'),(4,1,1,200,'credit_card','Pending','latur','2025-01-23 18:42:17','2025-01-23 18:42:17','2025-01-24 00:22:07'),(5,1,1,200,'credit_card','Pending','latur','2025-01-23 18:44:31','2025-01-23 18:44:31','2025-01-24 00:22:07'),(6,1,1,700,'debit_card','Pending','latur','2025-01-23 20:01:12','2025-01-23 20:01:12','2025-01-24 01:31:12'),(7,1,1,700,'credit_card','Pending','latur','2025-01-25 17:51:05','2025-01-25 17:51:05','2025-01-25 23:21:05'),(8,1,1,700,'credit_card','Pending','latur','2025-01-25 17:51:23','2025-01-25 17:51:23','2025-01-25 23:21:23'),(9,1,1,700,'cod','Pending','Latur','2025-01-25 17:57:56','2025-01-25 17:57:56','2025-01-25 23:27:56'),(10,1,1,200,'cod','Pending','Latur','2025-01-25 18:07:25','2025-01-25 18:07:25','2025-01-25 23:37:25'),(11,1,1,550,'debit_card','Processing','Pune','2025-01-25 19:03:16','2025-01-25 19:03:16','2025-01-26 00:33:16'),(12,1,1,200,'debit_card','Processing','Pune','2025-01-25 19:35:23','2025-01-25 19:35:23','2025-01-26 01:05:23'),(13,1,7,100,'Credit Card','confirmed','1234 Main St, City','2025-01-25 19:52:14','2025-01-25 19:52:14','2025-01-26 01:22:14'),(14,3,1,1700,'debit_card','Processing','Latur','2025-01-26 06:57:35','2025-01-26 06:57:35','2025-01-26 12:27:35'),(15,3,1,100,'cod','Pending','Latur','2025-01-26 07:24:03','2025-01-26 07:24:03','2025-01-26 12:54:03'),(16,3,1,750,'cod','Pending','latur','2025-01-26 07:33:15','2025-01-26 07:33:15','2025-01-26 13:03:15');
/*!40000 ALTER TABLE `order_management` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_history`
--

DROP TABLE IF EXISTS `payment_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_history` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `payment_amount` int NOT NULL,
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `payment_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order_management` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_history`
--

LOCK TABLES `payment_history` WRITE;
/*!40000 ALTER TABLE `payment_history` DISABLE KEYS */;
INSERT INTO `payment_history` VALUES (1,1,'credit_card','Pending',200,'2025-01-23 18:33:59'),(2,2,'credit_card','Pending',200,'2025-01-23 18:38:21'),(3,3,'credit_card','Pending',200,'2025-01-23 18:40:57'),(4,4,'credit_card','Pending',200,'2025-01-23 18:42:17'),(5,5,'credit_card','Pending',200,'2025-01-23 18:44:31'),(6,6,'debit_card','Pending',700,'2025-01-23 20:01:12'),(7,7,'credit_card','Pending',700,'2025-01-25 17:51:06'),(8,8,'credit_card','Pending',700,'2025-01-25 17:51:23'),(9,9,'cod','Pending',700,'2025-01-25 17:57:56'),(10,10,'cod','Pending',200,'2025-01-25 18:07:25'),(11,11,'debit_card','Success',550,'2025-01-25 19:03:16'),(12,12,'debit_card','Success',200,'2025-01-25 19:35:23'),(13,14,'debit_card','Success',1700,'2025-01-26 06:57:35'),(14,15,'cod','Pending',100,'2025-01-26 07:24:03'),(15,16,'cod','Pending',750,'2025-01-26 07:33:15');
/*!40000 ALTER TABLE `payment_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_feedback`
--

DROP TABLE IF EXISTS `product_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_feedback` (
  `feedback_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `buyer_id` int NOT NULL,
  `rating` int NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `response` text,
  PRIMARY KEY (`feedback_id`),
  KEY `product_id` (`product_id`),
  KEY `buyer_id` (`buyer_id`),
  CONSTRAINT `product_feedback_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product_management` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `product_feedback_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `buyer_registration` (`buyer_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_feedback`
--

LOCK TABLES `product_feedback` WRITE;
/*!40000 ALTER TABLE `product_feedback` DISABLE KEYS */;
INSERT INTO `product_feedback` VALUES (1,1,3,4,'good quality','2025-01-26 07:58:22','2025-01-26 07:58:22',NULL),(2,7,3,5,'good price','2025-01-26 07:59:24','2025-01-26 07:59:52','thank you');
/*!40000 ALTER TABLE `product_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_management`
--

DROP TABLE IF EXISTS `product_management`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_management` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `farmer_id` int NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text,
  `price` int NOT NULL,
  `quantity_available` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  KEY `farmer_id` (`farmer_id`),
  CONSTRAINT `product_management_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmer_registration` (`farmer_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_management`
--

LOCK TABLES `product_management` WRITE;
/*!40000 ALTER TABLE `product_management` DISABLE KEYS */;
INSERT INTO `product_management` VALUES (1,7,'uploads/1737553353_apple.jpg','Apple','Eat an apple a day, keep doctor away',100,200,'2025-01-22 13:42:33','2025-01-22 13:42:33'),(2,7,'uploads/1737553822_mango.jpg','Mango','Mango is the king of Fruits',250,100,'2025-01-22 13:50:22','2025-01-22 13:50:22'),(3,7,'uploads/1737554138_mango.jpg','Mango','Mango is the king of Fruits',250,100,'2025-01-22 13:55:38','2025-01-22 13:55:38'),(5,7,'uploads/1737636787_banana.jpg','Banana','It has higher iron',50,150,'2025-01-23 12:53:07','2025-01-23 12:53:26'),(6,8,'uploads/1737637139_banana.jpg','Banana','It have iron',90,50,'2025-01-23 12:58:59','2025-01-23 13:00:05'),(7,10,'uploads/strawberry.jpg','Strawberry','Good choice for your Skin',500,100,'2025-01-26 06:54:52','2025-01-26 06:54:52');
/*!40000 ALTER TABLE `product_management` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-26 13:53:45
