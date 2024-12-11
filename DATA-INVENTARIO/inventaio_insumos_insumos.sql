-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: inventaio_insumos
-- ------------------------------------------------------
-- Server version	8.0.36

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
-- Table structure for table `insumos`
--

DROP TABLE IF EXISTS `insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `insumos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tipo` char(50) DEFAULT NULL,
  `codigo` int DEFAULT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_cre` date DEFAULT NULL,
  `fecha_ven` date DEFAULT NULL,
  `retirante` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_ret` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insumos`
--

LOCK TABLES `insumos` WRITE;
/*!40000 ALTER TABLE `insumos` DISABLE KEYS */;
INSERT INTO `insumos` VALUES (4,'Hibuprofeno','Medicamento',45862,'2024-05-31','2024-06-12','2027-07-15',NULL,NULL),(10,'Hibuprofeno','Medicamento',15684,'2024-06-02','2024-06-01','2024-06-14',NULL,NULL),(11,'Tapa boca','Insumo',1526,'2024-06-08','2024-06-08','2024-06-08','Fernando','2024-07-07'),(12,'Tapa boca','Insumo',1564,'2024-06-09','2024-06-08','2024-06-08',NULL,NULL),(14,'Hibuprofeno','Medicamento',12345,'2024-06-09','2024-06-08','2024-06-28','Pedro','2024-07-05'),(15,'Hibuprofeno','Medicamento',1562,'2024-06-09','2024-06-09','2024-06-20','Pedro','2024-06-27'),(16,'Atamel','Medicamento',1563,'2024-06-09','2024-06-09','2024-06-08',NULL,NULL),(17,'Tapa boca','Insumo',1234567,'2024-06-09','2024-06-09','2024-07-05','Pedro','2024-06-26'),(18,'Loratadina','Medicamento',30346174,'2024-06-09','2024-06-09','2024-06-13',NULL,NULL),(20,'teragrip','Medicamento',987654,'2024-06-22','2024-06-20','2024-06-30',NULL,NULL),(22,'Teragrip','Medicamento',159753,'2024-06-22','2024-06-22','2024-06-29',NULL,NULL);
/*!40000 ALTER TABLE `insumos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-11 18:31:33
