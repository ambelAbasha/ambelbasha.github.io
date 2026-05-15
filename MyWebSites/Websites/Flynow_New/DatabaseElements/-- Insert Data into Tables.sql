-- Insert Data into Tables
USE flynow;

INSERT INTO `airline_company` (`air_com_id`, `comp_name`) VALUES
(1, 'Aegean Airlines'),
(2, 'AirFrance');

INSERT INTO `user_auth` (`user_id`, `username`, `password`, `email`, `created_at`) VALUES
(14, 'ambelbasha1976', '$2y$10$fdQ/4Nr86OMiwyHLD/3Re.Tjl27O92UZ0JiI8CbC4.UsqnbHINE4q', 'ambelbasha@hotmail.com', '2024-05-04 00:01:06'),
(15, 'ambelbasha28071976', '$2y$10$IkWCMI.UaTCArz/RG77voO17TU3vtu7E9SKrcuLYO45Iuwt90XW9O', 'ambelbasha@hotmail.com', '2024-05-04 00:01:31'),
(16, 'NEWLINK', '$2y$10$LpllpXYUi8M9piBO32Tc9upwHRwRAOzVLpbiaE0jF1BKFvutKIhNW', 'ambelbasha@gmail.com', '2024-05-31 17:37:31'),
(17, 'cs121105', '$2y$10$8oIdFeW8la6saNGe2.1EW.MyMiZ6205YrYqLdWty39jClabLdv8ne', 'cs121105@uniwa.gr', '2024-05-31 18:52:07');

INSERT INTO `passenger` (`passenger_id`, `p_name`, `p_lastname`, `city`, `user_id`) VALUES
(1, 'John', 'Doe', 'New York', NULL),
(2, 'Jane', 'Smith', 'Los Angeles', NULL),
(3, 'Alice', 'Johnson', 'Chicago', NULL);

INSERT INTO `booking` (`booking_id`, `passenger_id`, `flight_id`, `booking_date`) VALUES
(16, 1, 1, '2024-06-01 09:00:00'),
(17, 2, 2, '2024-06-02 10:00:00');

INSERT INTO `flight` (`flight_id`, `arrival_date`, `departure_date`, `destination_airport`, `departure_airport`, `business_class`, `economy_seat`, `price_economy`, `price_business`, `air_com_id`, `selected`) VALUES
(1, '2024-06-01 10:30:00', '2024-06-01 08:30:00', 'barcelona', 'athens', 10, 100, 199.99, 599.99, 1, 0),
(2, '2024-06-01 14:30:00', '2024-06-01 12:30:00', 'athens', 'barcelona', 5, 50, 219.99, 619.99, 2, 0),
(3, '2024-06-01 18:30:00', '2024-06-01 16:30:00', 'london', 'barcelona', 10, 100, 239.99, 639.99, 1, 0),
(4, '2024-06-02 10:30:00', '2024-06-02 08:30:00', 'athens', 'london', 10, 100, 249.99, 649.99, 2, 0),
(5, '2024-06-02 14:30:00', '2024-06-02 12:30:00', 'london', 'athens', 10, 100, 259.99, 659.99, 1, 0),
(6, '2024-06-02 18:30:00', '2024-06-02 16:30:00', 'barcelona', 'london', 10, 100, 279.99, 669.99, 2, 0),
(7, '2024-06-03 09:30:00', '2024-06-03 07:30:00', 'barcelona', 'athens', 10, 100, 289.99, 679.99, 1, 0),
(8, '2024-06-03 13:30:00', '2024-06-03 11:30:00', 'london', 'athens', 10, 100, 299.99, 689.99, 2, 0),
(9, '2024-06-03 17:30:00', '2024-06-03 15:30:00', 'athens', 'barcelona', 10, 100, 309.99, 699.99, 1, 0),
(10, '2024-06-03 21:30:00', '2024-06-03 19:30:00', 'athens', 'london', 10, 100, 319.99, 709.99, 2, 0);

INSERT INTO `reserv` (`reservation_id`, `fid`, `username`) VALUES
(9, 1, 'ambelbasha1976'),
(10, 2, 'ambelbasha28071976');
