-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 14, 2026 lúc 06:27 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `car_rental`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `enquiries`
--

CREATE TABLE `enquiries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `membership_level` varchar(50) DEFAULT 'basic',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `reset_otp` varchar(10) DEFAULT NULL,
  `otp_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `membership_level`, `created_at`, `phone`, `avatar`, `reset_otp`, `otp_expire`) VALUES
(1, 'Lorca Le', 'adminLorca@system.com', '$2y$10$V9n0dN5aVrXMf9aPOgyCa.vtGLqUIfgaHEy12zxkUnNQ5Z3LZOzVy', 'basic', '2026-03-13 11:11:27', '0859609735', 'uploads/avatars/1773400983_Tom-Holland-4-1122x631.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `Branch` varchar(50) NOT NULL,
  `price_per_day` int(11) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `transmission` varchar(50) DEFAULT NULL,
  `fuel_type` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `brand_logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `pickup_time` time DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `return_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `vehicles`
--

INSERT INTO `vehicles` (`id`, `name`, `Branch`, `price_per_day`, `seats`, `transmission`, `fuel_type`, `image`, `available`, `created_at`, `image2`, `image3`, `brand_logo`, `description`, `rating`, `location`, `pickup_date`, `pickup_time`, `return_date`, `return_time`) VALUES
(1, 'Honda Civic', 'Honda', 700000, 5, 'Automatic', 'Petrol', 'Civic.jpg', 1, '2026-03-13 10:25:34', 'civic2.jpg', 'civic3.jpg', 'Honda-logo.jpg', 'The Honda Civic is one of the standout C-segment sedans, boasting a sporty design, powerful performance, and an exhilarating driving experience. With its modern exterior, refined interior, and advanced safety technology, the Civic appeals to customers who appreciate a distinctive, sporty style and demand a high-quality driving feel. At CarRental, the Honda Civic is officially distributed in three versions: RS, G, and RS e:HEV, catering to a wide range of customers – from young individuals and business professionals to small families seeking a sedan that is both elegant and efficient.', 4.5, 'Ho Chi Minh City', '2026-03-16', NULL, '2026-03-18', NULL),
(2, 'KIA Carnival', 'KIA', 1200000, 7, 'Automatic', 'Diesel', 'Carnival.jpg', 1, '2026-03-13 10:25:34', NULL, NULL, 'KIA-logo.jpg', 'Experience the ultimate in group travel with the Kia Carnival, a versatile multi-purpose vehicle that combines the luxurious space of a minivan with the bold, modern styling of an SUV. Designed to accommodate up to eight passengers in premium comfort, its incredibly roomy cabin features flexible VIP seating and tri-zone climate control, making it perfect for family vacations or executive transport. At the center of the dashboard is an intuitive, high-resolution infotainment system equipped with seamless smartphone integration to keep everyone connected and entertained on the go. To make your journey even more effortless, the Carnival boasts smart power sliding doors and a hands-free tailgate, allowing you to easily load up passengers and luggage without missing a beat.', 4.0, 'Ha Noi City', '2026-03-16', NULL, '2026-03-19', NULL),
(3, 'Mazda CX5', 'Mazda', 800000, 5, 'Automatic', 'Petrol', 'CX5.jpg', 1, '2026-03-13 10:25:34', NULL, NULL, 'Mazda-logo.jpg', 'A stylish and responsive compact SUV offering premium comfort, excellent fuel efficiency, and advanced safety features. Perfect for city driving or weekend getaways.', 3.5, 'Ho Chi Minh City', '2026-03-18', NULL, '2026-03-20', NULL),
(4, 'Vinfast VF8', 'Vinfast', 900000, 5, 'Automatic', 'Electric', 'VF8.jpg', 1, '2026-03-13 10:25:34', 'VF8-1.jpg', 'VF8-2.jpg', 'Vinfast-logo.jpg', 'Step into the future of driving with the VinFast VF8, a premium all-electric SUV that blends sleek design with eco-friendly performance. This spacious, zero-emissions vehicle provides a whisper-quiet ride and instantaneous acceleration, making it perfect for any modern traveler. At the heart of its minimalist cabin is a stunning 15.6-inch interactive touchscreen that serves as your complete command center for navigation and entertainment. Elevating the experience further is the advanced \"Hey VinFast\" voice assistant, allowing you to control the climate, windows, and media completely hands-free while you focus on the road.', 4.0, 'Ho Chi Minh City', '2026-03-20', NULL, '2026-03-24', NULL),
(5, 'Mitsubishi Xpander', 'Mitsubishi', 800000, 7, 'Automatic', 'Petrol', 'Xpander.jpg', 1, '2026-03-14 15:44:47', 'Xpander1.jpg', 'Xpander2.jpg', 'Mitsubishi-logo.jpg', 'The Mitsubishi Xpander is a highly popular 7-seater favored for its outstanding versatility and practicality. Its distinctive Dynamic Shield design gives the vehicle a rugged and striking presence on the street. The flexible interior allows you to fold down the rear rows perfectly flat to maximize cargo space. Offering impressive fuel efficiency, it serves as a highly economical transportation solution for large groups.', 4.6, 'Ho Chi Minh City', NULL, NULL, NULL, NULL),
(6, 'Toyota Vios', 'Toyota', 600000, 5, 'Automatic', 'Petrol', 'Vios.jpg', 1, '2026-03-14 15:44:47', 'Vios1.jpg', 'Vios2.jpg', 'Toyota-logo.jpg', 'The Toyota Vios is renowned for its outstanding reliability, low maintenance costs, and exceptional durability. The updated exterior presents a more youthful and dynamic appearance than its predecessors. Inside, the cabin offers ample space for five adults along with a generously sized luggage compartment. Its stable performance and excellent fuel economy make it a highly practical choice for daily city driving.', 4.5, 'Hanoi City', NULL, NULL, NULL, NULL),
(7, 'Honda CR-V', 'Honda', 1200000, 5, 'Automatic', 'Petrol', 'CRV.jpg', 1, '2026-03-14 15:44:47', 'CRV1.jpg', 'CRV2.jpg', 'Honda-logo.jpg', 'The Honda CR-V is a globally recognized compact SUV known for its perfect balance of performance and comfort. It features a sophisticated exterior design paired with a thoughtfully laid out, driver-centric cabin. The powerful turbocharged engine delivers thrilling acceleration while keeping fuel consumption impressively low. Advanced safety features ensure peace of mind for you and your family during every adventure.', 4.8, 'Da Nang City', NULL, NULL, NULL, NULL),
(8, 'Hyundai Accent', 'Hyundai', 650000, 5, 'Automatic', 'Petrol', 'Accent.jpg', 1, '2026-03-14 15:44:47', 'Accent1.jpg', 'Accent2.jpg', 'Hyundai-logo.jpg', 'The Hyundai Accent is a stylish compact sedan that perfectly blends modern aesthetics with everyday functionality. It features a bold front grille and dynamic lines that give it a sporty edge. The well-appointed interior is packed with user-friendly technology and comfortable seating for daily commutes. With its agile handling and efficient powertrain, it navigates narrow city streets with remarkable ease.', 4.6, 'Can Tho City', NULL, NULL, NULL, NULL),
(9, 'Ford Ranger', 'Ford', 1100000, 5, 'Automatic', 'Diesel', 'ranger.jpg', 1, '2026-03-14 15:44:47', 'ranger1.jpg', 'ranger2.jpg', 'Ford-logo.jpg', 'The Ford Ranger is a robust pickup truck designed to handle both tough work environments and weekend adventures. Its muscular exterior styling immediately communicates strength and exceptional off-road capability. Despite its rugged nature, the cabin provides SUV-like comfort and advanced infotainment systems. A high-torque diesel engine allows you to conquer challenging terrains or carry heavy loads effortlessly.', 4.7, 'Lam Dong', NULL, NULL, NULL, NULL),
(10, 'Kia Seltos', 'Kia', 900000, 5, 'Automatic', 'Petrol', 'Seltos.jpg', 1, '2026-03-14 15:44:47', 'Seltos1.jpg', 'Seltos2.jpg', 'KIA.jpg', 'The Kia Seltos is a trendy subcompact crossover that appeals to modern drivers with its striking exterior. The spacious interior boasts high-quality materials and a large touchscreen display for seamless connectivity. Its elevated driving position provides excellent visibility and confidence when maneuvering through busy traffic. The responsive engine and smooth transmission create an engaging and enjoyable daily driving experience.', 4.6, 'Dong Nai', NULL, NULL, NULL, NULL),
(11, 'Toyota Fortuner', 'Toyota', 1400000, 7, 'Automatic', 'Diesel', 'Fortuner.jpg', 1, '2026-03-14 15:44:47', 'Fortuner1.jpg', 'Fortuner2.jpg', 'Toyota-logo.jpg', 'The Toyota Fortuner is a rugged 7-seat SUV built on a durable body-on-frame platform for maximum toughness. Its imposing stance and aggressive front fascia command attention wherever it goes. The versatile three-row cabin is designed to comfortably accommodate large families and all their travel gear. With its proven four-wheel-drive system and high ground clearance, it effortlessly tackles unpaved roads and steep inclines.', 4.7, 'Ho Chi Minh City', NULL, NULL, NULL, NULL),
(12, 'Hyundai Santa Fe', 'Hyundai', 1500000, 7, 'Automatic', 'Diesel', 'SantaFe.jpg', 1, '2026-03-14 15:44:47', 'SantaFe1.jpg', 'SantaFe2.jpg', 'Hyundai-logo.jpg', 'The Hyundai Santa Fe is a premium family SUV that delivers an exceptional combination of luxury and utility. The striking exterior is highlighted by a distinctive grille and modern LED lighting signatures. Inside, the whisper-quiet cabin features soft-touch materials, panoramic views, and premium audio for ultimate relaxation. A refined powertrain ensures rapid yet incredibly smooth acceleration on long highway cruises.', 4.8, 'Ho Chi Minh City', NULL, NULL, NULL, NULL),
(13, 'VinFast VF5', 'VinFast', 600000, 5, 'Automatic', 'Electric', 'VF5.jpg', 1, '2026-03-14 15:44:47', 'VF5-1.jpg', 'VF5-2.jpg', 'Vinfast-logo.jpg', 'The VinFast VF5 is an agile and compact electric vehicle perfectly tailored for urban environments. Its vibrant color options and youthful styling make it a standout choice for modern city dwellers. The minimalist interior maximizes space efficiency while integrating seamless smart connectivity features. Offering zero emissions and incredibly low running costs, it represents the future of accessible daily transportation.', 4.5, 'Ho Chi Minh City', NULL, NULL, NULL, NULL),
(14, 'Mazda 3', 'Mazda', 850000, 5, 'Automatic', 'Petrol', 'Mazda3.jpg', 1, '2026-03-14 15:44:47', 'Mazda31.jpg', 'Mazda32.jpg', 'Mazda-logo.jpg', 'The Mazda 3 is a beautifully sculpted compact car that elevates the standard of automotive design. Its minimalist yet luxurious interior rivals premium European brands in both material quality and layout. The precise steering and sport-tuned suspension deliver an incredibly engaging and dynamic driving experience. It perfectly balances thrilling performance with impressive fuel economy for the enthusiastic driver.', 4.7, 'Hanoi City', NULL, NULL, NULL, NULL),
(15, 'Honda City', 'Honda', 700000, 5, 'Automatic', 'Petrol', 'City.jpg', 1, '2026-03-14 15:44:47', 'City1.jpg', 'City2.jpg', 'Honda-logo.jpg\r\n', 'The Honda City is a sophisticated subcompact sedan that consistently leads its class in interior space. Its sleek aerodynamic profile enhances both visual appeal and high-speed stability on the highway. The exceptionally roomy rear seats provide comfort levels typically found in much larger vehicle segments. A rev-happy yet efficient engine makes it both fun to drive and highly practical to own.', 4.6, 'Dong Nai', NULL, NULL, NULL, NULL),
(16, 'Suzuki XL7', 'Suzuki', 800000, 7, 'Automatic', 'Petrol', 'XL7.jpg', 1, '2026-03-14 15:44:47', 'XL7-1.jpg', 'XL7-2.jpg', 'Suzuki-logo.jpg', 'The Suzuki XL7 is a crossover-styled MPV that offers the perfect blend of SUV looks and family practicality. Its muscular fenders and roof rails give it an adventurous and rugged personality. The highly practical 7-seat layout provides excellent headroom and legroom for all passengers on board. Powered by a dependable and remarkably efficient engine, it is a smart choice for budget-conscious families.', 4.5, 'Binh Duong', NULL, NULL, NULL, NULL),
(17, 'Kia Morning', 'Kia', 450000, 4, 'Automatic', 'Petrol', 'Morning.jpg', 1, '2026-03-14 15:47:17', 'Morning1.jpg', 'Morning2.jpg', 'KIA-logo.jpg', 'The Kia Morning is a highly agile and compact hatchback perfect for navigating tight city streets. Its cheerful exterior design is matched by a surprisingly well-equipped and user-friendly interior cabin. Despite its small footprint, it offers excellent visibility and effortless parking in crowded urban environments. An incredibly fuel-efficient engine makes it the ultimate budget-friendly choice for daily commuting.', 4.5, 'Dong Nai', NULL, NULL, NULL, NULL),
(18, 'Hyundai Grand i10', 'Hyundai', 500000, 4, 'Automatic', 'Petrol', 'i10.jpg', 1, '2026-03-14 15:47:17', 'i101.jpg', 'i102.jpg', 'Hyundai-logo.jpg', 'The Hyundai Grand i10 is a popular city car known for offering class-leading interior space and comfort. Its modern exterior features fluid lines and a signature cascading grille that catches the eye. The cabin boasts superior build quality and advanced entertainment features rarely found in this segment. A responsive powertrain ensures a smooth and remarkably quiet ride during your daily urban journeys.', 4.6, 'Ho Chi Minh City', NULL, NULL, NULL, NULL),
(19, 'VinFast Fadil', 'VinFast', 550000, 4, 'Automatic', 'Petrol', 'Fadil.jpg', 1, '2026-03-14 15:47:17', 'Fadil1.jpg', 'Fadil2.jpg', 'Vinfast-logo.jpg', 'The VinFast Fadil is a premium small hatchback built on a sturdy European-engineered chassis. Its sporty exterior styling gives it a dynamic and confident presence on Vietnamese roads. Inside, the vehicle comes loaded with top-tier safety features and a highly comfortable seating arrangement. A surprisingly powerful engine delivers thrilling acceleration while maintaining excellent stability at high speeds.', 4.7, 'Dong Nai', NULL, NULL, NULL, NULL),
(20, 'Toyota Wigo', 'Toyota', 500000, 4, 'Automatic', 'Petrol', 'Wigo.jpg', 1, '2026-03-14 15:47:17', 'Wigo1.jpg', 'Wigo2.jpg', 'Toyota-logo.jpg', 'The Toyota Wigo is a practical and dependable compact car designed for maximum everyday utility. Its sharp and aggressive front fascia adds a touch of sporty flair to its compact dimensions. The remarkably spacious cabin provides ample legroom and a highly efficient climate control system for tropical weather. Renowned Toyota reliability and exceptionally low maintenance costs make it a very smart long-term investment.', 4.4, 'Binh Duong', NULL, NULL, NULL, NULL),
(21, 'Honda Brio', 'Honda', 550000, 4, 'Automatic', 'Petrol', 'Brio.jpg', 1, '2026-03-14 15:47:17', NULL, NULL, NULL, 'The Honda Brio is a stylish and energetic hatchback that appeals strongly to youthful drivers. Its striking rear design with a large glass panel gives it a uniquely modern and sporty look. The driver-focused interior is logically laid out and maximizes every inch of available space. Nimble handling and a highly efficient engine make every city drive an enjoyable and economical experience.', 4.5, 'Da Nang City', NULL, NULL, NULL, NULL),
(22, 'Mercedes-Benz C300 AMG', 'Mercedes-Benz', 2000000, 5, 'Automatic', 'Petrol', 'c300.jpg', 1, '2026-03-14 16:55:33', 'c3001.jpg', NULL, 'Mer-logo.jpg', 'The Mercedes-Benz C300 AMG brings a perfect blend of sporty aggression and elegant luxury to the compact executive class. Its meticulously crafted cabin features premium materials, customizable ambient lighting, and the intuitive MBUX infotainment system. A potent turbocharged engine paired with an advanced suspension system provides a thrilling yet incredibly smooth driving experience. This prestigious sedan is an excellent choice for business professionals and those seeking refined daily transportation.', 4.8, 'Ho Chi Minh City', NULL, NULL, NULL, NULL),
(23, 'Mercedes-Benz GLC 300', 'Mercedes-Benz', 2500000, 5, 'Automatic', 'Petrol', 'glc300.jpg', 1, '2026-03-14 16:55:33', 'glc3001.jpg', NULL, 'Mer-logo.jpg', 'The Mercedes-Benz GLC 300 is a highly sought-after luxury crossover that effortlessly combines practicality with sophisticated styling. The spacious interior offers exceptional comfort with heavily bolstered leather seats and state-of-the-art acoustic insulation. Equipped with the legendary 4MATIC all-wheel-drive system, it handles challenging weather and diverse road conditions with absolute confidence. It represents the pinnacle of premium family transport for both urban commutes and weekend getaways.', 4.9, 'Hanoi City', NULL, NULL, NULL, NULL),
(24, 'BMW 330i M Sport', 'BMW', 2200000, 5, 'Automatic', 'Petrol', 'bmw3.jpg', 1, '2026-03-14 16:55:33', 'bmw31.jpg', NULL, 'bmw-logo.jpg', 'The BMW 330i M Sport is a dynamic luxury sedan built for drivers who crave exhilarating performance and precise handling. Its aggressive aerodynamic styling package immediately distinguishes it from standard models on the road. The driver-focused cockpit is engineered for absolute control, featuring heavily contoured sport seats and a thick steering wheel. With its perfectly balanced chassis and punchy engine, every corner becomes an opportunity for driving enjoyment.', 4.8, 'Dong Nai', NULL, NULL, NULL, NULL),
(25, 'BMW X3', 'BMW', 2400000, 5, 'Automatic', 'Petrol', 'x3.jpg', 1, '2026-03-14 16:55:33', 'x31.jpg', 'x32.jpg', 'bmw-logo.jpg', 'The BMW X3 is a versatile premium compact SUV that sets the benchmark for driving dynamics in its segment. Its bold exterior features a prominent kidney grille and athletic proportions that project a strong sense of capability. Inside, passengers are treated to a beautifully appointed cabin with ample headroom, legroom, and generous cargo capacity. The intelligent xDrive system continuously monitors traction, ensuring optimal grip and safety across all driving environments.', 4.7, 'Da Nang City', NULL, NULL, NULL, NULL),
(26, 'Audi Q5', 'Audi', 2300000, 5, 'Automatic', 'Petrol', 'q5.jpg', 1, '2026-03-14 16:55:33', 'q51.jpg', 'q52.jpg', 'Audi-logo.jpg', 'The Audi Q5 is a masterclass in understated elegance, offering a refined and highly sophisticated luxury SUV experience. Its exterior design is characterized by sharp character lines and the signature Singleframe grille. The remarkably quiet cabin showcases impeccable German build quality and features the innovative Audi Virtual Cockpit digital display. Powered by a responsive turbocharged engine and the renowned quattro all-wheel-drive system, it delivers secure and effortless progress.', 4.8, 'Ho Chi Minh City', NULL, NULL, NULL, NULL),
(27, 'Audi A4', 'Audi', 1900000, 5, 'Automatic', 'Petrol', 'a4.jpg', 1, '2026-03-14 16:55:33', NULL, NULL, 'Audi-logo.jpg', 'The Audi A4 is a sharply dressed luxury sedan that seamlessly blends cutting-edge technology with timeless design aesthetics. Its finely tuned suspension expertly absorbs road imperfections while maintaining excellent body control through tight corners. The intuitively designed interior provides a serene environment with deeply supportive seats and premium acoustic glass. It serves as a highly capable and exceptionally comfortable executive cruiser for daily journeys and long trips alike.', 4.6, 'Ho Chi Minh City', NULL, NULL, NULL, NULL),
(28, 'Lexus RX 350', 'Lexus', 3000000, 5, 'Automatic', 'Petrol', 'rx350.jpg', 1, '2026-03-14 16:55:33', 'rx3501.jpg', 'rx3502.jpg', 'Lexus-logo.jpg', 'The Lexus RX 350 is the gold standard for mid-size luxury SUVs, renowned for its unmatched reliability and resale value. Its bold spindle grille and sharp LED headlights give it a commanding and highly distinctive presence. Inside, the meticulously crafted cabin surrounds occupants with ultra-soft leather, exquisite wood trims, and whisper-quiet serenity. A buttery-smooth engine delivers seamless power, prioritizing ultimate ride comfort over aggressive sporty dynamics.', 4.9, 'Ho Chi Minh City', NULL, NULL, NULL, NULL),
(29, 'Toyota Innova', 'Toyota', 900000, 7, 'Automatic', 'Petrol', 'innova.jpg', 1, '2026-03-14 16:55:33', NULL, NULL, 'Toyota-logo.jpg', 'The Toyota Innova is an iconic and incredibly durable MPV that has long been a staple of Vietnamese roads. Its spacious and airy cabin is thoughtfully designed to comfortably accommodate large families and extended groups. Built on a tough rear-wheel-drive chassis, it easily handles heavy passenger loads and uneven provincial road surfaces. Legendary reliability and easily accessible spare parts make it the ultimate worry-free choice for long-distance travel.', 4.6, 'Binh Duong', NULL, NULL, NULL, NULL),
(30, 'Ford Everest', 'Ford', 1500000, 7, 'Automatic', 'Diesel', 'everest.jpg', 1, '2026-03-14 16:55:33', NULL, NULL, 'Ford-logo.jpg', 'The Ford Everest is a formidable mid-size SUV that pairs exceptional off-road capability with highly refined on-road manners. Its imposing muscular exterior is complemented by a remarkably quiet and technologically advanced three-row cabin. The torquey diesel engine provides massive pulling power, making it perfect for towing or conquering steep mountain passes. An array of advanced safety systems and luxurious amenities ensure passengers travel in both ultimate comfort and absolute security.', 4.8, 'Dong Nai', NULL, NULL, NULL, NULL),
(31, 'Hyundai Tucson', 'Hyundai', 1100000, 5, 'Automatic', 'Petrol', 'tucson.jpg', 1, '2026-03-14 16:55:33', 'tucson1.jpg', NULL, 'Hyundai-logo.jpg', 'The Hyundai Tucson is a strikingly modern compact crossover that completely redefines the visual standards of its class. Its parametric hidden lights and bold geometric surface details create a futuristic and highly unique road presence. The spacious interior features a dual-cockpit design that intuitively wraps around the driver and front passenger. A refined powertrain and supple suspension tune make it an exceptionally pleasant companion for daily urban commuting.', 4.7, 'Can Tho City', NULL, NULL, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Chỉ mục cho bảng `enquiries`
--
ALTER TABLE `enquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `enquiries`
--
ALTER TABLE `enquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Các ràng buộc cho bảng `enquiries`
--
ALTER TABLE `enquiries`
  ADD CONSTRAINT `enquiries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
