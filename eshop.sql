CREATE TABLE `products`(
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `title` varchar(50) COLLATE utf8_romanian_ci NOT NULL,
    `description` varchar(500) COLLATE utf8_romanian_ci NOT NULL,
    `price` float(6,2) NOT NULL,
    `fileType` varchar(10) COLLATE utf8_romanian_ci NOT NULL,
    KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;


INSERT INTO `products`(`title`,`description`,`price`,`fileType`) VALUES
('Water-repellent jacket','Padded jacket in windproof, water-repellent functional fabric designed to keep you dry during light showers and protect you from the wind.','250','.jpg'),
('Twill overshirt','Overshirt in twill with a collar, buttons down the front and a yoke at the back. Flap chest pockets with a button, and long sleeves with buttoned cuffs.','170','.jpg'),
('Cashmere jumper','Jumper in soft, fine-knit cashmere with a round neckline, long sleeves and ribbing around the neckline, cuffs and hem.','80','.jpg'),
('Slim Jeans','5-pocket jeans in stretch cotton denim with a regular waist, zip fly and button, and slim legs.','100','.jpg'),
('Relaxed Fit jumper','Jumper in a soft knit containing some wool with dropped shoulders, long sleeves and ribbing around the neckline, cuffs and hem.','99','.jpg'),
('Relaxed Fit Hoodie','Hoodie in sweatshirt fabric made from a cotton blend. Relaxed fit with a lined, drawstring hood, long sleeves, kangaroo pocket and ribbing at the cuffs and hem. Soft brushed inside.','199','.jpg');


CREATE TABLE `orders`(
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `userName` varchar(50) COLLATE utf8_romanian_ci NOT NULL,
    `contactDetails` varchar(500) COLLATE utf8_romanian_ci NOT NULL,
    `comments` varchar(50) COLLATE utf8_romanian_ci NOT NULL,
    `productsId` varchar(500) COLLATE utf8_romanian_ci NOT NULL,
    `datetime` varchar(50) COLLATE utf8_romanian_ci NOT NULL,
    KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;
--
--  Indexes FOR TABLE `products`
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`)
--
--  Indexes FOR TABLE `orders`
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`)