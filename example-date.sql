SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dns-api`
--

-- --------------------------------------------------------

--
-- Table structure for table `domain`
--

CREATE TABLE IF NOT EXISTS `domain` (
  `id` int(10) unsigned NOT NULL,
  `fqdn` varchar(255) CHARACTER SET ascii NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tabela FQDN';

--
-- Dumping data for table `domain`
--

INSERT INTO `domain` (`id`, `fqdn`) VALUES
(1, 'its.edu.rs'),
(2, 'bancaintesa.rs'),
(3, 'bbc.com'),
(4, 'microsoft.com'),
(5, 'apple.com'),
(6, 'netflix.com'),
(7, 'alibaba.com'),
(8, 'amazon.com');

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

CREATE TABLE IF NOT EXISTS `record` (
  `id` int(10) unsigned NOT NULL,
  `type` enum('NS','A','AAAA','CNAME','MX','PTR','SOA','TXT','AXFR','SRV','CAA','none') NOT NULL DEFAULT 'A',
  `domain` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `val` text,
  `ttl` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=ascii COMMENT='Tabela DNS zapisa';

--
-- Dumping data for table `record`
--

INSERT INTO `record` (`id`, `type`, `domain`, `name`, `val`, `ttl`) VALUES
(1, 'A', 1, 'www.its.edu.rs', '144.76.229.30', 14400),
(2, 'A', 2, 'www.bancaintesa.rs', '193.227.213.223', 3600),
(3, 'A', 3, 'www.bbc.com', '151.101.84.81', 14400),
(4, 'MX', 1, 'mail.its.edu.rs', '144.76.229.30', 3600),
(5, 'A', 7, 'www.alibaba.com', '2.19.118.106', 3600),
(6, 'A', 8, 'www.amazon.com', '162.219.225.118', 14400);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `domain`
--
ALTER TABLE `domain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_domain` (`domain`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `domain`
--
ALTER TABLE `domain`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `record`
--
ALTER TABLE `record`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `record`
--
ALTER TABLE `record`
  ADD CONSTRAINT `FK_domain` FOREIGN KEY (`domain`) REFERENCES `domain` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
