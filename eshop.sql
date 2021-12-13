CREATE TABLE `products`(
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `title` varchar(50) COLLATE utf8_romanian_ci NOT NULL,
    `description` varchar(500) COLLATE utf8_romanian_ci NOT NULL,
    `price` float(6,2) NOT NULL,
    `fileType` varchar(10) COLLATE utf8_romanian_ci NOT NULL,
    KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;

CREATE TABLE `orders`(
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `userName` varchar(50) COLLATE utf8_romanian_ci NOT NULL,
    `contactDetails` varchar(500) COLLATE utf8_romanian_ci NOT NULL,
    `comments` varchar(50) COLLATE utf8_romanian_ci NOT NULL,
    `datetime` varchar(50) COLLATE utf8_romanian_ci NOT NULL,
    KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;

CREATE TABLE `pivot_order`(
    `idProd` int(10) NOT NULL,
    `idOrder` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;
--
--  Indexes FOR TABLE `products`
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`)
--
--  Indexes FOR TABLE `orders`
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`)