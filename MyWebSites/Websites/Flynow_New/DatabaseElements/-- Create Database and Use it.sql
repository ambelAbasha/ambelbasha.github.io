-- Create Database and Use it
CREATE DATABASE IF NOT EXISTS flynow;
USE flynow;

-- Create Tables with Foreign Keys
CREATE TABLE `airline_company` (
  `air_com_id` int(11) NOT NULL AUTO_INCREMENT,
  `comp_name` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`air_com_id`),
  UNIQUE KEY `comp_name` (`comp_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `user_auth` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `passenger` (
  `passenger_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_name` varchar(20) DEFAULT NULL,
  `p_lastname` varchar(20) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`passenger_id`),
  KEY `passenger_user_fk` (`user_id`),
  CONSTRAINT `passenger_user_fk` FOREIGN KEY (`user_id`) REFERENCES `user_auth` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `flight` (
  `flight_id` int(11) NOT NULL AUTO_INCREMENT,
  `arrival_date` datetime DEFAULT NULL,
  `departure_date` datetime DEFAULT NULL,
  `destination_airport` varchar(40) DEFAULT NULL,
  `departure_airport` varchar(40) DEFAULT NULL,
  `business_class` int(11) DEFAULT NULL,
  `economy_seat` int(11) DEFAULT NULL,
  `price_economy` float(6,2) DEFAULT NULL,
  `price_business` float(6,2) DEFAULT NULL,
  `air_com_id` int(11) NOT NULL,
  `selected` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`flight_id`),
  KEY `air_com_id` (`air_com_id`),
  CONSTRAINT `flight_ibfk_1` FOREIGN KEY (`air_com_id`) REFERENCES `airline_company` (`air_com_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `passenger_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `booking_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`booking_id`),
  KEY `passenger_id` (`passenger_id`),
  KEY `flight_id` (`flight_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`passenger_id`) REFERENCES `passenger` (`passenger_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`flight_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reserv` (
  `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`reservation_id`),
  KEY `fid` (`fid`),
  KEY `username` (`username`),
  CONSTRAINT `reserv_ibfk_1` FOREIGN KEY (`fid`) REFERENCES `flight` (`flight_id`) ON DELETE CASCADE,
  CONSTRAINT `reserv_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user_auth` (`username`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
