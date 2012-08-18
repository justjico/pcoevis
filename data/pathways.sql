--
-- Database: `pathways`
--

-- --------------------------------------------------------

--
-- Table structure for table `pathways`
--

DROP TABLE IF EXISTS `pathways`;
CREATE TABLE IF NOT EXISTS `pathways` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `PathwayA` varchar(400) DEFAULT NULL,
  `PA_id` int(11) NOT NULL,
  `GenesA` int(2) DEFAULT NULL,
  `PathwayB` varchar(400) DEFAULT NULL,
  `PB_id` int(11) NOT NULL,
  `GenesB` int(3) DEFAULT NULL,
  `Overlap` int(1) DEFAULT NULL,
  `OverlapMetric` decimal(65,30) DEFAULT NULL,
  `Correlation` decimal(65,30) DEFAULT NULL,
  `Adj_correlation` decimal(65,30) DEFAULT NULL,
  `Adjacency` decimal(65,30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `PA_id` (`PA_id`),
  KEY `PB_id` (`PB_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
