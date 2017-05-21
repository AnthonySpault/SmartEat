-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Dim 21 Mai 2017 à 16:39
-- Version du serveur :  10.0.30-MariaDB-0+deb8u2
-- Version de PHP :  5.6.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `SmartEat`
--
CREATE DATABASE IF NOT EXISTS `SmartEat` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `SmartEat`;

-- --------------------------------------------------------

--
-- Structure de la table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `defaultAddress` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `streetNumber` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` int(11) NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FRANCE',
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `addresses`
--

INSERT INTO `addresses` (`id`, `userid`, `defaultAddress`, `streetNumber`, `street`, `zipcode`, `city`, `country`, `firstname`, `lastname`, `phone`) VALUES
(1, 1, 'true', '95', 'Avenue Parmentier', 75011, 'Paris', 'FRANCE', 'Administrateur', 'SmartEat', '0610203040'),
(2, 2, 'false', '15', 'Avenue des Champs-Élysées', 75008, 'Paris-8E-Arrondissement', 'FRANCE', 'John', 'Doe', '0690807060'),
(3, 2, 'true', '95', 'Avenue Parmentier', 75011, 'Paris', 'FRANCE', 'John', 'Doe', '0690807060');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `products` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `total` float NOT NULL,
  `tips` float NOT NULL,
  `billingaddress` int(11) NOT NULL,
  `shippingaddress` int(11) NOT NULL,
  `orderdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `orders`
--

INSERT INTO `orders` (`id`, `userid`, `products`, `total`, `tips`, `billingaddress`, `shippingaddress`, `orderdate`) VALUES
(1, 2, '1x Gratin Dauphinois;1x Shake de fruits printaniers : Fraise, Coco, kiwi, menthe;1x Coca-Cola 33cl', 15, 4, 2, 2, '2017-05-21 19:30:49'),
(2, 2, '1x Fondant au chocolat façon SmartEat', 5.5, 0, 2, 2, '2017-05-21 19:34:09');

-- --------------------------------------------------------

--
-- Structure de la table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `partners`
--

INSERT INTO `partners` (`id`, `firstname`, `lastname`, `email`, `phone`, `date`) VALUES
(1, 'Otto ', 'Beisheim', 'otto.beishem@metro.com', '0685457574', '2017-05-21 20:31:00'),
(2, 'Jean-Paul', ' Mochet', 'jean-paul.mochet@franprix.com', '0685455254', '2017-05-21 20:37:00');

-- --------------------------------------------------------

--
-- Structure de la table `plates`
--

CREATE TABLE `plates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `ingredients` text COLLATE utf8_unicode_ci NOT NULL,
  `allergenes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `trick` text COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `plates`
--

INSERT INTO `plates` (`id`, `name`, `description`, `ingredients`, `allergenes`, `trick`, `image`, `price`, `category`, `status`) VALUES
(1, 'Nouilles de riz au boeuf fondant et aux légumes', 'Des oignons, du gingembre, de l\'ail, de la coriandre fraîche, des oignons cébettes et du sésame grillé parfument ces vermicelles de riz le tout avec un bœuf fondant', 'ail, poivron rouge, sucre, carotte, gingembre frais, oignon cébette, huile de tournesol, huile de sésame, coriandre fraîche, sésame, oignon blanc, vermicelle de riz, sauce de tamari bio, paleron de boeuf, piment frais rouge', 'sésame, piment', 'Versez la sauce et savourez. A consommer avec notre tiramisu maison pour un repas équilibré et gourmand.\r\n', 'uploads/plates_img/1.png', 6.95, 'dish', 'active'),
(2, 'Bavette à la moutarde violette & gratin', 'Des tranches de bavette fondante servi avec une sauce à l’échalote et à la moutarde pour des saveurs inoubliables. Accompagner par un délicieux gratin de pomme de terre et artichaut pour un moment appétissant.\r\n', 'ail, sucre, beurre, pomme de terre, vin blanc, artichaut, crème liquide, échalote, lait, huile de tournesol, sel et poivre, bavette de boeuf, moutarde violette, ciboulette et thym frais\r\n', 'lactose, moutarde, sulfite', 'Réchauffez le plat pendant 2mn, à consommer avec le délicieux jus de pomme que nous vous proposons. \r\n', 'uploads/plates_img/2.png', 8.5, 'dish', 'active'),
(3, 'Tartelette au caramel et beurre salé  ', '                         Une tartelette fondante et savoureuse qui sent bon la Bretagne. Goutez-là Il n’y a pas de mal à s’offrir du plaisir.', '                         sucre, beurre, Farine, Sel, Amande, crème liquide, oeuf plein air, fleur de sel', '                 ', '                         Vous pouvez ajouter une petite boule de glace vanille dessus pour un moment gourmand', 'uploads/plates_img/3.png', 3, 'dessert', 'active'),
(4, 'Tiramisu maison', 'Un délice pour le palais, un mascarpone au café saupoudré de cacao qui vous donnera envie de le savourer jusqu’à la dernière cuillère.\n', ': sucre, crème liquide, oeuf, vanille, mascarpone, café, biscuits à la cuillère, cacao\r\n', 'Lactose, gluten', 'A consommer froid avec un délicieux jus de pomme pour un moment incomparable.', 'uploads/plates_img/4.png', 2.5, 'dessert', 'inactive'),
(5, 'Shake de fruits printaniers : Fraise, Coco, kiwi, menthe', 'Des fruits savoureux et croquants avec une touche de menthe.', 'Menthe, kiwi, noix de coco, fraise', 'aucun', 'A consommer un délicieux jus de pomme pour un moment fruité.', 'uploads/plates_img/5.png', 3.5, 'dessert', 'active'),
(6, 'Bionina citron', 'Boire du soda sans colorant, pesticide et conservateurs c’est avec Bionina et ses fruits bio. Ici la version citron.', '', '', '', 'uploads/plates_img/6.png', 1.5, 'drink', 'active'),
(7, 'Coca-Cola Zéro 33cl', 'Canette de Coca-Cola zéro 33cl.', '', '', '', 'uploads/plates_img/7.png', 1, 'drink', 'active'),
(8, 'Blue Keld Pêche 50cl', 'Provenant d’une source anglaise et follement rafraichissante dans sa version pêche.', '', '', '', 'uploads/plates_img/8.png', 2, 'drink', 'active'),
(9, 'Coca-Cola 33cl', 'Canette de Coca-Cola 33cl.', '', '', '', 'uploads/plates_img/9.png', 1, 'drink', 'active'),
(10, 'San Pellegrino 50cl', 'Bouteille de San Pellegrino 50cl.', '', '', '', 'uploads/plates_img/10.png', 1, 'drink', 'active'),
(11, 'Jus de pomme 25cl', 'Provenant des vergers français, Délicatement acidulé et naturellement bon.', '', '', '', 'uploads/plates_img/11.png', 2.5, 'drink', 'active'),
(12, 'Houmous épinard', '  Un houmous qui sent bon le printemps, une entrée fraiche et onctueuse avec de l’épinard pour rester tonique toute la journée.', '  ail, graine de courge, pousse d’épinard, huile d’olive, tahini, pois-chiche', '  sésame', '  A consommer froid et avec une bonne tranche de pain, c’est un régal&amp;nbsp;!', 'uploads/plates_img/12.png', 3.5, 'extra', 'active'),
(42, 'Bortsh', ' \r\nLe bortsch est un potage préparé dans plusieurs pays slaves.\r\nRetrouvez dans ce plat un assortiment de couleurs qui vous donnera le sourire, ainsi qu’une odeur sans pareil. Découvrez les sensations scandinaves avec ce plat préparé aux petits oignons', ' crème épaisse\r\n- tomates\r\n- betteraves\r\n- navet\r\n- céleri rave\r\n- pommes de terre\r\n- chou\r\n- poireaux\r\n- gousses d\'ail\r\n- cuillères à soupe de beurre\r\n-  cuillères à soupe de vinaigre\r\n- sucre\r\n- citron\r\n- sel, poivre\r\n', ' lactose', ' Se marie très bien  avec de la crème additionnée d\'un peu de jus de citron ', 'uploads/plates_img/42.png', 8, 'dish', 'inactive'),
(43, 'Gratin Dauphinois', 'Plat cuisiné à partir de pommes de terre. Il s\'agit de pommes de terre coupées en fines rondelles avec de la crème fraîche et du fromage par dessus. Le tout est cuit au four pour gratiner.', '- pommes de terre\r\n- gousses d\'ail\r\n- crème\r\n- beurre\r\n- lait\r\n- muscade\r\n- sel\r\n- poivre\r\n', 'Lactose', 'Ajouter un peu d\'huile de truffe pour parfumé légèrement le plat', 'uploads/plates_img/43.jpg', 6.5, 'dish', 'active'),
(46, 'Fondant au chocolat façon SmartEat', 'Ce fondant est beaucoup moins calorique que l’ordinaire grâce à une utilisation du beurre doux ! Le goût étant encore meilleur ! Bien tendre au cœur, il vous fera déglutir de plaisir en un clin d’œil !', '- chocolat à cuire\r\n- beurre doux\r\n- sucre semoule\r\n- oeufs\r\n- farine', 'Lactose', 'N\'hésitez pas à rajouter une petite touche de crème anglaise pour encore plus de plaisir', 'uploads/plates_img/46.jpg', 3.5, 'dessert', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `points` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `role`, `firstname`, `lastname`, `email`, `password`, `phone`, `points`) VALUES
(1, 'admin', 'Administrateur', 'SmartEat', 'admin@smarteat.fr', '$2y$10$L580y4vs/gUoixzUEuGlq.EVFbi0U9aIN5TzB2Scrw8N0lnyYbk1G', '0610203040', 10),
(2, 'client', 'John', 'Doe', 'john@doe.com', '$2y$10$hEc1KMi61MhOPrV.PlB1rusV6F3a0fj/916/6cmHMu15snUTa8PI6', '0690807060', 26);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Index pour la table `plates`
--
ALTER TABLE `plates`
  ADD PRIMARY KEY (`id`,`name`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `plates`
--
ALTER TABLE `plates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
