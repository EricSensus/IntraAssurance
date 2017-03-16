SET FOREIGN_KEY_CHECKS = 0;

-- 
-- Table structure for table `itr_accesslevels` 
-- 

DROP TABLE IF EXISTS `itr_accesslevels`;
CREATE TABLE `itr_accesslevels` (
`id` int(11) NOT NULL auto_increment,
`name` text NOT NULL,
`alias` text NOT NULL,
`description` text NOT NULL,
`level` int(11) NOT NULL,
`permissions` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_articles` 
-- 

DROP TABLE IF EXISTS `itr_articles`;
CREATE TABLE `itr_articles` (
`id` int(11) NOT NULL auto_increment,
`title` text NOT NULL,
`body` longtext NOT NULL,
`catid` int(11) NOT NULL,
`publishdate` int(11) NOT NULL,
`enabled` text NOT NULL,
`hits` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_car_models` 
-- 

DROP TABLE IF EXISTS `itr_car_models`;
CREATE TABLE `itr_car_models` (
`id` int(10) NOT NULL auto_increment,
`make_id` int(10) NOT NULL,
`code` varchar(125) NOT NULL,
`title` varchar(125) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_categories` 
-- 

DROP TABLE IF EXISTS `itr_categories`;
CREATE TABLE `itr_categories` (
`id` int(11) NOT NULL auto_increment,
`categoryname` text NOT NULL,
`parentid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_commissions` 
-- 

DROP TABLE IF EXISTS `itr_commissions`;
CREATE TABLE `itr_commissions` (
`id` int(11) NOT NULL auto_increment,
`insurers_id` int(11) NOT NULL,
`products_id` int(11) NOT NULL,
`percentage` float NOT NULL,
`collection_means` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_companies` 
-- 

DROP TABLE IF EXISTS `itr_companies`;
CREATE TABLE `itr_companies` (
`id` int(11) NOT NULL auto_increment,
`name` text NOT NULL,
`email_address` text NOT NULL,
`postal_address` varchar(100) NOT NULL,
`telephone` varchar(100) NOT NULL,
`physical_address` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_countries` 
-- 

DROP TABLE IF EXISTS `itr_countries`;
CREATE TABLE `itr_countries` (
`country_id` int(11) NOT NULL auto_increment,
`country_name` varchar(50) NOT NULL,
`status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`country_id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_customer_data` 
-- 

DROP TABLE IF EXISTS `itr_customer_data`;
CREATE TABLE `itr_customer_data` (
`id` int(11) NOT NULL auto_increment,
`insurer_agents_id` int(11) NOT NULL,
`customers_id` int(11) NOT NULL,
`customer_info` text NOT NULL,
`product_info` text NOT NULL,
`customer_entity_data_id` int(11) NOT NULL,
`pricing` text NOT NULL,
`status` varchar(50) NOT NULL,
`datetime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_customer_entity_data` 
-- 

DROP TABLE IF EXISTS `itr_customer_entity_data`;
CREATE TABLE `itr_customer_entity_data` (
`id` int(11) NOT NULL auto_increment,
`customers_id` int(11) NOT NULL,
`entities_id` int(11) NOT NULL,
`entity_values` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_customer_quotes` 
-- 

DROP TABLE IF EXISTS `itr_customer_quotes`;
CREATE TABLE `itr_customer_quotes` (
`id` int(11) NOT NULL auto_increment,
`customers_id` int(11) NOT NULL,
`products_id` varchar(100) NOT NULL,
`datetime` int(11) NOT NULL,
`introtext` text NOT NULL,
`customer_info` text DEFAULT NULL,
`product_info` text NOT NULL,
`customer_entity_data_id` text NOT NULL,
`amount` text NOT NULL,
`recommendation` int(11) NOT NULL,
`status` varchar(100) NOT NULL,
`acceptedoffer` varchar(100) NOT NULL,
`source` varchar(50) NOT NULL DEFAULT 'Internal',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_customers` 
-- 

DROP TABLE IF EXISTS `itr_customers`;
CREATE TABLE `itr_customers` (
`id` int(11) NOT NULL auto_increment,
`name` text NOT NULL,
`mobile_no` text NOT NULL,
`email` text NOT NULL,
`date_of_birth` int(11) DEFAULT NULL,
`enabled` varchar(20) DEFAULT NULL,
`postal_address` varchar(50) DEFAULT NULL,
`postal_code` varchar(20) DEFAULT NULL,
`regdate` int(11) NOT NULL,
`insurer_agents_id` int(11) DEFAULT NULL,
`additional_info` text DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `esu_customers_esu_insurer_agents_id_fk` (`insurer_agents_id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_customers_documents` 
-- 

DROP TABLE IF EXISTS `itr_customers_documents`;
CREATE TABLE `itr_customers_documents` (
`id` int(11) NOT NULL auto_increment,
`customers_id` int(11) NOT NULL,
`documents_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_documents` 
-- 

DROP TABLE IF EXISTS `itr_documents`;
CREATE TABLE `itr_documents` (
`id` int(11) NOT NULL auto_increment,
`filename` varchar(250) NOT NULL,
`filepath` text NOT NULL,
`description` text NOT NULL,
`doctype` varchar(100) NOT NULL,
`datetime` int(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_entities` 
-- 

DROP TABLE IF EXISTS `itr_entities`;
CREATE TABLE `itr_entities` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(200) NOT NULL,
`alias` varchar(100) NOT NULL,
`entity_types_id` int(11) NOT NULL,
`forms_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_entity_types` 
-- 

DROP TABLE IF EXISTS `itr_entity_types`;
CREATE TABLE `itr_entity_types` (
`id` int(11) NOT NULL auto_increment,
`type` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_forms` 
-- 

DROP TABLE IF EXISTS `itr_forms`;
CREATE TABLE `itr_forms` (
`id` int(11) NOT NULL auto_increment,
`form_name` varchar(100) NOT NULL,
`controls` text NOT NULL,
`map` varchar(100) NOT NULL,
`field_order` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_frontpage` 
-- 

DROP TABLE IF EXISTS `itr_frontpage`;
CREATE TABLE `itr_frontpage` (
`id` int(11) NOT NULL auto_increment,
`articles_id` int(11) NOT NULL,
`order` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_imgmanager` 
-- 

DROP TABLE IF EXISTS `itr_imgmanager`;
CREATE TABLE `itr_imgmanager` (
`id` int(11) NOT NULL auto_increment,
`imgname` varchar(200) NOT NULL,
`filename` varchar(200) NOT NULL,
`imglocation` text NOT NULL,
`uploaddate` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_insurer_agents` 
-- 

DROP TABLE IF EXISTS `itr_insurer_agents`;
CREATE TABLE `itr_insurer_agents` (
`id` int(11) NOT NULL auto_increment,
`names` text NOT NULL,
`physical_location` text NOT NULL,
`telephone_number` varchar(100) NOT NULL,
`email_address` text NOT NULL,
`users_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_insurers` 
-- 

DROP TABLE IF EXISTS `itr_insurers`;
CREATE TABLE `itr_insurers` (
`id` int(11) NOT NULL auto_increment,
`name` text NOT NULL,
`official_name` text NOT NULL,
`email_address` text DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_make` 
-- 

DROP TABLE IF EXISTS `itr_make`;
CREATE TABLE `itr_make` (
`id` int(10) NOT NULL auto_increment,
`code` varchar(55) NOT NULL,
`title` varchar(55) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_medical_pricing` 
-- 

DROP TABLE IF EXISTS `itr_medical_pricing`;
CREATE TABLE `itr_medical_pricing` (
`id` int(11) NOT NULL auto_increment,
`agerange_benefits` varchar(50) DEFAULT NULL,
`P1` int(11) DEFAULT NULL,
`P2` int(11) DEFAULT NULL,
`P3` int(11) DEFAULT NULL,
`P4` int(11) DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_menu_groups` 
-- 

DROP TABLE IF EXISTS `itr_menu_groups`;
CREATE TABLE `itr_menu_groups` (
`id` int(10) unsigned NOT NULL auto_increment,
`title` varchar(48) NOT NULL,
`alias` varchar(100) NOT NULL,
`description` varchar(255) NOT NULL,
`accesslevels_id` int(11) NOT NULL,
`permissions` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `groupid` (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_menus` 
-- 

DROP TABLE IF EXISTS `itr_menus`;
CREATE TABLE `itr_menus` (
`id` int(11) NOT NULL auto_increment,
`linkname` text NOT NULL,
`linkalias` text NOT NULL,
`href` varchar(200) NOT NULL,
`linkorder` int(11) NOT NULL,
`menu_groups_id` text NOT NULL,
`parentid` int(11) NOT NULL,
`published` tinyint(4) NOT NULL,
`home` text DEFAULT NULL,
`params` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_other_covers` 
-- 

DROP TABLE IF EXISTS `itr_other_covers`;
CREATE TABLE `itr_other_covers` (
`id` int(11) NOT NULL auto_increment,
`details` text NOT NULL,
`type` varchar(50) NOT NULL,
`step` varchar(50) NOT NULL,
`user_profiles_id` int(4) NOT NULL,
`customer_data_id` int(11) DEFAULT NULL,
`no_of_covers` int(11) DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_own_company` 
-- 

DROP TABLE IF EXISTS `itr_own_company`;
CREATE TABLE `itr_own_company` (
`id` int(11) NOT NULL auto_increment,
`name` text NOT NULL,
`email_address` text NOT NULL,
`postal_address` varchar(100) NOT NULL,
`telephone` varchar(100) NOT NULL,
`physical_details` text NOT NULL,
`contact_person` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_payment_confirmation` 
-- 

DROP TABLE IF EXISTS `itr_payment_confirmation`;
CREATE TABLE `itr_payment_confirmation` (
`id` int(11) NOT NULL auto_increment,
`customers_id` int(11) NOT NULL,
`customer_quotes_id` int(11) DEFAULT NULL,
`tracking_id` varchar(400) DEFAULT NULL,
`merchant_reference` int(11) DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_personal_cover_pricing` 
-- 

DROP TABLE IF EXISTS `itr_personal_cover_pricing`;
CREATE TABLE `itr_personal_cover_pricing` (
`id` int(11) NOT NULL auto_increment,
`age_bracket` varchar(50) NOT NULL,
`class` varchar(50) NOT NULL,
`band` varchar(50) NOT NULL,
`premium` double(10,2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_policies` 
-- 

DROP TABLE IF EXISTS `itr_policies`;
CREATE TABLE `itr_policies` (
`id` int(11) NOT NULL auto_increment,
`policy_number` varchar(100) NOT NULL,
`customers_id` int(50) NOT NULL,
`issue_date` int(11) NOT NULL,
`start_date` int(50) NOT NULL,
`end_date` int(50) NOT NULL,
`insurers_id` int(50) NOT NULL,
`products_id` int(11) NOT NULL,
`customer_quotes_id` int(11) NOT NULL,
`status` varchar(100) NOT NULL,
`datetime` int(11) NOT NULL,
`currency_code` varchar(20) NOT NULL,
`amount` float DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_policies_documents` 
-- 

DROP TABLE IF EXISTS `itr_policies_documents`;
CREATE TABLE `itr_policies_documents` (
`id` int(11) NOT NULL auto_increment,
`policies_id` int(11) NOT NULL,
`documents_id` int(11) NOT NULL,
`column_4` int(11) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `esu_policies_documents_esu_documents_id_fk` (`documents_id`),
  KEY `esu_policies_documents_esu_policies_id_fk` (`policies_id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_products` 
-- 

DROP TABLE IF EXISTS `itr_products`;
CREATE TABLE `itr_products` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(200) NOT NULL,
`alias` varchar(200) NOT NULL,
`type` int(11) NOT NULL,
`entity_types_id` varchar(100) NOT NULL,
`forms_id` text NOT NULL,
`multiple_entities` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_quotes_documents` 
-- 

DROP TABLE IF EXISTS `itr_quotes_documents`;
CREATE TABLE `itr_quotes_documents` (
`id` int(11) NOT NULL auto_increment,
`customer_quotes_id` int(11) NOT NULL,
`documents_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_rates` 
-- 

DROP TABLE IF EXISTS `itr_rates`;
CREATE TABLE `itr_rates` (
`id` int(11) NOT NULL auto_increment,
`rate_name` text NOT NULL,
`rate_value` text NOT NULL,
`rate_type` text NOT NULL,
`rate_category` text NOT NULL,
`insurer_id` int(11) NOT NULL DEFAULT '14',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_region` 
-- 

DROP TABLE IF EXISTS `itr_region`;
CREATE TABLE `itr_region` (
`id` int(11) NOT NULL auto_increment,
`region` text NOT NULL,
`parent_region_id` int(11) NOT NULL,
`enabled` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_session_data` 
-- 

DROP TABLE IF EXISTS `itr_session_data`;
CREATE TABLE `itr_session_data` (
`id` int(100) NOT NULL auto_increment,
`session_id` varchar(100) NOT NULL,
`token` varchar(100) NOT NULL,
`user` longtext NOT NULL,
`session_data` text NOT NULL,
`session_expire` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `id` (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_site_activity` 
-- 

DROP TABLE IF EXISTS `itr_site_activity`;
CREATE TABLE `itr_site_activity` (
`id` int(11) NOT NULL auto_increment,
`insurance_type` varchar(11) NOT NULL,
`hits` int(11) NOT NULL,
`datetime` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_subscriber_data` 
-- 

DROP TABLE IF EXISTS `itr_subscriber_data`;
CREATE TABLE `itr_subscriber_data` (
`sbid` int(11) NOT NULL auto_increment,
`subid` int(11) NOT NULL,
`step1data` text NOT NULL,
`step2data` text NOT NULL,
`step3data` text NOT NULL,
`status` varchar(50) NOT NULL,
`datetime` int(11) NOT NULL,
`datecompleted` int(11) DEFAULT NULL,
  PRIMARY KEY  (`sbid`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_subscribers` 
-- 

DROP TABLE IF EXISTS `itr_subscribers`;
CREATE TABLE `itr_subscribers` (
`subid` int(11) NOT NULL auto_increment,
`title` varchar(5) DEFAULT NULL,
`name` varchar(250) NOT NULL,
`mobile` varchar(20) NOT NULL,
`email` varchar(250) NOT NULL,
`dob` date DEFAULT NULL,
`enabled` tinyint(1) DEFAULT '0',
`postal_address` varchar(50) DEFAULT NULL,
`postal_code` varchar(20) DEFAULT NULL,
`town` varchar(100) DEFAULT NULL,
`registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`subid`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_tasks` 
-- 

DROP TABLE IF EXISTS `itr_tasks`;
CREATE TABLE `itr_tasks` (
`id` int(11) NOT NULL auto_increment,
`customers_id` int(11) NOT NULL,
`dategen` int(11) NOT NULL,
`tasktype` varchar(100) NOT NULL,
`subject` text NOT NULL,
`description` text NOT NULL,
`priority` int(11) NOT NULL,
`remainder` int(11) NOT NULL,
`insurer_agents_id` int(11) NOT NULL,
`completed` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_travel_pricing` 
-- 

DROP TABLE IF EXISTS `itr_travel_pricing`;
CREATE TABLE `itr_travel_pricing` (
`id` int(11) NOT NULL auto_increment,
`plan` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_user_profiles` 
-- 

DROP TABLE IF EXISTS `itr_user_profiles`;
CREATE TABLE `itr_user_profiles` (
`id` int(11) NOT NULL auto_increment,
`name` text NOT NULL,
`mobile_no` text NOT NULL,
`email` text NOT NULL,
`enabled` text NOT NULL,
`regdate` int(11) NOT NULL,
`postal_address` varchar(100) DEFAULT NULL,
`date_of_birth` varchar(100) DEFAULT NULL,
`postal_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `itr_users` 
-- 

DROP TABLE IF EXISTS `itr_users`;
CREATE TABLE `itr_users` (
`id` int(11) NOT NULL auto_increment,
`username` varchar(200) NOT NULL,
`password` varchar(300) NOT NULL,
`accesslevels_id` text NOT NULL,
`user_profiles_id` int(11) NOT NULL,
`insurer_agents_id` int(11) DEFAULT NULL,
`enabled` text NOT NULL,
`last_login` int(15) DEFAULT NULL,
`permissions` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_accesslevels` 
-- 

INSERT INTO `itr_accesslevels` (`id`, `name`, `alias`, `description`, `level`, `permissions`) VALUES ('7','Super System Administrator','super-system-administrator','','5',''),
 ('9','Administrator','admin','','4',''),
 ('15','Editor','editor','','3','{"agents":{"create":true,"read":true,"edit":true,"delete":false,"save":true,"agentlogin":false},"companies":{"read":true,"getinsurer":false,"saveinsurer":false,"delete":false,"save":true},"insurers":{"read":true,"getcommissions":false,"savecommissions":false},"products":{"read":true,"create":false,"createproduct":false,"delete":false,"addedit":false},"customers":{"create":true,"read":true,"edit":false,"delete":false,"editentity":false},"users":{"manage":false,"login":false},"policies":{"read":true,"readunprocessedpolicies":false,"edit":true,"export":true,"printer":true,"saveedits":false,"create":true,"save":true,"issuepolicy":false},"quotes":{"read":true,"create":true,"edit":true,"export":true,"printer":true,"delete":true,"save":true},"entities":{"add":false,"edit":false,"delete":false},"tasks":{"add":true,"edit":true,"show":true,"delete":true},"front":{"access":true}}'),
 ('16','Subscriber','subscriber','','2','{"agents":{"create":false,"read":true,"edit":false,"delete":false,"save":false,"agentlogin":false},"companies":{"read":true,"getinsurer":false,"saveinsurer":false,"delete":false,"save":false},"insurers":{"read":true,"getcommissions":false,"savecommissions":false},"products":{"read":true,"create":false,"createproduct":false,"delete":false,"addedit":false},"customers":{"create":false,"read":true,"edit":false,"delete":false,"editentity":false},"users":{"manage":false,"login":false},"policies":{"read":true,"readunprocessedpolicies":false,"edit":false,"export":true,"printer":true,"saveedits":false,"create":false,"save":false,"issuepolicy":false},"quotes":{"read":true,"create":false,"edit":false,"export":true,"printer":true,"delete":false,"save":false},"entities":{"add":false,"edit":false,"delete":false},"tasks":{"add":true,"edit":true,"show":true,"delete":true},"front":{"access":true}}'),
 ('17','Guest','guest','','1','{"agents":{"create":false,"read":false,"edit":false,"delete":false,"save":false,"agentlogin":false},"companies":{"read":false,"getinsurer":false,"saveinsurer":false,"delete":false,"save":false},"insurers":{"read":false,"getcommissions":false,"savecommissions":false},"products":{"read":false,"create":false,"createproduct":false,"delete":false,"addedit":false},"customers":{"create":false,"read":false,"edit":false,"delete":false,"editentity":false},"users":{"manage":false,"login":false},"policies":{"read":false,"readunprocessedpolicies":false,"edit":false,"export":false,"printer":false,"saveedits":false,"create":false,"save":false,"issuepolicy":false},"quotes":{"read":false,"create":false,"edit":false,"export":false,"printer":false,"delete":false,"save":false},"entities":{"add":false,"edit":false,"delete":false},"tasks":{"add":false,"edit":false,"show":false,"delete":false},"front":{"access":true}}'),
 ('18','Tech Administrator','tech-administrator','','6','');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_articles` 
-- 

INSERT INTO `itr_articles` (`id`, `title`, `body`, `catid`, `publishdate`, `enabled`, `hits`) VALUES ('523','About Bima 247','<p>Bima247.com is the new smart, simple, fast, convenient, and secure way to purchase your insurance online in Kenya. It\'s been developed by some of the top insurance experts around and run by Emmanet Insurance, which is authorised and regulated by Kenya\'s Insurance Regulatory Authority (IRA).</p>\r\n<p>We are industry leaders shaping how insurance is bought and viewed by millions. Not only that, we take great pride in what we do, and are absolutely committed to providing the best customer care possible. Additionally, we are a team with great expertise and many years of experience in insurance as well as ICTs: pioneers driving innovation in the market that&rsquo;s aimed at putting you firmly in control of your insurances and finances.</p>\r\n<p>What Bima247.com does is bring together the products that help you protect your life, your health, your loved ones, your future and your possessions into one intuitive, interactive simple-to-use and secure place. It is an insurance system built around you and packed with many features and benefits - already being experienced by many many other Kenyans -, some of which are as follows:</p>\r\n<table border="0" width="100%">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<h2 class="headingitalics">You are in control</h2>\r\n</td>\r\n<td>\r\n<h2 class="headingitalics">You are secure</h2>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>\r\n<ul>\r\n<li>Buy insurance whenever you wish wherever you are in the world 24/7 365 days.</li>\r\n<li>At a glance you know what is covered and how much it costs you.</li>\r\n</ul>\r\n</td>\r\n<td>\r\n<ul>\r\n<li>Input your details once in our secure system and we hold them securely until you decide otherwise.</li>\r\n<li>Insurance by a multiple award winning and the largest composite insurer around.</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>\r\n<h2 class="headingitalics">Your convenience</h2>\r\n</td>\r\n<td>\r\n<h2 class="headingitalics">Your Insurer</h2>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td>\r\n<ul>\r\n<li>&lsquo;Click and collect&rsquo; your motor certificate or have it posted for the first time in Kenya.</li>\r\n<li>Text and or email reminder before your renewal so you never forget to renew.</li>\r\n</ul>\r\n</td>\r\n<td>\r\n<ul>\r\n<li>Best claims payer award winner.</li>\r\n<li>Most trusted brand in East Africa award winner.</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h2 class="headingitalics">How It Works</h2>\r\n<p>That&rsquo;s as easy as A, B, C</p>\r\n<p><span style="color: #ff0000;"><strong>A.</strong>&nbsp;</span> You complete the online form which is exactly the same as the standard industry one you would complete if you went to any insurance company or representative: we have just simplified the process and brought it online.</p>\r\n<p><span style="color: #ff0000;"><strong>B</strong>.</span> Form completion takes a few short minutes and you get a quote in seconds.</p>\r\n<p><span style="color: #ff0000;"><strong>C.</strong></span> Once you pay and subject to the proposal being acceptable, you are covered.<br /> <br /><strong> It&rsquo;s that simple!</strong></p>\r\n<p>Contacting us is also simple, just<br /><br /> email<strong> customercare at bima247.com </strong>anytime and we will get back to you right away.<strong><br /><br /></strong><strong><br /></strong></p>','0','1396242000','Yes','0'),
 ('524','FAQs, Knowledge and Help Centre ','<span style="color: #ff0000; font-size: large;"><strong>coming soon...</strong></span><br /><br />......the most extensive knowleedge and help centre around<br /><a title="Read more..." href="?content=com_articles&amp;folder=same&amp;file=articles&amp;artid=530" target="_blank"><br /></a><br /><br /> \r\n<table border="0" width="797" height="126">\r\n<tbody>\r\n<tr>\r\n<td><br /></td>\r\n<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br /></td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td><br /></td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<br />','0','1399266000','Yes','0'),
 ('839','Ground breaking partnerships to enable insurance brokers and agents in Kenya sell insurance online easily and more affordably','<br /><span style="color: #ff0000; font-size: large;">Coming soon...</span>','0','1403845200','Yes','0'),
 ('840','Buying insurance, why that is vital and probably one of the most important things you can do today. ','<br /><span style="color: #ff0000; font-size: large;">Coming soon...</span>','0','1403845200','Yes','0'),
 ('841','What every insurance company executive in Kenya should know about our innovative rating, underwriting and payment system.','<br /><span style="color: #ff0000; font-size: large;">Coming soon...</span>','0','1403845200','Yes','0'),
 ('842','Top things to consider when buying insurance cover.','<br /><span style="color: #ff0000; font-size: large;">Coming soon...</span>','0','1403845200','Yes','0'),
 ('843','Blogs','<br /><span style="color: #ff0000; font-size: large;">Coming soon...</span>','0','1403845200','Yes','0'),
 ('844','Press releases','<br /><span style="color: #ff0000; font-size: large;">Comng soon...</span>','0','1403845200','Yes','0'),
 ('530','Our commitment to superior service','<p>At Bima247.com, we take great pride in what we do, and are absolutely  committed to providing the best customer service care possible. In many  ways we are redefining how insurance is viewed and purchased in the  Kenyan market. As pioneers doing this, we are constantly learning in an  effort to provide the best possible service and are therefore keenly  aware that sometimes we will get things wrong. If we do we will be the  first to hold our hands up and apologise and try make good where we have  gone wrong. In the same vein, if you find us wanting, tell us and we  will be happy to address the situation. Regardless, always remember that  you as the customer will remain at the heart of everything we do.</p>','0','1400475600','Yes','0'),
 ('531','Our Values and Mottos','<p><strong>Our values:</strong></p>\r\n<p><strong>Our customers</strong>. We do value your custom and our commitment is to your   satisfaction and an unrivalled customer care. Which is why we have   invested heavily into a dedicated team and state of art live help   contact centre so that whenever you need to talk to us about your   insurances we are there 24/7 every day of the year. Our guarantee to you   is that we will treat they way we would like to be treated. We know this   because we have set ourselves goals that are exceedingly high since it   is by stretching ourselves that we can go further than expected<em>. </em></p>\r\n<p><strong>Our society.</strong> We are a social enterprise becuase we care deeply about our society and the communities we   live in whether at home or in the Diaspora, and are thus leading the   way in terms of making a real difference in those communities through   innovative sustainable long-term funding solutions. Indeed our key   measure of success is not just how well we do financially but also our   success in making a meaningful impact on the societies and communities   we do business in. We firmly believe these are complementary goals. So in effect we are trying to   leave our society a little better than we find it but are conscious of   the fact that we also have to have a sustainable business that provides   value for our shareholders and customers, and to our mind, these  needn&rsquo;t  be mutually exclusive.</p>\r\n<p><strong>Our colleagues.</strong> We have an innate understanding that our associates   and colleagues are our key to providing you with a superior and   unparalleled service. We know that, and that&rsquo;s why our primary aim is to   make our colleagues and associates happy in the work they do and in   their personal lives, and the rest will take care of itself.</p>\r\n<p><strong>Our vision:</strong></p>\r\n<ul>\r\n<li>To      become the largest insurance agency by digital footprint.</li>\r\n</ul>\r\n<br />','0','1400562000','Yes','0'),
 ('529','Who is providing the insurance cover?','<p>Jubilee Insurance Kenya is the insurer. Jubilee is the largest  composite insurer in the region, and one that has been providing protection  for individuals like you, their loved ones and valued possessions  in Kenya since 1937. It\'s a company known not only for  an excellent customer service ethos but also a well-reputed and  unrivalled claims payment record. Jubilee is indeed a recent multiple  recipient of some of the industry&rsquo;s most prestigious awards including  Composite Insurer of the Year, Medical Insurance Underwriter of the  Year, Customer Satisfaction Award, and Best Claims Settlement Award.  These awards by its peers are certainly a mark of Jubilee&rsquo;s high esteem  within professional circles especially with regards to professionalism,  and a key reason why it is the first choice for Kenyans when it comes to  insurance cover. Jubilee Insurance is also the winner of the &lsquo;East  Africa&rsquo;s Most Trusted Brand&rsquo; Award.</p>\r\n<p>So, in short, you are safe and secure in the knowledge that your  cover is by a multiple award winning insurer in the market, and a  compant with one of the most stable financial capabilities as well as a  solid emphasis on security for policyholders, committed, knowledgeable  and professional staff, and a reputation for superior customer service.  As a demonstration of their commitment to service excellence, Jubilee  has implemented quality management system and is an ISO 9001:2000  certified company.</p>','0','1400475600','Yes','0'),
 ('836','Terms and Conditions for acting as a publisher','<p><strong>Terms and Conditions</strong></p>\r\n<p><strong><br /> for</strong></p>\r\n<p><strong>&nbsp;</strong></p>\r\n<p><strong>Agreement to act as a publisher of our codes within your website and or social media URL and or account and or any other online presence and or property.</strong></p>\r\n<p>&nbsp;</p>\r\n<p>In consideration of us agreeing that you publish our software codes within your website and or social media URL and or account and or any other online presence and or property hereafter referred to as &lsquo;your online presence and or property&rsquo; you may chose to publish the said codes, you hereby consequently agree to abide by the terms, conditions, obligations and rules set in this Agreement document. For the purposes of the Agreement you will be deemed as the &lsquo;Recipient Party&rsquo; to our codes, and us as the &lsquo;Disclosing Party&rsquo;.</p>\r\n<p>&nbsp;</p>\r\n<ol> </ol>1. Your roles and responsibilities as the &lsquo;Recipient Party&rsquo;<ol> </ol>\r\n<p>1.1.&nbsp;&nbsp;&nbsp; Your role is to act as a publisher of our codes as stated in the paragraph above. <strong>IMPORTANT:</strong> please note that embedding, including or adding our codes, links, banners or using any other means that may ultimately lead traffic to our website from your own online property and or presence does not transfer any rights onto you to act as an insurance agent.</p>\r\n<p>1.2.&nbsp;&nbsp;&nbsp; You therefore <strong>MUST ABSOLUTELY NOT ENGAGE</strong> in any selling of insurance and or any thing related; and or giving advise on insurance and or insurance related matters in relation to any of our past, current, or future products or services to any member (s) of the public and or body and or groups of individuals and or entity (ies). <strong>To do this, the law requires that you MUST be registered and regulated by the Insurance Regulatory and or relevant licensing Authorities</strong> in your market. It shall remain your responsibility to abide by the relevant and correct legal requirements.</p>\r\n<p>1.3.&nbsp;&nbsp;&nbsp; You must not introduce any malicious code(s) through any of our links and or software codes.</p>\r\n<p>1.4.&nbsp;&nbsp;&nbsp; You must not manipulate, change in any way, copy without express and written authorisation from us, share and or otherwise use our codes outside of what is expressly written and agreed by us in this Agreement.</p>\r\n<p>1.5.&nbsp;&nbsp;&nbsp; &nbsp;You must not have any software codes that are in competition with and or similar in nature and or intention to our own directly or indirectly, within &lsquo;your online presence and or property&rsquo; during the term of our relationship without our prior written consent and for at least twelve (12) months after its termination.</p>\r\n<br /><ol> </ol>2. Our roles and responsibilities as the &lsquo;Disclosing Party&rsquo;<ol> </ol>\r\n<p>2.1.&nbsp;&nbsp;&nbsp; We shall be responsible for all and or any insurance and or insurance related needs of the member (s) of public and or body (ies) and or groups of individuals and or entity (ies) who comes to our website (s) through any of &lsquo;your online presence and or property&rsquo;. This responsibility shall include but not be limited to: advise on the insurance products we have on the site as well as any others we are authorised to deal with, follow ups with our underwriting company on documentations, claims and any other matter as necessary in our role as the registered and regulated insurance agency.</p>\r\n<p>2.2.&nbsp;&nbsp;&nbsp; We will provide you with engaging content, software codes and interactive banners all aimed at getting the attention of your website&rsquo;s visitors, and driving them to our own website (s), products and services.</p>\r\n<p>2.3.&nbsp;&nbsp;&nbsp; We shall pay the agreed amounts for qualified leads as stated on our publicly published pricing and revenue sharing structures. Please see the relevant page on our website for the latest amounts and or percentage figures as these may be subject to changes.</p>\r\n<p>2.4.&nbsp;&nbsp;&nbsp; We fully reserve the right to make any changes to the pricing and revenue sharing structures at any moment.</p>\r\n<p>&nbsp;</p>\r\n<ol> </ol>3. Our partnership program terms <ol> </ol>\r\n<p>3.1.&nbsp;&nbsp;&nbsp; Use of Creative and Banners</p>\r\n<p>&nbsp;Please note that if you are promoting any of our insurance programmes via creative and are unable to refresh banners &nbsp;&nbsp;&nbsp;&nbsp;dynamically, you are required to use our logos only.</p>\r\n<p>3.2.&nbsp;&nbsp;&nbsp; PPC Restrictions</p>\r\n<p>PPC Restrictions &ndash; No Brand Bidding</p>\r\n<p>Do not bid for any of our brand related keywords or brand related keywords plus product on Google, Yahoo!, Bing or any other search engine and or social media website.</p>\r\n<p>3.3.&nbsp;&nbsp;&nbsp; Google, Yahoo!, Bing or any other search engine and or social media websites:</p>\r\n<p>3.3.1.&nbsp; Brand bidding on Google on this partnerships program is prohibited and any Recipient Parties found to be doing so will be removed from the program. Any commission made on these sales will not be paid by us.</p>\r\n<p>3.3.2.&nbsp; Examples of terms Recipient Parties should not bid on, appear against or use in ad copy are below:</p>\r\n<ul>\r\n<li>Brand-only keyword &ndash; &lsquo;bima247.com&rsquo;</li>\r\n<li>Misspellings of brand-only keywords &ndash; &lsquo;bma247.com\', \'bma.com\', \'bima\' et cetera</li>\r\n<li>Broad match terms that include the bima247.com or misspellings of the bima247.com brand name.&nbsp;</li>\r\n</ul>\r\n<p>3.4.&nbsp;&nbsp;&nbsp; However, we allow Recipient Parties to bid on generic terms relevant to the products promoted by us e.g. &lsquo;motor insurance&rsquo;, &lsquo;home insurance&rsquo;, &lsquo;travel insurance&rsquo;, \' medical insurance\' et cetera.</p>\r\n<p>3.5.&nbsp;&nbsp;&nbsp; Recipient Parties&rsquo; URLs -</p>\r\n<p>3.5.1.&nbsp; No affiliate is allowed to use any bima247.com or any similarly or nearly similarly named derivative as a domain (s).</p>\r\n<p>3.5.2.&nbsp; Recipient Parties must not have reference to bima247.com or any similarly or nearly similarly named derivative at the end of their domain (s).</p>\r\n<p>3.5.3.&nbsp; Recipient Parties may not use the term as a subdomain in their URL.</p>\r\n<p>Examples: www.bima247.com.mysite.co.uk is not permitted</p>\r\n<p>&nbsp;</p>\r\n<ol> </ol>4. Conduct and behaviour<ol> </ol>\r\n<p>4.1.&nbsp;&nbsp;&nbsp; You are also our brand ambassador which means that you and your activities both off line and or related to any of &lsquo;your online presence and or property&rsquo; must at all time be of the highest legal and moral standards. In effect, this shall mean but not be limited to the following: you and or your representatives shall not commit any act or do anything that&rsquo;s illegal, criminal, dishonest, immoral, contrary to public decency, of moral turpitude or which might tend to bring us and or the insurance company providing us with insurance cover into public disrepute, contempt, scandal, or ridicule, and or which might reflect unfavourably and or is likely to reduce, diminish, or damage the goodwill, value, or materially injure the reputation and or brand equity of our company and or that of the insurance company providing us with insurance cover.</p>\r\n<p>&nbsp;</p>\r\n<ol> </ol>5. Contraventions and Breaches <ol> </ol>\r\n<p>5.1.&nbsp;&nbsp;&nbsp; Contravention will automatically make this agreement null and void.</p>\r\n<p>5.2.&nbsp;&nbsp;&nbsp; You will be in contravention of the applicable laws if you contravene some of the roles and responsibilities here e.g. sub section 1.2 above and thus liable for legal action to the fullest extent of the law by the relevant authorities.</p>\r\n<p>5.3.&nbsp;&nbsp;&nbsp; In addition, with any contravention, your details with us shall immediately be sent to the relevant authorities. They will then deal with you to the fullest extent of the law and that may include prosecution and criminal charges.</p>\r\n<p>5.4.&nbsp;&nbsp;&nbsp; Any conduct and or behaviour contrary to section 3 sub-section 3.1 will result in us taking legal action and or seeking legal redress to the fullest allowable and or possible extent with the cost of any suit borne by you and or your representative (s).</p>\r\n<p>&nbsp;</p>\r\n<ol> </ol>6. Term of relationship<br />\r\n<p><br />6.2.&nbsp;&nbsp; This shall be for a period of Eighteen (18) months from the day our software code(s)&rsquo; published within any of &lsquo;your online presence and or property&rsquo;, and auto renewed in the first instance with subsequent renewals based upon the mutual agreement of both parties to this Agreement.&nbsp;</p>\r\n<p>6.2.&nbsp;&nbsp;&nbsp; We reserve the rights to terminate this Agreement or change its terms at anytime before the end of the term of this relationship or any subsequent renewed terms; and do so without giving any reason for such termination or change (s).</p>\r\n<p>&nbsp;</p>\r\n<ol> </ol>7. Miscellaneous.<ol> </ol>\r\n<p>7.1.&nbsp;&nbsp;&nbsp; The validity, construction and performance of this Agreement shall be governed and construed in accordance with the laws of Kenya applicable to contracts made, and or any other appropriate legal jurisdiction anywhere in the world.</p>\r\n<p>7.2.&nbsp;&nbsp;&nbsp; Any failure by the &lsquo;Disclosing Party&rsquo; to enforce the other party&rsquo;s strict performance of any provision of this Agreement will not constitute a waiver of its right to subsequently enforce such provision or any other provision of this Agreement.</p>\r\nThis Agreement is specific in nature, and neither party may directly or indirectly assign or transfer it by operation of law or otherwise without the prior written consent of the other party, which consent will not be unreasonably withheld. All obligations contained in this Agreement shall extend to and be binding upon the parties to this Agreement and their respective successors, assigns and designees','0','1402030800','Yes','0'),
 ('525','Non Profits, NGOs and Diaspora Associations','<h1 class="headingitalics">Why Us? It\'s Simple.</h1>\r\n<p>First we admire what you do since you are undoubtedly the unsung heroes in our society. In many ways you do share our ideals and a belief that our society is only as good as how we treat the disadvantaged in our society. And to be honest, deep inside, we do think of ourselves a bit as rainbow warriors. That&rsquo;s why we invite you to work with us.</p>\r\n<p>We care deeply about our society and the communities we live in whether at home in Kenya or in the Diaspora and are thus leading the way in terms of making a real difference in those communities through innovative sustainable long-term funding solutions. We are doing this by doing what we know best i.e. bringing together insurance and ICTs to offer a sound business model &ndash; after all we are a business that needs to create value for our shareholders and customers &ndash; whilst leaving you to do what you do best i.e. helping others in our societies and communities.</p>\r\n<p>In many ways a partnership with us is a win-win situation. You get a new stream of funds for your activities and we achieve one of our objectives, which is to leave the world a little better of than we found it.</p>\r\n<p>Insurance is something crucial that lots of people need but beyond that it is in some cases compulsory to have. Most insurance covers are annual contracts, which must be renewed after 12 months. This is why insurance companies have millions of customers who regularly renew their policies every year. So when you become a Bima247.com partner, your website or url or social media account will be showing your website visitors a product range that&rsquo;s needed by many on a regular basis, and offered by one of the best known and respected brand in Kenya.</p>\r\n<p>And best of all, it\'s FREE.<span style="text-decoration: underline;"> </span>&nbsp;Partnering with us won&rsquo;t cost you a shilling, so why wait.</p>\r\n<table border="0" cellpadding="5" width="931" height="263">\r\n<tbody>\r\n<tr valign="top">\r\n<td width="50%">\r\n<h2 class="headingitalics"><strong>How will it work?</strong></h2>\r\n</td>\r\n<td width="50%">\r\n<h2 class="headingitalics"><strong>Are the banners and or links easy to install?</strong></h2>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td>\r\n<p>All you have to do is display our banner or a link on your web site  and start getting a stream of funds to finance you societal and  developmental activities. Nothing else! It\'s that simple! *</p>\r\n<p>Then, every time a visitor to your website, url and or social media  account clicks on that banner or a link and makes a purchase that is  accepted by an insurance company, we will pay you a share of the  commission revenue we get from the insurer or insurers who eventually  provide the insurance policy.</p>\r\n<p>This payment is uncapped which means that the more your online  visitor&rsquo;s purchase insurance cover, the more you earn - there\'s no  limit!</p>\r\n</td>\r\n<td>No, you don\'t need to be a technical whiz &ndash; just copy and paste the code  we\'ve provided onto your Web page or blog or social media link or in  your email and you\'re done! Happy to help if you are stuck, just get in  touch and we will give you a hand.<br /><br />* IMPORTANT: please note that having our banners and or links does not transfer any rights onto you to act as an insurance agent and our partnership does not allow you to  act  as such. Therefore, you MUST ABSOLUTELY NOT ENGAGE in any insurance selling and or  any advise to any member of the public and or other  entitie(s). <strong>To do this, the law requires that you MUST be registered and regulated by the Insurance Regulatory and or relevant licensing Authorities</strong> in your market. It shall remain your responsibility to abide by the relevant and correct legal requirements. <br /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table border="0" cellpadding="5" width="924" height="237">\r\n<tbody>\r\n<tr valign="top">\r\n<td width="50%">\r\n<h2 class="headingitalics"><strong>What\'s in it for you?</strong></h2>\r\n</td>\r\n<td width="50%">\r\n<h2 class="headingitalics"><strong>How we will help you earn even more?</strong></h2>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td>\r\n<ul>\r\n</ul>\r\nWhat is in for you includes:\r\n<ul>\r\n<li>A new and sustainable source of funding revenue</li>\r\n<li>Your online presence generating funds 24/7 365 days a year. You don&rsquo;t need to do anything else!</li>\r\n<li>Working with an innovative organisation as well as fantastic and leading insurance brand.&nbsp;</li>\r\n<li>Be part of something new and exciting. &nbsp;</li>\r\n<li>Our technology means that you will never miss any income towards your funding.</li>\r\n<li>Payment to your account or by cheque or build up credit.</li>\r\n<li>Real-time statistics availed to you.</li>\r\n<li><strong>FREE</strong>!! to join with a very quick sign up process.</li>\r\n</ul>\r\n<br /></td>\r\n<td>\r\n<p>We will also help you to promote our products in many ways including:</p>\r\n<ul>\r\n<li>By giving you full access to our wide range of eye-catching web  banners. Choose from a variety of sizes and decide whether you want to  use a static or animated banner on your site.</li>\r\n<li>Through promotions and special discounts on our products which will  also be available for your website, url and or social media accounts.</li>\r\n<li>Editorial to help you build good solid content on your site in addition to the massive range of creative for you to use.</li>\r\n<li>Bespoke campaigns, which we are happy to run with you. Please feel free to contact us to discuss any ideas you may have.</li>\r\n</ul>\r\n<br /></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h2 class="headingitalics"><strong>&nbsp;</strong><strong>How will this work with our other sources of funds and fund raising activities?</strong></h2>\r\n<p>These can be complementary. Just add this option to your &lsquo;please donate&rsquo; or &lsquo;how you can help&rsquo; page of your website. Thereafter any visitor or potential supporter then has a number of options to support your cause or activities. So for example they can chose to simply donate an amount of money, or donate goods, or their services, or they can click on our banners on your site and if they do and purchase insurance, then you get our share of the premium commission. It is as simple as that. Just remember people need insurance on a regular basis, so your supporters can show their support also by buying insurance off your site.</p>\r\n<h2 class="headingitalics"><strong>How will we keep track and statistics? </strong></h2>\r\n<p>We use tracking technology to monitor everything, so you will always know who has come through to us from your website (s), how much they have paid in terms of premiums, and your earnings.</p>\r\n<h2 class="headingitalics"><strong>How much will you earn?</strong></h2>\r\n<p>75% per premium commission paid to us as agents for proposals accepted by our insurance company. &nbsp;<br /> <br /> This is an INTRODUCTORY percentage rate for a LIMITED PERIOD OF TIME. Join now since it won&rsquo;t be around for long.</p>\r\n<h2 class="headingitalics"><strong>Will it be more expensive for our supporters if they buy on this site?</strong></h2>\r\n<p>No absolutely not. We use the same industry standard forms and rates like every other player in the market. The only difference is that we have simplified the process and digitised the forms.</p>\r\n<p><span style="color: #ff0000;"><strong>So, Nothing to Lose!</strong></span></p>\r\n<p>Join us in partnership: it is 100% FREE of charge AND takes seconds to sign up. And as indicated above, all you have to do is display a banner on your web site and start seeing a new more sustainable funding source deliver! And nothing else!!!</p>\r\n<p>Joining us also means that your web presence will be promoting the products of one of Kenya&rsquo;s leading insurance brands whilst ensuring you have the funds and freedom to continue doing what you do best i.e. helping others. And we do admire you for that, which is why we want to help as much as possible.</p>\r\n<p>So let your online presence be a source of funds 24/7 365 days a year.</p>','26','1399611600','Yes','0'),
 ('526','Website Owners, Bloggers, Social Media','<h1 class="headingitalics"><strong>Why Us? It\'s Simple.</strong></h1>\r\n<p>Insurance is something crucial that lots of people need but beyond that it is in some cases compulsory to have. Most insurance covers are annual contracts, which must be renewed after 12 months. This is why insurance companies have millions of customers who regularly renew their policies every year. So when you become a Bima247.com partner, your website or url or social media account will be showing your website visitors a product range that&rsquo;s needed by many on a regular basis, and offered by one of the best known and respected brand in Kenya. And best of all, it\'s FREE.<span style="text-decoration: underline;"> </span>&nbsp;Partnering with us won&rsquo;t cost you a shilling, so why wait.</p>\r\n<table border="0" cellpadding="5" width="100%">\r\n<tbody>\r\n<tr valign="top">\r\n<td width="50%">\r\n<h2 class="headingitalics"><strong>How does it work?</strong></h2>\r\n</td>\r\n<td width="50%">\r\n<h2 class="headingitalics"><strong>Are the banners and or links easy to install?</strong></h2>\r\n<br /></td>\r\n</tr>\r\n<tr valign="top">\r\n<td>\r\n<p>All you have to do is display our banner or a link on your web site and start earning! Nothing else! It\'s that simple! *</p>\r\n<p>Then, every time a visitor to your website, url and or social media  account clicks on that banner or a link and makes a purchase that is  accepted by an insurance company, we will pay you a share of the  commission revenue we get from the insurer or insurers who eventually  provide the insurance policy.</p>\r\n<p>This payment is uncapped which means that the more your online  visitor&rsquo;s purchase insurance cover, the more you earn - there\'s no  limit!</p>\r\n</td>\r\n<td>Yes absolutely, you don\'t need to be a technical whiz &ndash; just copy and  paste the code we will provide onto your Web page or blog or social  media link or in your email and you\'re done! Happy to help if you are  stuck, just get in touch and we will give you a hand.<br /><br />* IMPORTANT: please note that having our banners and or links does not  transfer any rights onto you to act as an insurance agent and our  partnership does not allow you to  act  as such. Therefore, you MUST  ABSOLUTELY NOT ENGAGE in any insurance selling and or  any advise to any  member of the public and or other  entitie(s). <strong>To do this, the  law requires that you MUST be registered and regulated by the Insurance  Regulatory and or relevant licensing Authorities</strong> in your market. It shall remain your responsibility to abide by the relevant and correct legal requirements.</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table border="0" cellpadding="5" width="100%">\r\n<tbody>\r\n<tr>\r\n<td width="50%">\r\n<h2 class="headingitalics"><strong>What\'s in it for you?</strong></h2>\r\n</td>\r\n<td width="50%">\r\n<h2 class="headingitalics"><strong>How we will help you earn even more?</strong></h2>\r\n</td>\r\n</tr>\r\n<tr valign="top">\r\n<td>\r\n<ul>\r\n</ul>\r\nWhat is in for you includes:\r\n<ul>\r\n<li>Increased revenues as you monetise your online presence in an intelligent and subtle way.</li>\r\n<li>&nbsp;Your online presence earns you money 24/7 365 days a year. You don&rsquo;t need to do anything else!</li>\r\n<li>Working with an innovative organisation as well as fantastic and leading insurance brand.&nbsp;</li>\r\n<li>Be part of something new and exciting. &nbsp;</li>\r\n<li>Our technology means that you will never miss any earnings.</li>\r\n<li>Payment to your account or by cheque or build up credit.</li>\r\n<li>Real-time statistics availed to you.</li>\r\n<li><strong>FREE</strong>!! to join with a very quick sign up process.</li>\r\n</ul>\r\n</td>\r\n<td>\r\n<p>We will also help you to promote our products in many ways including:</p>\r\n<ul>\r\n<li>By giving you full access to our wide range of eye-catching web  banners. Choose from a variety of sizes and decide whether you want to  use a static or animated banner on your site.</li>\r\n<li>Through promotions and special discounts on our products which will  also be available for your website, url and or social media accounts.</li>\r\n<li>Editorial to help you build good solid content on your site in addition to the massive range of creative for you to use.</li>\r\n<li>Bespoke campaigns, which we are happy to run with you. Please feel free to contact us to discuss any ideas you may have.</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<table border="0" cellpadding="5" width="100%">\r\n<tbody>\r\n</tbody>\r\n</table>\r\n<table border="0" cellpadding="5" width="100%">\r\n<tbody>\r\n</tbody>\r\n</table>\r\n<h2 class="headingitalics"><strong>How will we keep track and statistics </strong></h2>\r\n<p class="headingitalics">We use tracking technology to monitor everything, so you will always   know who has come through to us from your website (s), how much they   have paid in terms of premiums, and your earnings.</p>\r\n<h2 class="headingitalics"><strong>How much will you earn?</strong></h2>\r\n<p><span style="color: #ff0000;">60% per commission paid to us as agents for proposals accepted by our insurance company. &nbsp;</span><br /> <br /> This is our MARKET INTRODUCTORY percentage rate for a LIMITED PERIOD OF TIME. Join now since it won&rsquo;t be around for long.</p>\r\n<h2 class="headingitalics"><strong>So, Nothing to Lose!</strong></h2>\r\n<p>Join us in partnership: it is 100% free of charge. &nbsp;And as indicated above, all you have to do is display a banner on your web site and start earning! And nothing else!!!</p>\r\n<p>Joining us also means that your web presence will be promoting the products of one of Kenya&rsquo;s leading insurance brands whilst earning you fantastic money.</p>\r\n<p>So let your online presence earn you money 24/7 365 days a year. Why wait, it is a win-win situation for both of us, i.e. you monetise your online presence effortlessly and we increase our digital footprint.</p>','0','1400475600','Yes','0'),
 ('527','Partnership opportunities for insurance companies, brokers and agents','<p>eSurance365 - the software of choice for insurance companies - is a \'white label\' system that can be easily and quickly integrated with all insurance company\'s core system. It is also inexpensive and with an innovative revenue model.&nbsp; Click on the left icon (eSurance365) for more details.</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<ol> </ol>','26','1400475600','Yes','0'),
 ('834','Privacy','<p>Why we need your information<br /> <br /> We need your information and that of others you name on the policy to give you quotations, and manage your insurance policy, including underwriting and claims handling. Your information comprises of all the details we hold about you and your transactions and includes information we obtain about you from third parties. We will only collect the information we need so that we can provide you with the service you expect from us.</p>\r\n<p>Data protection<br /> <br /> We respect the privacy of every individual who visits our site. This policy has been developed because we want you to feel confident about the privacy and security of your personal information entered on our site. We follow strict security procedures in the storage and disclosure of your information. Our security procedures mean that we may ask for proof of your identity before we will disclose information about you, or to you, be this on our site or over the telephone. This is what your password is for.<br /> <br /> In order to provide our services to you we need to collect and process personal information about you and disclose that information to a number of third party service providers and, where they are involved, their appointed representatives and intermediaries. This information is necessary to provide you with the quotations you have requested. This information is necessary to provide you with the quotations you have requested. All personal information will be held in strictest confidence and used only for the purposes for providing you the service requested with certain caveats as described below.</p>\r\n<p>Information disclosure<br /> <br /> Our sophisticated technology may also disclose the information you have entered into the Bima247.com&rsquo;s website to our partner websites for the purpose of providing us with quotations on the requested product. We will also be using your data for our own research and for tracking any purchased policies. By using our site you are giving us permission to use your data in this way. <br /> <br /> Except as set out in this Privacy and Security Statement, we will not disclose this information to other parties without your permission unless we are legally required to do so (for example, if required to do so by Court order) or for the purposes of prevention of fraud or other crime. By submitting information into our site, you are consenting to the processing of this data about you by us and by intermediaries, the product providers and their appointed representatives referred to in this Privacy and Security Statement. This consent applies equally both to ordinary personal data and to sensitive personal data like, for example, health information and any criminal proceedings or convictions</p>\r\n<p>We will not disclose any of your information to other parties without your permission unless we are legally required to do so by, for example, a court order or for the purposes of prevention of fraud or other crime. By submitting information into our site, you consent to the processing of this data about you by the product providers and us. We may also use the information you supply to us to keep you informed of any new products or remind you when your renewal may be due.</p>\r\n<p>If you are not happy for your data to be used in this way please feel free to email our customer services at Bima247.com</p>\r\n<p>Your personal data<br /> <br /> Transafricana Risk Management and Insurance Agency trading as Bima247.com takes all reasonable care to prevent any unauthorised access to your personal data in compliance with most data protection legislations. We however reserve the right to amend or modify this privacy policy statement at any time and in response to changes in applicable data protection and privacy legislation.</p>\r\n<p>How we use your personal data<br /> Your personal information is used for the purpose of getting you an insurance quote. Additionally, Transafricana Risk Management and Insurance Agency trading as Bima247.com and other carefully selected third parties may use your information to keep you informed by post, telephone, SMS or email about current and new products and services which may be of interest to you. Your information may also be disclosed and used for these purposes after your policy has lapsed. <br /> <br /> If you are not happy for your data to be used in this way please feel free to email our customer services</p>\r\n<p>Monitoring<br /> <br /> Monitoring or recording of your calls, emails, text messages and other communications may take place for business purposes, such as for quality control and training; processing necessary for the entering into or the performance of a contract; to prevent unauthorised use of our telecommunication systems and web sites; to ensure effective systems operation; to meet any legal obligation; in order to prevent or detect crime; and for the purposes of the legitimate interests of the data controller.</p>\r\n<p>Confidentiality<br /> <br /> We will endeavour to treat all your personal information as private and confidential. Other than under the terms of this Privacy Statement we will not disclose any of your information to anyone. We would like to bring your attention though to our obligations to disclose information in the following four exceptional cases permitted by law. These are where we are legally compelled to do so; where there is a duty to the public to disclose; where disclosure is required to protect our interest; and where disclosure is made at your request or with your consent.</p>\r\n<p>Cookies and IP addresses<br /> <br /> We respect your privacy and therefore we will only use cookies where your browser enables us to use them. We use cookies to help you navigate through our site and to improve security. If you do not want one of our cookies on your site, please ensure your browser is configured to refuse to accept them. You can still use our site of course, but without the cookies. <br /> <br /> Cookies are used by many organisations to track your every move on their site. A cookie is a small text file that is placed on your hard disk by a Web page server. Cookies cannot be used to run programs or deliver viruses to your computer. Cookies are uniquely assigned to you, and can only be read by a web server in the domain that issued the cookie to you.</p>\r\n<p>Your IP address is a series of numbers that identify a computer on the internet. We use IP addresses to help diagnose possible service interruptions and administer our service and website.</p>\r\n<p>Law<br /> <br /> These terms of trading are subject to Kenyan law and to the exclusive jurisdiction of the Kenyan courts. You acknowledge that by providing data to us, you consent to the processing of your data in accordance with this privacy and security statement.</p>\r\n<p>Security statement</p>\r\n<p>Site security<br /> We use the industry standard secure sockets layer (SSL) 128-bit encryption technology to ensure that all your personal and transactional information is encrypted before transmission. To check that you are in a secure area of our site look at the bottom of your browser and you will see a closed padlock. All this technology and our policies are to safeguard your privacy from unauthorised access/improper use and we will continue to update these measures as new technology becomes available.</p>\r\n<p>Third party site security<br /> We cannot be responsible for the privacy policies and practices of other websites, even if you access them using links from our website and recommend that you check the policy of each site you visit.</p>\r\n<p>For extra protection<br /> &nbsp;<br /> In order to provide you with maximum protection, we ask you to choose a password to access your data on our site. In many instances, this password will also access the quotes we find for you on the web. Your password is unique to you and helps us to protect your personal information. You must keep this password safe and must not disclose it to anyone. You will need your password to access your personal information and potentially to buy cover through our insurance providers. Your password is unique to you and helps us to protect your personal information. Please contact us if you want to change your password for any reason.</p>\r\n<p>If you forget your password don\'t panic! Simply click on "forgot your password".</p>\r\nBe aware<br /> <br /> Please be aware that communications over the Internet, such as emails/webmails, are not secure unless they have been encrypted. Your communications may route through a number of countries before being delivered - this is the nature of the World Wide Web/Internet. We cannot accept responsibility for any unauthorised access or loss of personal information that is beyond our control.','26','1401771600','Yes','0'),
 ('528','Insurance Brokers, Agents and Bancassurers','<h1 class="headingitalics"><strong>Why Us? It\'s Simple. <br /></strong></h1>\r\n<p>We are an independent, innovative, vibrant and energetic company full of transformative ideas for the future of insurance. We are also highly  experienced ICT systems\' specialists, underwriters  and brokers developing  scalable web-based underwriting, rating premium  payment systems for  other underwriters and insurance professionals in  Kenya. The fact that insurance is one of our core competences, and a subject we know very well, together with our significant wealth of related experiences make us understand the nuances of risks and underwriting better than any other system provider in this market. <span>What we also particularly understand is that ICTs and new media  technologies hold a number of significant promises for the insurance  industry. One of the biggest being that of improved productivity and  efficiencies through reductions in the number of handoffs between  proposers, insureds, agents, adjustors, and the company; through  automation of straightforward decisions &ndash; whether on underwriting,  claims or premium payments &ndash; using expert software.&nbsp; <br /></span></p>\r\n<!--  /* Font Definitions */ @font-face 	{font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-font-charset:78; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1791491579 18 0 131231 0;} @font-face 	{font-family:"Cambria Math"; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1107305727 0 0 415 0;} @font-face 	{font-family:Cambria; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1073743103 0 0 415 0;}  /* Style Definitions */ p.MsoNormal, li.MsoNormal, div.MsoNormal 	{mso-style-unhide:no; 	mso-style-qformat:yes; 	mso-style-parent:""; 	margin:0cm; 	margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} .MsoChpDefault 	{mso-style-type:export-only; 	mso-default-props:yes; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} @page WordSection1 	{size:612.0pt 792.0pt; 	margin:72.0pt 90.0pt 72.0pt 90.0pt; 	mso-header-margin:36.0pt; 	mso-footer-margin:36.0pt; 	mso-paper-source:0;} div.WordSection1 	{page:WordSection1;} -->\r\n<p>Which is where we come in with our proprietary browser-based fully integrated insurance rating and payment system that is the first of its kind in Kenya. It is a system that not only allows an organization in the insurance sector to take advantage of new distribution and delivery channels but also bring down costs significantly through its highly optimized automated service delivery pathways.</p>\r\n<p>We believe that the system, which is packed with many benefits and  features, will add significant value to your organisation not just in  terms of availing your products and services 24/7 365 days anytime  anywhere in the world to those with the requisite insurable interests  but also improving on your existing customers experiences, and of course  your revenues. To see a demo of how it works, we are giving you a  chance to sample the product for as long as you like: so take a tour of  this site - which is run by our system - to get a feel of how the system  works in your own time and test it as many times as you wish. You can  also click on any of the products elsewhere on this website to go  through the proposal stages. To experience the payment process, click on  Domestic Package and when asked for sum insureds put 100,000 under  buildings, get a quote, pay and then thereafter email us for a  cancellation and full refund. Please put \'Demo\' as the subject of your  email.</p>\r\n<h2 class="headingitalics"><strong>What is our relationship with Jubilee Insurance?</strong></h2>\r\n<p>We are an open and transparent company, and take a great deal of pride in this, so for that and the sake of clarity, our relationship with Jubilee is as follows:</p>\r\n<p><span style="color: #ff0000;">Business to Consumer (b2c)</span>:  we have a registered insurance agency &ndash; Transafricana Risk Management  and Insurance Agency &ndash;, which distributes Jubilee insurance company\'s  products online through this website. We get the normal rates of  commissions for that.</p>\r\n<p><span style="color: #ff0000;">Business to Business (b2b):</span> we have no relationship or affiliations to Jubilee Insurance in this  regard. Our scalable Internet-based underwriting, rating and premium  payment system is not only independent of anyone else in the industry  but also designed for local Kenyan market conditions as a white label <strong>&lsquo;plug and play&rsquo; </strong>product,  which can be easily installed and used by a wide range of insurance  organisations and others in the sector. Ask for our product brochure  for more details on how we can help your company achieve more in terms  of richer interactive consumer experiences and higher revenues.</p>\r\n<h2 class="headingitalics"><strong>If we are a broker or agency selling insurance for another insurance company (ies) and not Jubilee, can we still use this system?</strong></h2>\r\n<p>Yes, of course you can. It is a fully customisable solution which means that if you are an agent or broker or bancassurer selling products for any other insurance company in this market (e.g. AIG, UAP, Madison et cetera) then you just let us know that insurance company, their rates and the system will be customised so that visitors to your website will see that their cover is from the company you have a relationship with, if it is not Jubilee. We are currently developing an upgrade that will allow you to have a panel of different insurers and as part of our partnership you will get that upgrade <span style="color: #ff0000;"><strong>FREE</strong></span> once it&rsquo;s finished and tested.</p>\r\n<p>What&rsquo;s more: you can customise this system such that your visitors buy different products from different insurance providers. So, for example, if you are a Britam broker or agent for medical then we can put their (Britam&rsquo;s) medical rates, and if you are also a Heritage broker or agent for motor, we can put their (Heritage&rsquo;s) motor rates in the system for you. Or alternatively you can have only Britam&rsquo;s rates across all your products. The choice is yours to make, just let us know how you want it customised.</p>\r\n<p>Like we have said, it is fully customisable system to suit your needs whether you want sell for one insurer across all the products or different insurance companies for different products on your website. It is easy, fast to install and best of all, you can test it right here on this site to see how it will work for you: just imagine it is your organisation&rsquo;s logo on the top banner and corporate brand colours, and not bima247.com.</p>\r\n<table border="0" cellpadding="5" width="100%">\r\n<tbody>\r\n<tr valign="top">\r\n<td>\r\n<h2 class="headingitalics"><strong>How will it work for you?</strong></h2>\r\n</td>\r\n<td>\r\n<h2 class="headingitalics"><strong>System&rsquo;s key features?</strong></h2>\r\n<br /></td>\r\n<td>\r\n<h2 class="headingitalics"><strong>What\'s in it for you?</strong></h2>\r\n<br /></td>\r\n</tr>\r\n<tr valign="top">\r\n<td>\r\n<p>Our system is &lsquo;<strong>plug and play</strong>&rsquo; which means that its integration to  your existing website or systems is straightforward and fast. The proposal form questions are infact the standard industry ones, just digitalised by us. Indeed our  system is specifically developed for Kenyan insurance sector organisations,&nbsp; which makes it  fit quite easily within all the core insurance systems in the market. &nbsp;</p>\r\n<p>We are also happy to help in case you don\'t have a websiite: just talk to us.</p>\r\n<p>See our &lsquo;Faqs on working with us as an insurance broker, agency or bancassurer&rsquo; for more details.</p>\r\n</td>\r\n<td>\r\n<ul>\r\n<li><strong>policyNET is an Internet-based fully transactional and integrated insurance rating and payment system</strong> via any Internet-enabled device.</li>\r\n<li><strong>24/7 365 days of the year access to your insurance products</strong> and services by customers and proposers anywhere in the world.</li>\r\n<li><strong>A system developed by experienced underwriters</strong> with significant  experience, in-depth underwriting knowledge and expertise gained from  working for blue chip insurers both in Kenya and abroad (mostly in the  London Insurance market). </li>\r\n</ul>\r\n</td>\r\n<td>\r\n<ul>\r\n<li><strong>You don&rsquo;t have to spend huge amounts of money and time</strong> (both  likely to cost you hundreds of thousands or even millions of Kenya  shillings) developing a similar or near similar system. We have done  that already.&nbsp;&nbsp;</li>\r\n<li><strong>An Internet-based integrated underwriting and  payment solution</strong>, that helps you save significant costs, streamlines  your insurance processes and improves on customer experience levels.</li>\r\n<li><strong></strong><strong>A freedom to focus on your resources</strong> more on the larger, more complex non-commoditisable risks</li>\r\n<li><strong>A  fully supported local solution for local organizations by local  experts</strong>. And a team at the cutting edge of technological advances with a  unique perspective of the industry, which allows us to spot potentially  disruptive or useful technologies quite early on and leverage those to  your key strengths and resources.</li>\r\n</ul>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h2 class="headingitalics"><strong>What is the pricing structure?</strong></h2>\r\n<p>This is simple and with no hidden charges. <span style="color: #ff0000;">It is FREE and easy to sign up, and you also get to keep your commissions 100%!!</span>. We take great pride in this and on our transparency.</p>\r\n<p>For a limited period of time our market introductory pricing structure is as follows:</p>\r\n<table border="0">\r\n<tbody>\r\n<tr>\r\n<td>One-time system charge plus set up fee&nbsp;&nbsp; <br /></td>\r\n<td>FREE (for a limited time)</td>\r\n</tr>\r\n<tr>\r\n<td>Annual IP licence and maintenance</td>\r\n<td>FREE (for a limited time)</td>\r\n</tr>\r\n<tr>\r\n<td><strong>Per transaction charge</strong></td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td><span style="color: #ff0000;">Set Fee</span></td>\r\n<td><span style="color: #ff0000;">KES 40 (max 2 charges per policy per renewal period)</span></td>\r\n</tr>\r\n<tr>\r\n<td>PLUS</td>\r\n<td>Nil % age per premium (i.e. FREE for a limited time)</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<h2 class="headingitalics">Sign up today since this market introductory price won&rsquo;t be around for long.</h2>','0','1400389200','Yes','0'),
 ('534','What is Bima247.com?','<p>Bima247.com is the new smarter, faster, simpler, secure and convenient way to buy insurance online in Kenya. Behind it, we are a team with a great deal of expertise and many years of experience in insurance underwriting and broking as well as ICTs, driving innovation in the market that&rsquo;s aimed at putting you firmly in control of your insurances and finances. These are experiences and expertise gained from working in blue chip companies and with some of the most reputable brands in the Kenyan and London finance and insurance markets.</p>\r\n<p>The fact that we have been working in this sector for a long time means that we understand insurance, people and technology more than most. These are things we know and do well. Most of all we understand that you need peace of mind protection from an award-winning Insurer at a time and place of your convenience.</p>\r\n<p>Please see the &lsquo;About us&rsquo; section of this website to find out further details.</p>','0','1401426000','Yes','0'),
 ('535','Who is providing the insurance cover?','<p>Jubilee Insurance Kenya is the insurer. Jubilee is the largest composite insurer in the region, and that has been providing protection for individuals like you, their loved ones and valued possessions continuously in Kenya since 1937. It is a company known not only for an excellent customer service ethos but also a well-reputed and unrivalled claims payment record. Jubilee is indeed a recent multiple recipient of some of the industry&rsquo;s most prestigious awards including Composite Insurer of the Year, Medical Insurance Underwriter of the Year, Customer Satisfaction Award, and Best Claims Settlement Award. These awards by its peers are certainly a mark of Jubilee&rsquo;s high esteem within professional circles especially with regards to professionalism, and a key reason why it is the first choice for Kenyans when it comes to insurance cover. Jubilee Insurance is also the winner of the &lsquo;East Africa&rsquo;s Most Trusted Brand&rsquo; Award.</p>\r\n<p>So, in short, you are safe and secure in the knowledge that your cover is by a multiple award winning insurer in the market, and a compant with one of the most stable financial capabilities as well as a solid emphasis on security for policyholders, committed, knowledgeable and professional staff, and a reputation for superior customer service. As a demonstration of their commitment to service excellence, Jubilee has implemented quality management system and is an ISO 9001:2000 certified company.</p>','0','1401426000','Yes','0'),
 ('536','Are you authorised and regulated?','<p>Yes, certainly, and by the Kenya Insurance Regulatory Authority as Transafricana Risk Management and Insurance Agency, registration no. IRA/05/283019/2014.</p>','0','1401426000','Yes','0'),
 ('537','What are your products?','<p>At the moment, we offer motor, domestic package, travel, personal accident and medical insurances. We are though working to bring you more insurance solutions for you, your family and valued possessions by taking advantages of the advances in mobile and other technological advances.</p>','0','1401426000','Yes','0'),
 ('538','Do you plan to operate in other markets?','<p>Yes, so if you are in insurance business in the wider East African region or a potential insured, email us and we will be happy to speak to you.</p>\r\n<p>Kenyan companies with regional market penetration ambitions or strategic expansion plans are also welcome to get in touch.</p>','0','1401426000','Yes','0'),
 ('539','What payment solution is used on this site?','<p>The payment solution we use here is the most advanced and secure encryption technology used by many of the major banks in the world.</p>','0','1401426000','Yes','0'),
 ('540','What happens if I want a product that is not on the website?','<p>No need to worry about that, just click on the live help and our dedicated team will take your details for a call back by one of our specialist underwriters.</p>','0','1401426000','Yes','0'),
 ('541','Do you have a mobile version or apps?','<p>No, not yet but these are coming soon.</p>','0','1401426000','Yes','0'),
 ('542','What is a social enterprise?','&lt;!--  /* Font Definitions */ @font-face 	{font-family:Times; 	panose-1:2 0 5 0 0 0 0 0 0 0; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:3 0 0 0 1 0;} @font-face 	{font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-font-charset:78; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1791491579 18 0 131231 0;} @font-face 	{font-family:"Cambria Math"; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1107305727 0 0 415 0;} @font-face 	{font-family:Calibri; 	panose-1:2 15 5 2 2 2 4 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-520092929 1073786111 9 0 415 0;} @font-face 	{font-family:Cambria; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1073743103 0 0 415 0;}  /* Style Definitions */ p.MsoNormal, li.MsoNormal, div.MsoNormal 	{mso-style-unhide:no; 	mso-style-qformat:yes; 	mso-style-parent:""; 	margin:0cm; 	margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} .MsoChpDefault 	{mso-style-type:export-only; 	mso-default-props:yes; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} @page WordSection1 	{size:612.0pt 792.0pt; 	margin:72.0pt 90.0pt 72.0pt 90.0pt; 	mso-header-margin:36.0pt; 	mso-footer-margin:36.0pt; 	mso-paper-source:0;} div.WordSection1 	{page:WordSection1;} --&gt;\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span style="font-size: 10.0pt; font-family: Calibri; mso-bidi-font-family: &quot;Times New Roman&quot;; color: black;">A social enterprise is concerned with issues around social impacts and societal development through innovative solutions that generate funds in a more sustainable manner. Social enterprises are run on sound business principles. The only difference is that their focus is on social impacts and outcomes.</span></p>','0','1401426000','Yes','0'),
 ('543','What CSR/social programmes are you currently engaged in?','<p>Apart from the fact that a proportion of our profits will go to good causes, we have also leased out our technology to non-profit and community organisations at no charge plus they get to keep most of the commissions we would have otherwise have earned from Jubilee Insurance. We firmly believe this to be a more sustainable way of helping the individuals and organisations - the real unsung heroes in our society - that do a lot to build our communities.</p>','0','1401426000','Yes','0'),
 ('544','Does getting my insurances through them mean I pay more?','<p>No, precisely not, you pay the same rate as any other person who didn&rsquo;t go through them or us. The rates in the market generally tend to be similar for most products. The only difference is that they get a regular source of funding, which they need; and, you get your insurance cover, which you need to protect yourself, your loved ones and valued possessions.</p>','0','1401426000','Yes','0'),
 ('545','Can I buy insurance in Kenya even if I live abroad?','<p>Yes you can if you have &lsquo;Insurable Interest&rsquo; and can comply with the &lsquo;Jurisdiction Clause&rsquo; requirement. Please see what these are under our &lsquo;Jargon Buster&rsquo; page. In brief though the key requirement when it comes to buying insurance is that the loss or damage to the item concerned (the insured item) would cause that you to suffer a financial loss and/or other kinds of loss. So, for example, if you own property in Kenya, you can certainly insure that with a Kenyan insurance company. In some instances though you can pay someone&rsquo;s insurance even if you don&rsquo;t have insurable interest as long as you don&rsquo;t benefit from it e.g. if you are a Kenyan abroad and wish to pay for your relative&rsquo;s medical cover in Kenya.</p>','0','1401426000','Yes','0'),
 ('546','Why should I buy insurance through bima247.com?','<ul>\r\n<li>We      are the new smart, simple, fast, convenient, and secure way to purchase      your insurance and manage your finances online in Kenya, and for those      living abroad.</li>\r\n<li>The      cover is provided by an insurance company that not just the largest      composite insurer but also the largest medical insurer in Kenya. In      addition, it is a company that&rsquo;s a recent multiple recipient of some of      the industry&rsquo;s most prestigious awards including Composite Insurer of the      Year, Medical Insurance Underwriter of the Year, Customer Satisfaction      Award, Best Claims Settlement Award, and Most Trusted Brand In East      Africa.</li>\r\n<li>We      bring together the products that help you protect your life, your health,      your loved ones, your future and your possessions into one intuitive,      interactive simple-to-use and secure place. So at a glance you know what      is covered and how much that&rsquo;s costing you.</li>\r\n<li>You      can view your insurance details, or change them, wherever you are in the      world.</li>\r\n<li>You      are in full control of where and when you get cover, whether from the      comfort of your home or anywhere else in the world any time any day of the      year at a time and place of your choosing. It is a system built around you      so that.</li>\r\n<li>You      only have to spend a few short minutes entering your details in our secure      system once and we keep them securely forever.</li>\r\n<li>You      get an instant quote on screen and by email.</li>\r\n<li>You      pay online securely through any of the commonly used types of payment      methods. </li>\r\n<li>We      will email or text you when renewal is due so you don\'t have to keep      remembering the various renewal dates for all your policies or what      precise details you filled in your forms during the proposal stage.</li>\r\n<li>For      any issues, just click on the live help button and our award winning      customer service team in our state-of the-art help centre will be happy to      help 24/7 every day of the year or get in touch via email.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('547','Which insurance company is providing the cover?','<p>Jubilee Insurance Kenya is the insurer. Jubilee is the largest composite insurer in the region, and that has been providing protection for individuals like you, their loved ones and valued possessions continuously in Kenya since 1937. It is a company known not only for an excellent customer service ethos but also a well-reputed and unrivalled claims payment record.</p>\r\n<p>Jubilee is indeed a recent multiple recipient of some of the industry&rsquo;s most prestigious awards including Composite Insurer of the Year, Medical Insurance Underwriter of the Year, Customer Satisfaction Award, and Best Claims Settlement Award. These awards by its peers are certainly a mark of Jubilee&rsquo;s high esteem within professional circles especially with regards to professionalism, and a key reason why it is the first choice for many Kenyans when it comes to insurance cover. &nbsp;Jubilee Insurance Kenya is also the winner of the &lsquo;East Africa&rsquo;s Most Trusted Brand&rsquo; Award.</p>\r\n<p><br /> So, in short, you are safe and secure in the knowledge that your cover is by the best and multiple award winning insurer in the market, and one with the most stable financial capabilities as well as a solid emphasis on security for policyholders, committed, knowledgeable and professional staff, and a reputation for superior customer service. As a demonstration of their commitment to service excellence, Jubilee has implemented quality management system and is an ISO 9001:2000 certified company.</p>','0','1401426000','Yes','0'),
 ('548','Will my claims be paid in local currency?','Yes any settlement will be in local currency, in some cases the claim may be based on replacement value. Please see what this means under our &lsquo;Jargon Buster&rsquo; Page.','0','1401426000','Yes','0'),
 ('549','Can I pay for medical insurance on the site for my relatives in Kenya?','<p>Yes, you can do that. Through bima247.com, one annual premium payment will mean that your relative&rsquo;s medical expenses are taken care of throughout the whole year. This will also mean that you no longer have worry about unplanned monetary expenses for medical emergencies or have to hold harambees in your Diaspora communities. We have taken that worry away for you.</p>','0','1401426000','Yes','0'),
 ('550','Can I pay for medical insurance on the site for any other person in Kenya?','<p>Yes, a case in example is if you are supporting someone who is disadvantaged or if you are a non-profit organisation or individual who wishes to support other financially less able than yourself. To do this you can be based either in Kenya or anywhere else abroad.</p>','0','1401426000','Yes','0'),
 ('551','What is the procedure for paying for medical insurance on the site for a person or persons in Kenya?','<p>This is simple.</p>\r\n<ul>\r\n<li>Click      on the medical insurance link. </li>\r\n<li>Complete      their details on the medical proposal form including their name and post      box address.</li>\r\n<li><span style="color: #993300;"><strong>In      the field *Mobile Number and *Email Address, please put your own details      including country code.</strong></span> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>Complete      the remaining steps by putting in their details when prompted until you      get the quotation.</li>\r\n<li>Click      on &lsquo;checkout&rsquo; and select a payment method to pay. If you are out of the      Country, you may be best choosing either Visa or Mastercard, or</li>\r\n<li>Pay      through the link sent to your email with your quote. Here again, better to      chose Visa or Mastercard.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('552','What happens once I complete the form for medical insurance on the site and pay for another person?','<p>Just leave the rest to us and the insurance company providing their medical cover. We will get in touch with the person you are paying the insurance for once the proposal is approved by our insurers with details of what to do next.</p>','0','1401426000','Yes','0'),
 ('553','What is the procedure for paying for medical insurance on the site for myself whenever I am living in Kenya? ','<p>This is simple</p>\r\n<ul>\r\n<li>Click      on the medical insurance link. </li>\r\n<li>Complete      their details on the medical proposal form including your name and post      box address.</li>\r\n<li><span style="color: #993300;"><strong>In      the field *Mobile Number and *Email Address, please put your own details      including country code.</strong></span> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>Complete      the remaining steps by putting in your details when prompted until you get      the quotation.</li>\r\n<li>Click      on &lsquo;checkout&rsquo; and select a payment method to pay. If you are out of the      Country, you may be best choosing either Visa or Mastercard. If in Kenya,      do free to use the wide range of payment methods, or</li>\r\n<li>Pay      through the link sent to your email with your quote. Here again, better to      chose Visa or Mastercard, or use the wide range of payment methods      available.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('554','What happens once I complete the form for medical insurance on the site for myself?','<p>Just leave the rest to us and the insurance company providing your medical cover. We will get in touch with you once your proposal is approved by our insurers with details of what to do next.</p>','0','1401426000','Yes','0'),
 ('555','What is the procedure for buying Motor insurance on the site as a Diaspora?','<p>This is simple, if you have &lsquo;Insurable Interest&rsquo; and can comply with the &lsquo;Jurisdiction Clause&rsquo; requirement. Please see what these are under our &lsquo;Jargon Buster&rsquo; page.</p>\r\n<ul>\r\n<li>Click      on the Motor insurance link. </li>\r\n<li>Complete      your details on the motor proposal form including a local Kenyan address      and post box address because we will need this for correspondences. You      can have a relative&rsquo;s, lawyer&rsquo;s, friend&rsquo;s or any other representative&rsquo;s      here.</li>\r\n<li>Indicate      as &lsquo;n/a&rsquo; where appropriate e.g. if you don&rsquo;t have a PIN.</li>\r\n<li><span style="color: #993300;"><strong>In      the field *Mobile Number and *Email Address, please put your own details      including country code.</strong></span> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>Complete      the remaining steps by putting the correct details when prompted until you      get the quotation.</li>\r\n<li>Click      on &lsquo;checkout&rsquo; and select a payment method to pay. If you are out of the      Country, you may be best choosing either Visa or Mastercard, or</li>\r\n<li>Pay      through the link sent to your email with your quote. Here again, better to      chose Visa or Mastercard.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('556','What happens once I complete the form for Motor insurance on the site and pay?','<p>Just leave the rest to us and the insurance company providing your motor insurance cover. We will email you or the person you have put down with confirmation and then the motor certificate can be subsequently collected with policy documentations to follow.</p>','0','1401426000','Yes','0'),
 ('557','What is the procedure for buying Domestic Package insurance on the site as a Diaspora?','<p>This is simple, if you have &lsquo;Insurable Interest&rsquo; and can comply with the &lsquo;Jurisdiction Clause&rsquo; requirement. Please see what these are under our &lsquo;Jargon Buster&rsquo; page.</p>\r\n<ul>\r\n<li>Click      on the Domestic Package insurance link. </li>\r\n<li>Complete      your details on the Domestic Package insurance proposal form including a      local Kenyan address and post box address because we will need this for      correspondences. You can have a relative&rsquo;s, lawyer&rsquo;s, friend&rsquo;s or any      other representative&rsquo;s here.</li>\r\n<li>Indicate      as &lsquo;n/a&rsquo; where appropriate e.g. if you don&rsquo;t have a PIN.</li>\r\n<li><span style="color: #993300;"><strong>In      the field *Mobile Number and *Email Address, please put your own details      including country code.</strong></span> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>Complete      the remaining steps by putting the correct details when prompted until you      get the quotation.</li>\r\n<li>Click      on &lsquo;checkout&rsquo; and select a payment method to pay. If you are out of the      Country, you may be best choosing either Visa or Mastercard, or</li>\r\n<li>Pay      through the link sent to your email with your quote. Here again, better to      chose Visa or Mastercard.</li>\r\n</ul>\r\n<p>&nbsp;</p>\r\n<br />','0','1401426000','Yes','0'),
 ('558','What happens once I complete the form for Domestic Package insurance on the site and pay?','<p>Just leave the rest to us and the insurance company providing your Domestic Package insurance cover. We will email you or the person you have put down with confirmation, and then policy documentations will follow.</p>','0','1401426000','Yes','0'),
 ('559','What is the procedure for buying Travel insurance on the site as a Diaspora?','<p>This is simple, if you have &lsquo;Insurable Interest&rsquo; and can comply with the &lsquo;Jurisdiction Clause&rsquo; requirement. Please see what these are under our &lsquo;Jargon Buster&rsquo; page.</p>\r\n<ul>\r\n<li>Click      on the Travel insurance link. </li>\r\n<li>Complete      your details on the Travel insurance proposal form including a local      Kenyan address and post box address because we will need this for      correspondences. You can have a relative&rsquo;s, lawyer&rsquo;s, friend&rsquo;s or any      other representative&rsquo;s here.</li>\r\n<li>Indicate      as &lsquo;n/a&rsquo; where appropriate e.g. if you don&rsquo;t have a PIN.</li>\r\n<li><strong>In      the field *Mobile Number and *Email Address, please put your own details      including country code.</strong> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>Complete      the remaining steps by putting the correct details when prompted until you      get the quotation.</li>\r\n<li>Click      on &lsquo;checkout&rsquo; and select a payment method to pay. If you are out of the      Country, you may be best choosing either Visa or Mastercard, or</li>\r\n<li>Pay      through the link sent to your email with your quote. Here again, better to      chose Visa or Mastercard.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('560','What happens once I complete the form for Travel insurance on the site and pay?','<p>Just leave the rest to us and the insurance company providing your Travel insurance cover. We will email you or the person you have put down with confirmation, and then policy documentations will follow.</p>','0','1401426000','Yes','0'),
 ('561','What is the procedure for buying Personal Accident insurance on the site as a Diaspora?','<p>This is simple, if you have &lsquo;Insurable Interest&rsquo; and can comply with the &lsquo;Jurisdiction Clause&rsquo; requirement. Please see what these are under our &lsquo;Jargon Buster&rsquo; page.</p>\r\n<ul>\r\n<li>Click      on the Personal Accident insurance link. </li>\r\n<li>Complete      your details on the Personal Accident insurance proposal form including a      local Kenyan address and post box address because we will need this for      correspondences. You can have a relative&rsquo;s, lawyer&rsquo;s, friend&rsquo;s or any      other representative&rsquo;s here.</li>\r\n<li>Indicate      as &lsquo;n/a&rsquo; where appropriate e.g. if you don&rsquo;t have a PIN.</li>\r\n<li><span style="color: #993300;"><strong>In      the field *Mobile Number and *Email Address, please put your own details      including country code.</strong></span> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>Complete      the remaining steps by putting the correct details when prompted until you      get the quotation.</li>\r\n<li>Click      on &lsquo;checkout&rsquo; and select a payment method to pay. If you are out of the      Country, you may be best choosing either Visa or Mastercard, or</li>\r\n<li>Pay      through the link sent to your email with your quote. Here again, better to      chose Visa or Mastercard.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('562','What happens once I complete the form for Personal Accident insurance on the site and pay?','<p>Just leave the rest to us and the insurance company providing your Personal Accident insurance cover. We will email you or the person you have put down with confirmation, and then policy documentations will follow.</p>','0','1401426000','Yes','0'),
 ('563','Can I pay for any other insurance for others?','<p>Yes, remember that payment for the cover is not an issue since it will be just like you giving or loaning them a sum of money. What is an issue is that you must not stand to benefit from that insurance if you have no insurable interest. So, for instance, you can&rsquo;t pay insurance premium for your friend&rsquo;s house and expect to claim and be paid when the house is damaged. Please see our &lsquo;Jargon Buster&rsquo; page for more on what insurable interest is.</p>','0','1401426000','Yes','0'),
 ('564','Can I pay for motor insurance for a friend, relative or someone in Kenya?','<p>Yes, remember that payment for the cover is not an issue since it will be just like you giving or loaning them a sum of money. What is an issue is that you must not stand to benefit from that insurance if you have no insurable interest. So, for instance, you can&rsquo;t pay insurance premium for someone&rsquo;s car and expect to claim and be paid when the car is stolen or damaged. Please see our &lsquo;Jargon Buster&rsquo; page for more on what insurable interest is.</p>\r\n<p>The procedure itself is simple:</p>\r\n<ul>\r\n<li>Click      on the Motor insurance link and complete the proposal form yourself or get      that person you are paying their insurance to do that by putting in the      correct details when prompted. </li>\r\n<li><span style="color: #993300;"><strong>In      the field *Mobile Number and *Email Address, please put your own details including      country code.</strong></span> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>You      will then get an email through that email address with details of the      premium and a link to how to pay. </li>\r\n<li>Click      on the link to payment choices and select a payment method to pay. If you      are out of the Country, you may be best choosing either Visa or      Mastercard.</li>\r\n<li>Please      note that if you complete the details yourself, you can pay at the end of      the quote process i.e. step 4.</li>\r\n<li>Once      everything is paid, the proposal approved and cover commences, you will get      an email and the person you are paying the insurance for can collect their      insurance and get their policy documentations in due course.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('565','Can I pay for Domestic Package insurance for a friend, relative or someone in Kenya?','<p>Yes, remember that payment for the cover is not an issue since it will be just like you giving or loaning them a sum of money. What is an issue is that you must not stand to benefit from that insurance if you have no insurable interest. So, for instance, you can&rsquo;t pay insurance premium for someone&rsquo;s house and expect to claim and be paid when the house is damaged or destroyed. &nbsp;Please see our &lsquo;Jargon Buster&rsquo; page for more on what insurable interest is.</p>\r\n&nbsp;The procedure itself is simple: \r\n<ul>\r\n<li>Click      on the Domestic Package insurance link and complete the proposal form      yourself or get that person you are paying their insurance to do that by      putting in the correct details when prompted. </li>\r\n<li><span style="color: #993300;"><strong>In      the field *Mobile Number and *Email Address, please put your own details      including country code.</strong></span> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>You      will then get an email through that email address with details of the      premium and a link to how to pay. </li>\r\n<li>Click      on the link to payment choices and select a payment method to pay. If you      are out of the Country, you may be best choosing either Visa or      Mastercard.</li>\r\n<li>Please      note that if you complete the details yourself, you can pay at the end of      the quote process i.e. step 4.</li>\r\n<li>Once      everything is paid, the proposal approved and cover commences, you will get      an email and the person you are paying the insurance for will get their      policy documentations in due course.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('566','Can I pay for Travel insurance for a friend, relative or someone in Kenya?','<p>Yes, remember that payment for the cover is not an issue since it will be just like you giving or loaning them a sum of money. What is an issue is that you must not stand to benefit from that insurance if you have no insurable interest. So, for instance, you can&rsquo;t pay insurance premium for someone&rsquo;s house and expect to claim and be paid when the house is damaged or destroyed. &nbsp;Please see our &lsquo;Jargon Buster&rsquo; page for more on what insurable interest is.</p>\r\n<p>The procedure itself is simple:</p>\r\n<ul>\r\n<li>Click      on the Travel insurance link and complete the proposal form yourself or      get that person you are paying their insurance to do that by putting in      the correct details when prompted. </li>\r\n<li><span style="color: #993300;"><strong>In      the field *Mobile Number and *Email Address, please put your own details      including country code.</strong></span> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>You      will then get an email through that email address with details of the      premium and a link to how to pay. </li>\r\n<li>Click      on the link to payment choices and select a payment method to pay. If you      are out of the Country, you may be best choosing either Visa or      Mastercard.</li>\r\n<li>Please      note that if you complete the details yourself, you can pay at the end of      the quote process i.e. step 4.</li>\r\n<li>Once      everything is paid, the proposal approved and cover commences, you will get      an email and the person you are paying the insurance for will get their      policy documentations in due course.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('567','Can I pay for Personal Accident insurance for a friend, relative or someone in Kenya?','<p>Yes, remember that payment for the cover is not an issue since it will be just like you giving or loaning them a sum of money. What is an issue is that you must not stand to benefit from that insurance if you have no insurable interest. So, for instance, you can&rsquo;t pay insurance premium for someone&rsquo;s house and expect to claim and be paid when the house is damaged or destroyed. &nbsp;Please see our &lsquo;Jargon Buster&rsquo; page for more on what insurable interest is.</p>\r\n<p>The procedure itself is simple:</p>\r\n<ul>\r\n<li>Click      on the Personal Accident insurance link and complete the proposal form      yourself or get that person you are paying their insurance to do that by      putting in the correct details when prompted. </li>\r\n<li><span style="color: #993300;"><strong>In      the field *Mobile Number and *Email Address, please put your own details      including country code.</strong></span> \r\n<ul>\r\n<li>This       email address must be correct, as it will need to be verified. Check your       spam email box in case you can&rsquo;t see the verification link in your inbox.</li>\r\n</ul>\r\n</li>\r\n<li>You      will then get an email through that email address with details of the      premium and a link to how to pay. </li>\r\n<li>Click      on the link to payment choices and select a payment method to pay. If you      are out of the Country, you may be best choosing either Visa or      Mastercard.</li>\r\n<li>Please      note that if you complete the details yourself, you can pay at the end of      the quote process i.e. step 4.</li>\r\n<li>Once      everything is paid, the proposal approved and cover commences, you will get      an email and the person you are paying the insurance for will get their      policy documentations in due course.</li>\r\n</ul>','0','1401426000','Yes','0'),
 ('568','I want to a savings or investment plan, can I get that?','<p>Yes, although not yet on the site as that&rsquo;s coming soon. At the moment just use our &lsquo;contact us&rsquo; page with some details and we will get in touch.</p>','0','1401426000','Yes','0'),
 ('569','We are a Diaspora group or association; can we get a savings or investment plan?','<p>Yes, although not yet on the site as that&rsquo;s coming soon. At the moment just use our &lsquo;contact us&rsquo; page with some details and we will get in touch.</p>','0','1401426000','Yes','0'),
 ('570','I want to a funeral expenses plan, can I get that? ','<p>Yes, although not yet on the site as that&rsquo;s coming soon. At the moment just use our &lsquo;contact us&rsquo; page with some details and we will get in touch.</p>','0','1401426000','Yes','0'),
 ('571','We are a Diaspora group or association; can we get a group funeral expenses plan for our members?','<p>Yes, although not yet on the site as that&rsquo;s coming soon. At the moment just use our &lsquo;contact us&rsquo; page with some details and we will get in touch.</p>','0','1401426000','Yes','0'),
 ('572','We are a Diaspora group; can we get any other type of insurance cover thatâ€™s not within the site? ','<p>Yes, certainly we can arrange bespoke covers, just use our &lsquo;contact us&rsquo; page with some details and we will get in touch.</p>','0','1401426000','Yes','0'),
 ('573','Can I pay for insurance on the site when I am outside Kenya and abroad?','<p>Yes, we have provided a very good range ways to make international payments for your convenience.</p>','0','1401426000','Yes','0'),
 ('574','Do you partner with Diaspora or Diaspora associations and organisations?','<p>Yes, we do. Please read the relevant pages on working with us for more information on this.</p>','0','1401426000','Yes','0'),
 ('575','What is Insurable Interest?','A person is regarded as having an insurable interest in something when the loss or damage to the item concerned (the insured item) would cause that person to suffer a financial loss and/or other kinds of loss. Most insured have, or should have, insurable interest in any of their insured property. However in some cases they are not the only ones. For example, a bank may have an insurable interest in a vehicle or a building if they helped pay for the purchase.&nbsp; <br /><br />What this means is that you must stand to suffer a loss if what you are insuring is lost or damaged. So for example, if you own a car and it is stolen then you have insurable interest in the car, and can not only legally purchase insurance but also claim under your policy. You cannot insure your neighbours or relative&rsquo;s car and claim after a loss or damage. Insurance interest is therefore the connection between you &ndash; the person purchasing the insurance &ndash; and what is being insured. That connection may sometimes not be just about property. This can happen with life insurance where the person purchasing it may suffer a loss, which can be either emotional or financial when the person who is insured passes on.','0','1401512400','Yes','0'),
 ('576','What must I absolutely DO when looking to buy insurance?','<ol> </ol><ol>\r\n<li>Disclose      all material facts on the risks to be covered.</li>\r\n<li>Answer      all the questions fully and accurately.</li>\r\n<li>Ensure      your property and possessions are valued accurately.</li>\r\n</ol><ol> </ol>','0','1401512400','Yes','0'),
 ('577','What must I absolutely NOT DO when looking to buy insurance?','<ol>\r\n<li>Leave      any question in the proposal form unanswered.</li>\r\n<li>Withhold      or misrepresent any material facts. If you do that, your insurance will be      void. What this means in your claim may not get paid; and in addition any      premium you have paid will not be refunded. Always remember that it is      your duty and responsibility to ensure that all the correct information is      given to the insurance company.</li>\r\n<li>Underinsure,      as you will be penalized by the application of Average in the event of a      claim. &nbsp;What this means is that you will be paid less than the sum      insured indicated in your policy.</li>\r\n</ol>','0','1401512400','Yes','0'),
 ('578','What information should I have before I start the process?','<p>This varies but at a minimum you must have the correct postal and email addresses, age, contact number, ID or Passport, and PIN. You may be also asked details of the property or possessions you are insuring, so it is important to have that to hand.</p>','0','1401512400','Yes','0'),
 ('579','What if my details are incorrect?','<p>You are applying for insurance cover and entering a legally binding contractual relationship so you must make sure you have all the key details and input them correctly. NOT DOING THIS CORRECTLY MAY MEAN YOUR POLICY IS INVALID. It will also mean that any claim you make may not be paid; and, you will not get your premium back. So it is vitally important that you ensure your details are absolutely correct.</p>','0','1401512400','Yes','0'),
 ('580','What if the details of products and prices on the website are incorrect?','<p>We will do our best to correct errors and omissions as soon as we can. Nevertheless on occasion there may be mistakes in the price or type of product shown. In the event that such error in price, product or service is shown then we reserve the right to cancel that contract, but this of course will be without any liability to you and a refund will be offered.</p>','0','1401512400','Yes','0'),
 ('581','What if something changes after I have taken the policy?','<p>If that does, you MUST inform us, or our underwriters immediately. Please keep a record of any correspondence since some changes can make your policy invalid, which again means that any claim you may have will not be paid and your premium won&rsquo;t be refunded.</p>','0','1401512400','Yes','0'),
 ('582','What happens once I pay?','<p>You will get an email from us confirming receipt and then Jubilee Insurance Kenya will get in touch to confirm whether or not the proposal has been accepted.</p>\r\n<p>IMPORTANT NOTE: The cover will only commence upon payment of premiums, and after you have received written confirmation from Jubilee Insurance Kenya.</p>','0','1401512400','Yes','0'),
 ('583','At what point am I actually covered?','<p>Cover starts only once your proposal(s) accepted by, and you get a written confirmation from, Jubilee Insurance Kenya.</p>','0','1401512400','Yes','0'),
 ('584','Am I insured once I input my details on this website?','<p>The content of the Site does not constitute an offer by us to sell products and services. Your request to purchase a product or service represents an offer by you and will be subject to the terms and conditions of that product or service that we may accept or reject. After you make a request through the Site to purchase the product or service then assuming such product or service is available to you and your offer is accepted, you will receive confirmation of your purchase and payment from either us or Jubilee Insurance Kenya.</p>','0','1401512400','Yes','0'),
 ('585','Are all the terms and conditions of the policy or product I am purchasing on this website?','<p>The information and descriptions on the Site do not necessarily represent complete descriptions of all terms, conditions and exclusions and the precise cover provided (as applicable). These shall be included in the schedule of cover, policy documents and/or conditions of purchase issued to you.</p>','0','1401512400','Yes','0'),
 ('586','When can the cover start?','<p>You decide the date you\'d like the cover to start on. &nbsp;Note though that cover can never be backdated.</p>','0','1401512400','Yes','0'),
 ('587','How is my policy to be delivered?','<p>Your policy will be delivered either by Jubilee Insurance Kenya or us.</p>','0','1401512400','Yes','0'),
 ('588','Who pays any claims?','<p>Jubilee Insurance Kenya as the underwriter will also be paying the claims. Do rest assured that you are in the best hands given Jubilee&rsquo;s well-deserved reputation for claims payment. Jubilee is indeed a recent recipient of some of the industry&rsquo;s most prestigious awards including Composite Insurer of the Year, Medical Insurance Underwriter of the Year, Customer Satisfaction Award, and Best Claims Settlement Award as well as the \'Most Trusted Brand in East Africa\'</p>','0','1401512400','Yes','0'),
 ('589','What happens with a claim?','<p>For medical, you go to any of the approved providers who will treat you based on your policy terms and conditions.</p>\r\n<p>For non-medical, you need to inform both the insurance company and us. So, come back onto the site inform us immediately then complete the appropriate form as soon as feasibly possible.</p>\r\n<p><br /> The completed the claim notification form with all the details and necessary documentations should go to Jubilee Insurance Kenya&rsquo;s claims department.</p>\r\n<p>Keep copies of any forms and evidence since we will need that in case we have to follow up on your behalf.</p>','0','1401512400','Yes','0'),
 ('590','What does PHCF, which I can see added to my premium, mean?','<p>The Policyholders Compensation Fund (PHCF) is a State Corporation under the Ministry for Finance that was established through the Legal Notice No.105 of 2004 and commenced its operations in January 2005.</p>\r\n<p>The Fund was established for the primary purpose of providing compensation to policyholders of an insurer that has been declared insolvent and for the secondary purpose of increasing the general public&rsquo;s confidence in the insurance sector. The decision to establish the Fund was informed by the collapse of several Insurance companies prior to the year 2005. The Fund is governed by section 179 of the Insurance Act (Cap 487) and the Insurance (Policyholders Compensation Fund) Regulations, 2010.</p>','0','1401512400','Yes','0'),
 ('591','What are the rights of policyholders in relation to compensation from the PHCF?','<p>The insurance policyholders are the beneficiaries of the Fund. It is therefore the policyholders right to be compensated when the company providing them with an insurance cover is declared insolvent by a court of law.</p>','0','1401512400','Yes','0'),
 ('592','What is the Insurance Training Levy added to my premium?','<p>That is a levy insurance companies are obligated to charge by Law and aimed at contributing to the education of personnel in the industry.</p>','0','1401512400','Yes','0'),
 ('593','What is an excess?','<p>The excess is the amount that you would need to pay towards any claim. So, for example, if you make a claim of KES 100,000 and the excess is KES 10,000, you will only be able to claim back KES 90,000. The KES 10,000 will come from your own pocket. Sometimes the excess can be in percentage form.</p>','0','1401512400','Yes','0'),
 ('594','Where can I find information about my excess?','<p>The excess amount is displayed in the policy wording. Please check your policy documentations.</p>','0','1401512400','Yes','0'),
 ('595',' What are terms and conditions?','<p>Terms and conditions, sometimes also written as T&amp;Cs, are a key part of what governs your relationship with the insurers. You must read them very carefully as any contravention may mean your claim does not get paid. You will also lose your premium.</p>','0','1401512400','Yes','0'),
 ('596','Is Stamp Duty a one-off charge?','<p>Yes, it only applies to a new policy.</p>','0','1401512400','Yes','0'),
 ('597','What is meant by the term Material Disclosure?','<p>What this means is that all the information that can influence an Insurer&rsquo;s decision in accepting the risk (s) and or determining the terms must be disclosed to them. These are known as material facts.</p>\r\n<p>Simply put, you must truthfully disclose anything and everything related to the risk you want to insure otherwise you run the risk of your claim not being paid and your policy becoming null and void. You are best disclosing things even if you are not sure they are material.</p>','0','1401512400','Yes','0'),
 ('598','How do I determine what material facts are?','<p>Material facts are those things that would cause an insurer to either price your insurance differently or not accept your insurance proposal. Examples include: a series of past similar claims, a new room or extension added to the original property, a change of the property&rsquo;s occupancy and so on. You must inform your insurers about any material facts otherwise you risk your claims being invalidated. &nbsp;&nbsp;</p>','0','1401512400','Yes','0'),
 ('599','What information does the insurance company need to know?','<p>You must tell your insurer everything that is relevant to your insurance. If you don\'t then you risk having your policy cancelled, and being left without cover. So for example with motor insurance, they need to know all your details, your driving history (any previous claims you and any other drivers may have had), who will be using the car, the car details and the location.</p>','0','1401512400','Yes','0'),
 ('600','Why do I need to give so much information?','<p>Insurance companies need to take a lot of information in order to calculate a price: things like the make and model of car, driving history, claim experience, occupation, and type (s) of use.</p>','0','1401512400','Yes','0'),
 ('601','What are exclusions and exceptions?','<p>All policies have exclusions and exceptions. You are best advised to check what these are on the policy documents. This is because, once again, any claims that happen to fall under the exclusions will not be paid for.</p>','0','1401512400','Yes','0'),
 ('602','What is a pre-existing medical condition?','<p>This is any medical condition &ndash; usually specified in the policy document and or proposal form &ndash; which any person insured or to be insured on the policy has, or has had for which they have received treatment (including surgery, tests or investigations by a doctor or a consultant/specialist and prescribed drugs or medication). &nbsp;Please read your policy document and proposal forms carefully.</p>','0','1401512400','Yes','0'),
 ('603','Can I get insurance even with pre-existing conditions?','<p>Yes, in some cases you can. It is always best to check. Some insurers though have an exclusion or waiting period (e.g. 2 years) during which time any claims resulting from that condition is not covered or paid.</p>','0','1401512400','Yes','0'),
 ('604','How long does an insurance policy run for?','<p>Typically a policy will remain in force for 12 months from the start date (or as otherwise shown on the policy) and for any period for which you renew the policy, as long as you continue to pay your premium.</p>','0','1401512400','Yes','0'),
 ('605','What happens if I have any problem with my policy other than claims?','<p>You can always come back online and talk to us. We are here 24/7 all the days of the year.</p>','0','1401512400','Yes','0'),
 ('606','Can I cancel my policy?','<p>You have a right to cancel. It you do, you are entitled to get a proportion of your premium for the unexpired term.</p>','0','1401512400','Yes','0'),
 ('607','How much premium do I get back if I cancel a policy?','<p>This proportion is based on the unexpired period of insurance. With some policies and types of risks this may be a straightforward annual premium divided by the number of days multiplied by the unexpired number of days. In some cases though the insurer may base this on a percentage, which means you get a proportionately less premium amount back.</p>','0','1401512400','Yes','0'),
 ('608','Can I cancel my policy and get premium back if I have had a claim?','<p>No, not if there has been a claim during that current period of insurance.</p>','0','1401512400','Yes','0'),
 ('609','What happens if there is another insurance policy covering the same property or risk?','<p>In such cases, if there is a claim, the insurance company will not be liable for the full sum insured under your policy. The company will only pay its rateable proportion. The total claim amount will be divided between all the insurers.</p>','0','1401512400','Yes','0'),
 ('610','What happens if I go abroad, am I still insured?','<p>Most policies have what are known as territorial limits. What this means is that if the policy states the territorial limit is Kenya, then if you go or take the property insured abroad, you are no longer covered. Some types of policies though allow you to do this e.g. Travel Insurance.</p>','0','1401512400','Yes','0'),
 ('611','Are there types of insurance covers that allow me to travel abroad and still be insured?','<p>Yes, some types of insurances do. Good examples of these are Travel Insurance and some Personal Accident ones.</p>','0','1401512400','Yes','0'),
 ('612','How much detail do I need to give of my propertyâ€™s security?','<p>Security levels will affect how insurers view your risk so give as much details as possible and just remember this is likely be checked in the event of a claim. Common security precautions include burglar proofing and alarms, immobilizers and trackers.</p>','0','1401512400','Yes','0'),
 ('613','How much should I enter as the total sum insured?','<p>The amount should be an accurate valuation of your actual loss in case something happens to your possessions. Be careful that you do not under insure i.e. indicate a value that is less than the total value of the contents at the address you\'d like to insure. Under insurance whether unwittingly or not can cause insurers to pay you much less than the sum insured in case of a claim.</p>','0','1401512400','Yes','0'),
 ('614','What is consequential loss?','<p>This is an indirect loss that accompanies an insured loss. What this means is that, for example, if there is fire damage to your building and you lose your rental income or business earnings as a consequence of the fire, you don&rsquo;t get that income or those earnings paid by the policy.</p>','0','1401512400','Yes','0'),
 ('615','Can I get insurance for consequential loss?','<p>Yes, and usually you have to pay a separate premium for this.</p>','0','1401512400','Yes','0'),
 ('616','How quickly will my claims be paid?','<p>Jubilee Insurance is an award-winning insurer with numerous awards including for claims settlement in the past, so do rest assured that your claim will be dealt with as rapidly as possible. Of course you can help speed up the process by providing the correct and required documentations as soon as possible. In terms of speed though, it is hard to give a benchmark figure since a simple burglary claim is likely to be different to a motor claim involving injuries and liabilities.</p>','0','1401512400','Yes','0'),
 ('617','I canâ€™t remember my password, what do I do?','<p>No need to panic, just reset it through the forgotten password link.</p>','0','1401512400','Yes','0'),
 ('618','Someone may have hacked into my account or stolen my password?','<p>If you suspect that someone else has unauthorised knowledge of your password, please get in touch immediately.</p>','0','1401512400','Yes','0'),
 ('619','What is covered by motor insurance?','<p>Broadly, a motor insurance policy covers you against loss or damage to your own vehicle due to accidental fire, theft, accident, third&ndash;party bodily injury or death, third party property loss or damage.</p>','0','1401512400','Yes','0'),
 ('620','Must I have a motor insurance policy?','Yes, it is compulsory to insure any motor vehicle (s) against third party risks in Kenya.','0','1401512400','Yes','0'),
 ('621','What if I donâ€™t have a motor insurance policy?','<p>Then you would be committing an offence and will be liable for prosecution under the laws of the land.</p>','0','1401512400','Yes','0'),
 ('622','What types of motor insurance covers can I purchase?','<p>Motor insurance is typically divided into three types of covers</p>\r\n<ol>\r\n<li>Third      Party Only &ndash; This is the least amount of cover you can buy, which protects      you against third party losses including death, bodily injury and/or      property damage.</li>\r\n<li>Third      party Fire and Theft &ndash; This is a more enhanced cover, which builds upon      your protection against all third party risks mentioned above in addition      to loss and/or damage to your vehicle due to fire and theft.</li>\r\n<li>Comprehensive      &ndash; This is the most cover available, which builds upon both covers above to      protect you against third party losses including death, bodily injury      and/or property damage as well as loss/ damage to your vehicle due to      accidental fire, theft or an accident.</li>\r\n</ol>','0','1401512400','Yes','0'),
 ('623','Who is a Third Party?','<p>An insurance contract has three parties to it:</p>\r\n<p>You as the vehicle owner and the insured are considered to be the first party.</p>\r\n<p>Your insurance company is deemed to be the second party.</p>\r\n<p>The third party to the insurance contract is the individual &ndash; e.g. property owner, pedestrian or occupants of another motor vehicle &ndash; who suffers as a result of an accident involving your motor vehicle. This can be a loss or damage to their property or death or bodily injury.</p>','0','1401512400','Yes','0'),
 ('624','What is normally NOT COVERED under motor insurance policies?','<p>You will need to read the policy documents carefully for an exhaustive list of what&rsquo;s not covered normally. Some key ones, though, are as follows:</p>\r\n<ol>\r\n<li>The      excess stated in your policy schedule.</li>\r\n<li>Any      loss or damage caused by unauthorised or unlawful use of the motor vehicle      e.g. a vehicle not insured for business use but then used for that      purpose.</li>\r\n<li>Your      own death or bodily injury or that of someone in your employ arising out      of and in the course of such employment</li>\r\n<li>Damage      to property that belongs to you or your family, or for which you are      responsible.</li>\r\n<li>Any      consequential loss, depreciation, wear and tear, rust and corrosion,      mechanical or electronic breakdowns, equipment or computer malfunction.</li>\r\n<li>Any      loss or damage caused by or due to cheating.</li>\r\n<li>Any      loss or damage that occurs whilst an unauthorized driver is driving the vehicle.</li>\r\n<li>Any      loss or damage whilst the vehicle is being driven under the influence of      alcohol</li>\r\n<li>Any      loss or damage to the vehicle&rsquo;s tyre (s) unless damage is caused to other      parts of the vehicle at the same time.</li>\r\n</ol>','0','1401512400','Yes','0'),
 ('625','How much should I insure my vehicle for?','Your vehicle should be insured for its current market value i.e. the cost of replacing your vehicle with a similar make, model, age and condition. If the amount covered is less than the market value, then the average (underinsurance) condition will apply.','0','1401512400','Yes','0'),
 ('626','What happens when I donâ€™t insure the correct value of my property or car?','<p>It is important you do that since you will be considered underinsured and the average (underinsurance) clause will apply.</p>','0','1401512400','Yes','0'),
 ('627','How does the average (underinsurance) clause work?','<p>If at the time of a claim, the market value of the insured vehicle (s) is greater than the estimated value you wrote down in the original proposal or at renewal &nbsp;(including accessories and spare parts) then you will be paid less than that estimated value. So if your car&rsquo;s current market value is KES 400,000 and you insure it for KES 250,000 and you later suffer a loss of KES 80,000. The claim amount paid to you will be as follows:</p>\r\n<p><span style="text-decoration: underline;">80,000(loss value) x 250,000(insured value)</span> =50,000<br /> 400, 000(current market value)</p>\r\n<p>You will get paid KES 50,000 and NOT the full amount of the claim i.e. KES 80,000. &nbsp;The difference (KES 30,000) will be paid by you out of your own pockets.</p>','0','1401512400','Yes','0'),
 ('628','What should I do if I get into an accident?','<p>Here are a few things you need to do:</p>\r\n<ol>\r\n<li>Don&rsquo;t      admit liability to any third party.</li>\r\n<li>Get      as much information as per below in relation to the accident as possible.</li>\r\n<li>Names      and addresses of all drivers and passengers involved in the accident.</li>\r\n<li>Registration      numbers make and model of each vehicle involved in the accident.</li>\r\n<li>Details      of the driving license, insurance policy and certificate of the third      party driver.</li>\r\n<li>Names      and addresses of as many witnesses as possible.</li>\r\n<li>Stay      safe by phoning the police and moving your vehicle &ndash; if necessary &ndash; to      somewhere safe.</li>\r\n<li>Make      sure the police and your insurance company are informed as soon as      possible. A follow up notification in writing is best too.</li>\r\n<li>Complete      the correct claims form and attach all the necessary supporting documents.</li>\r\n</ol>','0','1401512400','Yes','0'),
 ('629','What are my obligations when it comes to making a claim?','<ul>\r\n<li>You must report any accident, injury, loss or damage soon as reasonably possible.</li>\r\n<li> </li>\r\n<li>If the loss or damage is as a result of theft, attempted theft or malicious damage, you must make a report to the police and obtain a police report.</li>\r\n<li>You must also inform the insurer immediately you become aware of any current or future prosecution or proceedings in connection with any for which there may be any liability under this policy.</li>\r\n<li>Any correspondence relating to any incident should be sent to the insurer immediately. Please note that the insurance company will be entitled to take over and carry out in your name the defence or settlement of any claim. In addition the insurer may also prosecute in your name to recover any amounts paid by them.</li>\r\n<li>When you make a claim, the insurance company will be entitled to instruct and give information relating to the claim to other people such as lawyers, investigators, loss assessors, garages and you will be expected to give them all the necessary co-operation.</li>\r\n</ul>','0','1401512400','Yes','0'),
 ('630','What must I do if I want to cancel the insurance cover?','<p>Simply send a written notice to the insurance company. Although you are entitled to a refund when you cancel, the insurance company may penalise you for cancelling early. &nbsp;You must also return the motor certificate.</p>','0','1401512400','Yes','0'),
 ('631','What happens to the certificate when I sell my vehicle?','<p>It immediately becomes invalid and the new vehicle owner must obtain a new certificate otherwise they will be in contravention of the laws. Once you return the certificate, you may be entitled to a refund.</p>','0','1401512400','Yes','0'),
 ('632','What is a No Claims Discount (NCD)?','<p>NCDs are incentives to you for not making a claim during the preceding period of motor insurance. It is a discount is given according to the class of motor insurance and you will get it when you renew. The discounts are:</p>\r\n<ol>\r\n<li>For      Third Party only &ndash; 0%</li>\r\n<li>For      Third Party Fire and Theft &ndash; 10% (in year 2), 20% (in year 3).</li>\r\n<li>For      Comprehensive &ndash; 10% (in year 2), 20% (in year 3), 30% (in year 4), 40% (in      year 5), 50% (in year 6 and subsequent ones).</li>\r\n</ol>','0','1401512400','Yes','0'),
 ('633','What happens to my No Claims Discount (NCD) if I make a claim?','<p>If a claim is paid or payable in one policy year, you will automatically lose two (2) years&rsquo; discounts while if two (2) or more claims are paid or payable in one policy year you lose all the discounts.</p>','0','1401512400','Yes','0'),
 ('634','Are NCDs transferrable?','<p>NCDs are not transferrable whether from one vehicle to another or between individuals.</p>','0','1401512400','Yes','0'),
 ('635','I have more than one vehicle, what happens to their NCDs?','<p>If all your vehicles are insured under this policy then each one will be treated as if they are insured under a different policy for the purposes of the NCDs. So you will continue to enjoy the NCD percentages earned by each of the vehicles.</p>','0','1401512400','Yes','0'),
 ('636','What is a tracker?','&lt;!--  /* Font Definitions */ @font-face 	{font-family:Times; 	panose-1:2 0 5 0 0 0 0 0 0 0; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:3 0 0 0 1 0;} @font-face 	{font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-font-charset:78; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1791491579 18 0 131231 0;} @font-face 	{font-family:"Cambria Math"; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1107305727 0 0 415 0;} @font-face 	{font-family:Calibri; 	panose-1:2 15 5 2 2 2 4 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-520092929 1073786111 9 0 415 0;} @font-face 	{font-family:Cambria; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1073743103 0 0 415 0;}  /* Style Definitions */ p.MsoNormal, li.MsoNormal, div.MsoNormal 	{mso-style-unhide:no; 	mso-style-qformat:yes; 	mso-style-parent:""; 	margin:0cm; 	margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} .MsoChpDefault 	{mso-style-type:export-only; 	mso-default-props:yes; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} @page WordSection1 	{size:612.0pt 792.0pt; 	margin:72.0pt 90.0pt 72.0pt 90.0pt; 	mso-header-margin:36.0pt; 	mso-footer-margin:36.0pt; 	mso-paper-source:0;} div.WordSection1 	{page:WordSection1;} --&gt;\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span style="font-size: 10.0pt; font-family: Calibri; mso-bidi-font-family: &quot;Times New Roman&quot;; color: black;">A tracker is an electronic device (normally fitted as an accessory after purchase of the car) that emits a signal enabling the car to be located wherever it is anywhere in Kenya, or even in some cases outside the country, if it has been stolen.</span></p>','0','1401512400','Yes','0'),
 ('637','What is an immobiliser?','<p>An immobiliser is an electronic device that stops the car from being started if it is broken into. Although this won\'t stop your car from being broken into, it may very well stop it from being driven away.</p>','0','1401512400','Yes','0'),
 ('638','How do I know what sort of immobiliser/tracker/alarm system my car has?','<p>Simply look up the details on the documentation that comes with the car.</p>','0','1401512400','Yes','0'),
 ('639','Who is meant by â€˜Main Driverâ€™?','The main driver is the person who uses the car the most. It is better to be honest here if you are planning on insuring a car for your spouse, son, daughter or employee as insurers may not pay your claim (s) if you don\'t tell them the truth.','0','1401512400','Yes','0'),
 ('640','What is meant by â€˜Modificationsâ€™?','<p>These are any changes to the vehicle and not part of the standard vehicle specification. This could include such things as alloy wheels, spoilers, engine modifications et cetera. &nbsp;It is important that you inform the insurance company of any changes or modifications to the vehicle (s) insured or the one(s) you want insured. Not doing this may not only mean your insurance cover is invalid but also that any claims you make are not payable. On top of this, any premiums paid by you will not be refunded.</p>','0','1401512400','Yes','0'),
 ('641','What is a Domestic Package Policy?','<p>It is a policy that covers the combined risks to your building (s), contents whilst inside and outside the house against loss and or damage. As well as that, your house servants are covered against death or injury whilst in the course of their employment. Additionally, any personal legal liability to third parties is covered.</p>','0','1401512400','Yes','0'),
 ('642','What is building insurance?','<p>Building insurance is the right cover if you only want to insure the structure, fixtures and fittings of your house. Having building insurance means that if, for example, your house caught fire accidentally or was struck by lightning or got damaged by any of the insured risks, you can claim on it.</p>','0','1401512400','Yes','0'),
 ('643','What is content insurance?','<p>Unlike insurance on your building, content insurance covers your possessions in case of theft or damage. This includes your carpets, furniture and electronic equipment and other property in your house. In some cases it can even include valuables, which you might take out with you, like a laptop, tablet or smartphone. You will need to have taken care to keep your property safe once outside otherwise you may not get, so for example, any possessions left in your car must be locked in and secure.</p>','0','1401512400','Yes','0'),
 ('644','Is it compulsory to have both building and content insurance?','<p>Strictly speaking, when you own a house, building insurance and content insurance are not compulsory - but in reality, it is wise to have them. This is not only for your peace of mind but also because for most of us a house is probably the largest investment we have ever made, which makes protecting that an eminently sensible choice.</p>\r\n<p>Most mortgage lenders insist that when you buy a house, building insurance is in place. If the property were to fall down or get otherwise damaged, the mortgage lender would want to know that the house has building insurance.</p>\r\n<p>Content insurance covers your possessions and provides some liability insurance. If someone were to get injured in your home, they could make a claim against you. Depending on the extent of the injury, this could be a significant sum &ndash; so, again, it is worth having some protection in place.</p>','0','1401512400','Yes','0'),
 ('645','What risks am I exactly protected against when I take a Domestic Package policy?','<p>You should look in the policy documents for an exhaustive list. However, some of the key ones are:</p>\r\n<ol>\r\n<li>Fire,      Lightning, Thunderstorm, Earthquake or Volcanic Flow.</li>\r\n<li>Explosion.</li>\r\n<li>Riot,      Strike and Civil Commotion.</li>\r\n<li>Malicious      Damage caused by any other person other than a member of your household.</li>\r\n<li>Aircraft      or other Aerial Devices or any Article dropped from thereon.</li>\r\n<li>Bursting      or overflowing or escape of water from tanks, pipes and other â€¨apparatus excluding (<em>some exclusions apply      here, so make sure you look at the full policy documents for those</em>).</li>\r\n<li>Theft      accompanied by actual forcible and violent break-in into or out of the      Buildings or any attempt thereat (<em>some exclusions apply here, so make      sure look at the full policy documents for those</em>).</li>\r\n<li>Impact      with the Buildings (<em>see policy for documents for more details</em>)</li>\r\n<li>Wind,      Storm, or Tempest (including Floods and overflow of the Sea occasioned      thereby).</li>\r\n<li>Your      liability as an employer with regards to domestic workers (<em>limits apply      and some extra charges may also apply here, so please see the full policy      document</em>).</li>\r\n<li>Your      liability as an occupier or owner (<em>limits apply and some extra charges      may also apply here, so please see the full policy document</em>).</li>\r\n</ol>','0','1401512400','Yes','0'),
 ('646','What are not covered by the policy?','<p>Some key ones are listed below. However you are advised to have a careful read of the policy documentations for a complete list.</p>\r\n<ol>\r\n<li>The      excess, which can be found in your policy documentation.</li>\r\n<li>Consequential      loss of any kind (a small amount of this may be allowed, please see the      policy document).</li>\r\n<li>Deeds,      bonds, bills of exchange, promissory notes, cheques, traveller\'s cheques,      securities for money, stamps, documents of any kind, cash currency notes,      manuscripts, medals, coins, motor vehicles and accessories and â€¨Livestock unless specially mentioned as      covered within the policy document.</li>\r\n<li>Losses      outside the territorial limits.</li>\r\n<li>Losses      from wear and tear, depreciation gradual deterioration, moth, vermin,      insects, inherent vice, rust or atmospheric conditions or action of light.</li>\r\n<li>Losses      arising from electrical or mechanical breakdown, faulty manipulation or      mechanical defects.</li>\r\n<li>Losses      due to theft or attempted theft by any member of the your household.</li>\r\n<li>Theft      of property from a motor vehicle unless the property is contained in a      locked vehicle and provided that utmost precaution has been taken to      protect â€¨the property from exposure      of loss.</li>\r\n</ol>','0','1401512400','Yes','0'),
 ('647','Do I have to tie-in the insurance to my mortgage lender?','<p>You don\'t have to buy your building or content insurance from your mortgage lender unless you agreed to a special mortgage deal, which requires you to buy insurance through them or \'their\' insurance.</p>','0','1401512400','Yes','0'),
 ('648','Can I store petrol and or any liquid and or mineral oil and or anything similar in nature within my insured premises?','<p>Your policy has a Petrol and Mineral Oil Warranty. What this means is that you should NOT have more than 270 litres (60 gallons) of liquid fuel and or mineral oil or any other inflammable or combustible material within the insured property. If you do, your policy may be invalid and any claim may not be paid by the insurance company.</p>','0','1401512400','Yes','0'),
 ('649','What are â€˜special termsâ€™?','<p>Sometimes insurance companies impose special terms on customers. You must let the insurer know if this has happened with any of your previous insurers. Not doing that may mean you don&rsquo;t get your claim (s) paid. And you also lose any premium you may have already paid.</p>','0','1401512400','Yes','0'),
 ('650','What if the property is also used for business?','<p>You need to tell the insurance company if you use the property for business purposes. This is because that would change the nature of your risk.</p>','0','1401512400','Yes','0'),
 ('651','Must I inform the insurer if my property is left unoccupied for a period?','<p>Yes, you must do that or even if you intend to be occasionally unoccupied throughout the term of your insurance.</p>','0','1401512400','Yes','0'),
 ('652','I have Jewellery and other valuables whose value exceeds KES 50,000. Would that be covered?','<p>Yes but the insurance company will not be liable for anything whose individual value exceeds KES 50,000 unless you provide an independent valuation from a professional Jeweller.</p>','0','1401512400','Yes','0'),
 ('653','I have a lot of belongings that may fall down and break, would that be covered?','<p>No, the policy specifically excludes claims from breaking of articles that are of a brittle nature unless they lenses.</p>','0','1401512400','Yes','0'),
 ('654','To what extent are my lenses covered?','<p>You are not covered for damage to or scratching of lenses or prisms unless it is part of other damage to the property that&rsquo;s sustained at the same time.</p>','0','1401512400','Yes','0'),
 ('655','I am travelling outside Kenya; will my insurance cover still be valid?','<p>No, not if it is outside the territorial limit. Most covers exclude losses or damages to your property whilst outside the territorial limit stated in your policy documentation.</p>','0','1401512400','Yes','0'),
 ('656','What of those items I take away from my home or insured premises?','<p>Insurance can be arranged for those possessions that can be taken away from the home or insured premises. Examples of these would include: Jewellery, Cameras, Computers, Smartphones and Tablets. Some insurers would call this All-Risks cover.</p>\r\n<p>Please note that most of the time you have to request for this otherwise you will not be covered for any items away from the home or insured premises.</p>\r\n<p>Also note that even if you are covered for the items you they have to be secure and locked whilst away from the property otherwise you run the risk of having your claim (s) not being paid.</p>','0','1401512400','Yes','0'),
 ('657','I have sports equipment, can I claim under the policy if they are damaged or lost?','<p>Not if this happens when you are playing or using the equipment and unless the loss is caused by fire or theft or accident.</p>','0','1401512400','Yes','0'),
 ('658','Will my claim be paid in cash?','<p>Usually insurers will work with you to find a mutually satisfactory method. However, the Insurer may at its own option repair, reinstate or replace any such property lost or damaged or may pay in cash in lieu of the amount of the loss or damage.</p>','0','1401512400','Yes','0'),
 ('659','My property has been confiscated and/or detained by Customs or Other Officials, can I claim for any loss or damage due to that?','<p>Unfortunately this is a specific exception in the policy so you cannot claim.</p>','0','1401512400','Yes','0'),
 ('660','My property was being worked on and it got damaged, can I claim under the policy?','<p>Unfortunately you cannot claim in this case. There is a specific exception which excludes loss or damage to the property insured &lsquo;if its undergoing any process involving the application of heat or the actual process of dyeing, cleaning, repair, renovation or alteration or its being worked upon&rsquo;.</p>','0','1401512400','Yes','0'),
 ('661','On what basis will my claim be settled?','<p>The basis of settlement under the sections shall be-</p>\r\n<p>Under section A of a Domestic Package Policy - Replacement value of the property.</p>\r\n<p>Under section B of a Domestic Package Policy - Replacement value less a reasonable deduction for depreciation, wear and tear.</p>\r\n<p>Please note that the insurance company may at its option make payment replace, reinstate or repair the property damaged, stolen or destroyed.</p>','0','1401512400','Yes','0'),
 ('662','What is Replacement value?','<p>This is the cost to replace the damaged property with materials of like kind and quality, without any deduction for depreciation.</p>','0','1401512400','Yes','0'),
 ('663','Why should I buy medical and health insurance?','<p>For your peace of mind. With insurance cover you don&rsquo;t need to have ready cash if you or a loved member of your family needs medical treatment in a hospital. &nbsp;You also avoid the need for harambees.</p>','0','1401512400','Yes','0'),
 ('664','Can I buy medical insurance for my children?','<p>Yes but they must not be older than 18 years. Older children are expected to financially independent.</p>','0','1401512400','Yes','0'),
 ('665','How does medical insurance work generally?','<p>Once accepted by the insurance company, the company will pay the bill on your behalf if you or your insured family member needs to be treated in a hospital. This will be up to certain specified amounts, which are dependant on the level of cover and optional benefits you choose and paid for.</p>','0','1401512400','Yes','0'),
 ('666','Can I also get cover for funeral expenses and personal accident under medical insurance?','<p>Yes, if you choose those benefits and pay the appropriate premiums.</p>','0','1401512400','Yes','0'),
 ('667','When does my cover commence?','<p>The cover commences upon payment of premiums, acceptance of your proposal and written confirmation to you from Jubilee Insurance Kenya.</p>','0','1401512400','Yes','0'),
 ('668','What am I covered for?','<p>A member is covered inpatient and other optional plans purchased.</p>','0','1401512400','Yes','0'),
 ('669','What are the benefits of the medical policy?','<p>These can be found on the policy documents or on the medical insurance cover details page of this website. &nbsp;At a glance, although, this varies depending on the level of cover, they are:</p>\r\n<p><em>Inpatient cover</em></p>\r\n<p>Overall limit per year &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ndash; up to a maximum KEE 5M</p>\r\n<p>Bed limit &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - up to a maximum KES 12,500</p>\r\n<p><em><br />Outpatient cover</em></p>\r\n<p>Overall limit per year &nbsp;&nbsp;&nbsp;&nbsp; &ndash; up to a maximum KES 100,000</p>\r\n<p>Maternity benefits &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Normal Delivery: up to a maximum KES 80,000</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - Caesarean Section: &nbsp;up to a maximum KES 150,000</p>\r\n<p>Last expense &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &ndash; up to a maximum KES 100,000</p>\r\n<p>Personal Accident &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &ndash; up to a maximum KES 500,000</p>\r\n<p>in addition</p>\r\n<p><strong>Main Products:</strong></p>\r\n<p>Inpatient cover</p>\r\n<p><strong>Optional Benefits</strong></p>\r\n<ul>\r\n<li>Outpatient cover</li>\r\n<li>Maternity cover</li>\r\n<li>Last expense cover</li>\r\n<li>Personal Accident Benefit Cover</li>\r\n</ul>\r\n<p><strong>Enhanced Benefits </strong></p>\r\n<p>We offer enhanced cover options that cater for the following conditions after the first policy period</p>\r\n<ul>\r\n<li>Cover for Pre-existing and Chronic conditions</li>\r\n<li>Cover for HIV/AIDS and ARV therapy</li>\r\n</ul>\r\n<p><em>NB. Members above 50 years will be required to undergo a medical  examination at specific providers before membership and eligibility of  cover can be confirmed. Please note that this will be at the applicant\'s  cost</em></p>\r\n<strong>Maternity Cover</strong>\r\n<p>This caters for:</p>\r\n<ul>\r\n<li>Normal delivery including professional fees, labour and recovery  wards up to Kshs. 80,000 under the Royal plan and Kshs. 60,000 under the  Executive Plan</li>\r\n</ul>\r\n<ul>\r\n<li>Costs of delivery, and other related ailments and complications including ectopic pregnancies, miscarriage, etc</li>\r\n</ul>\r\n<ul>\r\n<li>Caesarean section delivery including professional fees, labour and  recovery wards up to Kshs. 150,000 under the Royal plan and Kshs.  120,000 under the Executive Plan</li>\r\n</ul>\r\n<p><em>Please note maternity has a 12 months waiting period of continued coverage with Jubilee</em></p>\r\n<p><strong>Personal Accident Benefit</strong></p>\r\nThe benefit of Kshs. 500,000 is payable to the nominated beneficiary in the event of accidental death to the Employee','0','1401512400','Yes','0'),
 ('670','I have pre-existing medical conditions, am I still able to get medical insurance?','<p>Yes you can but you will be subject to other conditions e.g. a waiting period.</p>','0','1401512400','Yes','0'),
 ('671','What is a waiting period?','<p>What that means is that you can only claim on any pre-existing medical conditions only after a waiting for a period of time e.g. 2 or 3 years. &nbsp;So for examples:</p>\r\n<p>Treatment for Fibroids &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; - available in year 3: paid in full</p>\r\n<p>Cancer treatment &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; - available in year 3: maximum claim KES 300,000</p>\r\n<p>Any other pre-existing and chronic illness &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; - available in year 2: maximum claim KES 300,000</p>\r\n<p>HIV/AIDS treatment &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; - available in year 2: maximum claim KES 300,000</p>\r\n<p>Organ transplants &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - &nbsp;available in year 2: maximum claim KES 300,000&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>\r\n<br />','0','1401512400','Yes','0'),
 ('672','Which hospitals can I go to?','<p>You can access medical care within the appointed panel of providers. You will get a list after you purchase the policy and are accepted by the Insurer.</p>','0','1401512400','Yes','0'),
 ('673','What do I need in order to access a credit facility?','<p>Remember to always carry your medical card as means of identification.</p>','0','1401512400','Yes','0'),
 ('674','What happens when I lose my medical photo card?','&lt;!--  /* Font Definitions */ @font-face 	{font-family:Times; 	panose-1:2 0 5 0 0 0 0 0 0 0; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:3 0 0 0 1 0;} @font-face 	{font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-font-charset:78; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1791491579 18 0 131231 0;} @font-face 	{font-family:"Cambria Math"; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1107305727 0 0 415 0;} @font-face 	{font-family:Calibri; 	panose-1:2 15 5 2 2 2 4 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-520092929 1073786111 9 0 415 0;} @font-face 	{font-family:Cambria; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1073743103 0 0 415 0;}  /* Style Definitions */ p.MsoNormal, li.MsoNormal, div.MsoNormal 	{mso-style-unhide:no; 	mso-style-qformat:yes; 	mso-style-parent:""; 	margin:0cm; 	margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} .MsoChpDefault 	{mso-style-type:export-only; 	mso-default-props:yes; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} @page WordSection1 	{size:612.0pt 792.0pt; 	margin:72.0pt 90.0pt 72.0pt 90.0pt; 	mso-header-margin:36.0pt; 	mso-footer-margin:36.0pt; 	mso-paper-source:0;} div.WordSection1 	{page:WordSection1;} --&gt;\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span style="font-size: 10.0pt; font-family: Calibri; mso-bidi-font-family: &quot;Times New Roman&quot;; color: black;">Report to Jubilee Insurance Kenya and provide a passport sized photograph. A card will be replaced at a cost of KES 250 each card.</span></p>','0','1401512400','Yes','0'),
 ('675','What should I do when a pharmacist says that the prescribed drugs are not covered by Jubilee?','<p>Call the Jubilee Insurance Kenya&rsquo;s 24-hour call centre numbers.</p>','0','1401512400','Yes','0'),
 ('676','What is the procedure of enrolling a newly born child?','<p>Provide all documentation (premium, application form, passport size photo &amp; birth certificate) after the baby attains 3 months.</p>','0','1401512400','Yes','0'),
 ('677','Whom can I contact in case of an emergency?','<p>On your medical photo card, there are emergency help-line numbers that can be used in such cases. You will be assisted on what to do or who to contact. Please use these in emergencies only.</p>','0','1401512400','Yes','0'),
 ('678','What is the procedure for a scheduled admission?','<p>Once the doctor advises on an admission for a certain future date, he/she will provide you with a letter. This letter should be forwarded to Jubilee who will confirm if the procedure is covered. If so, Jubilee will provide a letter to the hospital and/or doctor authorizing for the treatment and confirming their liability. If the procedure is not covered, they will also prepare a letter advising on the decline.</p>','0','1401512400','Yes','0'),
 ('679','What happens when I am referred to a specialist?','<p>The general practitioner will provide you with a referral letter, which you will carry with you to the specialist together with a copy of your claim form.</p>\r\n<p>If referred to Jubilee Insurance Kenya&rsquo;s panel of doctors, you will only need to go to the specialist and receive treatment.</p>\r\n<p>If referred to a non-panel doctor, it is advisable to contact Jubilee who will arrange for treatment on credit.</p>\r\n<p>Alternatively, you can opt to pay and seek reimbursement.</p>','0','1401512400','Yes','0'),
 ('680','What happens when my medical benefit is exhausted?','<p>You will be required to stop using your medical photo-card for the benefit that is exhausted. Any excesses will be paid through Jubilee Insurance Kenya&rsquo;s Human Resource office, who will guide you on amounts in excess. In addition, members will be provided with quarterly statements showing usage on an on going basis to demonstrate utilization. This is in order to avoid excess above the limit.</p>','0','1401512400','Yes','0'),
 ('681','What should I do if the provider says they do not have my name on their database?','<p>Kindly call the emergency helpline numbers provided by Jubilee and you will be assisted</p>','0','1401512400','Yes','0'),
 ('682','What does a Personal Accident (PA) policy cover?','<p>This policy covers accidental death or disablement to the insured, more specifically:</p>\r\n<p>A &ndash; Death:</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Death as a result of an accident.</p>\r\n<p>B &ndash; Permanent Disablement:</p>\r\n<p>(i) &nbsp;Injury specified in the Permanent Disability Scale; or</p>\r\n<p>(ii) Injury not specified in the Permanent Disability Scale where the injury is not specified, the Company will adopt a percentage of disablement which is consistent with the provisions of the permanent disability scale.</p>\r\n<p>C &ndash; &nbsp;Temporary Total Disablement.</p>\r\n<p>D &ndash; &nbsp;Medical Expenses.</p>','0','1401512400','Yes','0'),
 ('683','What is Jubilee Insuranceâ€™s PA Plus?','<p>PA plus is a unique and complete personal accident protection policy. In this innovative product, a single premium based on an individual&rsquo;s age and occupation gets you a host of benefits as a result of an accident covering all members of the immediate family. Immediate family members include spouse and children.</p>','0','1401512400','Yes','0'),
 ('684','Am I covered by the Jubilee Insuranceâ€™s PA Plus policy if I take insurance from Bima247.com?','<p>Yes.</p>','0','1401512400','Yes','0'),
 ('685','What is Permanent Total Disablement?','<p>This is where an accident makes you incapable of engaging in your current or another occupation or going about your normal daily business.</p>\r\nThis disability must however be total, continuous and permanent, to be a Permanent Total Disablement.','0','1401512400','Yes','0'),
 ('686','What is Temporary Total Disablement?','<p>This is where an accident makes you incapable of engaging in your current or another occupation or going about your normal daily business.</p>\r\nThis disability is only for a period of time and does not have to be total, continuous and permanent.','0','1401512400','Yes','0'),
 ('687','What is the Permanent Disability Scale?','<p>This is a scale insurance companies use to determine the amount you will be paid if you are disabled as part of an accident. You will find this in the policy documentations.</p>','0','1401512400','Yes','0'),
 ('688','What happens if I am left-handed?','<p>Then the percentages set in the Permanent Disability Scale for the various disabilities of right hand and left hand will be transposed. &nbsp;So for Loss of or the Permanent Total loss of use of one limb: you will be get 100% &nbsp;(if the loss is of your left hand which is your main hand) and 60% if the loss is of your right hand (now considered as the &lsquo;left hand&rsquo;).</p>','0','1401512400','Yes','0'),
 ('689','Will I be subjected to a medical as part of the claims process?','<p>Yes, you will be sent to see a doctor</p>','0','1401512400','Yes','0'),
 ('690','Who will be paid in case of a claim?','<p>The insurance company will pay either you &ndash; if you are the insured &ndash; or the insured&rsquo;s legal representative.</p>','0','1401512400','Yes','0'),
 ('691','Will I be subjected to a medical as part of the claims process?','<p>Yes, you will be sent to see a doctor</p>','0','1401512400','Yes','0'),
 ('692','Who will be paid in case of a claim?','<p>The insurance company will pay either you &ndash; if you are the insured &ndash; or the insured&rsquo;s legal representative.</p>','0','1401512400','Yes','0'),
 ('693','How much will be paid out?','<p>Payment or any compensation will be in accordance with the sum insured, and scale of compensation agreed and specified in the policy schedule. Please read that carefully.</p>','0','1401512400','Yes','0'),
 ('694','How long will get paid for if I am claiming under Total Permanent Disablement?','<p>Typically under Personal Accident cover, payment shall not be made for more than 104 weeks from the date of the accident.</p>','0','1401512400','Yes','0'),
 ('695','Is there a maximum period after which a claim cannot be made on this policy?','<p>Yes, typically for death and disablement the claim has to be made within 12 calendar months of the accident.</p>','0','1401512400','Yes','0'),
 ('696','Who is eligible for Jubilee Insurance Kenyaâ€™s PA Plus policy?','<p>Anyone, as long as you are accepted by Jubilee plus members your immediate family.</p>','0','1401512400','Yes','0'),
 ('697','Is there are lower and upper age limit when it comes to the PA Plus policy?','<p>The entry age for children is 3 years. Cover continues up to age 18 unless the child is still in school or college, in which case cover continues up to age 25. The Temporary Total Disablement is excluded for this category of persons.</p>\r\n<p>The maximum entry age for adults is 59 with an upper age limit of 70 years.</p>','0','1401512400','Yes','0'),
 ('698','Can the policy also be available to groups?','<p>Yes, to individuals, schools and organised groups on reasonable premium terms. Just get in touch through our contact pages and we will be happy to discuss this with you.</p>','0','1401512400','Yes','0'),
 ('699','Can the policy also be available to groups?','<p>Yes, to individuals, schools and organised groups on reasonable premium terms. Just get in touch through our contact pages and we will be happy to discuss this with you.</p>','0','1401512400','Yes','0'),
 ('700','What are the key benefits of Jubilee Insurance Kenyaâ€™s PA Plus Policy?','<ol>\r\n<li>Accidental      death benefits payment to your beneficiaries.</li>\r\n<li>Accidental      permanent disablement benefit, which will be up to 150% of the sum insured      under death benefit following paralysation or permanent confinement to a      wheelchair as a result of the accident.</li>\r\n<li>Cost      of artificial appliances prescribed by a medical practitioner following an      accident.</li>\r\n<li>Medical      expenses up to the policy limits. </li>\r\n<li>Hospital      cash &ndash; payable to the insured person if hospitalization is for more than      two days.</li>\r\n<li>Funeral      expenses.</li>\r\n<li>Repatriation      expenses if the insured individual is injured outside the country.</li>\r\n<li>Flying      doctors cover.</li>\r\n</ol>','0','1401512400','Yes','0'),
 ('701','What are Jubilee Insurance Kenyaâ€™s PA Plus advantages?','<ol>\r\n<li>Comprehensive      accidental insurance under one policy for peace of mind.</li>\r\n<li>Single      discounted premium for a variety of benefits.</li>\r\n<li>Single      policy covering all members of the immediate family</li>\r\n<li>Flying      doctors cover which includes sickness evacuation in addition to accidental injuries </li>\r\n<li>Funeral      expenses are payable within 48 hours on production of documentary evidence      such a death notification and burial permit.</li>\r\n<li>Children      are covered.</li>\r\n<li>Caters for international repatriation.</li>\r\n<li>No medical examination required. </li>\r\n</ol> <ol></ol>','0','1401512400','Yes','0'),
 ('702','What are the general exclusions and exceptions under a Personal Accident policy?','<p>These are various and range from self inflicted ones like criminal acts, duels, suicide, and self-injury to ones that relate to pre-existing medical conditions to risks that you expose yourself to e.g. hazardous sports or pastimes. Please make sure you have read the full list on the policy document carefully.</p>','0','1401512400','Yes','0'),
 ('703','Why do I need Travel Insurance?','<p>When travelling abroad and something happens to you, the cost of hospitalisation or repatriation back home can be very expensive. Having a Travel Insurance policy removes the need for harambees and/or having to sell your prized possessions so that you can fund the costs of any travel or medical-related emergencies whilst you&rsquo;re outside Kenya.</p>','0','1401512400','Yes','0'),
 ('704','Does the policy cover medical bills?','<p>Yes, most travel insurance plans will cover medical bills as well as pay for repatriation to bring you home for treatment in Kenya. &nbsp;</p>','0','1401512400','Yes','0'),
 ('705','What are the other benefits of travel insurance?','<p>Travel insurance can also cover you against other mishaps while you\'re abroad, from lost luggage and theft to flight delays. &nbsp;You must make sure you get the right cover though as it is not &nbsp;a given these will be covered in a typical travel insurance policy.</p>','0','1401512400','Yes','0'),
 ('706','What does â€˜plug and playâ€™ mean?','<p>It means that integration of our system to your existing website or system is fairly straightforward and fast. Indeed our system is specifically developed for the Kenyan insurance market, which makes it fit in quite easily within all the core insurance systems in the market.</p>','0','1401512400','Yes','0'),
 ('707','How come you decided on this innovative pricing?','<p>We believe in long-terms relationships where the partners not only face the challenges of new ICT developments but also reap the benefits of success. So instead of charging a lump sum upfront and disappearing after installation as some system providers might do, we will stay with you for the long-term, helping you grow your revenues through innovative online distribution methods especially since your business doing well is also inextricably tied to our own business success. &nbsp;</p>','0','1401512400','Yes','0'),
 ('708','What is the rationale for the pricing?','<p>We have a deep commitment to increasing insurance penetration in the market, which most commentators agree is woefully low. This is a commitment that stems from the fact that we see insurance as a public good with the more people having cover the better it is for society. It therefore follows that the more we can do to encourage wider acceptance and usage of our insurance gateway system through a reasonably priced innovative solution the higher the penetration levels: something that can only benefit society. Of course we are a business too, and our pricing is carefully set at a level that helps us achieve this commitment and at the same time ensures our investors and shareholders get an increased value and return on their investments with us.</p>','0','1401512400','Yes','0'),
 ('709','How fast can this system be up and running for us once we sign up?','<p>We would need to have a more specific understanding of your system requirements to get a good idea of this. Typically once that is done it would be around 6-7 weeks. This is because the questions on the proposal forms and attendant ratings &ndash; which we have based our algorithms &ndash; are standard industry ones that are the same as what you have in your current paper-based proposal forms. We would only need to change the rates if necessary. As well as that we will change to your logo and company colours as part of the integration and test the system before you go live.</p>','0','1401512400','Yes','0'),
 ('710','What will integration involve?','<p>There are a number of options here to suit, which we are of course happy to discuss with you. Most of these options are quite easy and straightforward. The main thing is that you once integration is complete, you will be selling your insurance products and services online under your own brand and company name. So</p>\r\n<p>if you are known as Insurance Co Kenya Limited with the website<a href="http://www.insurancecokenya.co.ke/"> http://www.insurancecokenya.co.ke</a> then your customers are going to buy Insurance Co Kenya Limited&rsquo;s insurance products online through that website.</p>\r\n<p>if you are known as Insurance Broker or Agent Kenya Limited with the website<a href="http://www.insurancebrokeroragentkenya.co.ke/"> http://www.insurancebrokeroragentkenya.co.ke</a> then your customers are going to buy Insurance Broker or Agent Co Kenya Limited&rsquo;s insurance products online through that website.</p>\r\n<p>If you are known as Bank or Bancassurance Kenya Limited with the website<a href="http://www.bankorbancassurance.co.ke/"> http://www.bankorbancassurance.co.ke</a> then your customers are going to buy Bank or Bancasssurance Kenya Limited&rsquo;s insurance products online through that website.</p>','0','1401512400','Yes','0'),
 ('711','What about our existing policyholders, can they be migrated to this system?','<p>Yes, that can be done quite easily as the system is designed with this in mind.</p>','0','1401512400','Yes','0'),
 ('712','What if we need to change the rates ourselves?','<p>You will have full dashboard and panel controls. What this means is that you can change anything you wish. So if you decide to lower or increase the rates than that can be done quite easily. And we will always be there to guide and provide support.</p>','0','1401512400','Yes','0'),
 ('713','Can we control the types of risks coming through the system?','<p>Yes, you can, so for example, if you decide to alter the types of property you want to insure or exclude property in certain areas or vehicles beyond a certain age then that&rsquo;s easily done through the control panel and backend. In fact this is a key feature of the system, i.e., the ability to control the risk profiles of risks coming into your books either globally or locally.</p>','0','1401512400','Yes','0'),
 ('714','How can we use this system to control our underwriting risks globally and or locally?','<p>You can control the risks globally by, for example, simply going to the control panel and backend, and changing the rate there, so that thereafter any proposer who completes the online proposal form is charged a premium at the new rate.</p>\r\n<p>You can also do this locally for individual risks by setting certain specific parameters: so, for example, you can set it such that your underwriting team is informed of any proposer with a pre-determined medical condition by an automatically triggered email. They can then make a decision on whether or not to accept or decline the risk. &nbsp;In the meantime the proposer is also emailed at the end of the quote process to tell them that they will be informed on whether or not their proposal is acceptable.</p>\r\n<p>Quite importantly, there are no limitations to how many of these parameters you can set. This is another key feature of our system, which makes it quite flexible and responsive to your individual underwriting needs as a company.</p>','0','1401512400','Yes','0'),
 ('715','Can we run statistics like loss ratios?','<p>Yes, as the system populates, you will be able to run a number of statistics to gain better insights into your consumers and their risk profiles whether these are global risk profiles or for specific individuals.</p>','0','1401512400','Yes','0'),
 ('716','What about general management information, can we get that?','<p>Yes, you will be able to know almost at a glance quite a lot of information about your insureds and the risks. We will, of course, be happy to configure the system to give you regular automated management reports on any aspect of your products and insureds.</p>','0','1401512400','Yes','0'),
 ('717','Can we send renewal notices through the system?','<p>Yes, you can do that via email or text to remind the insured closer to the renewal date.</p>','0','1401512400','Yes','0'),
 ('718','Does training our staff come as part of the package?','<p>Yes it does. We will also always be there to provide any additional support as needed.</p>','0','1401512400','Yes','0'),
 ('719','Are there any hidden charges?','<p>No. We take pride in our openness and transparency. What you see here on this site is what you get. Our pricing structure is clearly laid out elsewhere on this website: and, you can see the system work, again on this website.</p>\r\n<p>Also, with our innovative pricing you can rest assured that we will work hard to ensure the success of our relationship since we only benefit if it succeeds in the long-term. We feel it is important to be upfront about this from the very start.</p>','0','1401512400','Yes','0'),
 ('720','What if we want to add any more products?','<p>We will be glad to work with you to do that as our business model is based on more and better products&rsquo; developments.</p>','0','1401512400','Yes','0'),
 ('721','Is adding new products a complex process?','<p>Not once you are on board with us. The system is built in what we call a modular block form, so it will just be a matter of adding new blocks (i.e. other products) to what you already have.</p>','0','1401512400','Yes','0'),
 ('722','What if we want it to be mobile friendly and with apps available?','<p>That is something we can also do. Just let us know your requirements and we will be happy to discuss this.</p>','0','1401512400','Yes','0'),
 ('723','In what ways are you different from other systems providers?','<p>There are many differences including the fact that we not only have some of the best ICT experts around but also an unrivalled in-depth understanding of underwriting and broking as well as this insurance industry. Perhaps, and more importantly, our core competences in insurance and technology means you can continue to do other things related to your core competencies and leave us do what we know and do very well.</p>\r\n<p>All the above have enable us develop a system that makes a complex underwriting process deceptively simple and an extremely user friendly for your customers.</p>\r\n<p>In addition, our key people have been in the industry for many years and plan to still be involved in insurance for many more to come. What this means for you is that our relationship will be a long-term one. You are unlikely to get this combination with other system providers.</p>','0','1401512400','Yes','0'),
 ('724','What insurance experience and expertise base is behind the system?','<p>Our key people have a collective underwriting and broking experience that spans over several decades both in the Kenyan and London Insurance markets, and all of these in blue chip companies. We also have some of the best technology brains in the industry as well as a customer service contact centre that provides unrivalled service 24/7 every day all year round.</p>','0','1401512400','Yes','0'),
 ('725','In what other ways can we use this system as a distribution channel?','<p>There are many other ways to take advantage of this system. These include, for example, having self-service facilities for clients in your customer service, sales or reception areas with several touchscreen and or other computer equipment.</p>','0','1401512400','Yes','0'),
 ('726','Will we get to keep our commissions from the insurance company?','<p>Yes, you get to keep your all commissions in full. As indicated elsewhere in our pricing structure, we only charge a small fee per transaction.</p>','0','1401512400','Yes','0'),
 ('727','What if we donâ€™t have a website?','<p>We can develop one for you and even host it for a small extra separate charge. &nbsp;Just talk to us as it is in our business interests to get you online.</p>','0','1401512400','Yes','0'),
 ('728','Will we get to keep our commissions or any payments for introducing the business from the insurance company?','<p>Yes, you get to keep your all commissions or any payments for introducing the business in full. As indicated elsewhere in our pricing structure, we only charge a small fee per transaction.</p>','0','1401512400','Yes','0'),
 ('729','What would a partnership with you mean?','<p>It means a way for you to generate extra revenue through your website and or online social media activities in a subtle and intelligent way by simply having our banners or links, and doing nothing else.</p>','0','1401512400','Yes','0'),
 ('730','How will it work?','Once you sign up we will either allow you to download our software codes or send you the relevant codes and or banner links to our site. Thereafter, every time someone comes to us through your site or online links, and buys insurance that is accepted by Jubilee Insurance Kenya then you get a share of the agency commission revenue we normally get as insurance agents. It is that simple, you do nothing else and just wait for your online presence to earn you extra revenues.','0','1401512400','Yes','0'),
 ('731','How much do I earn?','<p>The more qualified leads that come through to us the more you earn. For the most current details of potential earnings, please see the relevant &lsquo;work with us&rsquo; partnership page?</p>','0','1401512400','Yes','0'),
 ('732','Does that mean I am also an insurance agent?','<p>No, you are not an insurance agent and our partnership does not allow you to act as such.</p>\r\n<p><span style="color: #ff6600;"> IMPORTANT: please note that embedding, including or adding our codes, links, banners or using any other means that may ultimately lead traffic to our website from your own online property and or presence does not transfer any rights onto you to act as an insurance agent.</span></p>\r\n<p><span style="color: #ff6600;">Therefore, you MUST ABSOLUTELY NOT ENGAGE in any selling of or any advise of insurance to any member of the public or other entities. To do this, the law requires that you MUST be registered by the Insurance Regulatory Authorities in your market.</span></p>','0','1401512400','Yes','0'),
 ('733','What happens if I sell or try to sell the insurance products and or offer any advice on insurance related matters?','<p>Two things will happen:</p>\r\n<ol>\r\n<li>As      part of your overall agreement with us, you are agreeing not to do this      and any contravention will automatically make this agreement null and      void.</li>\r\n<li>In      addition, your details with us shall immediately be sent to the relevant      authorities. They will then deal with you to the fullest extent of the law      and that may include prosecution and criminal charges.</li>\r\n</ol>','0','1401512400','Yes','0'),
 ('734','So what is the exact nature of my relationship with your company?','<p>You are simply acting as a publisher of our codes on the Internet and or as our online brand ambassadors. We will provide you with software codes, website content and interactive banners that are aimed at drawing the attention of your online visitors to our site and products.</p>','0','1401512400','Yes','0'),
 ('735','As a publisher or brand ambassador or affiliate, am I responsible for the policyholdersâ€™ policy documentations, claims or any support once they come through my site or social media account and or links?','<p>No, once again, you will be in contravention of the laws if you do that and thus liable for legal action to the fullest extent of the law by the relevant authorities.</p>','0','1401512400','Yes','0'),
 ('736','So who then is responsible for that i.e. policyholdersâ€™ policy documentations, claims or any other support including any future changes to the policy details?','<p>That will be between us &ndash; since we are registered and regulated to do this &ndash; and the insurance company, which is one of the best reputed in this region, and multiple award winner when it comes to claims payment and customer service. &nbsp;We also have a 24/7 all year round online live help support from our state of art contact centre. Your role is to simply publish our software codes and wait for any earnings from proposals accepted by Jubilee Insurance Company Kenya.</p>','0','1401512400','Yes','0'),
 ('737','How can I be sure that my qualifying leads are converted into earnings?','<p>We will give you full control and sight of the movements from when someone visits your site to their movements through ours. So you will be able to see how far your leads have got and if anyone purchased cover that was approved by Jubilee Insurance Kenya.</p>','0','1401512400','Yes','0'),
 ('738','How will I get paid?','<p>After you register and sign our agreement, you will also be able to let us know your preferred method of payment.</p>','0','1401512400','Yes','0'),
 ('739','What happens when someone who comes through my website and or social media account cancels their policy (ies)?','<p>Typically commissions are reversed by the underwriter once when this happens, so unfortunately you will have to refund your part of the commissions paid.</p>','0','1401512400','Yes','0'),
 ('740','Will you have any more products than what is currently on offer?','<p>Yes, we are working on more products. We are though happy to hear from you in case you have something in mind.</p>','0','1401512400','Yes','0'),
 ('741','Can your software codes or products and or services be on any website and or social media account?','No, we are exceedingly keen to maintain not only our brand and reputation but also that of the insurance company that&rsquo;s appointed us as their representative. Therefore an integral part of our agreement with you is that your website and or any activities &ndash; whether online or offline &ndash; must be beyond reproach in terms of any matter(s) that may impact on all our reputations including but not limited to legal, moral and general or public decency matters. In addition when acting as a publisher of our software codes and or brand ambassador you must absolutely always act in a manner that at the very least befits the long established reputation and brand equity of the insurer that&rsquo;s appointed us as their representative, who are one of the leading brands in this region. Both the insurer and us do fully reserve the right to take legal action to the fullest possible extent if your actions are detrimental to our brand reputation and or are likely to injure our reputations and good standing.','0','1401512400','Yes','0'),
 ('742','Can I change your banners or make our own banners with your logo?','<p>No, all banners or visual aids must conform to our standards. We will though provide you with a range of visually pleasing banners and or software codes to suit most sizes and messages. Let us know if you want a specific format and/or wordings and we will do our best to try accommodate you.</p>','0','1401512400','Yes','0'),
 ('743','Can I alter your software codes?','<p>No, any unauthorised alterations will be deemed a contravention of our Agreement. You may also be considered to have been in breach of our intellectual property rights and we won&rsquo;t hesitate to take action to the fullest possible legal extent.</p>','0','1401512400','Yes','0'),
 ('744','Accidentâ€¨','<p>A sudden, violent and unexpected visible external event occurring during the period the policy is in force and resulting in death of or bodily injury to the Insured.</p>','0','1401598800','Yes','0'),
 ('745','Agent','<p>Insurance companies sell their services and products in a number of ways including directly and through intermediaries i.e. agents or brokers. As technology advances new innovative channels like Bima247.com will play an important role in how insurance is sold and bought by companies and consumers in the Kenyan insurance market. &nbsp;</p>\r\n<p>Agents must have names that reflect the kind of business done, so for example, XYZ insurance agents/agencies/agency. They must also be Kenyan citizens who are competent and professionally qualified. In addition they must be registered and regulated by the Kenya Insurance Regulatory Authority.</p>','0','1401598800','Yes','0'),
 ('746','Alteration','<p>The Insured shall notify to the Company in writing any material changes affecting the Insured property. All the benefits under the Policy shall be forfeited if the risk of loss or damage is increased unless such alteration is admitted to the Company and its written consent to continue the insurance be obtained.</p>\r\n<p>What this means is the insurance company must be informed of any significant changes to your property (ies) or the insured item (s). Failure to do this may mean that you don&rsquo;t get your claim paid. And you will lose the premium already paid. It is always best to inform the insurance company even if you aren&rsquo;t sure whether or not the change is significant.</p>','0','1401598800','Yes','0'),
 ('747','Average Condition','<p>The average condition is the penalty for underinsuring your vehicle or property. So for example if your car&rsquo;s current market value is KES 400,000 and you insure it for KES 250,000 and you later suffer a loss of KES 80,000. The claim amount paid to you will be as follows:</p>\r\n<p><span style="text-decoration: underline;">80,000(loss value) x 250,000(insured value) </span>= 50,000 (amount you will get paid)<br />400,000(current market value)</p>\r\n<p>So, you will only get paid KES 50,000 and NOT the full amount of the claim i.e. KES 80,000. &nbsp;The difference (KES 30,000) will be borne by you.</p>\r\n<p>See also Underinsurance</p>','0','1401598800','Yes','0'),
 ('748','Bancassurers','In recent years banks and other finance institutions have entered the market as insurance products distributors with a number of Kenyan institutions already licensed as Bancassurers.','0','1401598800','Yes','0'),
 ('749','Broker','&lt;!--  /* Font Definitions */ @font-face 	{font-family:Times; 	panose-1:2 0 5 0 0 0 0 0 0 0; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:3 0 0 0 1 0;} @font-face 	{font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-font-charset:78; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1791491579 18 0 131231 0;} @font-face 	{font-family:"Cambria Math"; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1107305727 0 0 415 0;} @font-face 	{font-family:Calibri; 	panose-1:2 15 5 2 2 2 4 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-520092929 1073786111 9 0 415 0;} @font-face 	{font-family:Cambria; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1073743103 0 0 415 0;}  /* Style Definitions */ p.MsoNormal, li.MsoNormal, div.MsoNormal 	{mso-style-unhide:no; 	mso-style-qformat:yes; 	mso-style-parent:""; 	margin:0cm; 	margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} .MsoChpDefault 	{mso-style-type:export-only; 	mso-default-props:yes; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} @page WordSection1 	{size:612.0pt 792.0pt; 	margin:72.0pt 90.0pt 72.0pt 90.0pt; 	mso-header-margin:36.0pt; 	mso-footer-margin:36.0pt; 	mso-paper-source:0;} div.WordSection1 	{page:WordSection1;} --&gt;\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span style="font-size: 10.0pt; font-family: Calibri; mso-bidi-font-family: &quot;Times New Roman&quot;; color: black;">Similar to agents and other intermediaries, brokers are licensed, regulated and authorised to advise you and place your business with insurance companies. Brokers are required by law to employ technically qualified personnel to run the operations of the company and incorporated under the Companies Act with at least 60 per cent Kenyan shareholding. <br /></span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span style="font-size: 10.0pt; font-family: Calibri; mso-bidi-font-family: &quot;Times New Roman&quot;; color: black;">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span style="font-size: 10.0pt; font-family: Calibri; mso-bidi-font-family: &quot;Times New Roman&quot;; color: black;">See also Agent.</span></p>','0','1401598800','Yes','0'),
 ('750','Buildings','<p>The residential premises including but not limited to landlords fixtures and fittings and the following, in so far as they form part of the property; walls, gates, fences, terraces, patios, drives, paths, carports, garages and outbuildings.</p>','0','1401598800','Yes','0'),
 ('751','Building insurance','<p>This insurance pays the cost of repairing or rebuilding your home if it is damaged by unforeseen events (look at the details in your insurance policy and also make sure you have understood the exceptions and conclusions).</p>','0','1401598800','Yes','0'),
 ('752','Business Use','<p>This option covers the car in connection with your job, such as driving to different sites away from your place of work.</p>','0','1401598800','Yes','0'),
 ('753','Certificate of Insurance','<p>Once your car insurance is paid, you should receive an insurance sticker as proof of insurance and one that is valid for the duration of the policy. When you cancel the policy, this certificate must be returned to your insurance company.</p>','0','1401598800','Yes','0'),
 ('754','Claims Co-operation Clause','<p>In the event of a claim the Insured shall permit authorized representatives or agents of the Company to examine the premises and shall furnish evidence to the Company to substantiate the claim made including invoices and other proof of value and ownership respectively having regard to the value of each item at the time of loss or damage and not including profit of any kind. The Insured shall not abandon or expose the property to any further damage or loss.</p>\r\n<p>What this means is that you must provide the insurance company with any and all the evidence needed to help substantiate the claim. &nbsp;</p>\r\n<p>You must also not do anything that makes the damage and or loss worse.</p>','0','1401598800','Yes','0'),
 ('755','Commercial Travelling','This covers the car to be used for travelling in the course of work: if, for example, you are a salesperson and travel between Mombasa and Nakuru regularly.','0','1401598800','Yes','0'),
 ('756','Commuting','<p>This option covers you to drive back and forth to a permanent place of work, for example, driving to and out of the Westlands from your home.</p>','0','1401598800','Yes','0'),
 ('757','Comprehensive','<p>Motor insurance that covers accidental damage to the policyholder&rsquo;s car in addition to what is covered by Third Party Fire and Theft (TPF&amp;T) cover.</p>\r\n<p>See also Third Party and Cover Types</p>','0','1401598800','Yes','0'),
 ('758','Compulsory excess','<p>See Excess</p>','0','1401598800','Yes','0'),
 ('759','Contents','<p>This refers to household goods, personal effects, including but not limited to valuables, furniture, fixtures and fittings (including interior decorations) all belonging to the Insured or a member of the household.</p>','0','1401598800','Yes','0'),
 ('760','Contents insurance','<p>This covers the cost of replacing possessions lost or damaged due to unforeseen events (as detailed in the insurance policy).</p>','0','1401598800','Yes','0'),
 ('761','Contribution','<p>If at the time any claim arises under the Policy, there are any other insurance (s) covering the same risk against loss or damage, the insurance company shall not be liable for more than its proportionate share of such a claim.</p>\r\n<p>What this means is that if you have a loss of KES 100,000 and the insurance for the risk(s) is provided by two different insurance companies, say company X and company Y, then the claim payment made to you will be proportionately divided between both insurance companies. In other words none of the companies will pay you the full KES 100,000: &nbsp;so, for example, you may have to claim KES 40,000 from insurance company X and KES 60,000 from insurance company Y.</p>','0','1401598800','Yes','0'),
 ('762','Cover','<p>The protection given by an insurance policy.</p>','0','1401598800','Yes','0'),
 ('763','Cover note','<p>A temporary certificate of insurance issued if you have not yet paid (but have promised to pay) or when you have been given temporary extensions of cover.</p>','0','1401598800','Yes','0'),
 ('764','Cover types','<p>Remember: it is important that you buy the right cover for your car. If you buy the wrong cover, your insurance company might not pay out on a claim. The following are the three most common types of car insurance in Kenya.</p>\r\n<p>Third Party Only (TPO): Motor insurance that only covers claims by third parties. It does not cover damage and or losses to the policyholder&rsquo;s own car or their property.</p>\r\n<p>Third Party Fire and Theft (TPFT): Motor insurance that covers fire and theft of the driver\'s car in addition to TPO cover. &nbsp;It does not cover damage and or losses to the policyholder&rsquo;s own car or their property.</p>\r\nComprehensive (Comp): Motor insurance that covers accidental damage to the policyholder&rsquo;s car in addition to what is covered by the Third Party Fire and Theft (TPF&amp;T) cover.','0','1401598800','Yes','0'),
 ('765','Current Value','<p>The new replacement cost of equipment, less allowance for depreciation taking into account its age.</p>','0','1401598800','Yes','0'),
 ('766','Deathâ€¨','The death of the insured occurring within 12 calendar months resulting directly and independently of any other cause from an accident. In some policies, this period may be shorter.','0','1401598800','Yes','0'),
 ('767','Domestic Staff','<p>A person employed by the insured to carry out domestic duties associated with the residential premises.</p>','0','1401598800','Yes','0'),
 ('768','Driver ','<p>See main driver</p>','0','1401598800','Yes','0'),
 ('769','Estimated Value','<p>This is what you should put on the proposal form as the value of your property.</p>\r\n<p>See also Market Value.</p>','0','1401598800','Yes','0'),
 ('770','Excess','<p>Excess is the amount of money that you pay in the event of a claim: for example, the first KES 50,000 of any claim.</p>\r\n<p>What this means is that, for instance, if your property is insured for KES 250,000 with an excess amount of KES 50,000, then you will have to bear the KES 50,000 of any claim payable to you for loss or damage (s) to the insured item. In this case the insurance company will only pay you KES 200,000 if you have had a total loss of KES 250,000. You will have to bear the remaining KES 50,000 out of your own pockets.</p>\r\n<p>The excess can sometimes be a percentage of the sum insured.</p>\r\n<p>It also varies with, for example, young drivers typically being asked to bear higher excess amounts.</p>\r\n<p>This excess is at times termed &lsquo;Compulsory Excess&rsquo; in some insurance markets rather than &lsquo;Voluntary Excess&rsquo; with the latter referring to an amount you decide to pay yourself if there is an accident. If you do opt for Voluntary Excess then you will pay the &lsquo;Compulsory Excess&rsquo; plus &lsquo;Voluntary Excess&rsquo;.</p>','0','1401598800','Yes','0'),
 ('771','Exclusions ','<p>See Exceptions</p>','0','1401598800','Yes','0'),
 ('772','Exceptions','<p>These are things that that your insurance policy will not cover. Your building insurance will, for instance, not cover accidental damage to contents and personal belongings unless specifically asked and paid for separately by you.</p>\r\n<p>All policy documents have a section listing all the exclusions and exceptions. It is important that you read that carefully because</p>\r\nIn some policy documents, the term sometimes used is &lsquo;Exclusions&rsquo;.','0','1401598800','Yes','0'),
 ('773','Fault/non-fault Claim','<p>The terms fault and non-fault can be confusing. A non-fault claim is simply a claim where the insurer is able to recover all their costs from someone else. If they are not able to recover all their costs, then it is a fault claim - even if the insured party didn\'t cause the claim to happen. For example, a theft is typically classed as a fault claim. This is because even though the driver is not to blame for the theft, there is no third party to pay the costs so the insurance company treats it as a fault claim.</p>','0','1401598800','Yes','0'),
 ('774','Foreign Travel','<p>See Yellow Card.</p>','0','1401598800','Yes','0'),
 ('775','Fraudulent Claims','<p>If you as the policyholder and or your representative (s) makes a claim knowing the same to be fraudulent, the claim shall be not be payable.</p>\r\n<p>What this also means is that if you, or anyone acting on your behalf, makes a claim which is in any way false or fraudulent, or supports a claim with any false or fraudulent statement or documents, including inflating or exaggerating a claim, you will lose all benefit and premiums you have paid for the policy. The insurance company may also recover any sums that have already been paid under the policy. In addition, they may then refer them to the relevant law enforcement authorities.</p>','0','1401598800','Yes','0'),
 ('776','Hazard','<p>In terms of insurance, hazards are the criteria that are likely to affect any loss, damage or injury. For example, a high performance BMW is a greater hazard than a Nissan Sunny family car and a young driver is a greater hazard than an experienced one. Generally, the greater the hazard, the higher the premium or excess is likely to be.</p>','0','1401598800','Yes','0'),
 ('777','Home','<p>The private dwelling used for domestic purposes only, all at the situation of premises shown in the schedule.</p>','0','1401598800','Yes','0'),
 ('778','Immobiliser','<p>An immobiliser is an electronic device that stops a car from being started until it is deactivated. Although this won\'t stop the car from being broken into, it may well stop it from being driven away. Some cars come with an alarm and/or immobiliser but with others you might need to buy one yourself.</p>','0','1401598800','Yes','0'),
 ('779','Indemnity','<p>This is the main principle of insurance. Insurance is there to replace something that has been lost or damaged, and an indemnity seeks to restore the insured person to the same financial position after the loss as immediately before.</p>\r\n<p>Indemnity is therefore where your insurer places you back in the same financial position as you were immediately before the loss. This means that you cannot profit or lose from your insurance policy.</p>','0','1401598800','Yes','0'),
 ('780','Indemnity Period','<p>The period starting when the insured loss or breakdown occurs and ending not later than the time specified in the Policy.</p>','0','1401598800','Yes','0'),
 ('781','Injury','<p>Bodily injury that is suffered by an Insured person during the period of this policy and caused by an Accident.</p>','0','1401598800','Yes','0'),
 ('782','Insurable Interest','<p>A person is regarded as having an insurable interest in something when the loss or damage to the item concerned (the insured item) would cause that person to suffer a financial loss and/or other kinds of loss. Most insured have, or should have, insurable interest in any of their insured property. However in some cases they are not the only ones. For example, a bank may have an insurable interest in a vehicle or a building if they helped pay for the purchase. &nbsp;What this means is that you must stand to suffer a loss if what you are insuring is lost or damaged. So for example, if you own a car and it is stolen then you have insurable interest in the car, and can not only legally purchase insurance but also claim under your policy. You cannot insure your neighbours or relative&rsquo;s car and claim after a loss or damage. Insurance interest is therefore the connection between you &ndash; the person purchasing the insurance &ndash; and what is being insured. That connection may sometimes not be just about property. This can happen with life insurance where the person purchasing it may suffer a loss, which can be either emotional or financial when the person who is insured passes on.</p>','0','1401598800','Yes','0'),
 ('783','Insurance Intermediary','<p>See Broker</p>','0','1401598800','Yes','0'),
 ('784','Insurance Regulatory Authority','<p>Insurance, broking and intermediaries\' firms in the Kenyan insurance market are licensed and regulated by the Kenyan Insurance Regulatory Authority (IRA) to ensure that they not only meet certain standards but also that the Kenyan public is properly protected. You can check that the insurer, broker or agent is authorised by visiting the regulator&rsquo;s website at ira.go.ke</p>','0','1401598800','Yes','0'),
 ('785','Interpretation Clause','<p>This Policy and the Schedule and endorsement shall be read together as one contract and any word or expression to which meaning has been attached in any part of the Policy or of the Schedule shall bear the same meaning wherever it may appear.</p>\r\n<p>So once the meaning of a word or term is established in the policy, that is what is will mean every other time it appears. Also, you cannot pick and choose parts of the document to like or agree with, you must read and accept the whole document in totality.</p>','0','1401598800','Yes','0'),
 ('786','Jurisdiction Clause','<p>The indemnity provided by this Policy shall apply only in respect of judgments that are in the first instance delivered by or obtained from a court of competent jurisdiction within the Republic of Kenya.</p>\r\nThis means that you cannot, for example, take the insurance company to a court outside Kenya.','0','1401598800','Yes','0'),
 ('787','Legal owner','<p>See Owner.</p>','0','1401598800','Yes','0'),
 ('788','Licence ','<p>Whichever way you buy your car insurance it is important that you hold a valid licence for the type (s) of vehicle (s) you are insured to drive or want insurance for. Not having this is likely to invalidate any claim you may make.</p>','0','1401598800','Yes','0'),
 ('789','Loss of Limbâ€¨','&lt;!--  /* Font Definitions */ @font-face 	{font-family:Times; 	panose-1:2 0 5 0 0 0 0 0 0 0; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:3 0 0 0 1 0;} @font-face 	{font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-font-charset:78; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1791491579 18 0 131231 0;} @font-face 	{font-family:"Cambria Math"; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1107305727 0 0 415 0;} @font-face 	{font-family:Calibri; 	panose-1:2 15 5 2 2 2 4 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-520092929 1073786111 9 0 415 0;} @font-face 	{font-family:Cambria; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1073743103 0 0 415 0;}  /* Style Definitions */ p.MsoNormal, li.MsoNormal, div.MsoNormal 	{mso-style-unhide:no; 	mso-style-qformat:yes; 	mso-style-parent:""; 	margin:0cm; 	margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} .MsoChpDefault 	{mso-style-type:export-only; 	mso-default-props:yes; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} @page WordSection1 	{size:612.0pt 792.0pt; 	margin:72.0pt 90.0pt 72.0pt 90.0pt; 	mso-header-margin:36.0pt; 	mso-footer-margin:36.0pt; 	mso-paper-source:0;} div.WordSection1 	{page:WordSection1;} --&gt;\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span style="font-size: 10.0pt; font-family: Calibri; mso-bidi-font-family: &quot;Times New Roman&quot;; color: black;">Loss by severance of a hand at or above the wrist or of a foot at or above the ankle.</span></p>','0','1401598800','Yes','0'),
 ('790','Loss of use','<p>Total functional disablement and is classified as the total loss of a specified limb or organ.</p>','0','1401598800','Yes','0'),
 ('791','Main driver','<p>The main driver is the person who uses the car the most. &nbsp;Not being honest about the person who uses the car permanently can invalidate any claim. What then may happen is that your claim is not paid.</p>','0','1401598800','Yes','0'),
 ('792','Market value','<p>Your vehicle should be insured for its current market value i.e. the cost of replacing your vehicle with a similar make, model, age and condition. If the amount covered is less than the market value, then the Average Condition shall apply.</p>\r\n<p>See also &lsquo;Estimated Value&rsquo; and &lsquo;Average Condition&rsquo;.</p>','0','1401598800','Yes','0'),
 ('793','Material fact','<p>This is very important. It is any factor that might affect an insurance company&rsquo;s decision to insure you and or your risk (s) and or property (ies). You must advise your insurance company of any material facts, such as modifications to your car or any previous claims you might have made whether paid or not paid.</p>\r\n<p>If facts come to light once a claim has been made &ndash; or even before &ndash;, which would have caused the insurer to either refuse cover or charge higher premium at the time of the proposal, the insurer has the right to refuse payment for all or part of the claim.</p>\r\n<p>So, simply put, a material fact is any information that can influence an insurer&rsquo;s decision in accepting the risk and determining the terms of your insurance cover.</p>','0','1401598800','Yes','0'),
 ('794','Medical Examination','<p>The insurance company may require you to go for a medical examination either prior to cover being given or in connection with a claim.</p>','0','1401598800','Yes','0'),
 ('795','Members of the Insuredâ€™s household','<p>People who normally reside in the premises described in the policy documents and schedule.</p>','0','1401598800','Yes','0'),
 ('796','Motor Insurance','<p>Pays out if you injure someone or damage someone else\'s property while driving. It may also cover damage to your own car or if it is stolen.</p>','0','1401598800','Yes','0'),
 ('797','Modifications','<p>This typically refers to any changes that have been made to your car since it was produced, including the addition of such things as spoilers and or engine modifications. It is best to always notify the insurance company of anything that&rsquo;s not standard from the manufacturer.</p>\r\n<p>See also &lsquo;Material Fact.&rsquo;</p>','0','1401645395','Yes','0'),
 ('798','New Replacement Value','&lt;!--  /* Font Definitions */ @font-face 	{font-family:Times; 	panose-1:2 0 5 0 0 0 0 0 0 0; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:3 0 0 0 1 0;} @font-face 	{font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-font-charset:78; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1791491579 18 0 131231 0;} @font-face 	{font-family:"Cambria Math"; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1107305727 0 0 415 0;} @font-face 	{font-family:Calibri; 	panose-1:2 15 5 2 2 2 4 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-520092929 1073786111 9 0 415 0;} @font-face 	{font-family:Cambria; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1073743103 0 0 415 0;}  /* Style Definitions */ p.MsoNormal, li.MsoNormal, div.MsoNormal 	{mso-style-unhide:no; 	mso-style-qformat:yes; 	mso-style-parent:""; 	margin:0cm; 	margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} .MsoChpDefault 	{mso-style-type:export-only; 	mso-default-props:yes; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} @page WordSection1 	{size:612.0pt 792.0pt; 	margin:72.0pt 90.0pt 72.0pt 90.0pt; 	mso-header-margin:36.0pt; 	mso-footer-margin:36.0pt; 	mso-paper-source:0;} div.WordSection1 	{page:WordSection1;} --&gt;\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span style="font-size: 10.0pt; font-family: Calibri; mso-bidi-font-family: &quot;Times New Roman&quot;; color: black;">This is the cost of replacement of the insured property e.g. equipment by new equipment of the same type, capacity and quality, including freight, customs and other duties if any, and all installation charges.</span></p>','0','1401598800','Yes','0'),
 ('799','No Claims Discount (NCD)','<p>This is the discount that you as a driver earns on a previous insurance policy or if you have not made a claim on an insurance policy for at least one full year. Insurers give discounts that are determined by the number of years a driver remains claim-free and a NCD must be earned separately for each insured car. Note though that underlying insurance premium can still increase even if you have a No Claim Discount: an increase that may, for example, be due to a general rise in premiums across the market.</p>','0','1401598800','Yes','0'),
 ('800','No Claims Discount Letter (NCDL)','This is the proof of your No Claims Discount entitlement, which the insurance company you are moving from should provide you with. &nbsp;You should give this proof to your new insurer otherwise any discount may be reversed.','0','1401598800','Yes','0'),
 ('801','New for Old','<p>Some insurance policies offer New for Old cover. This means they\'ll replace old damaged appliances and possessions with new ones when you claim.</p>','0','1401598800','Yes','0'),
 ('802','Occupationâ€¨','<p>The Insured&rsquo;s usual occupation, business, trade or profession.</p>','0','1401598800','Yes','0'),
 ('803','Other Insurances','If at the time of any claim arising out of the policy, there shall be any other insurance covering the same risk against loss or damage the company shall not be liable for more than its rateable proportion. What this means is that whenever there are two policies covering the same risk then any claim amount paid to you will be shared between those two policies.','0','1401598800','Yes','0'),
 ('804','Outbuildings','<p>Servants&rsquo; quarters, garages sheds and any other buildings that do not form part of the main building but are used for domestic purposes.</p>','0','1401598800','Yes','0'),
 ('805','Owner and Registered Keeper','<p>There may be a reason for the owner and registered keeper to be different people. For example, you may drive a company car that is owned by your employer, in which case, you would be the registered keeper. Another common instance is where parents who own a car and allow their child to use it, which would make that child the registered keeper.</p>','0','1401598800','Yes','0'),
 ('806','Personal Effects ','<p>Whenever the term Personal Effects is used in a policy it means:</p>\r\n<p>(a) Clothing</p>\r\n<p>(b) Luggage containers and briefcases</p>\r\n<p>(c) Jewellery, trinkets and toilet requisites</p>\r\n<p>(d) Other items of strictly personal nature generally worn used or carried.</p>','0','1401598800','Yes','0'),
 ('807','Policy Document','<p>This contains details of what your insurance covers, what it doesn\'t, and what it costs.</p>','0','1401598800','Yes','0'),
 ('808','Premises','<p>This is the building at the physical address stated in the policy schedule but excluding any yard, garden, veranda or outbuilding unless the contents thereof are specifically insured under the Policy.</p>','0','1401598800','Yes','0'),
 ('809','Premium','The amount your insurer requires you to pay for insurance. It tends to vary from insurer to insurer and also from one insurance cover to another. Premiums may rise year to year as a result of general increases or as a result of any claims you made during the year.','0','1401598800','Yes','0'),
 ('810','Pro Rata rates','<p>Sometimes, when a policy is cancelled, you will only be charged for the time you were covered by the insurer and not for the full term of the policy. The charges in such cases are based on what are known as Pro Rata rates.</p>\r\n<p>See also &lsquo;Short Period Rates&rsquo;</p>','0','1401598800','Yes','0'),
 ('811','Rating Factors','<p>These are used by underwriters to determine the price of your insurance. They can include claims experiences, modifications to your car, the materials used to construct your home etc. &nbsp;&nbsp;</p>','0','1401598800','Yes','0'),
 ('812','Reasonable Care','<p>What this means is that you shall take all ordinary and reasonable precautions for the safety of the property insured.</p>','0','1401598800','Yes','0'),
 ('813','Registered keeper','<p>See Owner.</p>','0','1401598800','Yes','0'),
 ('814','Replacement value','This is the cost to replace the damaged property with materials of like kind and quality, without any deduction for depreciation.','0','1401598800','Yes','0'),
 ('815','Risk','&lt;!--  /* Font Definitions */ @font-face 	{font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-font-charset:78; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1791491579 18 0 131231 0;} @font-face 	{font-family:"Cambria Math"; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1107305727 0 0 415 0;} @font-face 	{font-family:Calibri; 	panose-1:2 15 5 2 2 2 4 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-520092929 1073786111 9 0 415 0;} @font-face 	{font-family:Cambria; 	panose-1:2 4 5 3 5 4 6 3 2 4; 	mso-font-charset:0; 	mso-generic-font-family:auto; 	mso-font-pitch:variable; 	mso-font-signature:-536870145 1073743103 0 0 415 0;}  /* Style Definitions */ p.MsoNormal, li.MsoNormal, div.MsoNormal 	{mso-style-unhide:no; 	mso-style-qformat:yes; 	mso-style-parent:""; 	margin:0cm; 	margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} .MsoChpDefault 	{mso-style-type:export-only; 	mso-default-props:yes; 	font-family:Cambria; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-fareast-font-family:"ï¼­ï¼³ æ˜Žæœ"; 	mso-fareast-theme-font:minor-fareast; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-bidi-font-family:"Times New Roman"; 	mso-bidi-theme-font:minor-bidi;} @page WordSection1 	{size:612.0pt 792.0pt; 	margin:72.0pt 90.0pt 72.0pt 90.0pt; 	mso-header-margin:36.0pt; 	mso-footer-margin:36.0pt; 	mso-paper-source:0;} div.WordSection1 	{page:WordSection1;} --&gt;      <span style="font-size: 10.0pt; font-family: Calibri; mso-fareast-font-family: &quot;ï¼­ï¼³ æ˜Žæœ&quot;; mso-fareast-theme-font: minor-fareast; mso-bidi-font-family: &quot;Times New Roman&quot;; color: black; mso-ansi-language: EN-GB; mso-fareast-language: EN-US; mso-bidi-language: AR-SA;">All insurances in Kenya are offered by assessing the risk (s) to be insured. Insurers do this by looking at the details provided and asking how likely a claim will be made. In the case of medical insurance, the answer to this is driven by a number of factors including the insured&rsquo;s age.</span>','0','1401598800','Yes','0'),
 ('816','Road Traffic Act (RTA)','<p>This Act governs all car insurance in the Kenya. It first came into force to ensure funds were available to compensate the innocent victims of accidents and now includes include passengers and third party property. RTA cover is the minimum legal requirement for car insurance in Kenya and covers third party injury or death (both unlimited), property damage up to certain limits and emergency medical costs.</p>','0','1401598800','Yes','0'),
 ('817','Schedule','<p>The specific details of what\'s covered, and what\'s excluded, by a policy. This is a key document in your policy document.</p>','0','1401598800','Yes','0'),
 ('818','Short Period Rates','<p>Occasionally when a policy is cancelled, the policyholder may be charged a short period rate, which includes an additional charge for the period of cover over and above the Pro Rata rates. These rates are usually worked out on a percentage basis.</p>\r\n<p>See also &lsquo;Pro Rata Rates&rsquo;</p>','0','1401598800','Yes','0'),
 ('819','Social, Domestic and Pleasure','<p>This option covers drivers for normal day-to-day driving, such as driving to visit family and friends or shopping.</p>','0','1401598800','Yes','0'),
 ('820','Subrogation Clause','<p>The Company may at its own expense use legal means in the name of the Insured for recovery of any property lost or its value and the Insured shall give all reasonable assistance for that purpose. Upon settlement or making good any loss or damage under the Policy the Company shall be entitled to any property recovered.</p>\r\n<p>What this means is that if you have a loss, the insurance company will expect you to do everything possible in order that it is not made worse. Also once the claim has been paid, the property or what remains of it belongs to the insurance company.</p>\r\n<p>&nbsp;</p>','0','1401598800','Yes','0'),
 ('821','Terms','<p>Terms include provisions, conditions, warranties and exceptions of the policy.</p>','0','1401598800','Yes','0'),
 ('822','Third Party','You, as the vehicle owner, are the first party in a motor insurance contract with the insurer as the second party. The third party is any person (s) &ndash; including a property owner, a pedestrian, a driver or passengers in another vehicle &ndash;who suffers property damage or loss or death or bodily injury as a result of an accident involving your motor vehicle.','0','1401598800','Yes','0'),
 ('823','Third Party Only (TPO)','<p>Motor insurance that only covers claims by third parties. It does not cover damage and or losses to the driver\'s own car or their property.</p>\r\n<p>&nbsp;See also Cover types and Third Party</p>','0','1401598800','Yes','0'),
 ('824','TPFT','<p>Third Party Fire and Theft (TPFT): Motor insurance that covers fire and theft of the driver\'s car in addition to TPO cover. It does not cover damage and or losses to the policyholder&rsquo;s own car or their property.</p>\r\n<p>&nbsp;See also &lsquo;Cover Types&rsquo; and &lsquo;Third Party Only&rsquo;.</p>','0','1401598800','Yes','0'),
 ('825','Time Excess','<p>The number of days of interruption, which has to be borne by the Insured in the event of a loss.</p>','0','1401598800','Yes','0'),
 ('826','Tracker','<p>A tracker is an electronic device (normally fitted as an accessory after purchase of the car) that emits a signal enabling the location of the car anywhere in the Kenya, and even outside the country sometimes, if it has been stolen. Some newer models may have factory fitted trackers.</p>','0','1401598800','Yes','0'),
 ('827','Transfer of Rights','<p>Nothing contained in the Policy shall unless expressly stated give rights against the Company to any person other than the Insured, his executors or administrators, and the Company will not be bound by any passing of the interest otherwise than by death or operation of law unless and until the Company shall by endorsement declare the insurance to be continued.</p>\r\n<p>What this means that the insurance contract is between you, your executors and or administrators, and the insurance company.</p>','0','1401598800','Yes','0'),
 ('828','Underinsurance ','<p>If a claim recoverable under this Policy occurs whilst the value of the property is higher than the insured value, the Insured shall bear a rateable proportion of the loss. Every item of the schedule shall be separately subject to this condition</p>\r\n<p>This can happen when, for example, your contents are worth KES100, 000 yet you only purchase insurance cover for KES 50,000. In case of a claim, your insurers will not pay the full claim amount whether you did this unwittingly or not. They will also not refund any premiums already paid by you. &nbsp;</p>\r\n<p>See also &lsquo;Average Condition&rsquo;</p>','0','1401598800','Yes','0'),
 ('829','Usage types','<p>When arranging car insurance through Bima247.com or an insurer or any other authorised entity, it is important that you clearly state to them how your car is to be used. If you have put or stated the wrong use, you may find that your insurance company will not pay out on a claim. And you lose your premium.</p>\r\n<p>The following are the common types of use for car insurance in the Kenya:</p>\r\n<p><strong>Social, Domestic and Pleasure</strong>&nbsp; - This option covers drivers for normal day-to-day driving, such as driving to visit family and friends or shopping.</p>\r\n<p><strong>Commuting</strong> - This option covers you to drive back and forth to a permanent place of work: e.g. driving to and out of the Central Business District.</p>\r\n<p><strong>Business Use</strong> - This covers the car in connection with your job, such as driving to different sites away from your place of work.</p>\r\n<strong>Commercial Travelling</strong> - This covers the car to be used for travelling in the course of work if for example you are a salesperson and travel, for instance, between Mombasa and Nakuru regularly.','0','1401598800','Yes','0'),
 ('830','Unoccupied','<p>This typically refers to your Domestic Package Policy</p>\r\n<p>Under section A: A private dwelling that has been left uninhabited for more than 30 consecutive days.</p>\r\nUnder section B: A home that has been left uninhabited for more than 7 consecutive days.','0','1401598800','Yes','0'),
 ('831','Valuables','<p>Generally considered to be articles of value including but not limited to jewellery, metals, watches photographic equipment, binoculars, paintings and other works of art, radio televisions other audio or video and /or computer equipment, collections of stamps, coins and medals.</p>','0','1401598800','Yes','0'),
 ('832','Yellow Card','<p>This is what you need if to drive outside Kenya to any COMESA member countries. It is insurance that covers third-party liabilities and medical expenses for the driver of the vehicle and his passengers should they suffer any bodily injury as a result of an accident to an insured vehicle. It also facilitates cross border movement of vehicles between COMESA member countries. As this card is valid in many parts of the region, transporters and motorists do not have to buy insurance cover at each border post they cross.</p>','0','1401598800','Yes','0'),
 ('833','Terms and Conditions','<span style="color: #ff0000;"><strong><strong>Terms and Conditions specific to members of the general public and our customers.</strong></strong></span>\r\n<p style="text-align: center;"><strong>The Agreement</strong></p>\r\n<p style="text-align: left;"><br /> By using our website hereinafter also known as the &lsquo;Site&rsquo; and sophisticated technology you are agreeing to these terms and conditions so that we can deliver to you the cover you require where it is available from providers of insurance and other financial services in Kenya. In addition to these terms and conditions, you are agreeing to be bound by any other specific terms and conditions as there may be in relation to other aspects of using this site and or working in partnership with us.<br /> &nbsp;<br /> <strong>The service</strong><br /> <br /> We provide an online service, designed to save you time and money when it comes to reviewing your insurance and other financial products needs. Not only will we endeavour to find you a quote and smoothen your insurance purchase process but also provide you with as much information as possible so that you can make an informed choice on which policy best suits your needs.</p>\r\n<p><strong>The responsibility</strong><br /> <br /> We will provide you with a quotation based on the information you have provided to us. It is essential that all information and answers are true and accurate and that you also disclose all relevant facts. Failure to provide accurate information could lead to your insurance being invalid and you being liable for any third party costs in the event of an accident.</p>\r\n<p>Before accepting a policy it is of the utmost importance that all the information the insurer holds on you is accurate. It is your responsibility to ensure all details are correct. Failure to do so could result in your insurance being invalid. It is also important that you read the insurers terms or conditions as they will differ from ours and are the terms that you will be agreeing to.</p>\r\n<p>The technology used is very sophisticated but not infallible. If the information passed is not correct it is your responsibility to identify the mistake and, as such, we relinquish all liability, which by law we can exclude, in respect of all losses you may incur.</p>\r\n<p>If you have any doubt whether further information about you, not requested during the Bima247.com service is required to be disclosed, or that something may be incorrect, please contact the proposed insurer or us by email before you accept the quotation.</p>\r\n<p><strong>Product terms and conditions</strong></p>\r\n<p>The content of the Site does not constitute an offer by us to sell products and services. Your request to purchase a product or service represents an offer by you and will be subject to the terms and conditions of that product or service that we may accept or reject. After you make a request through the Site to purchase the product or service then assuming such product or service is available to you and your offer is accepted, you will receive confirmation of your purchase.</p>\r\n<p>The information and descriptions on the Site do not necessarily represent complete descriptions of all terms, conditions and exclusions and the precise cover provided (as applicable) shall be included in the schedule of cover, policy documents and/or conditions of purchase issued to you.</p>\r\n<p>If you apply for any product or service detailed on the Site, these conditions of use should be read in conjunction with any other terms and conditions relating to that product or service and, in the event of any contradiction between these conditions of use and the specific product of service terms and conditions, the latter shall prevail.</p>\r\n<p>You must ensure that the details you give to us while using this Site are correct and that there are sufficient funds to cover the cost of the product or service. In the case of requesting an automatic renewal the credit or debit card you are using must be your own and not in anyone else\'s name.</p>\r\n<p><strong>Complaints</strong></p>\r\n<p>We set ourselves very high standards and look to provide these high standards in everything we do, but we also know there may be occasions when you feel we have not achieved this. In these rare cases we want you to tell us as this is the only way we\'ll be able to improve our service. How do you do this? Well, you can send an email to us and we will look to resolve the matter as quickly and fairly as possible. &nbsp;Email us at help [at] Bima247.com</p>\r\n<p><strong>Other important information</strong></p>\r\n<p><strong>Links to other sites</strong></p>\r\n<p>We may provide links to other websites that are not under our control. These links are provided for your convenience. When you activate these links, you will leave the Site. We do not endorse or take responsibility for the content on third party websites or the availability of those websites and we are not liable for any loss or damage that you may suffer by using those websites. If you decide to access linked websites you do so at your own risk. Some links on the Site will lead you to websites that are under the control of other companies. Please consult the conditions of use and privacy statement on those websites for further details on use of those websites.</p>\r\n<p>We accept no liability for any statements, information, content, products or services that are published on, or may be accessible from, these third party sites. We can also give no guarantee they are free from viruses or anything else that could be infectious or destructive. <br /> <br /><strong> Website content</strong><br /> <br /> We have taken every step to ensure the information contained and displayed on our website is accurate and up-to-date. However, we can accept no liability for any errors or omissions. We reserve the right to add, amend or delete content from the site at any time.</p>\r\n<p><strong>Website access, usage and passwords</strong></p>\r\n<p>The Site is directed at those with an insurable interest in Kenya and or for the purchase of insurance and or related products for someone with an insurable interest in Kenya and or Kenyan Diaspora. We reserve to reject any applications for products that do not conform to the foregoing.</p>\r\n<p>To obtain access to certain online services you are given the opportunity to register with us. You are responsible for maintaining the confidentiality of your details and your password and for restricting access to your computer to prevent unauthorised access to your account. You accept responsibility for activities that occur under your account and you should take all steps to ensure your password is kept confidential. You agree to inform us immediately if you have reason to believe your password is being used in an unauthorised manner.</p>\r\n<p>You must not use the site in any way that causes or is likely to cause access to be interrupted, or impaired in any way and you acknowledge and agree that you are responsible for electronic communications sent from your computer.</p>\r\n<p><strong>Copyright notice</strong><br /> <br /> The copyright in the material contained on the site belongs Bima247.com. Any person may copy any part of this material, subject to the following conditions:</p>\r\n<p>The material may not be used for commercial purposes especially to set up a similar or rival service <br /> the copies must retain any copyrights or other intellectual property notices contained in the original material any images on the site are protected by copyright and may not be reproduced or used in any manner without written permission from the owner <br /> <br /><strong> Intellectual property</strong><br /> All intellectual property rights pertaining to CML and S are hereby reserved.</p>\r\n<p><strong>Trademarks</strong></p>\r\n<p>Images, logos, names and trademarks on the Site are proprietary marks of CMlL and S and/or any applicable relevant third party. Unless otherwise agreed in writing nothing on the Site shall be deemed to confer on any person any licence or right to use any such image, logo, name or trademark and any such use may constitute an infringement of the rights of the holder.</p>\r\n<p>More specifically, the name Bima247.com or a similar variation of Bima247.com may not be used by any company trading within insurance, banking, financial services and or the ICT business sectors without written permission from an appropriate representative of Bima247.com.</p>\r\n<p>Unauthorised reproduction or use of the Bima247.com or similar variations by businesses trading within the sectors listed above will be challenged. We shall pursue any infringements of our trading name to the fullest possible extents of the applicable laws.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Ownership of all materials on the Site</strong></p>\r\n<p>We are the owner of all copyright, design, graphical and text arrangements, database rights and other intellectual property rights that exist in the content of the Site unless otherwise stated. The Site is intended for non-commercial personal use only. You may not commercially exploit, publish, distribute, extract, re-utilise or reproduce any part of the Site in any material form (including photocopying or storing it in any medium by electronic means) or use it in any other way other than in accordance with the limited use licence set out in our copyright notice.</p>\r\n<p>Interference or entry to the Site with intent to corrupt, damage or deny service from the Site or for other commercial benefit shall be taken seriously and we shall take such action as is necessary to protect the Site from any such activities to protect our intellectual property rights. You acknowledge that damages may not be an adequate remedy for any infringement of such rights by you and that we are entitled to the remedies of injunction, specific performance, orders to deliver up infringing copies and any other statutory or equitable relief for any threatened or actual infringement and that no proof of special damages is necessary for reliance on such remedies.</p>\r\n<strong>Availability of the site </strong>\r\n<p>Whilst we have taken care in the preparation of the Site, certain technical matters may be beyond our control and we cannot guarantee that you will have uninterrupted or error free access to all of the Site at all times, that defects will be remedied, or that the Site, or the server that makes the Site available, are virus or bug free. Access may be suspended occasionally or restricted to allow for repair or maintenance or for the introduction of new services.</p>\r\n<p><strong>Amendments</strong></p>\r\n<p>We reserve the right to amend our terms and conditions at any time. The amended terms will be effective from the date they are posted on our site. As these Terms and Conditions of use may be updated from time to time, we suggest that you check them whenever you visit the Site.</p>\r\n<p><strong>&nbsp;</strong></p>\r\n<p><strong>Use of personal information</strong><br /> Through your use of the Site you agree that personal information that is provided will be dealt with in accordance with our privacy policy.</p>\r\n<p><strong>Disclaimer</strong></p>\r\n<p>We will do our best to correct errors and omissions as soon as we can. Nevertheless on occasion there may be mistakes in the price or type of product shown. In the event that such error in price, product or service is shown then we reserve the right to cancel that contract, but this of course will be without any liability to you and a refund will be offered.</p>\r\n<p>The following provisions should be read carefully as they exclude or limit our legal liability in connection with your use of this website. Nothing in these terms and conditions attempts to exclude liability that is not permissible under applicable law, including without limitation, death or personal injury, or for fraudulent misrepresentation.</p>\r\n<p>While we have taken all reasonable steps to ensure the accuracy and completeness of the content of the website, we exclude any warranties, undertakings or representations (either express or implied) to the full extent permitted under applicable law, that the website or (including without limitation) all or any part of the content or materials, accuracy, availability or completeness of the content of the website or any part of the content or materials are appropriate or available for use either in the Kenya or in other jurisdictions. If you use this website from other jurisdictions, you are responsible for compliance with applicable local laws.</p>\r\n<p>We accept no liability in contract, tort, negligence, statutory duty or otherwise (to the maximum extent permitted by applicable law) arising out of the use of or access to this website (which includes without limitation) any errors or omissions contained in this website or if the website is unavailable and we shall not be liable for any direct or indirect:</p>\r\n<ul>\r\n<li>economic losses      (including without limitation loss of revenues, data, profits, contracts,      use, opportunity, business or anticipated savings);</li>\r\n<li>loss of goodwill      or reputation;</li>\r\n<li>special,      incidental, consequential loss or damage, suffered or incurred arising out      of or in connection with your use of this website and these terms and      conditions.</li>\r\n</ul>\r\n<p>Access to and use of this website is at the user\'s own risk and we do not warrant that the use of this website or any material downloaded from it will not cause damage to any property, or otherwise minimise or eliminate the inherent risks of the internet including but not limited to loss of data, computer virus infection, spyware, malicious software, Trojans and worms. Also, we accept no liability in respect of losses or damages arising out of changes made to the content of this website by unauthorised third parties.</p>\r\n<p>Please note that this disclaimer does not affect your rights in respect of any products or services that you purchase from this site.</p>\r\n<p><strong>Waiver</strong></p>\r\n<p>If you breach these terms of use and we do not take immediate action against you we are still entitled to enforce our rights and remedies in respect of any such breach or any subsequent breach.</p>','26','1401598800','Yes','0'),
 ('838','New technological innovation ushers in the next generation of insurance services in Kenya and a new era for the insurance sector ','<br /><span style="color: #ff0000; font-size: medium;">Coming soon...</span>','0','1403845200','Yes','0'),
 ('835','Advisory Board','<strong>Coming soon...</strong><br />\r\n<div id="_mcePaste" class="mcePaste" style="position: absolute; left: -10000px; top: 416px; width: 1px; height: 1px; overflow: hidden;"><!--[if gte mso 9]><xml> <o:OfficeDocumentSettings> <o:AllowPNG /> </o:OfficeDocumentSettings> </xml><![endif]-->\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Dr Chris Odindo MBA, FHEA. </span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB"><br /> Chris is a social entrepreneur and one of Africa&rsquo;s leading experts on insurance, insurance markets and related ICT systems, and risk management. He has decades of underwriting, senior management and consultancy experience in the insurance &ndash; and the wider financial services &ndash; industry, both locally in Kenya with UAP Provincial and AIG Kenya and abroad, notably the London insurance market with Norwich Union (now AVIVA) and The Royal Sun Alliance. Chris&rsquo; experiences as an academic and consultant delivering on projects for blue chip financial services organizations like AVIVA, Direct Line Insurance, Prudential, Liverpool Victoria, Barclays, HSBC, Royal Bank of Scotland, Legal and General Group in the City of London, only adds to the wealth of his experience.</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">ï¿¼His consultancy work on financial services (especially on insurance, risks, ICTs competitive behaviours and optimizing efficiencies in insurance operations and markets) has been used to inform a number of the UK&rsquo;s House of Parliament Select Committees as well as various UK government bodies and private sector organizations.</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Chris has a BA (Hons) in insurance studies. He additionally has an MBA in Risk and Insurance, and Strategic Management from the University of Nottingham. His PhD in these same areas is also from the University of Nottingham. He is currently working towards his Fellowship of the Chartered Insurance Institute (FCII) qualification.</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Yvonne Wangeci Gitobu</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Yvonne is a vastly experienced insurance broking professional and former Divisional Director of Eagle Africa Insurance Brokers Limited Kenya. She is a lawyer by training but has spent much of her career in insurance broking at <span style="mso-bidi-font-weight: bold;">Hogg Robinson &amp; Capel Insurance Brokers of UK which changed to Bain Hogg Insurance Brokers</span> following a global name change and then re-named as <span style="mso-bidi-font-weight: bold;">Alexander Forbes Insurance Brokers Kenya Limited &ndash; a</span> top 10 global independent risk and financial services organisation delivering services to small, medium and large businesses, as well as individual clients in 27 countries in Africa, Europe, Asia and Latin America, which changed its name to <span style="mso-bidi-font-weight: bold;">Eagle Africa Insurance Brokers Limited.</span></span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Yvonne was educated in Kenya and United Kingdom at the Kenya School of Law and University of Warwick respectively where she also read Law.</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Stanley Ngumo </span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB"><br /> Stanley is an accomplished motivated and professional; software developer, programmer, web designer and infrastructure manager, well known for a personable approach to clients as well as for delivering and implementing advanced software solutions on-time and on-budget.</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">He has a wealth of practical knowledge on Information Technology and Systems from his background as an experienced programmer and designer who gained his experiences working for large private sector companies like Marshalls-Peugeot EA Limited; and, as a long-term software entrepreneur.</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">He has an Information Technology (I.T.) degree from the Strathmore University.</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Edward Rombo</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Edward Rombo is a well-reputed lawyer who is also well known as one of the few Kenya rugby players to turn professional when he moved to Leeds Rhinos in 1990. He is now retired from both rugby union and rugby league but has continued to be involved in the sport in different capacities, notably in administration as a former Director on the Kenya Rugby Football Union Board and as a coach at Mwamba RFC. </span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Edward read law both in Kenya and United Kingdom at the University of Nairobi and University of Leeds respectively.</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Carla Viezee </span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">&nbsp;</span></p>\r\n<p class="MsoNormal" style="text-align: justify; text-justify: inter-ideograph;"><span lang="EN-GB">Carla is a businesswoman who has been in the service industry for 25 years with a history of spotting new business opportunities. Carla became known for creating Pasara, the first and then immensely popular sandwich bar in central Nairobi. In recent times Carla&rsquo;s developed an interest in the ICT sector and especially the development of a Cloud based file back up service that manages automatic backup of users files. </span></p>\r\n<!--[if gte mso 9]><xml> <w:WordDocument> <w:View>Normal</w:View> <w:Zoom>0</w:Zoom> <w:TrackMoves /> <w:TrackFormatting /> <w:PunctuationKerning /> <w:ValidateAgainstSchemas /> <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid> <w:IgnoreMixedContent>false</w:IgnoreMixedContent> <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText> <w:DoNotPromoteQF /> <w:LidThemeOther>EN-GB</w:LidThemeOther> <w:LidThemeAsian>JA</w:LidThemeAsian> <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript> <w:Compatibility> <w:BreakWrappedTables /> <w:SnapToGridInCell /> <w:WrapTextWithPunct /> <w:UseAsianBreakRules /> <w:DontGrowAutofit /> <w:SplitPgBreakAndParaMark /> <w:EnableOpenTypeKerning /> <w:DontFlipMirrorIndents /> <w:OverrideTableStyleHps /> <w:UseFELayout /> </w:Compatibility> <w:DoNotOptimizeForBrowser /> <m:mathPr> <m:mathFont m:val="Cambria Math" /> <m:brkBin m:val="before" /> <m:brkBinSub m:val=" " /> <m:smallFrac m:val="off" /> <m:dispDef /> <m:lMargin m:val="0" /> <m:rMargin m:val="0" /> <m:defJc m:val="centerGroup" /> <m:wrapIndent m:val="1440" /> <m:intLim m:val="subSup" /> <m:naryLim m:val="undOvr" /> </m:mathPr></w:WordDocument> </xml><![endif]--><!--[if gte mso 9]><xml> <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"   DefSemiHidden="true" DefQFormat="false" DefPriority="99"   LatentStyleCount="267"> <w:LsdException Locked="false" Priority="0" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Normal" /> <w:LsdException Locked="false" Priority="9" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="heading 1" /> <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2" /> <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3" /> <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4" /> <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5" /> <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 6" /> <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7" /> <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8" /> <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9" /> <w:LsdException Locked="false" Priority="39" Name="toc 1" /> <w:LsdException Locked="false" Priority="39" Name="toc 2" /> <w:LsdException Locked="false" Priority="39" Name="toc 3" /> <w:LsdException Locked="false" Priority="39" Name="toc 4" /> <w:LsdException Locked="false" Priority="39" Name="toc 5" /> <w:LsdException Locked="false" Priority="39" Name="toc 6" /> <w:LsdException Locked="false" Priority="39" Name="toc 7" /> <w:LsdException Locked="false" Priority="39" Name="toc 8" /> <w:LsdException Locked="false" Priority="39" Name="toc 9" /> <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption" /> <w:LsdException Locked="false" Priority="10" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Title" /> <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font" /> <w:LsdException Locked="false" Priority="11" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Subtitle" /> <w:LsdException Locked="false" Priority="22" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Strong" /> <w:LsdException Locked="false" Priority="20" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Emphasis" /> <w:LsdException Locked="false" Priority="59" SemiHidden="false"    UnhideWhenUsed="false" Name="Table Grid" /> <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text" /> <w:LsdException Locked="false" Priority="1" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="No Spacing" /> <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading" /> <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List" /> <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid" /> <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1" /> <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2" /> <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1" /> <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2" /> <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1" /> <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2" /> <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3" /> <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List" /> <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading" /> <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List" /> <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid" /> <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 1" /> <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 1" /> <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 1" /> <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1" /> <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1" /> <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 1" /> <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision" /> <w:LsdException Locked="false" Priority="34" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="List Paragraph" /> <w:LsdException Locked="false" Priority="29" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Quote" /> <w:LsdException Locked="false" Priority="30" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Intense Quote" /> <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 1" /> <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1" /> <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1" /> <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1" /> <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 1" /> <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 1" /> <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 1" /> <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 1" /> <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 2" /> <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 2" /> <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 2" /> <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2" /> <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2" /> <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 2" /> <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 2" /> <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2" /> <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2" /> <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2" /> <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 2" /> <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 2" /> <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 2" /> <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 2" /> <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 3" /> <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 3" /> <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 3" /> <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3" /> <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3" /> <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 3" /> <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 3" /> <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3" /> <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3" /> <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3" /> <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 3" /> <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 3" /> <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 3" /> <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 3" /> <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 4" /> <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 4" /> <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 4" /> <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4" /> <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4" /> <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 4" /> <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 4" /> <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4" /> <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4" /> <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4" /> <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 4" /> <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 4" /> <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 4" /> <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 4" /> <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 5" /> <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 5" /> <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 5" /> <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5" /> <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5" /> <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 5" /> <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 5" /> <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5" /> <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5" /> <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5" /> <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 5" /> <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 5" /> <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 5" /> <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 5" /> <w:LsdException Locked="false" Priority="60" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Shading Accent 6" /> <w:LsdException Locked="false" Priority="61" SemiHidden="false"    UnhideWhenUsed="false" Name="Light List Accent 6" /> <w:LsdException Locked="false" Priority="62" SemiHidden="false"    UnhideWhenUsed="false" Name="Light Grid Accent 6" /> <w:LsdException Locked="false" Priority="63" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6" /> <w:LsdException Locked="false" Priority="64" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6" /> <w:LsdException Locked="false" Priority="65" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 1 Accent 6" /> <w:LsdException Locked="false" Priority="66" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium List 2 Accent 6" /> <w:LsdException Locked="false" Priority="67" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6" /> <w:LsdException Locked="false" Priority="68" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6" /> <w:LsdException Locked="false" Priority="69" SemiHidden="false"    UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6" /> <w:LsdException Locked="false" Priority="70" SemiHidden="false"    UnhideWhenUsed="false" Name="Dark List Accent 6" /> <w:LsdException Locked="false" Priority="71" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Shading Accent 6" /> <w:LsdException Locked="false" Priority="72" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful List Accent 6" /> <w:LsdException Locked="false" Priority="73" SemiHidden="false"    UnhideWhenUsed="false" Name="Colorful Grid Accent 6" /> <w:LsdException Locked="false" Priority="19" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis" /> <w:LsdException Locked="false" Priority="21" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis" /> <w:LsdException Locked="false" Priority="31" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference" /> <w:LsdException Locked="false" Priority="32" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Intense Reference" /> <w:LsdException Locked="false" Priority="33" SemiHidden="false"    UnhideWhenUsed="false" QFormat="true" Name="Book Title" /> <w:LsdException Locked="false" Priority="37" Name="Bibliography" /> <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading" /> </w:LatentStyles> </xml><![endif]--><!--[if gte mso 10]> <mce:style><!   /* Style Definitions */  table.MsoNormalTable 	{mso-style-name:"Table Normal"; 	mso-tstyle-rowband-size:0; 	mso-tstyle-colband-size:0; 	mso-style-noshow:yes; 	mso-style-priority:99; 	mso-style-parent:""; 	mso-padding-alt:0in 5.4pt 0in 5.4pt; 	mso-para-margin:0in; 	mso-para-margin-bottom:.0001pt; 	mso-pagination:widow-orphan; 	font-size:12.0pt; 	font-family:"Cambria","serif"; 	mso-ascii-font-family:Cambria; 	mso-ascii-theme-font:minor-latin; 	mso-hansi-font-family:Cambria; 	mso-hansi-theme-font:minor-latin; 	mso-ansi-language:EN-GB;} --> <!--[endif] --></div>','26','1401771600','Yes','0');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_car_models` 
-- 

INSERT INTO `itr_car_models` (`id`, `make_id`, `code`, `title`) VALUES ('1','1','CL_MODELS','CL Models (4)'),
 ('2','1','2.2CL',' - 2.2CL'),
 ('3','1','2.3CL',' - 2.3CL'),
 ('4','1','3.0CL',' - 3.0CL'),
 ('5','1','3.2CL',' - 3.2CL'),
 ('6','1','ILX','ILX'),
 ('7','1','INTEG','Integra'),
 ('8','1','LEGEND','Legend'),
 ('9','1','MDX','MDX'),
 ('10','1','NSX','NSX'),
 ('11','1','RDX','RDX'),
 ('12','1','RL_MODELS','RL Models (2)'),
 ('13','1','3.5RL',' - 3.5 RL'),
 ('14','1','RL',' - RL'),
 ('15','1','RSX','RSX'),
 ('16','1','SLX','SLX'),
 ('17','1','TL_MODELS','TL Models (3)'),
 ('18','1','2.5TL',' - 2.5TL'),
 ('19','1','3.2TL',' - 3.2TL'),
 ('20','1','TL',' - TL'),
 ('21','1','TSX','TSX'),
 ('22','1','VIGOR','Vigor'),
 ('23','1','ZDX','ZDX'),
 ('24','1','ACUOTH','Other Acura Models'),
 ('25','2','ALFA164','164'),
 ('26','2','ALFA8C','8C Competizione'),
 ('27','2','ALFAGT','GTV-6'),
 ('28','2','MIL','Milano'),
 ('29','2','SPID','Spider'),
 ('30','2','ALFAOTH','Other Alfa Romeo Models'),
 ('31','3','AMCALLIAN','Alliance'),
 ('32','3','CON','Concord'),
 ('33','3','EAGLE','Eagle'),
 ('34','3','AMCENC','Encore'),
 ('35','3','AMCSPIRIT','Spirit'),
 ('36','3','AMCOTH','Other AMC Models'),
 ('37','4','DB7','DB7'),
 ('38','4','DB9','DB9'),
 ('39','4','DBS','DBS'),
 ('40','4','LAGONDA','Lagonda'),
 ('41','4','RAPIDE','Rapide'),
 ('42','4','V12VANT','V12 Vantage'),
 ('43','4','VANTAGE','V8 Vantage'),
 ('44','4','VANQUISH','Vanquish'),
 ('45','4','VIRAGE','Virage'),
 ('46','4','UNAVAILAST','Other Aston Martin Models'),
 ('47','5','AUDI100','100'),
 ('48','5','AUDI200','200'),
 ('49','5','4000','4000'),
 ('50','5','5000','5000'),
 ('51','5','80','80'),
 ('52','5','90','90'),
 ('53','5','A3','A3'),
 ('54','5','A4','A4'),
 ('55','5','A5','A5'),
 ('56','5','A6','A6'),
 ('57','5','A7','A7'),
 ('58','5','A8','A8'),
 ('59','5','ALLRDQUA','allroad'),
 ('60','5','AUDICABRI','Cabriolet'),
 ('61','5','AUDICOUPE','Coupe'),
 ('62','5','Q3','Q3'),
 ('63','5','Q5','Q5'),
 ('64','5','Q7','Q7'),
 ('65','5','QUATTR','Quattro'),
 ('66','5','R8','R8'),
 ('67','5','RS4','RS 4'),
 ('68','5','RS5','RS 5'),
 ('69','5','RS6','RS 6'),
 ('70','5','S4','S4'),
 ('71','5','S5','S5'),
 ('72','5','S6','S6'),
 ('73','5','S7','S7'),
 ('74','5','S8','S8'),
 ('75','5','TT','TT'),
 ('76','5','TTRS','TT RS'),
 ('77','5','TTS','TTS'),
 ('78','5','V8','V8 Quattro'),
 ('79','5','AUDOTH','Other Audi Models'),
 ('80','6','CONVERT','Convertible'),
 ('81','6','COUPEAVANT','Coupe'),
 ('82','6','SEDAN','Sedan'),
 ('83','6','UNAVAILAVA','Other Avanti Models'),
 ('84','7','ARNAGE','Arnage'),
 ('85','7','AZURE','Azure'),
 ('86','7','BROOKLANDS','Brooklands'),
 ('87','7','BENCONT','Continental'),
 ('88','7','CORNICHE','Corniche'),
 ('89','7','BENEIGHT','Eight'),
 ('90','7','BENMUL','Mulsanne'),
 ('91','7','BENTURBO','Turbo R'),
 ('92','7','UNAVAILBEN','Other Bentley Models'),
 ('93','8','1_SERIES','1 Series (3)'),
 ('94','8','128I',' - 128i'),
 ('95','8','135I',' - 135i'),
 ('96','8','135IS',' - 135is'),
 ('97','8','3_SERIES','3 Series (29)'),
 ('98','8','318I',' - 318i'),
 ('99','8','318IC',' - 318iC'),
 ('100','8','318IS',' - 318iS'),
 ('101','8','318TI',' - 318ti'),
 ('102','8','320I',' - 320i'),
 ('103','8','323CI',' - 323ci'),
 ('104','8','323I',' - 323i'),
 ('105','8','323IS',' - 323is'),
 ('106','8','323IT',' - 323iT'),
 ('107','8','325CI',' - 325Ci'),
 ('108','8','325E',' - 325e'),
 ('109','8','325ES',' - 325es'),
 ('110','8','325I',' - 325i'),
 ('111','8','325IS',' - 325is'),
 ('112','8','325IX',' - 325iX'),
 ('113','8','325XI',' - 325xi'),
 ('114','8','328CI',' - 328Ci'),
 ('115','8','328I',' - 328i'),
 ('116','8','328IS',' - 328iS'),
 ('117','8','328XI',' - 328xi'),
 ('118','8','330CI',' - 330Ci'),
 ('119','8','330I',' - 330i'),
 ('120','8','330XI',' - 330xi'),
 ('121','8','335D',' - 335d'),
 ('122','8','335I',' - 335i'),
 ('123','8','335IS',' - 335is'),
 ('124','8','335XI',' - 335xi'),
 ('125','8','ACTIVE3',' - ActiveHybrid 3'),
 ('126','8','BMW325',' - 325'),
 ('127','8','5_SERIES','5 Series (19)'),
 ('128','8','524TD',' - 524td'),
 ('129','8','525I',' - 525i'),
 ('130','8','525XI',' - 525xi'),
 ('131','8','528E',' - 528e'),
 ('132','8','528I',' - 528i'),
 ('133','8','528IT',' - 528iT'),
 ('134','8','528XI',' - 528xi'),
 ('135','8','530I',' - 530i'),
 ('136','8','530IT',' - 530iT'),
 ('137','8','530XI',' - 530xi'),
 ('138','8','533I',' - 533i'),
 ('139','8','535I',' - 535i'),
 ('140','8','535IGT',' - 535i Gran Turismo'),
 ('141','8','535XI',' - 535xi'),
 ('142','8','540I',' - 540i'),
 ('143','8','545I',' - 545i'),
 ('144','8','550I',' - 550i'),
 ('145','8','550IGT',' - 550i Gran Turismo'),
 ('146','8','ACTIVE5',' - ActiveHybrid 5'),
 ('147','8','6_SERIES','6 Series (8)'),
 ('148','8','633CSI',' - 633CSi'),
 ('149','8','635CSI',' - 635CSi'),
 ('150','8','640I',' - 640i'),
 ('151','8','640IGC',' - 640i Gran Coupe'),
 ('152','8','645CI',' - 645Ci'),
 ('153','8','650I',' - 650i'),
 ('154','8','650IGC',' - 650i Gran Coupe'),
 ('155','8','L6',' - L6'),
 ('156','8','7_SERIES','7 Series (15)'),
 ('157','8','733I',' - 733i'),
 ('158','8','735I',' - 735i'),
 ('159','8','735IL',' - 735iL'),
 ('160','8','740I',' - 740i'),
 ('161','8','740IL',' - 740iL'),
 ('162','8','740LI',' - 740Li'),
 ('163','8','745I',' - 745i'),
 ('164','8','745LI',' - 745Li'),
 ('165','8','750I',' - 750i'),
 ('166','8','750IL',' - 750iL'),
 ('167','8','750LI',' - 750Li'),
 ('168','8','760I',' - 760i'),
 ('169','8','760LI',' - 760Li'),
 ('170','8','ACTIVE7',' - ActiveHybrid 7'),
 ('171','8','ALPINAB7',' - Alpina B7'),
 ('172','8','8_SERIES','8 Series (4)'),
 ('173','8','840CI',' - 840Ci'),
 ('174','8','850CI',' - 850Ci'),
 ('175','8','850CSI',' - 850CSi'),
 ('176','8','850I',' - 850i'),
 ('177','8','L_SERIES','L Series (1)'),
 ('178','8','L7',' - L7'),
 ('179','8','M_SERIES','M Series (8)'),
 ('180','8','1SERIESM',' - 1 Series M'),
 ('181','8','BMWMCOUPE',' - M Coupe'),
 ('182','8','BMWROAD',' - M Roadster'),
 ('183','8','M3',' - M3'),
 ('184','8','M5',' - M5'),
 ('185','8','M6',' - M6'),
 ('186','8','X5M',' - X5 M'),
 ('187','8','X6M',' - X6 M'),
 ('188','8','X_SERIES','X Series (5)'),
 ('189','8','ACTIVEX6',' - ActiveHybrid X6'),
 ('190','8','X1',' - X1'),
 ('191','8','X3',' - X3'),
 ('192','8','X5',' - X5'),
 ('193','8','X6',' - X6'),
 ('194','8','Z_SERIES','Z Series (3)'),
 ('195','8','Z3',' - Z3'),
 ('196','8','Z4',' - Z4'),
 ('197','8','Z8',' - Z8'),
 ('198','8','BMWOTH','Other BMW Models'),
 ('199','9','CENT','Century'),
 ('200','9','ELEC','Electra'),
 ('201','9','ENCLAVE','Enclave'),
 ('202','9','BUIENC','Encore'),
 ('203','9','LACROSSE','LaCrosse'),
 ('204','9','LESA','Le Sabre'),
 ('205','9','LUCERNE','Lucerne'),
 ('206','9','PARK','Park Avenue'),
 ('207','9','RAINIER','Rainier'),
 ('208','9','REATTA','Reatta'),
 ('209','9','REG','Regal'),
 ('210','9','RENDEZVOUS','Rendezvous'),
 ('211','9','RIV','Riviera'),
 ('212','9','BUICKROAD','Roadmaster'),
 ('213','9','SKYH','Skyhawk'),
 ('214','9','SKYL','Skylark'),
 ('215','9','SOMER','Somerset'),
 ('216','9','TERRAZA','Terraza'),
 ('217','9','BUVERANO','Verano'),
 ('218','9','BUOTH','Other Buick Models'),
 ('219','10','ALLANT','Allante'),
 ('220','10','ATS','ATS'),
 ('221','10','BROUGH','Brougham'),
 ('222','10','CATERA','Catera'),
 ('223','10','CIMA','Cimarron'),
 ('224','10','CTS','CTS'),
 ('225','10','DEV','De Ville'),
 ('226','10','DTS','DTS'),
 ('227','10','ELDO','Eldorado'),
 ('228','10','ESCALA','Escalade'),
 ('229','10','ESCALAESV','Escalade ESV'),
 ('230','10','EXT','Escalade EXT'),
 ('231','10','FLEE','Fleetwood'),
 ('232','10','SEV','Seville'),
 ('233','10','SRX','SRX'),
 ('234','10','STS','STS'),
 ('235','10','XLR','XLR'),
 ('236','10','XTS','XTS'),
 ('237','10','CADOTH','Other Cadillac Models'),
 ('238','11','ASTRO','Astro'),
 ('239','11','AVALNCH','Avalanche'),
 ('240','11','AVEO','Aveo'),
 ('241','11','AVEO5','Aveo5'),
 ('242','11','BERETT','Beretta'),
 ('243','11','BLAZER','Blazer'),
 ('244','11','CAM','Camaro'),
 ('245','11','CAP','Caprice'),
 ('246','11','CHECAPS','Captiva Sport'),
 ('247','11','CAV','Cavalier'),
 ('248','11','CELE','Celebrity'),
 ('249','11','CHEVETTE','Chevette'),
 ('250','11','CITATION','Citation'),
 ('251','11','COBALT','Cobalt'),
 ('252','11','COLORADO','Colorado'),
 ('253','11','CORSI','Corsica'),
 ('254','11','CORV','Corvette'),
 ('255','11','CRUZE','Cruze'),
 ('256','11','ELCAM','El Camino'),
 ('257','11','EQUINOX','Equinox'),
 ('258','11','G15EXP','Express Van'),
 ('259','11','G10','G Van'),
 ('260','11','HHR','HHR'),
 ('261','11','CHEVIMP','Impala'),
 ('262','11','KODC4500','Kodiak C4500'),
 ('263','11','LUMINA','Lumina'),
 ('264','11','LAPV','Lumina APV'),
 ('265','11','LUV','LUV'),
 ('266','11','MALI','Malibu'),
 ('267','11','CHVMETR','Metro'),
 ('268','11','CHEVMONT','Monte Carlo'),
 ('269','11','NOVA','Nova'),
 ('270','11','CHEVPRIZM','Prizm'),
 ('271','11','CHVST','S10 Blazer'),
 ('272','11','S10PICKUP','S10 Pickup'),
 ('273','11','CHEV150','Silverado and other C/K1500'),
 ('274','11','CHEVC25','Silverado and other C/K2500'),
 ('275','11','CH3500PU','Silverado and other C/K3500'),
 ('276','11','SONIC','Sonic'),
 ('277','11','SPARK','Spark'),
 ('278','11','CHEVSPEC','Spectrum'),
 ('279','11','CHSPRINT','Sprint'),
 ('280','11','SSR','SSR'),
 ('281','11','CHEVSUB','Suburban'),
 ('282','11','TAHOE','Tahoe'),
 ('283','11','TRACKE','Tracker'),
 ('284','11','TRAILBLZ','TrailBlazer'),
 ('285','11','TRAILBZEXT','TrailBlazer EXT'),
 ('286','11','TRAVERSE','Traverse'),
 ('287','11','UPLANDER','Uplander'),
 ('288','11','VENTUR','Venture'),
 ('289','11','VOLT','Volt'),
 ('290','11','CHEOTH','Other Chevrolet Models'),
 ('291','12','CHRYS200','200'),
 ('292','12','300','300'),
 ('293','12','CHRY300','300M'),
 ('294','12','ASPEN','Aspen'),
 ('295','12','CARAVAN','Caravan'),
 ('296','12','CIRRUS','Cirrus'),
 ('297','12','CONC','Concorde'),
 ('298','12','CHRYCONQ','Conquest'),
 ('299','12','CORDOBA','Cordoba'),
 ('300','12','CROSSFIRE','Crossfire'),
 ('301','12','ECLASS','E Class'),
 ('302','12','FIFTH','Fifth Avenue'),
 ('303','12','CHRYGRANDV','Grand Voyager'),
 ('304','12','IMPE','Imperial'),
 ('305','12','INTREPID','Intrepid'),
 ('306','12','CHRYLAS','Laser'),
 ('307','12','LEBA','LeBaron'),
 ('308','12','LHS','LHS'),
 ('309','12','CHRYNEON','Neon'),
 ('310','12','NY','New Yorker'),
 ('311','12','NEWPORT','Newport'),
 ('312','12','PACIFICA','Pacifica'),
 ('313','12','CHPROWLE','Prowler'),
 ('314','12','PTCRUIS','PT Cruiser'),
 ('315','12','CHRYSEB','Sebring'),
 ('316','12','CHRYTC','TC by Maserati'),
 ('317','12','TANDC','Town & Country'),
 ('318','12','VOYAGER','Voyager'),
 ('319','12','CHOTH','Other Chrysler Models'),
 ('320','13','LANOS','Lanos'),
 ('321','13','LEGANZA','Leganza'),
 ('322','13','NUBIRA','Nubira'),
 ('323','13','DAEOTH','Other Daewoo Models'),
 ('324','14','CHAR','Charade'),
 ('325','14','ROCKY','Rocky'),
 ('326','14','DAIHOTH','Other Daihatsu Models'),
 ('327','15','DAT200SX','200SX'),
 ('328','15','DAT210','210'),
 ('329','15','280Z','280ZX'),
 ('330','15','300ZX','300ZX'),
 ('331','15','310','310'),
 ('332','15','510','510'),
 ('333','15','720','720'),
 ('334','15','810','810'),
 ('335','15','DATMAX','Maxima'),
 ('336','15','DATPU','Pickup'),
 ('337','15','PUL','Pulsar'),
 ('338','15','DATSENT','Sentra'),
 ('339','15','STAN','Stanza'),
 ('340','15','DATOTH','Other Datsun Models'),
 ('341','16','DMC12','DMC-12'),
 ('342','17','400','400'),
 ('343','17','DOD600','600'),
 ('344','17','ARI','Aries'),
 ('345','17','AVENGR','Avenger'),
 ('346','17','CALIBER','Caliber'),
 ('347','17','DODCARA','Caravan'),
 ('348','17','CHALLENGER','Challenger'),
 ('349','17','DODCHAR','Charger'),
 ('350','17','DODCOLT','Colt'),
 ('351','17','DODCONQ','Conquest'),
 ('352','17','DODDW','D/W Truck'),
 ('353','17','DAKOTA','Dakota'),
 ('354','17','DODDART','Dart'),
 ('355','17','DAY','Daytona'),
 ('356','17','DIPLOMA','Diplomat'),
 ('357','17','DURANG','Durango'),
 ('358','17','DODDYNA','Dynasty'),
 ('359','17','GRANDCARAV','Grand Caravan'),
 ('360','17','INTRE','Intrepid'),
 ('361','17','JOURNEY','Journey'),
 ('362','17','LANCERDODG','Lancer'),
 ('363','17','MAGNUM','Magnum'),
 ('364','17','MIRADA','Mirada'),
 ('365','17','MONACO','Monaco'),
 ('366','17','DODNEON','Neon'),
 ('367','17','NITRO','Nitro'),
 ('368','17','OMNI','Omni'),
 ('369','17','RAIDER','Raider'),
 ('370','17','RAM1504WD','Ram 1500 Truck'),
 ('371','17','RAM25002WD','Ram 2500 Truck'),
 ('372','17','RAM3502WD','Ram 3500 Truck'),
 ('373','17','RAM4500','Ram 4500 Truck'),
 ('374','17','DODD50','Ram 50 Truck'),
 ('375','17','CV','RAM C/V'),
 ('376','17','RAMSRT10','Ram SRT-10'),
 ('377','17','RAMVANV8','Ram Van'),
 ('378','17','RAMWAGON','Ram Wagon'),
 ('379','17','RAMCGR','Ramcharger'),
 ('380','17','RAMPAGE','Rampage'),
 ('381','17','DODSHAD','Shadow'),
 ('382','17','DODSPIR','Spirit'),
 ('383','17','SPRINTER','Sprinter'),
 ('384','17','SRT4','SRT-4'),
 ('385','17','STREGIS','St. Regis'),
 ('386','17','STEAL','Stealth'),
 ('387','17','STRATU','Stratus'),
 ('388','17','VIPER','Viper'),
 ('389','17','DOOTH','Other Dodge Models'),
 ('390','18','EAGLEMED','Medallion'),
 ('391','18','EAGLEPREM','Premier'),
 ('392','18','SUMMIT','Summit'),
 ('393','18','TALON','Talon'),
 ('394','18','VISION','Vision'),
 ('395','18','EAGOTH','Other Eagle Models'),
 ('396','19','308GTB','308 GTB Quattrovalvole'),
 ('397','19','308TBI','308 GTBI'),
 ('398','19','308GTS','308 GTS Quattrovalvole'),
 ('399','19','308TSI','308 GTSI'),
 ('400','19','328GTB','328 GTB'),
 ('401','19','328GTS','328 GTS'),
 ('402','19','348GTB','348 GTB'),
 ('403','19','348GTS','348 GTS'),
 ('404','19','348SPI','348 Spider'),
 ('405','19','348TB','348 TB'),
 ('406','19','348TS','348 TS'),
 ('407','19','360','360'),
 ('408','19','456GT','456 GT'),
 ('409','19','456MGT','456M GT'),
 ('410','19','458ITALIA','458 Italia'),
 ('411','19','512BBI','512 BBi'),
 ('412','19','512M','512M'),
 ('413','19','512TR','512TR'),
 ('414','19','550M','550 Maranello'),
 ('415','19','575M','575M Maranello'),
 ('416','19','599GTB','599 GTB Fiorano'),
 ('417','19','599GTO','599 GTO'),
 ('418','19','612SCAGLIE','612 Scaglietti'),
 ('419','19','FERCALIF','California'),
 ('420','19','ENZO','Enzo'),
 ('421','19','F355','F355'),
 ('422','19','F40','F40'),
 ('423','19','F430','F430'),
 ('424','19','F50','F50'),
 ('425','19','FERFF','FF'),
 ('426','19','MOND','Mondial'),
 ('427','19','TEST','Testarossa'),
 ('428','19','UNAVAILFER','Other Ferrari Models'),
 ('429','20','2000','2000 Spider'),
 ('430','20','FIAT500','500'),
 ('431','20','BERTON','Bertone X1/9'),
 ('432','20','BRAVA','Brava'),
 ('433','20','PININ','Pininfarina Spider'),
 ('434','20','STRADA','Strada'),
 ('435','20','FIATX19','X1/9'),
 ('436','20','UNAVAILFIA','Other Fiat Models'),
 ('437','21','KARMA','Karma'),
 ('438','22','AERO','Aerostar'),
 ('439','22','ASPIRE','Aspire'),
 ('440','22','BRON','Bronco'),
 ('441','22','B2','Bronco II'),
 ('442','22','FOCMAX','C-MAX'),
 ('443','22','FORDCLUB','Club Wagon'),
 ('444','22','CONTOUR','Contour'),
 ('445','22','COURIER','Courier'),
 ('446','22','CROWNVIC','Crown Victoria'),
 ('447','22','E150ECON','E-150 and Econoline 150'),
 ('448','22','E250ECON','E-250 and Econoline 250'),
 ('449','22','E350ECON','E-350 and Econoline 350'),
 ('450','22','EDGE','Edge'),
 ('451','22','ESCAPE','Escape'),
 ('452','22','ESCO','Escort'),
 ('453','22','EXCURSION','Excursion'),
 ('454','22','EXP','EXP'),
 ('455','22','EXPEDI','Expedition'),
 ('456','22','EXPEDIEL','Expedition EL'),
 ('457','22','EXPLOR','Explorer'),
 ('458','22','SPORTTRAC','Explorer Sport Trac'),
 ('459','22','F100','F100'),
 ('460','22','F150PICKUP','F150'),
 ('461','22','F250','F250'),
 ('462','22','F350','F350'),
 ('463','22','F450','F450'),
 ('464','22','FAIRM','Fairmont'),
 ('465','22','FESTIV','Festiva'),
 ('466','22','FIESTA','Fiesta'),
 ('467','22','FIVEHUNDRE','Five Hundred'),
 ('468','22','FLEX','Flex'),
 ('469','22','FOCUS','Focus'),
 ('470','22','FREESTAR','Freestar'),
 ('471','22','FREESTYLE','Freestyle'),
 ('472','22','FUSION','Fusion'),
 ('473','22','GRANADA','Granada'),
 ('474','22','GT','GT'),
 ('475','22','LTD','LTD'),
 ('476','22','MUST','Mustang'),
 ('477','22','PROBE','Probe'),
 ('478','22','RANGER','Ranger'),
 ('479','22','TAURUS','Taurus'),
 ('480','22','TAURUSX','Taurus X'),
 ('481','22','TEMPO','Tempo'),
 ('482','22','TBIRD','Thunderbird'),
 ('483','22','TRANSCONN','Transit Connect'),
 ('484','22','WINDST','Windstar'),
 ('485','22','FORDZX2','ZX2 Escort'),
 ('486','22','FOOTH','Other Ford Models'),
 ('487','23','FRESPRINT','Sprinter'),
 ('488','24','GEOMETRO','Metro'),
 ('489','24','GEOPRIZM','Prizm'),
 ('490','24','SPECT','Spectrum'),
 ('491','24','STORM','Storm'),
 ('492','24','GEOTRACK','Tracker'),
 ('493','24','GEOOTH','Other Geo Models'),
 ('494','25','ACADIA','Acadia'),
 ('495','25','CABALLERO','Caballero'),
 ('496','25','CANYON','Canyon'),
 ('497','25','ENVOY','Envoy'),
 ('498','25','ENVOYXL','Envoy XL'),
 ('499','25','ENVOYXUV','Envoy XUV'),
 ('500','25','JIM','Jimmy'),
 ('501','25','RALLYWAG','Rally Wagon'),
 ('502','25','GMCS15','S15 Jimmy'),
 ('503','25','S15','S15 Pickup'),
 ('504','25','SAFARIGMC','Safari'),
 ('505','25','GMCSAVANA','Savana'),
 ('506','25','15SIPU4WD','Sierra C/K1500'),
 ('507','25','GMCC25PU','Sierra C/K2500'),
 ('508','25','GMC3500PU','Sierra C/K3500'),
 ('509','25','SONOMA','Sonoma'),
 ('510','25','SUB','Suburban'),
 ('511','25','GMCSYCLON','Syclone'),
 ('512','25','TERRAIN','Terrain'),
 ('513','25','TOPC4500','TopKick C4500'),
 ('514','25','TYPH','Typhoon'),
 ('515','25','GMCVANDUR','Vandura'),
 ('516','25','YUKON','Yukon'),
 ('517','25','YUKONXL','Yukon XL'),
 ('518','25','GMCOTH','Other GMC Models'),
 ('519','26','ACCORD','Accord'),
 ('520','26','CIVIC','Civic'),
 ('521','26','CRV','CR-V'),
 ('522','26','CRZ','CR-Z'),
 ('523','26','CRX','CRX'),
 ('524','26','CROSSTOUR_MODELS','Crosstour and Accord Crosstour Models (2)'),
 ('525','26','CROSSTOUR',' - Accord Crosstour'),
 ('526','26','HONCROSS',' - Crosstour'),
 ('527','26','HONDELSOL','Del Sol'),
 ('528','26','ELEMENT','Element'),
 ('529','26','FIT','Fit'),
 ('530','26','INSIGHT','Insight'),
 ('531','26','ODYSSEY','Odyssey'),
 ('532','26','PASSPO','Passport'),
 ('533','26','PILOT','Pilot'),
 ('534','26','PRE','Prelude'),
 ('535','26','RIDGELINE','Ridgeline'),
 ('536','26','S2000','S2000'),
 ('537','26','HONOTH','Other Honda Models'),
 ('538','27','HUMMER','H1'),
 ('539','27','H2','H2'),
 ('540','27','H3','H3'),
 ('541','27','H3T','H3T'),
 ('542','27','AMGOTH','Other Hummer Models'),
 ('543','28','ACCENT','Accent'),
 ('544','28','AZERA','Azera'),
 ('545','28','ELANTR','Elantra'),
 ('546','28','HYUELANCPE','Elantra Coupe'),
 ('547','28','ELANTOUR','Elantra Touring'),
 ('548','28','ENTOURAGE','Entourage'),
 ('549','28','EQUUS','Equus'),
 ('550','28','HYUEXCEL','Excel'),
 ('551','28','GENESIS','Genesis'),
 ('552','28','GENESISCPE','Genesis Coupe'),
 ('553','28','SANTAFE','Santa Fe'),
 ('554','28','SCOUPE','Scoupe'),
 ('555','28','SONATA','Sonata'),
 ('556','28','TIBURO','Tiburon'),
 ('557','28','TUCSON','Tucson'),
 ('558','28','VELOSTER','Veloster'),
 ('559','28','VERACRUZ','Veracruz'),
 ('560','28','XG300','XG300'),
 ('561','28','XG350','XG350'),
 ('562','28','HYUOTH','Other Hyundai Models'),
 ('563','29','EX_MODELS','EX Models (2)'),
 ('564','29','EX35',' - EX35'),
 ('565','29','EX37',' - EX37'),
 ('566','29','FX_MODELS','FX Models (4)'),
 ('567','29','FX35',' - FX35'),
 ('568','29','FX37',' - FX37'),
 ('569','29','FX45',' - FX45'),
 ('570','29','FX50',' - FX50'),
 ('571','29','G_MODELS','G Models (4)'),
 ('572','29','G20',' - G20'),
 ('573','29','G25',' - G25'),
 ('574','29','G35',' - G35'),
 ('575','29','G37',' - G37'),
 ('576','29','I_MODELS','I Models (2)'),
 ('577','29','I30',' - I30'),
 ('578','29','I35',' - I35'),
 ('579','29','J_MODELS','J Models (1)'),
 ('580','29','J30',' - J30'),
 ('581','29','JX_MODELS','JX Models (1)'),
 ('582','29','JX35',' - JX35'),
 ('583','29','M_MODELS','M Models (6)'),
 ('584','29','M30',' - M30'),
 ('585','29','M35',' - M35'),
 ('586','29','M35HYBRID',' - M35h'),
 ('587','29','M37',' - M37'),
 ('588','29','M45',' - M45'),
 ('589','29','M56',' - M56'),
 ('590','29','Q_MODELS','Q Models (1)'),
 ('591','29','Q45',' - Q45'),
 ('592','29','QX_MODELS','QX Models (2)'),
 ('593','29','QX4',' - QX4'),
 ('594','29','QX56',' - QX56'),
 ('595','29','INFOTH','Other Infiniti Models'),
 ('596','30','AMIGO','Amigo'),
 ('597','30','ASCENDER','Ascender'),
 ('598','30','AXIOM','Axiom'),
 ('599','30','HOMBRE','Hombre'),
 ('600','30','I280','i-280'),
 ('601','30','I290','i-290'),
 ('602','30','I350','i-350'),
 ('603','30','I370','i-370'),
 ('604','30','ISUMARK','I-Mark'),
 ('605','30','ISUIMP','Impulse'),
 ('606','30','OASIS','Oasis'),
 ('607','30','ISUPU','Pickup'),
 ('608','30','RODEO','Rodeo'),
 ('609','30','STYLUS','Stylus'),
 ('610','30','TROOP','Trooper'),
 ('611','30','TRP2','Trooper II'),
 ('612','30','VEHICROSS','VehiCROSS'),
 ('613','30','ISUOTH','Other Isuzu Models'),
 ('614','31','STYPE','S-Type'),
 ('615','31','XTYPE','X-Type'),
 ('616','31','XF','XF'),
 ('617','31','XJ_SERIES','XJ Series (10)'),
 ('618','31','JAGXJ12',' - XJ12'),
 ('619','31','JAGXJ6',' - XJ6'),
 ('620','31','JAGXJR',' - XJR'),
 ('621','31','JAGXJRS',' - XJR-S'),
 ('622','31','JAGXJS',' - XJS'),
 ('623','31','VANDEN',' - XJ Vanden Plas'),
 ('624','31','XJ',' - XJ'),
 ('625','31','XJ8',' - XJ8'),
 ('626','31','XJ8L',' - XJ8 L'),
 ('627','31','XJSPORT',' - XJ Sport'),
 ('628','31','XK_SERIES','XK Series (3)'),
 ('629','31','JAGXK8',' - XK8'),
 ('630','31','XK',' - XK'),
 ('631','31','XKR',' - XKR'),
 ('632','31','JAGOTH','Other Jaguar Models'),
 ('633','32','CHER','Cherokee'),
 ('634','32','JEEPCJ','CJ'),
 ('635','32','COMANC','Comanche'),
 ('636','32','COMMANDER','Commander'),
 ('637','32','COMPASS','Compass'),
 ('638','32','JEEPGRAND','Grand Cherokee'),
 ('639','32','GRWAG','Grand Wagoneer'),
 ('640','32','LIBERTY','Liberty'),
 ('641','32','PATRIOT','Patriot'),
 ('642','32','JEEPPU','Pickup'),
 ('643','32','SCRAMBLE','Scrambler'),
 ('644','32','WAGONE','Wagoneer'),
 ('645','32','WRANGLER','Wrangler'),
 ('646','32','JEOTH','Other Jeep Models'),
 ('647','33','AMANTI','Amanti'),
 ('648','33','BORREGO','Borrego'),
 ('649','33','FORTE','Forte'),
 ('650','33','FORTEKOUP','Forte Koup'),
 ('651','33','OPTIMA','Optima'),
 ('652','33','RIO','Rio'),
 ('653','33','RIO5','Rio5'),
 ('654','33','RONDO','Rondo'),
 ('655','33','SEDONA','Sedona'),
 ('656','33','SEPHIA','Sephia'),
 ('657','33','SORENTO','Sorento'),
 ('658','33','SOUL','Soul'),
 ('659','33','SPECTRA','Spectra'),
 ('660','33','SPECTRA5','Spectra5'),
 ('661','33','SPORTA','Sportage'),
 ('662','33','KIAOTH','Other Kia Models'),
 ('663','34','AVENT','Aventador'),
 ('664','34','COUNT','Countach'),
 ('665','34','DIABLO','Diablo'),
 ('666','34','GALLARDO','Gallardo'),
 ('667','34','JALPA','Jalpa'),
 ('668','34','LM002','LM002'),
 ('669','34','MURCIELAGO','Murcielago'),
 ('670','34','UNAVAILLAM','Other Lamborghini Models'),
 ('671','35','BETA','Beta'),
 ('672','35','ZAGATO','Zagato'),
 ('673','35','UNAVAILLAN','Other Lancia Models'),
 ('674','36','DEFEND','Defender'),
 ('675','36','DISCOV','Discovery'),
 ('676','36','FRELNDR','Freelander'),
 ('677','36','LR2','LR2'),
 ('678','36','LR3','LR3'),
 ('679','36','LR4','LR4'),
 ('680','36','RANGE','Range Rover'),
 ('681','36','EVOQUE','Range Rover Evoque'),
 ('682','36','RANGESPORT','Range Rover Sport'),
 ('683','36','ROVOTH','Other Land Rover Models'),
 ('684','37','CT_MODELS','CT Models (1)'),
 ('685','37','CT200H',' - CT 200h'),
 ('686','37','ES_MODELS','ES Models (5)'),
 ('687','37','ES250',' - ES 250'),
 ('688','37','ES300',' - ES 300'),
 ('689','37','ES300H',' - ES 300h'),
 ('690','37','ES330',' - ES 330'),
 ('691','37','ES350',' - ES 350'),
 ('692','37','GS_MODELS','GS Models (6)'),
 ('693','37','GS300',' - GS 300'),
 ('694','37','GS350',' - GS 350'),
 ('695','37','GS400',' - GS 400'),
 ('696','37','GS430',' - GS 430'),
 ('697','37','GS450H',' - GS 450h'),
 ('698','37','GS460',' - GS 460'),
 ('699','37','GX_MODELS','GX Models (2)'),
 ('700','37','GX460',' - GX 460'),
 ('701','37','GX470',' - GX 470'),
 ('702','37','HS_MODELS','HS Models (1)'),
 ('703','37','HS250H',' - HS 250h'),
 ('704','37','IS_MODELS','IS Models (6)'),
 ('705','37','IS250',' - IS 250'),
 ('706','37','IS250C',' - IS 250C'),
 ('707','37','IS300',' - IS 300'),
 ('708','37','IS350',' - IS 350'),
 ('709','37','IS350C',' - IS 350C'),
 ('710','37','ISF',' - IS F'),
 ('711','37','LEXLFA','LFA'),
 ('712','37','LS_MODELS','LS Models (4)'),
 ('713','37','LS400',' - LS 400'),
 ('714','37','LS430',' - LS 430'),
 ('715','37','LS460',' - LS 460'),
 ('716','37','LS600H',' - LS 600h'),
 ('717','37','LX_MODELS','LX Models (3)'),
 ('718','37','LX450',' - LX 450'),
 ('719','37','LX470',' - LX 470'),
 ('720','37','LX570',' - LX 570'),
 ('721','37','RX_MODELS','RX Models (5)'),
 ('722','37','RX300',' - RX 300'),
 ('723','37','RX330',' - RX 330'),
 ('724','37','RX350',' - RX 350'),
 ('725','37','RX400H',' - RX 400h'),
 ('726','37','RX450H',' - RX 450h'),
 ('727','37','SC_MODELS','SC Models (3)'),
 ('728','37','SC300',' - SC 300'),
 ('729','37','SC400',' - SC 400'),
 ('730','37','SC430',' - SC 430'),
 ('731','37','LEXOTH','Other Lexus Models'),
 ('732','38','AVIATOR','Aviator'),
 ('733','38','BLKWOOD','Blackwood'),
 ('734','38','CONT','Continental'),
 ('735','38','LSLINCOLN','LS'),
 ('736','38','MARKLT','Mark LT'),
 ('737','38','MARK6','Mark VI'),
 ('738','38','MARK7','Mark VII'),
 ('739','38','MARK8','Mark VIII'),
 ('740','38','MKS','MKS'),
 ('741','38','MKT','MKT'),
 ('742','38','MKX','MKX'),
 ('743','38','MKZ','MKZ'),
 ('744','38','NAVIGA','Navigator'),
 ('745','38','NAVIGAL','Navigator L'),
 ('746','38','LINCTC','Town Car'),
 ('747','38','ZEPHYR','Zephyr'),
 ('748','38','LINOTH','Other Lincoln Models'),
 ('749','39','ELAN','Elan'),
 ('750','39','LOTELISE','Elise'),
 ('751','39','ESPRIT','Esprit'),
 ('752','39','EVORA','Evora'),
 ('753','39','EXIGE','Exige'),
 ('754','39','UNAVAILLOT','Other Lotus Models'),
 ('755','40','430','430'),
 ('756','40','BITRBO','Biturbo'),
 ('757','40','COUPEMAS','Coupe'),
 ('758','40','GRANSPORT','GranSport'),
 ('759','40','GRANTURISM','GranTurismo'),
 ('760','40','QP','Quattroporte'),
 ('761','40','SPYDER','Spyder'),
 ('762','40','UNAVAILMAS','Other Maserati Models'),
 ('763','41','57MAYBACH','57'),
 ('764','41','62MAYBACH','62'),
 ('765','41','UNAVAILMAY','Other Maybach Models'),
 ('766','42','MAZDA323','323'),
 ('767','42','MAZDA626','626'),
 ('768','42','929','929'),
 ('769','42','B-SERIES','B-Series Pickup'),
 ('770','42','CX-5','CX-5'),
 ('771','42','CX-7','CX-7'),
 ('772','42','CX-9','CX-9'),
 ('773','42','GLC','GLC'),
 ('774','42','MAZDA2','MAZDA2'),
 ('775','42','MAZDA3','MAZDA3'),
 ('776','42','MAZDA5','MAZDA5'),
 ('777','42','MAZDA6','MAZDA6'),
 ('778','42','MAZDASPD3','MAZDASPEED3'),
 ('779','42','MAZDASPD6','MAZDASPEED6'),
 ('780','42','MIATA','Miata MX5'),
 ('781','42','MILL','Millenia'),
 ('782','42','MPV','MPV'),
 ('783','42','MX3','MX3'),
 ('784','42','MX6','MX6'),
 ('785','42','NAVAJO','Navajo'),
 ('786','42','PROTE','Protege'),
 ('787','42','PROTE5','Protege5'),
 ('788','42','RX7','RX-7'),
 ('789','42','RX8','RX-8'),
 ('790','42','TRIBUTE','Tribute'),
 ('791','42','MAZOTH','Other Mazda Models'),
 ('792','43','MP4','MP4-12C'),
 ('793','44','190_CLASS','190 Class (2)'),
 ('794','44','190D',' - 190D'),
 ('795','44','190E',' - 190E'),
 ('796','44','240_CLASS','240 Class (1)'),
 ('797','44','240D',' - 240D'),
 ('798','44','300_CLASS_E_CLASS','300 Class / E Class (6)'),
 ('799','44','300CD',' - 300CD'),
 ('800','44','300CE',' - 300CE'),
 ('801','44','300D',' - 300D'),
 ('802','44','300E',' - 300E'),
 ('803','44','300TD',' - 300TD'),
 ('804','44','300TE',' - 300TE'),
 ('805','44','C_CLASS','C Class (13)'),
 ('806','44','C220',' - C220'),
 ('807','44','C230',' - C230'),
 ('808','44','C240',' - C240'),
 ('809','44','C250',' - C250'),
 ('810','44','C280',' - C280'),
 ('811','44','C300',' - C300'),
 ('812','44','C320',' - C320'),
 ('813','44','C32AMG',' - C32 AMG'),
 ('814','44','C350',' - C350'),
 ('815','44','C36AMG',' - C36 AMG'),
 ('816','44','C43AMG',' - C43 AMG'),
 ('817','44','C55AMG',' - C55 AMG'),
 ('818','44','C63AMG',' - C63 AMG'),
 ('819','44','CL_CLASS','CL Class (6)'),
 ('820','44','CL500',' - CL500'),
 ('821','44','CL550',' - CL550'),
 ('822','44','CL55AMG',' - CL55 AMG'),
 ('823','44','CL600',' - CL600'),
 ('824','44','CL63AMG',' - CL63 AMG'),
 ('825','44','CL65AMG',' - CL65 AMG'),
 ('826','44','CLK_CLASS','CLK Class (7)'),
 ('827','44','CLK320',' - CLK320'),
 ('828','44','CLK350',' - CLK350'),
 ('829','44','CLK430',' - CLK430'),
 ('830','44','CLK500',' - CLK500'),
 ('831','44','CLK550',' - CLK550'),
 ('832','44','CLK55AMG',' - CLK55 AMG'),
 ('833','44','CLK63AMG',' - CLK63 AMG'),
 ('834','44','CLS_CLASS','CLS Class (4)'),
 ('835','44','CLS500',' - CLS500'),
 ('836','44','CLS550',' - CLS550'),
 ('837','44','CLS55AMG',' - CLS55 AMG'),
 ('838','44','CLS63AMG',' - CLS63 AMG'),
 ('839','44','E_CLASS','E Class (18)'),
 ('840','44','260E',' - 260E'),
 ('841','44','280CE',' - 280CE'),
 ('842','44','280E',' - 280E'),
 ('843','44','400E',' - 400E'),
 ('844','44','500E',' - 500E'),
 ('845','44','E300',' - E300'),
 ('846','44','E320',' - E320'),
 ('847','44','E320BLUE',' - E320 Bluetec'),
 ('848','44','E320CDI',' - E320 CDI'),
 ('849','44','E350',' - E350'),
 ('850','44','E350BLUE',' - E350 Bluetec'),
 ('851','44','E400',' - E400 Hybrid'),
 ('852','44','E420',' - E420'),
 ('853','44','E430',' - E430'),
 ('854','44','E500',' - E500'),
 ('855','44','E550',' - E550'),
 ('856','44','E55AMG',' - E55 AMG'),
 ('857','44','E63AMG',' - E63 AMG'),
 ('858','44','G_CLASS','G Class (4)'),
 ('859','44','G500',' - G500'),
 ('860','44','G550',' - G550'),
 ('861','44','G55AMG',' - G55 AMG'),
 ('862','44','G63AMG',' - G63 AMG'),
 ('863','44','GL_CLASS','GL Class (5)'),
 ('864','44','GL320BLUE',' - GL320 Bluetec'),
 ('865','44','GL320CDI',' - GL320 CDI'),
 ('866','44','GL350BLUE',' - GL350 Bluetec'),
 ('867','44','GL450',' - GL450'),
 ('868','44','GL550',' - GL550'),
 ('869','44','GLK_CLASS','GLK Class (1)'),
 ('870','44','GLK350',' - GLK350'),
 ('871','44','M_CLASS','M Class (11)'),
 ('872','44','ML320',' - ML320'),
 ('873','44','ML320BLUE',' - ML320 Bluetec'),
 ('874','44','ML320CDI',' - ML320 CDI'),
 ('875','44','ML350',' - ML350'),
 ('876','44','ML350BLUE',' - ML350 Bluetec'),
 ('877','44','ML430',' - ML430'),
 ('878','44','ML450HY',' - ML450 Hybrid'),
 ('879','44','ML500',' - ML500'),
 ('880','44','ML550',' - ML550'),
 ('881','44','ML55AMG',' - ML55 AMG'),
 ('882','44','ML63AMG',' - ML63 AMG'),
 ('883','44','R_CLASS','R Class (6)'),
 ('884','44','R320BLUE',' - R320 Bluetec'),
 ('885','44','R320CDI',' - R320 CDI'),
 ('886','44','R350',' - R350'),
 ('887','44','R350BLUE',' - R350 Bluetec'),
 ('888','44','R500',' - R500'),
 ('889','44','R63AMG',' - R63 AMG'),
 ('890','44','S_CLASS','S Class (30)'),
 ('891','44','300SD',' - 300SD'),
 ('892','44','300SDL',' - 300SDL'),
 ('893','44','300SE',' - 300SE'),
 ('894','44','300SEL',' - 300SEL'),
 ('895','44','350SD',' - 350SD'),
 ('896','44','350SDL',' - 350SDL'),
 ('897','44','380SE',' - 380SE'),
 ('898','44','380SEC',' - 380SEC'),
 ('899','44','380SEL',' - 380SEL'),
 ('900','44','400SE',' - 400SE'),
 ('901','44','400SEL',' - 400SEL'),
 ('902','44','420SEL',' - 420SEL'),
 ('903','44','500SEC',' - 500SEC'),
 ('904','44','500SEL',' - 500SEL'),
 ('905','44','560SEC',' - 560SEC'),
 ('906','44','560SEL',' - 560SEL'),
 ('907','44','600SEC',' - 600SEC'),
 ('908','44','600SEL',' - 600SEL'),
 ('909','44','S320',' - S320'),
 ('910','44','S350',' - S350'),
 ('911','44','S350BLUE',' - S350 Bluetec'),
 ('912','44','S400HY',' - S400 Hybrid'),
 ('913','44','S420',' - S420'),
 ('914','44','S430',' - S430'),
 ('915','44','S500',' - S500'),
 ('916','44','S550',' - S550'),
 ('917','44','S55AMG',' - S55 AMG'),
 ('918','44','S600',' - S600'),
 ('919','44','S63AMG',' - S63 AMG'),
 ('920','44','S65AMG',' - S65 AMG'),
 ('921','44','SL_CLASS','SL Class (13)'),
 ('922','44','300SL',' - 300SL'),
 ('923','44','380SL',' - 380SL'),
 ('924','44','380SLC',' - 380SLC'),
 ('925','44','500SL',' - 500SL'),
 ('926','44','560SL',' - 560SL'),
 ('927','44','600SL',' - 600SL'),
 ('928','44','SL320',' - SL320'),
 ('929','44','SL500',' - SL500'),
 ('930','44','SL550',' - SL550'),
 ('931','44','SL55AMG',' - SL55 AMG'),
 ('932','44','SL600',' - SL600'),
 ('933','44','SL63AMG',' - SL63 AMG'),
 ('934','44','SL65AMG',' - SL65 AMG'),
 ('935','44','SLK_CLASS','SLK Class (8)'),
 ('936','44','SLK230',' - SLK230'),
 ('937','44','SLK250',' - SLK250'),
 ('938','44','SLK280',' - SLK280'),
 ('939','44','SLK300',' - SLK300'),
 ('940','44','SLK320',' - SLK320'),
 ('941','44','SLK32AMG',' - SLK32 AMG'),
 ('942','44','SLK350',' - SLK350'),
 ('943','44','SLK55AMG',' - SLK55 AMG'),
 ('944','44','SLR_CLASS','SLR Class (1)'),
 ('945','44','SLR',' - SLR'),
 ('946','44','SLS_CLASS','SLS Class (1)'),
 ('947','44','SLSAMG',' - SLS AMG'),
 ('948','44','SPRINTER_CLASS','Sprinter Class (1)'),
 ('949','44','MBSPRINTER',' - Sprinter'),
 ('950','44','MBOTH','Other Mercedes-Benz Models'),
 ('951','45','CAPRI','Capri'),
 ('952','45','COUGAR','Cougar'),
 ('953','45','MERCGRAND','Grand Marquis'),
 ('954','45','LYNX','Lynx'),
 ('955','45','MARAUDER','Marauder'),
 ('956','45','MARINER','Mariner'),
 ('957','45','MARQ','Marquis'),
 ('958','45','MILAN','Milan'),
 ('959','45','MONTEGO','Montego'),
 ('960','45','MONTEREY','Monterey'),
 ('961','45','MOUNTA','Mountaineer'),
 ('962','45','MYSTIQ','Mystique'),
 ('963','45','SABLE','Sable'),
 ('964','45','TOPAZ','Topaz'),
 ('965','45','TRACER','Tracer'),
 ('966','45','VILLA','Villager'),
 ('967','45','MERCZEP','Zephyr'),
 ('968','45','MEOTH','Other Mercury Models'),
 ('969','46','SCORP','Scorpio'),
 ('970','46','XR4TI','XR4Ti'),
 ('971','46','MEROTH','Other Merkur Models'),
 ('972','47','COOPRCLUB_MODELS','Cooper Clubman Models (2)'),
 ('973','47','COOPERCLUB',' - Cooper Clubman'),
 ('974','47','COOPRCLUBS',' - Cooper S Clubman'),
 ('975','47','COOPCOUNTRY_MODELS','Cooper Countryman Models (2)'),
 ('976','47','COUNTRYMAN',' - Cooper Countryman'),
 ('977','47','COUNTRYMNS',' - Cooper S Countryman'),
 ('978','47','COOPCOUP_MODELS','Cooper Coupe Models (2)'),
 ('979','47','MINICOUPE',' - Cooper Coupe'),
 ('980','47','MINISCOUPE',' - Cooper S Coupe'),
 ('981','47','COOPER_MODELS','Cooper Models (2)'),
 ('982','47','COOPER',' - Cooper'),
 ('983','47','COOPERS',' - Cooper S'),
 ('984','47','COOPRROAD_MODELS','Cooper Roadster Models (2)'),
 ('985','47','COOPERROAD',' - Cooper Roadster'),
 ('986','47','COOPERSRD',' - Cooper S Roadster'),
 ('987','48','3000GT','3000GT'),
 ('988','48','CORD','Cordia'),
 ('989','48','DIAMAN','Diamante'),
 ('990','48','ECLIP','Eclipse'),
 ('991','48','ENDEAVOR','Endeavor'),
 ('992','48','MITEXP','Expo'),
 ('993','48','GALANT','Galant'),
 ('994','48','MITI','i'),
 ('995','48','LANCERMITS','Lancer'),
 ('996','48','LANCEREVO','Lancer Evolution'),
 ('997','48','MITPU','Mighty Max'),
 ('998','48','MIRAGE','Mirage'),
 ('999','48','MONT','Montero'),
 ('1000','48','MONTSPORT','Montero Sport'),
 ('1001','48','OUTLANDER','Outlander'),
 ('1002','48','OUTLANDSPT','Outlander Sport'),
 ('1003','48','PRECIS','Precis'),
 ('1004','48','RAIDERMITS','Raider'),
 ('1005','48','SIGMA','Sigma'),
 ('1006','48','MITSTAR','Starion'),
 ('1007','48','TRED','Tredia'),
 ('1008','48','MITVAN','Van'),
 ('1009','48','MITOTH','Other Mitsubishi Models'),
 ('1010','49','NIS200SX','200SX'),
 ('1011','49','240SX','240SX'),
 ('1012','49','300ZXTURBO','300ZX'),
 ('1013','49','350Z','350Z'),
 ('1014','49','370Z','370Z'),
 ('1015','49','ALTIMA','Altima'),
 ('1016','49','PATHARMADA','Armada'),
 ('1017','49','AXXESS','Axxess'),
 ('1018','49','CUBE','Cube'),
 ('1019','49','FRONTI','Frontier'),
 ('1020','49','GT-R','GT-R'),
 ('1021','49','JUKE','Juke'),
 ('1022','49','LEAF','Leaf'),
 ('1023','49','MAX','Maxima'),
 ('1024','49','MURANO','Murano'),
 ('1025','49','MURANOCROS','Murano CrossCabriolet'),
 ('1026','49','NV','NV'),
 ('1027','49','NX','NX'),
 ('1028','49','PATH','Pathfinder'),
 ('1029','49','NISPU','Pickup'),
 ('1030','49','PULSAR','Pulsar'),
 ('1031','49','QUEST','Quest'),
 ('1032','49','ROGUE','Rogue'),
 ('1033','49','SENTRA','Sentra'),
 ('1034','49','STANZA','Stanza'),
 ('1035','49','TITAN','Titan'),
 ('1036','49','NISVAN','Van'),
 ('1037','49','VERSA','Versa'),
 ('1038','49','XTERRA','Xterra'),
 ('1039','49','NISSOTH','Other Nissan Models'),
 ('1040','50','88','88'),
 ('1041','50','ACHIEV','Achieva'),
 ('1042','50','ALERO','Alero'),
 ('1043','50','AURORA','Aurora'),
 ('1044','50','BRAV','Bravada'),
 ('1045','50','CUCR','Custom Cruiser'),
 ('1046','50','OLDCUS','Cutlass'),
 ('1047','50','OLDCALAIS','Cutlass Calais'),
 ('1048','50','CIERA','Cutlass Ciera'),
 ('1049','50','CSUPR','Cutlass Supreme'),
 ('1050','50','OLDSFIR','Firenza'),
 ('1051','50','INTRIG','Intrigue'),
 ('1052','50','98','Ninety-Eight'),
 ('1053','50','OMEG','Omega'),
 ('1054','50','REGEN','Regency'),
 ('1055','50','SILHO','Silhouette'),
 ('1056','50','TORO','Toronado'),
 ('1057','50','OLDOTH','Other Oldsmobile Models'),
 ('1058','51','405','405'),
 ('1059','51','504','504'),
 ('1060','51','505','505'),
 ('1061','51','604','604'),
 ('1062','51','UNAVAILPEU','Other Peugeot Models'),
 ('1063','52','ACC','Acclaim'),
 ('1064','52','ARROW','Arrow'),
 ('1065','52','BREEZE','Breeze'),
 ('1066','52','CARAVE','Caravelle'),
 ('1067','52','CHAMP','Champ'),
 ('1068','52','COLT','Colt'),
 ('1069','52','PLYMCONQ','Conquest'),
 ('1070','52','GRANFURY','Gran Fury'),
 ('1071','52','PLYMGRANV','Grand Voyager'),
 ('1072','52','HORI','Horizon'),
 ('1073','52','LASER','Laser'),
 ('1074','52','NEON','Neon'),
 ('1075','52','PROWLE','Prowler'),
 ('1076','52','RELI','Reliant'),
 ('1077','52','SAPPOROPLY','Sapporo'),
 ('1078','52','SCAMP','Scamp'),
 ('1079','52','SUNDAN','Sundance'),
 ('1080','52','TRAILDUST','Trailduster'),
 ('1081','52','VOYA','Voyager'),
 ('1082','52','PLYOTH','Other Plymouth Models'),
 ('1083','53','T-1000','1000'),
 ('1084','53','6000','6000'),
 ('1085','53','AZTEK','Aztek'),
 ('1086','53','BON','Bonneville'),
 ('1087','53','CATALINA','Catalina'),
 ('1088','53','FIERO','Fiero'),
 ('1089','53','FBIRD','Firebird'),
 ('1090','53','G3','G3'),
 ('1091','53','G5','G5'),
 ('1092','53','G6','G6'),
 ('1093','53','G8','G8'),
 ('1094','53','GRNDAM','Grand Am'),
 ('1095','53','GP','Grand Prix'),
 ('1096','53','GTO','GTO'),
 ('1097','53','J2000','J2000'),
 ('1098','53','LEMANS','Le Mans'),
 ('1099','53','MONTANA','Montana'),
 ('1100','53','PARISI','Parisienne'),
 ('1101','53','PHOENIX','Phoenix'),
 ('1102','53','SAFARIPONT','Safari'),
 ('1103','53','SOLSTICE','Solstice'),
 ('1104','53','SUNBIR','Sunbird'),
 ('1105','53','SUNFIR','Sunfire'),
 ('1106','53','TORRENT','Torrent'),
 ('1107','53','TS','Trans Sport'),
 ('1108','53','VIBE','Vibe'),
 ('1109','53','PONOTH','Other Pontiac Models'),
 ('1110','54','911','911'),
 ('1111','54','924','924'),
 ('1112','54','928','928'),
 ('1113','54','944','944'),
 ('1114','54','968','968'),
 ('1115','54','BOXSTE','Boxster'),
 ('1116','54','CARRERAGT','Carrera GT'),
 ('1117','54','CAYENNE','Cayenne'),
 ('1118','54','CAYMAN','Cayman'),
 ('1119','54','PANAMERA','Panamera'),
 ('1120','54','POROTH','Other Porsche Models'),
 ('1121','55','RAM1504WD','1500'),
 ('1122','55','RAM25002WD','2500'),
 ('1123','55','RAM3502WD','3500'),
 ('1124','55','RAM4500','4500'),
 ('1125','56','18I','18i'),
 ('1126','56','FU','Fuego'),
 ('1127','56','LECAR','Le Car'),
 ('1128','56','R18','R18'),
 ('1129','56','RENSPORT','Sportwagon'),
 ('1130','56','UNAVAILREN','Other Renault Models'),
 ('1131','57','CAMAR','Camargue'),
 ('1132','57','CORN','Corniche'),
 ('1133','57','GHOST','Ghost'),
 ('1134','57','PARKWARD','Park Ward'),
 ('1135','57','PHANT','Phantom'),
 ('1136','57','DAWN','Silver Dawn'),
 ('1137','57','SILSERAPH','Silver Seraph'),
 ('1138','57','RRSPIR','Silver Spirit'),
 ('1139','57','SPUR','Silver Spur'),
 ('1140','57','UNAVAILRR','Other Rolls-Royce Models'),
 ('1141','58','9-2X','9-2X'),
 ('1142','58','9-3','9-3'),
 ('1143','58','9-4X','9-4X'),
 ('1144','58','9-5','9-5'),
 ('1145','58','97X','9-7X'),
 ('1146','58','900','900'),
 ('1147','58','9000','9000'),
 ('1148','58','SAOTH','Other Saab Models'),
 ('1149','59','ASTRA','Astra'),
 ('1150','59','AURA','Aura'),
 ('1151','59','ION','ION'),
 ('1152','59','L_SERIES','L Series (3)'),
 ('1153','59','L100',' - L100'),
 ('1154','59','L200',' - L200'),
 ('1155','59','L300',' - L300'),
 ('1156','59','LSSATURN','LS'),
 ('1157','59','LW_SERIES','LW Series (4)'),
 ('1158','59','LW',' - LW1'),
 ('1159','59','LW2',' - LW2'),
 ('1160','59','LW200',' - LW200'),
 ('1161','59','LW300',' - LW300'),
 ('1162','59','OUTLOOK','Outlook'),
 ('1163','59','RELAY','Relay'),
 ('1164','59','SC_SERIES','SC Series (2)'),
 ('1165','59','SC1',' - SC1'),
 ('1166','59','SC2',' - SC2'),
 ('1167','59','SKY','Sky'),
 ('1168','59','SL_SERIES','SL Series (3)'),
 ('1169','59','SL',' - SL'),
 ('1170','59','SL1',' - SL1'),
 ('1171','59','SL2',' - SL2'),
 ('1172','59','SW_SERIES','SW Series (2)'),
 ('1173','59','SW1',' - SW1'),
 ('1174','59','SW2',' - SW2'),
 ('1175','59','VUE','Vue'),
 ('1176','59','SATOTH','Other Saturn Models'),
 ('1177','60','SCIFRS','FR-S'),
 ('1178','60','IQ','iQ'),
 ('1179','60','TC','tC'),
 ('1180','60','XA','xA'),
 ('1181','60','XB','xB'),
 ('1182','60','XD','xD'),
 ('1183','61','FORTWO','fortwo'),
 ('1184','61','SMOTH','Other smart Models'),
 ('1185','62','SRTVIPER','Viper'),
 ('1186','63','825','825'),
 ('1187','63','827','827'),
 ('1188','63','UNAVAILSTE','Other Sterling Models'),
 ('1189','64','BAJA','Baja'),
 ('1190','64','BRAT','Brat'),
 ('1191','64','SUBBRZ','BRZ'),
 ('1192','64','FOREST','Forester'),
 ('1193','64','IMPREZ','Impreza'),
 ('1194','64','IMPWRX','Impreza WRX'),
 ('1195','64','JUSTY','Justy'),
 ('1196','64','SUBL','L Series'),
 ('1197','64','LEGACY','Legacy'),
 ('1198','64','LOYALE','Loyale'),
 ('1199','64','SUBOUTBK','Outback'),
 ('1200','64','SVX','SVX'),
 ('1201','64','B9TRIBECA','Tribeca'),
 ('1202','64','XT','XT'),
 ('1203','64','XVCRSSTREK','XV Crosstrek'),
 ('1204','64','SUBOTH','Other Subaru Models'),
 ('1205','65','AERIO','Aerio'),
 ('1206','65','EQUATOR','Equator'),
 ('1207','65','ESTEEM','Esteem'),
 ('1208','65','FORENZA','Forenza'),
 ('1209','65','GRANDV','Grand Vitara'),
 ('1210','65','KIZASHI','Kizashi'),
 ('1211','65','RENO','Reno'),
 ('1212','65','SAMUR','Samurai'),
 ('1213','65','SIDE','Sidekick'),
 ('1214','65','SWIFT','Swift'),
 ('1215','65','SX4','SX4'),
 ('1216','65','VERONA','Verona'),
 ('1217','65','VITARA','Vitara'),
 ('1218','65','X90','X-90'),
 ('1219','65','XL7','XL7'),
 ('1220','65','SUZOTH','Other Suzuki Models'),
 ('1221','66','ROADSTER','Roadster'),
 ('1222','67','4RUN','4Runner'),
 ('1223','67','AVALON','Avalon'),
 ('1224','67','CAMRY','Camry'),
 ('1225','67','CELICA','Celica'),
 ('1226','67','COROL','Corolla'),
 ('1227','67','CORONA','Corona'),
 ('1228','67','CRESS','Cressida'),
 ('1229','67','ECHO','Echo'),
 ('1230','67','FJCRUIS','FJ Cruiser'),
 ('1231','67','HIGHLANDER','Highlander'),
 ('1232','67','LC','Land Cruiser'),
 ('1233','67','MATRIX','Matrix'),
 ('1234','67','MR2','MR2'),
 ('1235','67','MR2SPYDR','MR2 Spyder'),
 ('1236','67','PASEO','Paseo'),
 ('1237','67','PICKUP','Pickup'),
 ('1238','67','PREVIA','Previa'),
 ('1239','67','PRIUS','Prius'),
 ('1240','67','PRIUSC','Prius C'),
 ('1241','67','PRIUSV','Prius V'),
 ('1242','67','RAV4','RAV4'),
 ('1243','67','SEQUOIA','Sequoia'),
 ('1244','67','SIENNA','Sienna'),
 ('1245','67','SOLARA','Solara'),
 ('1246','67','STARLET','Starlet'),
 ('1247','67','SUPRA','Supra'),
 ('1248','67','T100','T100'),
 ('1249','67','TACOMA','Tacoma'),
 ('1250','67','TERCEL','Tercel'),
 ('1251','67','TUNDRA','Tundra'),
 ('1252','67','TOYVAN','Van'),
 ('1253','67','VENZA','Venza'),
 ('1254','67','YARIS','Yaris'),
 ('1255','67','TOYOTH','Other Toyota Models'),
 ('1256','68','TR7','TR7'),
 ('1257','68','TR8','TR8'),
 ('1258','68','TRIOTH','Other Triumph Models'),
 ('1259','69','BEETLE','Beetle'),
 ('1260','69','VOLKSCAB','Cabrio'),
 ('1261','69','CAB','Cabriolet'),
 ('1262','69','CC','CC'),
 ('1263','69','CORR','Corrado'),
 ('1264','69','DASHER','Dasher'),
 ('1265','69','EOS','Eos'),
 ('1266','69','EUROVAN','Eurovan'),
 ('1267','69','VOLKSFOX','Fox'),
 ('1268','69','GLI','GLI'),
 ('1269','69','GOLFR','Golf R'),
 ('1270','69','GTI','GTI'),
 ('1271','69','GOLFANDRABBITMODELS','Golf and Rabbit Models (2)'),
 ('1272','69','GOLF',' - Golf'),
 ('1273','69','RABBIT',' - Rabbit'),
 ('1274','69','JET','Jetta'),
 ('1275','69','PASS','Passat'),
 ('1276','69','PHAETON','Phaeton'),
 ('1277','69','RABBITPU','Pickup'),
 ('1278','69','QUAN','Quantum'),
 ('1279','69','R32','R32'),
 ('1280','69','ROUTAN','Routan'),
 ('1281','69','SCIR','Scirocco'),
 ('1282','69','TIGUAN','Tiguan'),
 ('1283','69','TOUAREG','Touareg'),
 ('1284','69','VANAG','Vanagon'),
 ('1285','69','VWOTH','Other Volkswagen Models'),
 ('1286','70','240','240'),
 ('1287','70','260','260'),
 ('1288','70','740','740'),
 ('1289','70','760','760'),
 ('1290','70','780','780'),
 ('1291','70','850','850'),
 ('1292','70','940','940'),
 ('1293','70','960','960'),
 ('1294','70','C30','C30'),
 ('1295','70','C70','C70'),
 ('1296','70','S40','S40'),
 ('1297','70','S60','S60'),
 ('1298','70','S70','S70'),
 ('1299','70','S80','S80'),
 ('1300','70','S90','S90'),
 ('1301','70','V40','V40'),
 ('1302','70','V50','V50'),
 ('1303','70','V70','V70'),
 ('1304','70','V90','V90'),
 ('1305','70','XC60','XC60'),
 ('1306','70','XC','XC70'),
 ('1307','70','XC90','XC90'),
 ('1308','70','VOLOTH','Other Volvo Models'),
 ('1309','71','GV','GV'),
 ('1310','71','GVC','GVC'),
 ('1311','71','GVL','GVL'),
 ('1312','71','GVS','GVS'),
 ('1313','71','GVX','GVX'),
 ('1314','71','YUOTH','Other Yugo Models');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_categories` 
-- 

INSERT INTO `itr_categories` (`id`, `categoryname`, `parentid`) VALUES ('20','Banking','0'),
 ('21','Insurance','0'),
 ('22','Mortgages','0'),
 ('23','Credit Cards','0'),
 ('24','Utilities','0'),
 ('25','Loans','0'),
 ('26','Business','0');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_commissions` 
-- 

INSERT INTO `itr_commissions` (`id`, `insurers_id`, `products_id`, `percentage`, `collection_means`) VALUES ('9','13','1','10','1'),
 ('10','13','5','8','1'),
 ('11','13','7','17','1'),
 ('12','13','8','15','1'),
 ('13','14','1','7.5','2'),
 ('14','14','5','10','2'),
 ('15','14','7','5','2'),
 ('16','14','8','0','2');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_companies` 
-- 

INSERT INTO `itr_companies` (`id`, `name`, `email_address`, `postal_address`, `telephone`, `physical_address`) VALUES ('1','Jubilee Insurance Company of Kenya','info@jubilee.co.ke','','','0'),
 ('2','Jubilee Insurance Company of Kenya','info@jubilee.co.ke','','','0'),
 ('3','Jubilee Insurance Company of Kenya','info@jubilee.co.ke','','','0'),
 ('4','Jubilee Insurance Company of Kenya','info@jubilee.co.ke','','','0');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_countries` 
-- 

INSERT INTO `itr_countries` (`country_id`, `country_name`, `status`) VALUES ('1','Afghanistan','1'),
 ('2','Albania','1'),
 ('3','Algeria','1'),
 ('4','American Samoa','1'),
 ('5','Andorra','1'),
 ('6','Angola','1'),
 ('7','Anguilla','1'),
 ('8','Antarctica','1'),
 ('9','Antigua and Barbuda','1'),
 ('10','Argentina','1'),
 ('11','Armenia','1'),
 ('12','Aruba','1'),
 ('13','Australia','1'),
 ('14','Austria','1'),
 ('15','Azerbaijan','1'),
 ('16','Bahamas','1'),
 ('17','Bahrain','1'),
 ('18','Bangladesh','1'),
 ('19','Barbados','1'),
 ('20','Belarus','1'),
 ('21','Belgium','1'),
 ('22','Belize','1'),
 ('23','Benin','1'),
 ('24','Bermuda','1'),
 ('25','Bhutan','1'),
 ('26','Bolivia','1'),
 ('27','Bosnia and Herzegowina','1'),
 ('28','Botswana','1'),
 ('29','Bouvet Island','1'),
 ('30','Brazil','1'),
 ('31','British Indian Ocean Territory','1'),
 ('32','Brunei Darussalam','1'),
 ('33','Bulgaria','1'),
 ('34','Burkina Faso','1'),
 ('35','Burundi','1'),
 ('36','Cambodia','1'),
 ('37','Cameroon','1'),
 ('38','Canada','1'),
 ('39','Cape Verde','1'),
 ('40','Cayman Islands','1'),
 ('41','Central African Republic','1'),
 ('42','Chad','1'),
 ('43','Chile','1'),
 ('44','China','1'),
 ('45','Christmas Island','1'),
 ('46','Cocos (Keeling) Islands','1'),
 ('47','Colombia','1'),
 ('48','Comoros','1'),
 ('49','Congo','1'),
 ('50','Congo, the Democratic Republic of the','1'),
 ('51','Cook Islands','1'),
 ('52','Costa Rica','1'),
 ('53','Cote d\'Ivoire','1'),
 ('54','Croatia (Hrvatska)','1'),
 ('55','Cuba','1'),
 ('56','Cyprus','1'),
 ('57','Czech Republic','1'),
 ('58','Denmark','1'),
 ('59','Djibouti','1'),
 ('60','Dominica','1'),
 ('61','Dominican Republic','1'),
 ('62','East Timor','1'),
 ('63','Ecuador','1'),
 ('64','Egypt','1'),
 ('65','El Salvador','1'),
 ('66','Equatorial Guinea','1'),
 ('67','Eritrea','1'),
 ('68','Estonia','1'),
 ('69','Ethiopia','1'),
 ('70','Falkland Islands (Malvinas)','1'),
 ('71','Faroe Islands','1'),
 ('72','Fiji','1'),
 ('73','Finland','1'),
 ('74','France','1'),
 ('75','France Metropolitan','1'),
 ('76','French Guiana','1'),
 ('77','French Polynesia','1'),
 ('78','French Southern Territories','1'),
 ('79','Gabon','1'),
 ('80','Gambia','1'),
 ('81','Georgia','1'),
 ('82','Germany','1'),
 ('83','Ghana','1'),
 ('84','Gibraltar','1'),
 ('85','Greece','1'),
 ('86','Greenland','1'),
 ('87','Grenada','1'),
 ('88','Guadeloupe','1'),
 ('89','Guam','1'),
 ('90','Guatemala','1'),
 ('91','Guinea','1'),
 ('92','Guinea-Bissau','1'),
 ('93','Guyana','1'),
 ('94','Haiti','1'),
 ('95','Heard and Mc Donald Islands','1'),
 ('96','Holy See (Vatican City State)','1'),
 ('97','Honduras','1'),
 ('98','Hong Kong','1'),
 ('99','Hungary','1'),
 ('100','Iceland','1'),
 ('101','India','1'),
 ('102','Indonesia','1'),
 ('103','Iran (Islamic Republic of)','1'),
 ('104','Iraq','1'),
 ('105','Ireland','1'),
 ('106','Israel','1'),
 ('107','Italy','1'),
 ('108','Jamaica','1'),
 ('109','Japan','1'),
 ('110','Jordan','1'),
 ('111','Kazakhstan','1'),
 ('112','Kenya','1'),
 ('113','Kiribati','1'),
 ('114','Korea, Democratic People\'s Republic of','1'),
 ('115','Korea, Republic of','1'),
 ('116','Kuwait','1'),
 ('117','Kyrgyzstan','1'),
 ('118','Lao, People\'s Democratic Republic','1'),
 ('119','Latvia','1'),
 ('120','Lebanon','1'),
 ('121','Lesotho','1'),
 ('122','Liberia','1'),
 ('123','Libyan Arab Jamahiriya','1'),
 ('124','Liechtenstein','1'),
 ('125','Lithuania','1'),
 ('126','Luxembourg','1'),
 ('127','Macau','1'),
 ('128','Macedonia, The Former Yugoslav Republic of','1'),
 ('129','Madagascar','1'),
 ('130','Malawi','1'),
 ('131','Malaysia','1'),
 ('132','Maldives','1'),
 ('133','Mali','1'),
 ('134','Malta','1'),
 ('135','Marshall Islands','1'),
 ('136','Martinique','1'),
 ('137','Mauritania','1'),
 ('138','Mauritius','1'),
 ('139','Mayotte','1'),
 ('140','Mexico','1'),
 ('141','Micronesia, Federated States of','1'),
 ('142','Moldova, Republic of','1'),
 ('143','Monaco','1'),
 ('144','Mongolia','1'),
 ('145','Montserrat','1'),
 ('146','Morocco','1'),
 ('147','Mozambique','1'),
 ('148','Myanmar','1'),
 ('149','Namibia','1'),
 ('150','Nauru','1'),
 ('151','Nepal','1'),
 ('152','Netherlands','1');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_customer_entity_data` 
-- 

INSERT INTO `itr_customer_entity_data` (`id`, `customers_id`, `entities_id`, `entity_values`) VALUES ('1','5','2','{"Registration_no":"KERSDGA","Chassis_No":"ASD","Engine_No":"ASD","Make":"ASD","Model":"beast","Type_of_body":"ASDDA","Seating_Capacity":"44","Year_of_Manufacture":"2006","Estimated_Value":"9000000"}'),
 ('3','8','2','{"Registration_no":"KNS5747","Chassis_No":"ASD","Engine_No":"ASD","Make":"ASD","Model":"beast","Type_of_body":"ASDDA","Seating_Capacity":"44","Year_of_Manufacture":"2006","Estimated_Value":"8000000"}'),
 ('4','9','2','{"Registration_no":"KBY564J","Chassis_No":"343242","Engine_No":"34534","Make":"Toyota","Model":"Harrier","Type_of_body":"SUV","Seating_Capacity":"44","Year_of_Manufacture":"2006","Estimated_Value":"1000000"}'),
 ('5','9','2','{"Registration_no":"QYRH36673","Chassis_No":"ASD4234","Engine_No":"ASD","Make":"ASD","Model":"beast","Type_of_body":"ASDDA","Seating_Capacity":"44","Year_of_Manufacture":"2006","Estimated_Value":"6000000"}'),
 ('6','9','2','{"Registration_no":"KERSDGA2154","Chassis_No":"ASD21454","Engine_No":"ASD","Make":"ASD","Model":"beast","Type_of_body":"ASDDA","Seating_Capacity":"44","Year_of_Manufacture":"2006","Estimated_Value":"9000000"}'),
 ('7','10','5','{"refused_cover":"no","cover_refused_particulars":"","decline_cover":"no","cover_decline_particulars":"","demand_increased_rate":"no","increased_rate_particulars":"","imposed_special_terms":"no","special_terms_particulars":"","hobbies_injury_liable":"no","injury_liable_particulars":"","ever_made_claims":"no","made_claims_particulars":"","step":"2"}'),
 ('8','9','2','{"Registration_no":"KERSDGA455","Chassis_No":"ASD","Engine_No":"ASD","Make":"ASD","Model":"beast","Type_of_body":"ASDDA","Seating_Capacity":"44","Year_of_Manufacture":"2006","Estimated_Value":"9000000"}'),
 ('9','1','2','{"Registration_no":"KAT400F","Chassis_No":"34429089","Engine_No":"023840239","Make":"BMW","Model":"Sedan","Type_of_body":"BMW Compact","Seating_Capacity":"4","Year_of_Manufacture":"2016","Estimated_Value":"1200000"}'),
 ('10','0','2','{"Registration_no":"KBA345R","Chassis_No":"83024028","Engine_No":"80234900","Make":"BMW","Model":"Sedan","Type_of_body":"Acute Sedan","Seating_Capacity":"5","Year_of_Manufacture":"2016","Estimated_Value":"2000000"}'),
 ('11','0','2','{"Registration_no":"skdf","Chassis_No":"sdfjljl","Engine_No":"sd","Make":"ljsdfl","Model":"ljsdlfjl","Type_of_body":"jlsjdflj","Seating_Capacity":"ljsdflj","Year_of_Manufacture":"ljsldfjl","Estimated_Value":"8000"}'),
 ('12','6','2','{"Registration_no":"KCJ578J","Chassis_No":"23480990","Engine_No":"23849029","Make":"BMW","Model":"Sedan","Type_of_body":"Sedan","Seating_Capacity":"5","Year_of_Manufacture":"2016","Estimated_Value":"2000000"}'),
 ('13','3','2','{"Registration_no":"sdfjll","Chassis_No":"dfjl","Engine_No":"lsdjfljl","Make":"lsdjfljkl","Model":"kljsdlfjlj","Type_of_body":"ljlsdfjlj","Seating_Capacity":"lsjdfllas","Year_of_Manufacture":"2016","Estimated_Value":"2500000"}'),
 ('14','12','5','{"title":"Mr","proposer_surname":"zxfsdfsd","other_names":"sdfsdfsd","date_of_birth":"1899-12-31","gender":"Male","age_range_bracket":"19-30","id\\/passport":"sdfsd","nhif":"sdfsdf","blood_type":"O","nationality":"Algeria","relation_to_spouse":"sdfsd","occupation":"dsfsd"}'),
 ('15','13','3','{"plot_no":"253641","walls":"Concrete","roof":"Brick","height_in_storeys":"2","activities_carried":"no","activities_particulars":"","private_dwelling":"yes","pdwelling_particulars":"","self_contained_flat":"yes","solely_occupation":"yes","without_inhabitant_7days":"yes","no_inhabitant7days_extent":"","withoutinhabitant30days":"yes","inhabitant30days_particulars":"","good_state":"yes","step":"2"}'),
 ('16','15','2','{"RegNo":"659845","ChassisNo":"659832","EngineNo":"326589","CarMake":"TOYOTA","CC":"1500","BodyType":"Salon","SeatingCapacity":"5","ManufactureDate":"1999","ValueEstimate":"1000000","AntiTheft":"yes","AntiTheftDetails":"","NonStandardAccessories":"yes","NonStandardAccessoriesDetails":"","SpecialFeatures":"yes","SpecialFeaturesDetails":"","TheOwner":"yes","NameOfOwner":"","carusage":"_or_professional_purposes","step":"2"}'),
 ('17','16','5','{"Occupation":"Name","OccupationDescription":"","ManualLabour":"no","ManualLabourDetails":"","Salary":"1500","Rupture(hernia)":"no","Varicose_veins":"no","Slipped_disc":"no","Impairment_sight":"no","Infection_eyes":"no","Heart_disease":"no","Fits_blackouts":"no","form_chronic":"no","Back_strain":"no","Impairment_hearing":"no","Hearing_complaint":"no","Discharge_ear":"no","Duodenal_gastric_ulcer":"no","form_paralysis":"no","physical_defect_infirmity?":"no","RecentInjuries":"no","RecentInjuryDetails":"","ever_proposed_Personal_Accident_and\\/or_Life_Insurance":"no","Declined_issue_policy_you?":"no","Declined_continue_insurance":"no","Not_invited_renewal_policy":"no","Imposed_restrictions_special_conditions":"no","CoverDeclineParticulars":"","step":"2"}'),
 ('18','18','2','{"RegNo":"659845","ChassisNo":"659832","EngineNo":"326589","CarMake":"DODGE","CC":"1500","BodyType":"Salon","SeatingCapacity":"5","ManufactureDate":"2004","ValueEstimate":"1000000","AntiTheft":"yes","AntiTheftDetails":"","NonStandardAccessories":"no","NonStandardAccessoriesDetails":"","SpecialFeatures":"no","SpecialFeaturesDetails":"","TheOwner":"no","NameOfOwner":"","step":"2"}');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_customer_quotes` 
-- 

INSERT INTO `itr_customer_quotes` (`id`, `customers_id`, `products_id`, `datetime`, `introtext`, `customer_info`, `product_info`, `customer_entity_data_id`, `amount`, `recommendation`, `status`, `acceptedoffer`, `source`) VALUES ('13','9','1','1489014000','','{"customer":null,"email":"billalsobroook4@gmail.com","phone":"07234923478"}','{"Cover_Start":"2017-03-09","Cover_End":"2018-03-08","Cover_Type":"Comprehensive","Ncd_Percent":"10","Riotes":"No","Terrorism":"No","Windscreen":"No","Audio":"Yes","Passenger":"No"}','["4"]','{"quote_id":null,"entity_id":null,"_rates":{"model":{"table":"rates","relations":null,"fxnstatus":{"last_function":"formatOutput"},"last_altered_row":null,"errors":[],"schema":{"database":{"prefix":"esu_","_mysqli":{"affected_rows":null,"client_info":null,"client_version":null,"connect_errno":null,"connect_error":null,"errno":null,"error":null,"error_list":null,"field_count":null,"host_info":null,"info":null,"insert_id":null,"server_info":null,"server_version":null,"stat":null,"sqlstate":null,"protocol_version":null,"thread_id":null,"warning_count":null},"_join":[],"_where":[],"count":1,"config":{"db":"esurance_db","dbprefix":"esu_","host":"localhost","username":"root","password":"","port":"3306"}},"table":null},"dbobject":{"prefix":"esu_","_mysqli":{"affected_rows":null,"client_info":null,"client_version":null,"connect_errno":null,"connect_error":null,"errno":null,"error":null,"error_list":null,"field_count":null,"host_info":null,"info":null,"insert_id":null,"server_info":null,"server_version":null,"stat":null,"sqlstate":null,"protocol_version":null,"thread_id":null,"warning_count":null},"_join":[],"_where":[],"count":1,"config":{"db":"esurance_db","dbprefix":"esu_","host":"localhost","username":"root","password":"","port":"3306"}},"primarykey":"id","primary_key_value":{"id":"0"},"_primary_table":null,"tablecols":[{"Field":"id","Type":"int(11)","Null":"NO","Key":"PRI","Default":null,"Extra":"auto_increment"},{"Field":"rate_name","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_value","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_type","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_category","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"insurer_id","Type":"int(11)","Null":"NO","Key":"","Default":"14","Extra":""}],"totalcount":null,"data":[{"id":17,"rate_name":"Stamp Duty","rate_value":"40","rate_type":"Fixed","rate_category":"Travel","insurer_id":14}],"entity":null,"operators":["BETWEEN","NOT BETWEEN","LIKE","NOT LIKE","IN","NOT IN","IS NOT","IS NOT NULL","<","<=","=","!=",":=","^","|","<=>","->",">=",">"],"table_name":"rates","id":"","rate_name":"","rate_value":"","rate_type":"","rate_category":"","insurer_id":""},"view":{"basetemplate":"index.php","mainpanel":null,"panelsfolder":"panels","panel":null,"panelpath":"admin\\\\rates\\\\views\\\\panels","_ajax":false,"partials":null,"variables":[],"config":null,"tplengine":null,"response":{"headers":{}},"event":null,"defaults":null,"view":null,"resources":null,"controller":null,"main_method":"","shell":null,"user":null,"gateway":null,"current_route":null,"mode":"startup"},"auth":{},"event":null,"partials":null,"defaults":null,"resources":null,"controller":null,"main_method":"","shell":null,"user":null,"gateway":null,"current_route":null,"mode":"startup"},"_unset":["quote","customer","main_entity","other_entities","rates","customer_id","_customer","_entities","_quotes","entities"],"ent_ids":["4"],"cover_type":"Comprehensive","other_totals":167500,"cars":[{"tsi":"1000000","reg":"KBY564J","basic_premium":75000,"cover_type":"Comprehensive","riotes":null,"terrorism":null,"windscreen":null,"audio":100000,"passenger":null,"ncd_percent":"10","ncd_amount":7500,"basic_premium2":67500,"net_premium":167500}],"total":168293.75,"total_net_premiums":167500,"training_levy":335,"policy_levy":418.75,"stamp_duty":"40","insurer_id":14}','0','new','','Internal'),
 ('14','1','1','1489014000','','{"customer":null,"email":"intrestign@gmail.com","phone":"903580358"}','{"Cover_Start":"2017-03-09","Cover_End":"2018-03-08","Cover_Type":"Comprehensive","Ncd_Percent":"30","Riotes":"Yes","Terrorism":"No","Windscreen":"Yes","Audio":"No","Passenger":"No"}','["9"]','{"quote_id":null,"entity_id":null,"_rates":{"model":{"table":"rates","relations":null,"fxnstatus":{"last_function":"formatOutput"},"last_altered_row":null,"errors":[],"schema":{"database":{"prefix":"esu_","_mysqli":{"affected_rows":null,"client_info":null,"client_version":null,"connect_errno":null,"connect_error":null,"errno":null,"error":null,"error_list":null,"field_count":null,"host_info":null,"info":null,"insert_id":null,"server_info":null,"server_version":null,"stat":null,"sqlstate":null,"protocol_version":null,"thread_id":null,"warning_count":null},"_join":[],"_where":[],"count":1,"config":{"db":"esurance_db","dbprefix":"esu_","host":"localhost","username":"root","password":"","port":"3306"}},"table":null},"dbobject":{"prefix":"esu_","_mysqli":{"affected_rows":null,"client_info":null,"client_version":null,"connect_errno":null,"connect_error":null,"errno":null,"error":null,"error_list":null,"field_count":null,"host_info":null,"info":null,"insert_id":null,"server_info":null,"server_version":null,"stat":null,"sqlstate":null,"protocol_version":null,"thread_id":null,"warning_count":null},"_join":[],"_where":[],"count":1,"config":{"db":"esurance_db","dbprefix":"esu_","host":"localhost","username":"root","password":"","port":"3306"}},"primarykey":"id","primary_key_value":{"id":"0"},"_primary_table":null,"tablecols":[{"Field":"id","Type":"int(11)","Null":"NO","Key":"PRI","Default":null,"Extra":"auto_increment"},{"Field":"rate_name","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_value","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_type","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_category","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"insurer_id","Type":"int(11)","Null":"NO","Key":"","Default":"14","Extra":""}],"totalcount":null,"data":[{"id":17,"rate_name":"Stamp Duty","rate_value":"40","rate_type":"Fixed","rate_category":"Travel","insurer_id":14}],"entity":null,"operators":["BETWEEN","NOT BETWEEN","LIKE","NOT LIKE","IN","NOT IN","IS NOT","IS NOT NULL","<","<=","=","!=",":=","^","|","<=>","->",">=",">"],"table_name":"rates","id":"","rate_name":"","rate_value":"","rate_type":"","rate_category":"","insurer_id":""},"view":{"basetemplate":"index.php","mainpanel":null,"panelsfolder":"panels","panel":null,"panelpath":"admin\\\\rates\\\\views\\\\panels","_ajax":false,"partials":null,"variables":[],"config":null,"tplengine":null,"response":{"headers":{}},"event":null,"defaults":null,"view":null,"resources":null,"controller":null,"main_method":"","shell":null,"user":null,"gateway":null,"current_route":null,"mode":"startup"},"auth":{},"event":null,"partials":null,"defaults":null,"resources":null,"controller":null,"main_method":"","shell":null,"user":null,"gateway":null,"current_route":null,"mode":"startup"},"_unset":["quote","customer","main_entity","other_entities","rates","customer_id","_customer","_entities","_quotes","entities"],"ent_ids":["9"],"cover_type":"Comprehensive","other_totals":213000,"cars":[{"tsi":"1200000","reg":"KAT400F","basic_premium":90000,"cover_type":"Comprehensive","riotes":30000,"terrorism":null,"windscreen":120000,"audio":null,"passenger":null,"ncd_percent":"30","ncd_amount":27000,"basic_premium2":63000,"net_premium":213000}],"total":213998.5,"total_net_premiums":213000,"training_levy":426,"policy_levy":532.5,"stamp_duty":"40","insurer_id":14}','0','new','','Internal'),
 ('15','6','1','1489014000','','{"customer":null,"email":"samuelmer@hmsdail.com","phone":"0790663311"}','{"Cover_Start":"2017-03-16","Cover_End":"2018-03-15","Cover_Type":"Comprehensive","Ncd_Percent":"30","Riotes":"No","Terrorism":"Yes","Windscreen":"No","Audio":"Yes","Passenger":"No"}','["12"]','{"quote_id":null,"entity_id":null,"_rates":{"model":{"table":"rates","relations":null,"fxnstatus":{"last_function":"formatOutput"},"last_altered_row":null,"errors":[],"schema":{"database":{"prefix":"esu_","_mysqli":{"affected_rows":null,"client_info":null,"client_version":null,"connect_errno":null,"connect_error":null,"errno":null,"error":null,"error_list":null,"field_count":null,"host_info":null,"info":null,"insert_id":null,"server_info":null,"server_version":null,"stat":null,"sqlstate":null,"protocol_version":null,"thread_id":null,"warning_count":null},"_join":[],"_where":[],"count":1,"config":{"db":"esurance_db","dbprefix":"esu_","host":"localhost","username":"root","password":"","port":"3306"}},"table":null},"dbobject":{"prefix":"esu_","_mysqli":{"affected_rows":null,"client_info":null,"client_version":null,"connect_errno":null,"connect_error":null,"errno":null,"error":null,"error_list":null,"field_count":null,"host_info":null,"info":null,"insert_id":null,"server_info":null,"server_version":null,"stat":null,"sqlstate":null,"protocol_version":null,"thread_id":null,"warning_count":null},"_join":[],"_where":[],"count":1,"config":{"db":"esurance_db","dbprefix":"esu_","host":"localhost","username":"root","password":"","port":"3306"}},"primarykey":"id","primary_key_value":{"id":"0"},"_primary_table":null,"tablecols":[{"Field":"id","Type":"int(11)","Null":"NO","Key":"PRI","Default":null,"Extra":"auto_increment"},{"Field":"rate_name","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_value","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_type","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_category","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"insurer_id","Type":"int(11)","Null":"NO","Key":"","Default":"14","Extra":""}],"totalcount":null,"data":[{"id":17,"rate_name":"Stamp Duty","rate_value":"40","rate_type":"Fixed","rate_category":"Travel","insurer_id":14}],"entity":null,"operators":["BETWEEN","NOT BETWEEN","LIKE","NOT LIKE","IN","NOT IN","IS NOT","IS NOT NULL","<","<=","=","!=",":=","^","|","<=>","->",">=",">"],"table_name":"rates","id":"","rate_name":"","rate_value":"","rate_type":"","rate_category":"","insurer_id":""},"view":{"basetemplate":"index.php","mainpanel":null,"panelsfolder":"panels","panel":null,"panelpath":"admin\\\\rates\\\\views\\\\panels","_ajax":false,"partials":null,"variables":[],"config":null,"tplengine":null,"response":{"headers":{}},"event":null,"defaults":null,"view":null,"resources":null,"controller":null,"main_method":"","shell":null,"user":null,"gateway":null,"current_route":null,"mode":"startup"},"auth":{},"event":null,"partials":null,"defaults":null,"resources":null,"controller":null,"main_method":"","shell":null,"user":null,"gateway":null,"current_route":null,"mode":"startup"},"_unset":["quote","customer","main_entity","other_entities","rates","customer_id","_customer","_entities","_quotes","entities"],"ent_ids":["12"],"cover_type":"Comprehensive","other_totals":310000,"cars":[{"tsi":"2000000","reg":"KCJ578J","basic_premium":150000,"cover_type":"Comprehensive","riotes":null,"terrorism":5000,"windscreen":null,"audio":200000,"passenger":null,"ncd_percent":"30","ncd_amount":45000,"basic_premium2":105000,"net_premium":310000}],"total":311435,"total_net_premiums":310000,"training_levy":620,"policy_levy":775,"stamp_duty":"40","insurer_id":14}','0','policy_created','15','Internal'),
 ('16','3','1','1489014000','','{"customer":null,"email":"samuelme@hmsdail.com","phone":"00823462347623"}','{"Cover_Start":"2017-03-15","Cover_End":"2018-03-14","Cover_Type":"Third Party Fire and Theft","Ncd_Percent":"40","Riotes":"Yes","Terrorism":"No","Windscreen":"No","Audio":"Yes","Passenger":"No"}','["13"]','{"quote_id":null,"entity_id":null,"_rates":{"model":{"table":"rates","relations":null,"fxnstatus":{"last_function":"formatOutput"},"last_altered_row":null,"errors":[],"schema":{"database":{"prefix":"esu_","_mysqli":{"affected_rows":null,"client_info":null,"client_version":null,"connect_errno":null,"connect_error":null,"errno":null,"error":null,"error_list":null,"field_count":null,"host_info":null,"info":null,"insert_id":null,"server_info":null,"server_version":null,"stat":null,"sqlstate":null,"protocol_version":null,"thread_id":null,"warning_count":null},"_join":[],"_where":[],"count":1,"config":{"db":"esurance_db","dbprefix":"esu_","host":"localhost","username":"root","password":"","port":"3306"}},"table":null},"dbobject":{"prefix":"esu_","_mysqli":{"affected_rows":null,"client_info":null,"client_version":null,"connect_errno":null,"connect_error":null,"errno":null,"error":null,"error_list":null,"field_count":null,"host_info":null,"info":null,"insert_id":null,"server_info":null,"server_version":null,"stat":null,"sqlstate":null,"protocol_version":null,"thread_id":null,"warning_count":null},"_join":[],"_where":[],"count":1,"config":{"db":"esurance_db","dbprefix":"esu_","host":"localhost","username":"root","password":"","port":"3306"}},"primarykey":"id","primary_key_value":{"id":"0"},"_primary_table":null,"tablecols":[{"Field":"id","Type":"int(11)","Null":"NO","Key":"PRI","Default":null,"Extra":"auto_increment"},{"Field":"rate_name","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_value","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_type","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"rate_category","Type":"text","Null":"NO","Key":"","Default":null,"Extra":""},{"Field":"insurer_id","Type":"int(11)","Null":"NO","Key":"","Default":"14","Extra":""}],"totalcount":null,"data":[{"id":17,"rate_name":"Stamp Duty","rate_value":"40","rate_type":"Fixed","rate_category":"Travel","insurer_id":14}],"entity":null,"operators":["BETWEEN","NOT BETWEEN","LIKE","NOT LIKE","IN","NOT IN","IS NOT","IS NOT NULL","<","<=","=","!=",":=","^","|","<=>","->",">=",">"],"table_name":"rates","id":"","rate_name":"","rate_value":"","rate_type":"","rate_category":"","insurer_id":""},"view":{"basetemplate":"index.php","mainpanel":null,"panelsfolder":"panels","panel":null,"panelpath":"admin\\\\rates\\\\views\\\\panels","_ajax":false,"partials":null,"variables":[],"config":null,"tplengine":null,"response":{"headers":{}},"event":null,"defaults":null,"view":null,"resources":null,"controller":null,"main_method":"","shell":null,"user":null,"gateway":null,"current_route":null,"mode":"startup"},"auth":{},"event":null,"partials":null,"defaults":null,"resources":null,"controller":null,"main_method":"","shell":null,"user":null,"gateway":null,"current_route":null,"mode":"startup"},"_unset":["quote","customer","main_entity","other_entities","rates","customer_id","_customer","_entities","_quotes","entities"],"ent_ids":["13"],"cover_type":"Third Party Fire and Theft","other_totals":384500,"cars":[{"tsi":"2500000","reg":"sdfjll","basic_premium":120000,"cover_type":"Third Party Fire and Theft","riotes":62500,"terrorism":null,"windscreen":null,"audio":250000,"passenger":null,"ncd_percent":"40","ncd_amount":48000,"basic_premium2":72000,"net_premium":384500}],"total":386270.25,"total_net_premiums":384500,"training_levy":769,"policy_levy":961.25,"stamp_duty":"40","insurer_id":14}','0','policy_created','16','Internal'),
 ('17','12','9','1489131882','','{"form_step":"form_1","name_step_one":"step_one","zebra_honeypot_step_one":"","zebra_csrf_token_step_one":"7f6cead8090b68e3c98b3d5535be12a9","title":"Mr","surname":"Njue","names":"Eric Murimi","dob":"2017-03-10","gender":"Male","age_range_bracket":"19-30","id_passport_no":"25215","nhif":"","blood_type":"B","country":"Bahamas","address":"22229","code":"00100","city_town":"Dadaab","residential_physical_address":"sdfsdf","road":"sdfsdf","occupation_profession":"sdfsd","mobile":"sdfsdf","email":"sdfsd@gmail.com","nok_name":"","nok_email":"","nok_contact_no":"","nok_relationship":"","nok_id_pass":"","nok_blood_group":"","nok_postal_address":"","nok_postal_code":"","nok_city_town":"","btnsubmit":"Proceed to Step 2 >","step":"1"}','{"form_step":"form_3","name_step_two":"step_two","zebra_honeypot_step_two":"","zebra_csrf_token_step_two":"55ac0184c58447736656e02f67540d9c","core_plans":"royal","ba4":"ba4","bb2":"bb2","bb4":"bb4","bc4":"bc4","bd4":"bd4","have_dependants":"1","additional_covers":"1","btnsubmit":"Get your Quotation >","step":"3","name_step_three":"step_three","zebra_honeypot_step_three":"","zebra_csrf_token_step_three":"2d96db514924c02c299293271058ddf2","pricipal_insurer":"","pricipal_day":"","pricipal_month":"","pricipal_year":"","spouse_insurer":"","spouse_day":"","spouse_month":"","spouse_year":"","ever_cover_declined":"0","dependant_claimed_cover":"0","i_agree":"1"}','[14]','{"insurer_id":14,"core_premium":35750,"core_optional_benefits":73050,"dependants":{"1":{"dep_premium":35750,"dep_benefits":73050}},"dt_total":108800,"subtotal":217600,"levy":435.2,"phcf":544,"stamp_duty":"40.00","grand_total":218619.2}','0','new','','Internal'),
 ('18','13','8','1489485323','','{"id":13,"name":"Test","mobile_no":"215487","email":"sngumo@yahoo.com","date_of_birth":1489532400,"enabled":"yes","postal_address":"875421","postal_code":"00200","regdate":1489484642,"insurer_agents_id":null,"additional_info":"{\\"title\\":\\"Mr\\",\\"Occupation\\":\\"Name\\",\\"Email\\":\\"sngumo@yahoo.com\\",\\"Town\\":\\"Nairobi\\",\\"Type\\":\\"_ermanent\\",\\"DateIssued\\":\\"2017-03-21\\",\\"ProvisionalDriver\\":\\"no\\",\\"DefectiveVision\\":\\"no\\",\\"ConvictedOffense\\":\\"no\\",\\"OtherPolicies\\":\\"no\\",\\"OtherPolicyDetails\\":\\"\\",\\"step\\":\\"1\\"}"}','{"insurancefrom":"2017-03-15","insure_rent_receivable":"no","cover_amount":"","no_of_months":"","enhance_value_auto":"yes","percetage_increase":"0_10","dwelling_value":"109000","total_sum_insured":"100000","sectionb":"1","furniture":"Chairs","furniturevalue":"100000","household_linen":"","householdlinen_value":"","curtlery_and_others":"","curtleryandothers_value":"","pictures_and_ornaments":"","picturesandornaments_value":"","wines_and_spirits":"","winesandspirits_value":"","personal_clothing":"","personalclothing_value":"","photographic_equipment":"","photographicequipment_value":"","jewelry_and_valuables":"","jewelryandvaluables_value":"","other_specifications":"","othersvalue":"10000","more_articles":"","morearticles_values":"","insure_individually":"1","how_many":"2","item_name1":"","item_make1":"","item_model1":"","s_no1":"","item_value1":"","item_name2":"","item_make2":"","item_model2":"","s_no2":"","item_value2":"","security_arrangements":"own_watchman","sectionc":"1","section_c_no_of_items":"2","work_injury_benefit":"0","no_employees":"","limit_of_cover":"option_b","limit_of_indemnity":"100000","sectionf_limit_of_indemnity":"","i_agree":"1","step":"3"}','[15]','{"tsi_a":null,"tsi_b":null,"tsi_c":null,"section_a":0,"section_b":0,"section_c":0,"workmen":null,"owner_liability":null,"occupier_liability":null,"gross_premium":0,"training_rate":"0.2%","training_levy":0,"levy_value":"0.25%","policy_levy":0,"stamp_duty":"40","total":40,"insurer_id":14}','0','new','','Internal'),
 ('19','15','1','1489486136','','{"id":15,"name":"Test","mobile_no":"215487","email":"sngumo@yahoo.com","date_of_birth":1488322800,"enabled":"yes","postal_address":"875421","postal_code":"00200","regdate":1489485681,"insurer_agents_id":null,"additional_info":"{\\"title\\":\\"Mr\\",\\"Occupation\\":\\"Name\\",\\"Email\\":\\"sngumo@yahoo.com\\",\\"Town\\":\\"Nairobi\\",\\"Type\\":\\"_ermanent\\",\\"DateIssued\\":\\"2017-03-01\\",\\"ProvisionalDriver\\":\\"no\\",\\"DefectiveVision\\":\\"no\\",\\"ConvictedOffense\\":\\"no\\",\\"OtherPolicies\\":\\"no\\",\\"OtherPolicyDetails\\":\\"\\",\\"step\\":\\"1\\"}"}','{"coverstart":"2017-03-15","covertype":"Comprehensive","windscreen":"yes","WindscreenValue":"2500","ncddiscount":"10","NeedPersonalCover":"yes","PersonalCoverDetails":"","previousaccidents":"no","decline_cover":"no","demand_increased_rate":"no","imposed_special_terms":"no","declined_renewal":"no","previousdeclines":"no","claim_no_yr1":"","claim_amount_yr1":"","insurer_yr1":"","claim_details_yr1":"","claim_no_yr2":"","claim_amount_yr2":"","insurer_yr2":"","claim_details_yr2":"","claim_no_yr3":"","claim_amount_yr3":"","insurer_yr3":"","claim_details_yr3":"","pickat":"Nairobi, Mombasa Road, Tulip House, Ground Floor","acceptterms":"yes","step":"3"}','[16]','{"tsi":null,"basic_premium":0,"cover_type":"Comprehensive","riotes":null,"terrorism":null,"windscreen":null,"audio":false,"passenger":null,"ncd_percent":"10","ncd_amount":0,"basic_premium2":15000,"minimum":15000,"net_premium":15000,"levy_value":"0.25%","policy_levy":37.5,"stamp_duty":"40","no_of_covers":null,"training_levy":30,"total":15107.5,"insurer_id":14}','0','new','','Internal'),
 ('20','16','5','1489487211','','{"id":16,"name":"Test","mobile_no":"0722548798","email":"sngumo@yahoo.com","date_of_birth":null,"enabled":"yes","postal_address":"57377","postal_code":"00200","regdate":1489487042,"insurer_agents_id":null,"additional_info":"{\\"title\\":\\"Mr\\",\\"PIN\\":\\"215487\\",\\"Certificate\\":\\"548765\\",\\"Email\\":\\"sngumo@yahoo.com\\",\\"Town\\":\\"Nairobi\\",\\"Website\\":\\"\\",\\"Telephone\\":\\"\\",\\"Fax\\":\\"\\",\\"BeneficiaryName\\":\\"Test Name2\\",\\"beneficiaryAge\\":\\"12\\",\\"BeneficiaryRelationship\\":\\"Son\\",\\"step\\":\\"1\\"}"}','{"coverstart":"2017-03-15","InsuredAmount":"15000","cover_class":"classII","MedicalExpenses":"1200","TotalDisablement":"","ToCompliment":"no","OtherCompanyName":"","OtherCompanySumInsured":"","OtherCompanyPolicyNo":"","acceptterms":"yes","step":"3"}','[17]','{"band":null,"class":"classII","age_bracket":null,"premium_rate":2500,"levy":5,"levy_rate":"0.2%","sub_total":2505,"other_covers":null,"others":[],"policy_fund":6.25,"stamp_duty":"40","total":2551.25,"insurer_id":14}','0','new','','Internal');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_customers` 
-- 

INSERT INTO `itr_customers` (`id`, `name`, `mobile_no`, `email`, `date_of_birth`, `enabled`, `postal_address`, `postal_code`, `regdate`, `insurer_agents_id`, `additional_info`) VALUES ('1','May Waether Heat','903580358','intrestign@gmail.com',NULL,'yes','5346','78349573','1487852923',NULL,'{"name_motor_personal_details":"motor_personal_details","zebra_honeypot_motor_personal_details":"","zebra_csrf_token_motor_personal_details":"ecd9d49d2ca17c0e31a966d9cff326a3","title":"Mrs","occupation":"Wrestler","pin":"6RTWE7237","idpassport":"789000","dlno":"HJ577577","dlyearissued":"2001","drivingexperience":"15","email":"intrestign@gmail.com","town":"Kakamega","deffectivevision":"no","particulars":"","btnsubmit":"Proceed to Car Details >>","step":"1"}'),
 ('2','Test Interb Test 56','842390230','trempo@gmail.com',NULL,'yes','534','890234023','1487857123',NULL,'{"title":"Ms","occupation":"Intrine","pin":"34","idnumber":"23432423","email":"trempo@gmail.com","town":"Kajiado","step":"1"}'),
 ('3','Test Interb Test 34','00823462347623','samuelme@hmsdail.com','1014332400','yes','534','34','1487858391',NULL,'{"title":"Dr","occupation":"Doctor","age_bracket":"31-40","height":"45","weight":"78","town":"Kapenguria","email":"samuelme@hmsdail.com","beneficiary_name":"Interb Test","beneficiary_address":"534","beneficiary_code":"34","beneficiary_town":"Kapenguria","step":"1"}'),
 ('5','lin odero','0823479234','dervismata12@gmail.com',NULL,'yes','534','34','1487919892',NULL,'{"title":"Prof","occupation":"Nurse","pin":"2368423","idpassport":"5345","dlno":"34534534","dlyearissued":"2002","drivingexperience":"6","email":"dervismata12@gmail.com","town":"Kapenguria","deffectivevision":"yes","particulars":"i dont think its important","step":"1"}'),
 ('6','Test Interb Test 1','0790663311','samuelmer@hmsdail.com','1013468400','yes','534','34','1487928553',NULL,'{"title":"Ms","occupation":"48964","age_bracket":"19-30","height":"45","weight":"123","town":"Kapenguria","email":"samuelmer@hmsdail.com","beneficiary_name":"Interb Test","beneficiary_address":"534","beneficiary_code":"34","beneficiary_town":"Kapenguria","step":"1"}'),
 ('7','Test Interb Testb 78934','78987979','intigu56e@gmail.com','1025560800','yes','534','34','1487932807',NULL,'{"title":"Ms","occupation":"Tudu","pin":"34","idnumber":"23432423","email":"intigu56e@gmail.com","town":"Kapenguria","step":"1"}'),
 ('8','brock lesnah','07234923478','billalsobroook@gmail.com',NULL,'yes','666','67678','1487942015',NULL,'{"title":"Ms","occupation":"wrestler","pin":"A67732846238","idnumber":"234890","email":"billalsobroook@gmail.com","town":"Garissa","step":"1"}'),
 ('9','Bill Alsobrook','07234923478','billalsobroook4@gmail.com',NULL,'yes','666','67678','1488198941',NULL,'{"title":"Ms","occupation":"Activist","pin":"A67732846238","idpassport":"234234234","dlno":"dggfd544354","dlyearissued":"2016","drivingexperience":"3","email":"billalsobroook4@gmail.com","town":"Embu","deffectivevision":"yes","particulars":"sdfsdfsdf","step":"1"}'),
 ('10','LUTZ HASKEL YULE MSEE','07888832329','samuel0@gmail.com','764892000','yes','666','40107','1488872800',NULL,'{"title":"Prof","occupation":"Actor","age_bracket":"31-40","height":"2","weight":"151","town":"Kakamega","email":"samuel0@gmail.com","beneficiary_name":"Treso Laki","beneficiary_address":"677","beneficiary_code":"1209","beneficiary_town":"Kakamega","step":"1"}'),
 ('11','','','',NULL,NULL,NULL,NULL,'0','2',NULL),
 ('12','Njue Eric Murimi','sdfsdf','sdfsd@gmail.com','1489100400','yes','22229','00100','1489131762',NULL,'{"form_step":"form_1","name_step_one":"step_one","zebra_honeypot_step_one":"","zebra_csrf_token_step_one":"7f6cead8090b68e3c98b3d5535be12a9","title":"Mr","gender":"Male","age_range_bracket":"19-30","id_passport_no":"25215","nhif":"","blood_type":"B","country":"Bahamas","city_town":"Dadaab","residential_physical_address":"sdfsdf","road":"sdfsdf","occupation_profession":"sdfsd","email":"sdfsd@gmail.com","nok_name":"","nok_email":"","nok_contact_no":"","nok_relationship":"","nok_id_pass":"","nok_blood_group":"","nok_postal_address":"","nok_postal_code":"","nok_city_town":"","btnsubmit":"Proceed to Step 2 >","step":"1"}'),
 ('13','Test','215487','sngumo@yahoo.com','1489532400','yes','875421','00200','1489484642',NULL,'{"title":"Mr","Occupation":"Name","Email":"sngumo@yahoo.com","Town":"Nairobi","Type":"_ermanent","DateIssued":"2017-03-21","ProvisionalDriver":"no","DefectiveVision":"no","ConvictedOffense":"no","OtherPolicies":"no","OtherPolicyDetails":"","step":"1"}'),
 ('14','Test','072215487','sngumo@yahoo.com',NULL,'yes','00200','00200','1489484811',NULL,'{"title":"Mrs","PIN":"215487","Certificate":"457896644","Email":"sngumo@yahoo.com","Town":"Nairobi","Website":"","Telephone":"","Fax":"","BeneficiaryName":"Test Name2","beneficiaryAge":"12","BeneficiaryRelationship":"Son","step":"1"}'),
 ('15','Test','215487','sngumo@yahoo.com','1488322800','yes','875421','00200','1489485681',NULL,'{"title":"Mr","Occupation":"Name","Email":"sngumo@yahoo.com","Town":"Nairobi","Type":"_ermanent","DateIssued":"2017-03-01","ProvisionalDriver":"no","DefectiveVision":"no","ConvictedOffense":"no","OtherPolicies":"no","OtherPolicyDetails":"","step":"1"}'),
 ('16','Test','0722548798','sngumo@yahoo.com',NULL,'yes','57377','00200','1489487042',NULL,'{"title":"Mr","PIN":"215487","Certificate":"548765","Email":"sngumo@yahoo.com","Town":"Nairobi","Website":"","Telephone":"","Fax":"","BeneficiaryName":"Test Name2","beneficiaryAge":"12","BeneficiaryRelationship":"Son","step":"1"}'),
 ('17','Test','0722548798','sngumo@yahoo.com','1488927600','yes','00200','00200','1489581085',NULL,'{"title":"Mr","Occupation":"Name","Email":"sngumo@yahoo.com","Town":"Nairobi","Type":"_ermanent","DateIssued":"2017-03-22","ProvisionalDriver":"no","DefectiveVision":"no","ConvictedOffense":"no","OtherPolicies":"no","OtherPolicyDetails":"","step":"1"}'),
 ('18','Test','215487','sngumo@yahoo.com','1489100400','yes','875421','00200','1489581198',NULL,'{"title":"Mr","Occupation":"Name","Email":"sngumo@yahoo.com","Town":"Baragoi","DateIssued":"2017-03-16","ProvisionalDriver":"no","DefectiveVision":"no","ConvictedOffense":"no","OtherPolicies":"no","OtherPolicyDetails":"","step":"1"}');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_documents` 
-- 

INSERT INTO `itr_documents` (`id`, `filename`, `filepath`, `description`, `doctype`, `datetime`) VALUES ('47','quotation_31-01-2016.pdf','admin\\quotes\\documents\\quotation_31-01-2016.pdf','quotes_pdf','PDF document for Quote No.699','1468587701'),
 ('60','quotation_96_08-07-2016.pdf','admin\\quotes\\documents\\quotation_96_08-07-2016.pdf','PDF document for Quote No.702','quotes_pdf','1468670967'),
 ('63','quotation_702_08-07-2016.pdf','admin\\quotes\\documents\\quotation_702_08-07-2016.pdf','PDF document for Quote No.702','quotes_pdf','1468861384'),
 ('72','quotation_42_21-07-2016.pdf','admin\\quotes\\documents\\quotation_42_21-07-2016.pdf','PDF document for Quote No.715','quotes_pdf','1471274689'),
 ('73','quotation_715_21-07-2016.pdf','admin\\quotes\\documents\\quotation_715_21-07-2016.pdf','PDF document for Quote No.715','quotes_pdf','1471363841'),
 ('74','quotation_2_29-08-2016.pdf','admin\\quotes\\documents\\quotation_2_29-08-2016.pdf','PDF document for Quote No.720','quotes_pdf','1472482808'),
 ('75','139_level03(5).pdf','/admin/quotes/documents/139_level03(5).pdf','quotes document','quotes','1489056693'),
 ('79','676_esurance_db.sql','/admin/policies/documents/676_esurance_db.sql','policies document','policies','1489057781'),
 ('80','806_esurance_db.sql','/admin/policies/documents/806_esurance_db.sql','policies document','policies','1489057875'),
 ('81','362_esurance_db.sql','/admin/policies/documents/362_esurance_db.sql','policies document','policies','1489057893'),
 ('82','2_esurance_db.sql','/admin/policies/documents/2_esurance_db.sql','policies document','policies','1489057988'),
 ('83','805_esurance_db.sql','/admin/policies/documents/805_esurance_db.sql','policies document','policies','1489057997'),
 ('88','168_esurance_db.sql','/admin/policies/documents/168_esurance_db.sql','policies document','policies','1489061857'),
 ('89','751_sldjflsd.txt','/admin/policies/documents/751_sldjflsd.txt','policies document','policies','1489073202'),
 ('90','719_TravelView.php','/admin/policies/documents/719_TravelView.php','policies document','policies','1489073445');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_entities` 
-- 

INSERT INTO `itr_entities` (`id`, `name`, `alias`, `entity_types_id`, `forms_id`) VALUES ('2','Vehicle','vehicle','4','21'),
 ('3','Private Property','private_property','3','38'),
 ('4','Business','business','1','39'),
 ('5','Person','person','2','40');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_entity_types` 
-- 

INSERT INTO `itr_entity_types` (`id`, `type`) VALUES ('1','Business'),
 ('2','Person'),
 ('3','Private Property'),
 ('4','Vehicle'),
 ('5','Other');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_forms` 
-- 

INSERT INTO `itr_forms` (`id`, `form_name`, `controls`, `map`, `field_order`) VALUES ('1','motor_insurance','{"Cover_Start":{"human_name":"Cover Start","field_type":"date","field_attributes":{"date_format":"yy-mm-dd","class":"date_start","default":"","required":"yes"}},"Cover_End":{"human_name":"Cover End","field_type":"date","field_attributes":{"date_format":"yy-mm-dd","class":"date_end","default":"","required":"yes"}},"Cover_Type":{"human_name":"Cover Type","field_type":"select","field_attributes":{"choices":"Comprehensive,Third Party Fire and Theft,Third Party Only","class":"","default":"","required":"yes"}},"Riotes":{"human_name":"Riotes","field_type":"yes_no","field_attributes":{"no_default_value":"No","required":"yes"}},"Terrorism":{"human_name":"Terrorism","field_type":"yes_no","field_attributes":{"no_default_value":"No","required":"yes"}},"Windscreen":{"human_name":"Windscreen","field_type":"yes_no","field_attributes":{"no_default_value":"No","required":"yes"}},"Audio":{"human_name":"Audio","field_type":"yes_no","field_attributes":{"no_default_value":"No","required":"yes"}},"Passenger":{"human_name":"Passenger","field_type":"yes_no","field_attributes":{"no_default_value":"No","required":"yes"}},"Ncd_Percent":{"human_name":"Ncd Percent","field_type":"select","field_attributes":{"choices":"0,10,20,30,40,50","class":"","default":"","required":"yes"}}}','','["Cover_Start","Cover_End","Cover_Type","Ncd_Percent","Riotes","Terrorism","Windscreen","Audio","Passenger"]'),
 ('21','vehicle','{"Registration_no":{"human_name":"Registration no","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"yes"}},"Chassis_No":{"human_name":"Chassis No","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"yes"}},"Engine_No":{"human_name":"Engine No","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"yes"}},"Make":{"human_name":"Make","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"yes"}},"Model":{"human_name":"Model","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"no"}},"Type_of_body":{"human_name":"Type of body","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"no"}},"Seating_Capacity":{"human_name":"Seating Capacity","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"no"}},"Year_of_Manufacture":{"human_name":"Year of Manufacture","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"no"}},"Estimated_Value":{"human_name":"Estimated Value","field_type":"price","field_attributes":{"price_min_length":"","price_max_length":"","price_nagetive_values":null,"required":"no"}}}','','["Registration_no","Chassis_No","Engine_No","Make","Model","Type_of_body","Seating_Capacity","Year_of_Manufacture","Estimated_Value"]'),
 ('25','personal_accident','{"Class":{"human_name":"Class","field_type":"select","field_attributes":{"choices":"Class I,Class II","class":"","default":"","required":"yes"}},"Band":{"human_name":"Band","field_type":"radios","field_attributes":{"choices":"Band 1: Covers you upto a limit of 250000 for accidental death,Band 2: Covers you upto a limit of 500000 for accidental death,Band 3: Covers you upto a limit of 1000000 for accidental death,Band 4: Covers you upto a limit of 2000000 for accidental death,Band 5: Covers you upto a limit of 4000000 for accidental death,Band 6: Covers you upto a limit of 8000000 for accidental death,Band 7: Covers you upto a limit of 1000000 for accidental death","class":"","default":"","required":"yes"}}}','','["Class","Band"]'),
 ('36','domestic_package','{"Risks_Covered":{"human_name":"Risks Covered","field_type":"select","field_attributes":{"select_choices":"basic risks(fire),\\r\\nmost risks(fire; storm; hail),\\r\\nall risks","select_default_value":"","required":"yes"}},"Insured_Sum":{"human_name":"Insured Sum","field_type":"text","field_attributes":{"text_min_length":null,"text_max_length":null,"text_default_value":null,"required":"yes"}},"Deductible":{"human_name":"Deductible","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"no"}},"Personal_Property_Coverage":{"human_name":"Personal Property Coverage","field_type":"select","field_attributes":{"select_choices":"yes","select_default_value":"","required":"no"}},"Temporary_Rental_Costs_Coverage":{"human_name":"Temporary Rental Costs Coverage","field_type":"select","field_attributes":{"select_choices":"yes","select_default_value":"","required":"no"}}}','','["Risks_Covered","Insured_Sum","Deductible","Personal_Property_Coverage","Temporary_Rental_Costs_Coverage"]'),
 ('37','travel_insurance','{"Coverage_Territory":{"human_name":"Coverage Territory","field_type":"select","field_attributes":{"select_choices":"whole world,\\r\\nNorth\\/South America,\\r\\nEurope,\\r\\nAfrica,\\r\\nAustralia and New Zealand","select_default_value":"","required":"yes"}},"Medical_Coverage_Sum":{"human_name":"Medical Coverage Sum","field_type":"text","field_attributes":{"text_min_length":null,"text_max_length":null,"text_default_value":null,"required":"yes"}},"Lost_Coverage":{"human_name":"Lost Coverage","field_type":"select","field_attributes":{"select_choices":"yes","select_default_value":"","required":"no"}},"Lost_Coverage_Sum":{"human_name":"Lost Coverage Sum","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"0.00","required":"no"}},"Travel_interruption_coverage":{"human_name":"Travel interruption coverage","field_type":"select","field_attributes":{"select_choices":"yes","select_default_value":"","required":"no"}},"Travel_interruption_coverage_sum":{"human_name":"Travel interruption coverage sum","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"0.00","required":"no"}},"Multiple_Trips":{"human_name":"Multiple Trips","field_type":"select","field_attributes":{"select_choices":"yes","select_default_value":"","required":"no"}}}','','["Coverage_Territory","Medical_Coverage_Sum","Lost_Coverage","Lost_Coverage_Sum","Travel_interruption_coverage","Travel_interruption_coverage_sum","Multiple_Trips"]'),
 ('38','private_property','{"Address":{"human_name":"Address","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"yes"}},"Property_Type":{"human_name":"Property Type","field_type":"select","field_attributes":{"select_choices":"house,\\r\\napartment,\\r\\nbungalow,\\r\\nmaisonette,\\r\\ncondominium\\r\\n","select_default_value":"","required":"yes"}},"Year_Built":{"human_name":"Year Built","field_type":"text","field_attributes":{"text_min_length":null,"text_max_length":null,"text_default_value":null,"required":"no"}},"Area":{"human_name":"Area","field_type":"text","field_attributes":{"text_min_length":null,"text_max_length":null,"text_default_value":null,"required":"no"}},"Construction_Material":{"human_name":"Construction Material","field_type":"select","field_attributes":{"select_choices":"concrete,\\r\\nwood,\\r\\nmixed","select_default_value":"","required":"no"}}}','','["Address","Property_Type","Year_Built","Area","Construction_Material"]'),
 ('39','business','{"Street":{"human_name":"Street","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"no"}},"Zip_Code":{"human_name":"Zip Code","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"no"}},"City":{"human_name":"City","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"yes"}},"Country":{"human_name":"Country","field_type":"country","field_attributes":{"select_default_value":"","required":"no"}},"Number_of_Full_Time_employees":{"human_name":"Number of Full Time employees","field_type":"number","field_attributes":{"number_min_length":"","number_max_length":"","number_decimal_points":"0","number_nagetive_values":null,"required":"no"}},"Number_of_Part_Time_Employees":{"human_name":"Number of Part Time Employees","field_type":"number","field_attributes":{"number_min_length":"","number_max_length":"","number_decimal_points":"0","number_nagetive_values":null,"required":"no"}},"Description_of_Operations":{"human_name":"Description of Operations","field_type":"text","field_attributes":{"text_min_length":null,"text_max_length":null,"text_default_value":null,"required":"no"}},"Date_of_Start_of_Business":{"human_name":"Date of Start of Business","field_type":"date","field_attributes":{"date_format":"d F Y","required":"no"}},"Status":{"human_name":"Status","field_type":"select","field_attributes":{"select_choices":"Active,\\r\\nInactive","select_default_value":"","required":"no"}}}','','["Street","City","Zip_Code","Country","Date_of_Start_of_Business","Number_of_Full_Time_employees","Number_of_Part_Time_Employees","Description_of_Operations","Status"]'),
 ('40','person','{"First_Name":{"human_name":"First Name","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"yes"}},"Middle_and_Last_Names":{"human_name":"Middle and Last Names","field_type":"text","field_attributes":{"text_min_length":"","text_max_length":"","text_default_value":"","required":"yes"}},"Gender":{"human_name":"Gender","field_type":"select","field_attributes":{"select_choices":"Male,\\r\\nFemale","select_default_value":"","required":"yes"}},"ID_or_Passport_Number":{"human_name":"ID or Passport Number","field_type":"number","field_attributes":{"number_min_length":"","number_max_length":"","number_decimal_points":"0","number_nagetive_values":null,"required":"yes"}},"Date_of_Birth":{"human_name":"Date of Birth","field_type":"date","field_attributes":{"date_format":"d F Y","required":"no"}}}','','["First_Name","Middle_and_Last_Names","Gender","ID_or_Passport_Number","Date_of_Birth"]'),
 ('41','medical_insurance','{"Outpatient":{"human_name":"Outpatient","field_type":"select","field_attributes":{"select_choices":"no,\\r\\nyes","select_default_value":"","required":"no"}},"Blood_Group":{"human_name":"Blood Group","field_type":"select","field_attributes":{"select_choices":"A,\\r\\nB,\\r\\nAB,\\r\\nO","select_default_value":"","required":"no"}},"Personal_Accident_included":{"human_name":"Personal Accident included","field_type":"select","field_attributes":{"select_choices":"no,\\r\\nyes","select_default_value":"","required":"no"}},"Overall_limit_per_year":{"human_name":"Overall limit per year","field_type":"text","field_attributes":{"text_min_length":null,"text_max_length":null,"text_default_value":null,"required":"no"}}}','','["Blood_Group","Outpatient","Overall_limit_per_year","Personal_Accident_included"]'),
 ('42','ships','','','');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_imgmanager` 
-- 

INSERT INTO `itr_imgmanager` (`id`, `imgname`, `filename`, `imglocation`, `uploaddate`) VALUES ('256','banking.png','929banking.png','categories','1317632193'),
 ('272','Current.jpg','873Current.jpg','subcategories','1321529472'),
 ('273','Savings.jpg','554Savings.jpg','subcategories','1321529521'),
 ('274','Fixed deposit.jpg','812Fixed deposit.jpg','subcategories','1321529563'),
 ('275','Cheque.jpg','691Cheque.jpg','subcategories','1321529601'),
 ('276','Student.jpg','60Student.jpg','subcategories','1321529648'),
 ('277','Junior.jpg','606Junior.jpg','subcategories','1321529697'),
 ('263','insurance.png','897insurance.png','categories','1317636161'),
 ('279','Home.jpg','296Home.jpg','subcategories','1321529816'),
 ('278','Car 1 - Copy.jpg','845Car 1 - Copy.jpg','subcategories','1321529770'),
 ('266','mortgage.png','0mortgage.png','categories','1317637146'),
 ('419','House 10.JPG','462House 10.JPG','subcategories','1329819022'),
 ('282','Credit Card.jpg','530Credit Card.jpg','categories','1321529955'),
 ('269','loans.png','106loans.png','categories','1317638349'),
 ('280','Medical.jpg','275Medical.jpg','subcategories','1321529851'),
 ('328','MP900402447.2.jpg','634MP900402447.2.jpg','subcategories','1327921508'),
 ('284','Broaadband.jpg','192Broaadband.jpg','subcategories','1321530043'),
 ('285','creditcard.png','973creditcard.png','services','1321559731'),
 ('355','MP900262215.JPG','78MP900262215.JPG','subcategories','1327941988'),
 ('304','MP900309600.JPG','888MP900309600.JPG','subcategories','1327911439'),
 ('375','MP900404902.JPG','518MP900404902.JPG','subcategories','1328110356'),
 ('306','MP900448656.JPG','757MP900448656.JPG','subcategories','1327912890'),
 ('307','MP900305930.JPG','842MP900305930.JPG','categories','1327913029'),
 ('421','Cable 1.jpg','413Cable 1.jpg','subcategories','1330426192'),
 ('456','standard-chartered.png','250standard-chartered.png','services','1331063924'),
 ('457','standard-chartered.png','491standard-chartered.png','services','1331063950'),
 ('427','9ecd376e5371efaef9aad9bc9143aed8_L.jpg','4089ecd376e5371efaef9aad9bc9143aed8_L.jpg','services','1331034710'),
 ('428','9ecd376e5371efaef9aad9bc9143aed8_L.jpg','4459ecd376e5371efaef9aad9bc9143aed8_L.jpg','services','1331034740'),
 ('429','9ecd376e5371efaef9aad9bc9143aed8_L.jpg','5779ecd376e5371efaef9aad9bc9143aed8_L.jpg','services','1331034771'),
 ('430','9ecd376e5371efaef9aad9bc9143aed8_L.jpg','6579ecd376e5371efaef9aad9bc9143aed8_L.jpg','services','1331034793'),
 ('431','9ecd376e5371efaef9aad9bc9143aed8_L.jpg','909ecd376e5371efaef9aad9bc9143aed8_L.jpg','services','1331034815'),
 ('432','9ecd376e5371efaef9aad9bc9143aed8_L.jpg','3939ecd376e5371efaef9aad9bc9143aed8_L.jpg','services','1331034839'),
 ('433','9ecd376e5371efaef9aad9bc9143aed8_L.jpg','3239ecd376e5371efaef9aad9bc9143aed8_L.jpg','services','1331038511'),
 ('434','9ecd376e5371efaef9aad9bc9143aed8_L.jpg','5299ecd376e5371efaef9aad9bc9143aed8_L.jpg','services','1331038532'),
 ('435','9ecd376e5371efaef9aad9bc9143aed8_L.jpg','8559ecd376e5371efaef9aad9bc9143aed8_L.jpg','services','1331038544'),
 ('436','3145barclays_logo.jpg','9333145barclays_logo.jpg','services','1331038873'),
 ('437','3145barclays_logo.jpg','5163145barclays_logo.jpg','services','1331038908'),
 ('438','Commercial Bank of Africa logo 2011.png','829Commercial Bank of Africa logo 2011.png','services','1331039080'),
 ('439','Commercial Bank of Africa logo 2011.png','36Commercial Bank of Africa logo 2011.png','services','1331039098'),
 ('440','nbk_logo.jpg','202nbk_logo.jpg','services','1331039131'),
 ('441','zuku.gif','915zuku.gif','services','1331039206'),
 ('442','AccessKenya.png','719AccessKenya.png','services','1331039247'),
 ('443','yu-logo.jpg','657yu-logo.jpg','services','1331039323'),
 ('445','nbk_logo.jpg','413nbk_logo.jpg','services','1331040166'),
 ('446','nbk_logo.jpg','356nbk_logo.jpg','services','1331040203'),
 ('447','nbk_logo.jpg','494nbk_logo.jpg','services','1331040239'),
 ('448','nbk_logo.jpg','863nbk_logo.jpg','services','1331040303'),
 ('449','KCB.jpg','500KCB.jpg','services','1331040398'),
 ('450','KCB.jpg','620KCB.jpg','services','1331040414'),
 ('451','Commercial Bank of Africa logo 2011.png','869Commercial Bank of Africa logo 2011.png','services','1331040451'),
 ('452','airtel-new-logo1.jpg','271airtel-new-logo1.jpg','services','1331040474'),
 ('453','safaricomlogo_jpg_410x270_upscale_q85.jpg','937safaricomlogo_jpg_410x270_upscale_q85.jpg','services','1331040520'),
 ('454','Orange-Kenya-2.png','779Orange-Kenya-2.png','services','1331040568'),
 ('455','nbk_logo.jpg','555nbk_logo.jpg','services','1331040677');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_insurer_agents` 
-- 

INSERT INTO `itr_insurer_agents` (`id`, `names`, `physical_location`, `telephone_number`, `email_address`, `users_id`) VALUES ('2','Stanley Ngumo','Hazina Towers','0722958720','sngumo@gmail.com','54'),
 ('3','Chris Odindo','United Kingdom','0722859674','chris.odindo@cubicmedia.co.uk','0');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_insurers` 
-- 

INSERT INTO `itr_insurers` (`id`, `name`, `official_name`, `email_address`) VALUES ('13','AIG Insurance Limited','AIG Insurance Limited Kenya','info@aig.co.ke'),
 ('14','Jubilee Insurance Company of Kenya','The Jubilee Insurance Company of Kenya','info@jubilee.co.ke');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_make` 
-- 

INSERT INTO `itr_make` (`id`, `code`, `title`) VALUES ('1','ACURA','Acura'),
 ('2','ALFA','Alfa Romeo'),
 ('3','AMC','AMC'),
 ('4','ASTON','Aston Martin'),
 ('5','AUDI','Audi'),
 ('6','AVANTI','Avanti'),
 ('7','BENTL','Bentley'),
 ('8','BMW','BMW'),
 ('9','BUICK','Buick'),
 ('10','CAD','Cadillac'),
 ('11','CHEV','Chevrolet'),
 ('12','CHRY','Chrysler'),
 ('13','DAEW','Daewoo'),
 ('14','DAIHAT','Daihatsu'),
 ('15','DATSUN','Datsun'),
 ('16','DELOREAN','DeLorean'),
 ('17','DODGE','Dodge'),
 ('18','EAGLE','Eagle'),
 ('19','FER','Ferrari'),
 ('20','FIAT','FIAT'),
 ('21','FISK','Fisker'),
 ('22','FORD','Ford'),
 ('23','FREIGHT','Freightliner'),
 ('24','GEO','Geo'),
 ('25','GMC','GMC'),
 ('26','HONDA','Honda'),
 ('27','AMGEN','HUMMER'),
 ('28','HYUND','Hyundai'),
 ('29','INFIN','Infiniti'),
 ('30','ISU','Isuzu'),
 ('31','JAG','Jaguar'),
 ('32','JEEP','Jeep'),
 ('33','KIA','Kia'),
 ('34','LAM','Lamborghini'),
 ('35','LAN','Lancia'),
 ('36','ROV','Land Rover'),
 ('37','LEXUS','Lexus'),
 ('38','LINC','Lincoln'),
 ('39','LOTUS','Lotus'),
 ('40','MAS','Maserati'),
 ('41','MAYBACH','Maybach'),
 ('42','MAZDA','Mazda'),
 ('43','MCLAREN','McLaren'),
 ('44','MB','Mercedes-Benz'),
 ('45','MERC','Mercury'),
 ('46','MERKUR','Merkur'),
 ('47','MINI','MINI'),
 ('48','MIT','Mitsubishi'),
 ('49','NISSAN','Nissan'),
 ('50','OLDS','Oldsmobile'),
 ('51','PEUG','Peugeot'),
 ('52','PLYM','Plymouth'),
 ('53','PONT','Pontiac'),
 ('54','POR','Porsche'),
 ('55','RAM','RAM'),
 ('56','REN','Renault'),
 ('57','RR','Rolls-Royce'),
 ('58','SAAB','Saab'),
 ('59','SATURN','Saturn'),
 ('60','SCION','Scion'),
 ('61','SMART','smart'),
 ('62','SRT','SRT'),
 ('63','STERL','Sterling'),
 ('64','SUB','Subaru'),
 ('65','SUZUKI','Suzuki'),
 ('66','TESLA','Tesla'),
 ('67','TOYOTA','Toyota'),
 ('68','TRI','Triumph'),
 ('69','VOLKS','Volkswagen'),
 ('70','VOLVO','Volvo'),
 ('71','YUGO','Yugo');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_medical_pricing` 
-- 

INSERT INTO `itr_medical_pricing` (`id`, `agerange_benefits`, `P1`, `P2`, `P3`, `P4`) VALUES ('37','Ac','11250','13750','16500','19000'),
 ('38','A1s','17500','21500','26000','30000'),
 ('39','A1','21000','26000','31000','35750'),
 ('40','Ac','11250','13750','16500','19000'),
 ('41','A2s','18250','22500','27000','31250'),
 ('42','A2','22000','27000','32250','37250'),
 ('43','Ac','11250','13750','16500','19000'),
 ('44','A3s','22500','27500','33000','38250'),
 ('45','A3','26750','33000','39250','45500'),
 ('46','Ac','11250','13750','16500','19000'),
 ('47','A4s','29750','36750','44000','51000'),
 ('48','A4','35750','44000','52500','60750'),
 ('49','Ac','11250','13750','16500','19000'),
 ('50','A4s','29750','36750','44000','51000'),
 ('51','A4','35750','44000','52500','60750'),
 ('52','Ac','11250','13750','16500','19000'),
 ('53','A5s','48250','59750','71500','82750'),
 ('54','A5','58250','71500','85500','98750'),
 ('55','Ba1','18750','22500','26250','30000'),
 ('56','Ba2','22500','27000','31500','36000'),
 ('57','Ba3','24000','28750','33500','38500'),
 ('58','Ba4','24000','28750','33500','38500'),
 ('59','Bb1','0','0','0','0'),
 ('60','Bb2','0','0','0','0'),
 ('61','Bb3','0','0','25800','0'),
 ('62','Bb4','0','0','0','32250'),
 ('63','Bc1','900','0','0','0'),
 ('64','Bc2','0','900','0','0'),
 ('65','Bc3','0','0','1800','0'),
 ('66','Bc4','0','0','0','1800'),
 ('67','Bd1','500','0','0','0'),
 ('68','Bd2','0','500','0','0'),
 ('69','Bd3','0','0','500','0'),
 ('70','Bd4','0','0','0','500');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_menu_groups` 
-- 

INSERT INTO `itr_menu_groups` (`id`, `title`, `alias`, `description`, `accesslevels_id`, `permissions`) VALUES ('1','Admin Menu','admin-menu','The admin menu for the site','9',''),
 ('2','Frontend','frontend','Frontend menu','17',''),
 ('3','Super Admin','super-admin','Super Admin menu','7',''),
 ('4','Footer','footer','Footer menu','17',''),
 ('5','Technical Menu','technical-menu','The IT Specific menu for administration of the Esurance System','18','');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_menus` 
-- 

INSERT INTO `itr_menus` (`id`, `linkname`, `linkalias`, `href`, `linkorder`, `menu_groups_id`, `parentid`, `published`, `home`, `params`) VALUES ('58','Financials','financials','/admin/financials','4','1','0','0','',''),
 ('85','Dashboard','dashboard','{base}/admin/dashboard','1','1','0','1','yes',''),
 ('88','Companies','companies','/admin/companies','5','1','0','0','',''),
 ('84','Customers','customers','/admin/customers','2','1','0','1','',''),
 ('107','Reports','reports','/admin/reports','6','1','0','1','no',''),
 ('105','Setup','setup','{base}/admin/setup','1','3','0','1','yes',''),
 ('102','Policies','policies','/admin/policies','5','1','0','1',' ',''),
 ('104','Quotes','quotes','/admin/quotes','3','1','0','1','',''),
 ('106','Menus','menus','/admin/navigation','2','5','0','1','no',''),
 ('108','Add Policy','add-policy','/admin/policies/add','1','1','102','1','','{"icopath":"images\\/add_navicon.png"}'),
 ('109','Policies Listing','policies-listing','/admin/policies','3','1','102','1','','{"icopath":"images\\/policies_navicon.png"}'),
 ('110','Add Quote','add-quotes','/admin/quotes/add','1','1','104','1','','{"icopath":"images\\/add_navicon.png"}'),
 ('111','Quote Listing','quote-listing','/admin/quotes','2','1','104','1','','{"icopath":"images\\/quotes_navicon.png"}'),
 ('112','Global Settings','global','#','2','3','0','1','',''),
 ('113','Navigation','navigation','/admin/navigation/show','3','3','112','1','',''),
 ('114','Access Levels','access','/admin/navigation/access','2','3','112','1','',''),
 ('115','User Management','users','/admin/users','1','3','112','1','',''),
 ('116','System Policies','policies','/admin/navigation/access/policies','4','3','112','1','','{"icopath":"images\\/issue_policy_icon.png"}'),
 ('117','Rates','rates','{base}/admin/rates/show','0','1','0','1','yes','');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_other_covers` 
-- 

INSERT INTO `itr_other_covers` (`id`, `details`, `type`, `step`, `user_profiles_id`, `customer_data_id`, `no_of_covers`) VALUES ('1','{"no_of_covers":"2","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"Joan Chizi","other_names_1":"Mutsungah","day_1":"4","month_1":"June","year_1":"1991","gender_1":"Male","age_bracket_1":"19-30","id_or_passport_no_1":"22925055","nhif_no_1":"456987123","blood_type_1":"A","nationality_1":"KE","relation_to_proposer_1":"Brother","occupation_1":"","coreplan_1":"2 advanced","Ba2_1":"Ba2","Bc2_1":"Bc2","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"Pithon","other_names_2":"Kamau","day_2":"3","month_2":"January","year_2":"2012","gender_2":"Male","age_bracket_2":"31-40","id_or_passport_no_2":"54789625","nhif_no_2":"54789652314","blood_type_2":"A","nationality_2":"KE","relation_to_proposer_2":"Son","occupation_2":"","coreplan_2":"2 advanced","Ba2_2":"Ba2","Bc2_2":"Bc2"}','medical','dependants','38','53','2'),
 ('2','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"12457896","chassis_no_1":"659854879856","engine_no_1":"gvhvhgvh","make_1":"Fiat","model_1":"Carina","type_of_body_1":"Salon","seating_capacity_1":"5","value_including_accessories_an_1":"2700000","year_of_manufacture_1":"2011","what_is_your_no_claim_bonus_en_1":"10","is_the_vehicle_fitted_with_any_1":"1","if_no_please_give_particulars_1":"","where_is_this_car_normally_par_1":"Kept on a public","tell_us_the_town_estate_or_roa_1":"Nairobi","how_do_you_use_this_vehicle_1":"For social, domestic and pleasure purposes"}','motor','other_cars','38','49','1'),
 ('3','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"12457896","chassis_no_1":"nb n n bn n","make_1":"BMW","model_1":"Carina","type_of_body_1":"Salon","value_including_accessories_an_1":"2700000","minyear":"","maxyear":"","type_of_cover":"Comprehensive","what_is_your_no_claim_bonus_en_1":"","is_the_vehicle_fitted_with_any_1":"1","if_no_please_give_particulars_1":"Test Device","where_is_this_car_normally_par_1":"Driveway","tell_us_the_town_estate_or_roa_1":"Nairobi","how_do_you_use_this_vehicle_1":"For social, domestic and pleasure purposes"}','motor','other_cars','38','58','1'),
 ('4','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"12457896","chassis_no_1":"nb n n bn n","make_1":"AUDI","model_1":"Carina","type_of_body_1":"Salon","value_including_accessories_an_1":"2700000","year_1":"2009","type_of_cover_1":"Comprehensive","riots_strikes_1":"1","what_is_your_no_claim_bonus_en_1":"0","is_the_vehicle_fitted_with_any_1":"1","if_no_please_give_particulars_1":"","where_is_this_car_normally_par_1":"Driveway","tell_us_the_town_estate_or_roa_1":"Nairobi","how_do_you_use_this_vehicle_1":"For professional and business purposes","save_cars_2":"save_cars_2","registration_nomark_2":"123654857496","chassis_no_2":"98784565","make_2":"EAGLE","model_2":"Toyota","type_of_body_2":"Salon","value_including_accessories_an_2":"2000000","year_2":"2006","type_of_cover_2":"Comprehensive","windscreen_2":"1","radio_cassette_2":"1","what_is_your_no_claim_bonus_en_2":"10","is_the_vehicle_fitted_with_any_2":"1","if_no_please_give_particulars_2":"","where_is_this_car_normally_par_2":"Kept on a public road","tell_us_the_town_estate_or_roa_2":"Nairobi","how_do_you_use_this_vehicle_2":"For social, domestic and pleasure purposes"}','motor','other_cars','38','70','2'),
 ('5','{"no_of_other_covers":"1","other_cover_name1":"Test Other","other_cover_occupation1":"Test occupation","other_cover_date_of_birth1":"10-03-1983","other_cover_relationship1":"husband","other_cover_passport_no1":"879897899","other_cover_cover_type1":"africa basic plan","other_cover_days1":"7"}','travel','step3','38','71','1'),
 ('6','{"no_of_other_covers":"1","other_cover_name1":"Test Other","other_cover_relationship1":"wife","other_cover_agebracket1":"3-17","other_cover_education1":"primary","other_cover_band1":"band3","other_cover_class1":"classI"}','pa','step3','38','74','1'),
 ('7','{"no_of_other_covers":"0"}','pa','step3','42','81','0'),
 ('8','{"no_of_other_covers":"2","other_cover_name1":"Test Child 1","other_cover_occupation1":"Test Occupation","other_cover_date_of_birth1":"10-3-1983","other_cover_relationship1":"wife","other_cover_passport_no1":"879865","other_cover_cover_type1":"Europe Plus Plan","other_cover_days1":"21","other_cover_name2":"Test 2","other_cover_occupation2":"Occupation","other_cover_date_of_birth2":"10-3-1985","other_cover_relationship2":"wife","other_cover_passport_no2":"8798650","other_cover_cover_type2":"Europe Plus Plan","other_cover_days2":"21"}','travel','step3','32','91','2'),
 ('9','{"no_of_other_covers":"3","other_cover_name1":"Agnes Anyango Odongo","other_cover_relationship1":"wife","other_cover_agebracket1":"26 - 30","other_cover_education1":"college","other_cover_band1":"band3","other_cover_class1":"classII","other_cover_name2":"Shanice Achieng","other_cover_relationship2":"daughter","other_cover_agebracket2":"3-17","other_cover_education2":"primary","other_cover_band2":"band3","other_cover_class2":"classII","other_cover_name3":"Shabon Amondi","other_cover_relationship3":"daughter","other_cover_agebracket3":"3-17","other_cover_education3":"primary","other_cover_band3":"band3","other_cover_class3":"classII"}','pa','step3','43','97','3'),
 ('10','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"77","chassis_no_1":"88","make_1":"AMGEN","model_1":"yy","type_of_body_1":"yy","value_including_accessories_an_1":"20000000","year_1":"2001","type_of_cover_1":"Comprehensive","riots_strikes_1":"0","what_is_your_no_claim_bonus_en_1":"0","if_no_please_give_particulars_1":"","where_is_this_car_normally_par_1":"Driveway","tell_us_the_town_estate_or_roa_1":"8","how_do_you_use_this_vehicle_1":"For social, domestic and pleasure purposes"}','motor','other_cars','41','108','1'),
 ('12','{"no_of_covers":"3","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"Odongo","other_names_1":"Agnes Anyango","day_1":"12","month_1":"November","year_1":"1984","gender_1":"Female","age_bracket_1":"19-30","id_or_passport_no_1":"230600758","nhif_no_1":"","blood_type_1":"A","nationality_1":"kenyan","relation_to_proposer_1":"Husband","occupation_1":"Buisiness","Ba1_1":"Ba1","save_dependants_2":"save_dependants_2","title_2":"Ms","proposer_surname_2":"Onyango","other_names_2":"Shanice Achieng","day_2":"9","month_2":"August","year_2":"2007","gender_2":"Female","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"A","nationality_2":"kenyan","relation_to_proposer_2":"Father","occupation_2":"","Ba1_2":"Ba1","save_dependants_3":"save_dependants_3","title_3":"Ms","proposer_surname_3":"Onyango","other_names_3":"Shabon  Amondi","day_3":"4","month_3":"April","year_3":"2012","gender_3":"Female","age_bracket_3":"1-18","id_or_passport_no_3":"","nhif_no_3":"","blood_type_3":"A","nationality_3":"kenyan","relation_to_proposer_3":"Father","occupation_3":"","Ba1_3":"Ba1"}','medical','dependants','43','110','3'),
 ('13','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"12457896","chassis_no_1":"nb n n bn n","make_1":"DATSUN","model_1":"Carina","type_of_body_1":"Salon","value_including_accessories_an_1":"2700000","year_1":"2003","type_of_cover_1":"Comprehensive","what_is_your_no_claim_bonus_en_1":"10","is_the_vehicle_fitted_with_any_1":"1","if_no_please_give_particulars_1":"","where_is_this_car_normally_par_1":"Car Park","tell_us_the_town_estate_or_roa_1":"Nairobi","how_do_you_use_this_vehicle_1":"For professional and business purposes"}','motor','other_cars','54','146','1'),
 ('14','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"y","chassis_no_1":"y","make_1":"ALFA","model_1":"y","type_of_body_1":"y","value_including_accessories_an_1":"400000","year_1":"2002","type_of_cover_1":"Comprehensive","riots_strikes_1":"1","windscreen_1":"1","what_is_your_no_claim_bonus_en_1":"0","if_no_please_give_particulars_1":"","where_is_this_car_normally_par_1":"Driveway","tell_us_the_town_estate_or_roa_1":"tt","how_do_you_use_this_vehicle_1":"For social, domestic and pleasure purposes","save_cars_2":"save_cars_2","registration_nomark_2":"vvv","chassis_no_2":"v","make_2":"BUICK","model_2":"yy","type_of_body_2":"y","value_including_accessories_an_2":"2500000","year_2":"2001","type_of_cover_2":"Comprehensive","riots_strikes_2":"1","windscreen_2":"1","radio_cassette_2":"1","passenger_liability_2":"1","terrorism_2":"1","what_is_your_no_claim_bonus_en_2":"","if_no_please_give_particulars_2":"","where_is_this_car_normally_par_2":"Garaged at home","tell_us_the_town_estate_or_roa_2":"7"}','motor','other_cars','53','150','2'),
 ('15','{"no_of_covers":"3","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"7","other_names_1":"7","day_1":"3","month_1":"January","year_1":"2013","gender_1":"Male","age_bracket_1":"19-30","id_or_passport_no_1":"7","nhif_no_1":"y","blood_type_1":"B","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"8","other_names_2":"7","day_2":"3","month_2":"April","year_2":"2010","gender_2":"","age_bracket_2":"31-40","id_or_passport_no_2":"7","nhif_no_2":"","blood_type_2":"","nationality_2":"","relation_to_proposer_2":"","occupation_2":"","Ba3_2":"Ba3","save_dependants_3":"save_dependants_3","title_3":"Mr","proposer_surname_3":"7","other_names_3":"8","day_3":"4","month_3":"June","year_3":"2012","gender_3":"","age_bracket_3":"31-40","id_or_passport_no_3":"","nhif_no_3":"","blood_type_3":"","nationality_3":"","relation_to_proposer_3":"","occupation_3":"","Ba3_3":"Ba3","Bd3_3":"Bd3"}','medical','dependants','53','153','3'),
 ('16','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"Test","other_names_1":"Test2","day_1":"5","month_1":"March","year_1":"1994","gender_1":"Female","age_bracket_1":"19-30","id_or_passport_no_1":"215748789","nhif_no_1":"21456987","blood_type_1":"B","nationality_1":"kenyan","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"2 advanced","Ba2_1":"Ba2","Bc2_1":"Bc2"}','medical','dependants','54','155','1'),
 ('17','{"no_of_covers":"0"}','medical','dependants','8','158','0'),
 ('18','{"no_of_other_covers":"4","other_cover_name1":"","other_cover_occupation1":"","other_cover_date_of_birth1":"","other_cover_relationship1":"wife","other_cover_passport_no1":"","other_cover_cover_type1":"Worldwide Basic Plan","other_cover_days1":"15","other_cover_name2":"","other_cover_occupation2":"","other_cover_date_of_birth2":"","other_cover_relationship2":"wife","other_cover_passport_no2":"","other_cover_cover_type2":"Worldwide Basic Plan","other_cover_days2":"15","other_cover_name3":"","other_cover_occupation3":"","other_cover_date_of_birth3":"","other_cover_relationship3":"wife","other_cover_passport_no3":"","other_cover_cover_type3":"Worldwide Basic Plan","other_cover_days3":"15","other_cover_name4":"","other_cover_occupation4":"","other_cover_date_of_birth4":"","other_cover_relationship4":"wife","other_cover_passport_no4":"","other_cover_cover_type4":"Worldwide Basic Plan","other_cover_days4":"15"}','travel','step3','8','159','4'),
 ('19','{"no_of_other_covers":"0"}','pa','step3','8','160','0'),
 ('20','{"no_of_other_covers":"0"}','pa','step3','53','163','0'),
 ('21','{"no_of_covers":"2","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"o","other_names_1":"a","day_1":"4","month_1":"January","year_1":"2006","gender_1":"","age_bracket_1":"19-30","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"4 royal","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"0","other_names_2":"a","day_2":"6","month_2":"March","year_2":"2008","gender_2":"","age_bracket_2":"41-50","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"","nationality_2":"","relation_to_proposer_2":"","occupation_2":"","coreplan_2":"4 royal"}','medical','dependants','53','168','2'),
 ('22','{"no_of_other_covers":"0"}','pa','step3','53','169','0'),
 ('23','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"aa","chassis_no_1":"y","make_1":"AMC","model_1":"y","type_of_body_1":"y","value_including_accessories_an_1":"1000000","year_1":"2001","type_of_cover_1":"Comprehensive","what_is_your_no_claim_bonus_en_1":"0","if_no_please_give_particulars_1":"yy","where_is_this_car_normally_par_1":"Driveway","tell_us_the_town_estate_or_roa_1":"7","how_do_you_use_this_vehicle_1":"For social, domestic and pleasure purposes","save_cars_2":"save_cars_2","registration_nomark_2":"77u00a777","chassis_no_2":"77u00a7","make_2":"BUICK","model_2":"7u00a7","type_of_body_2":"7","value_including_accessories_an_2":"2000000","year_2":"2002","type_of_cover_2":"Third Party Only","riots_strikes_2":"1","windscreen_2":"1","radio_cassette_2":"1","passenger_liability_2":"1","terrorism_2":"1","what_is_your_no_claim_bonus_en_2":"","if_no_please_give_particulars_2":"","where_is_this_car_normally_par_2":"Driveway","tell_us_the_town_estate_or_roa_2":"7","how_do_you_use_this_vehicle_2":"For social, domestic and pleasure purposes","save_cars_3":"save_cars_3","registration_nomark_3":"6","chassis_no_3":"7","make_3":"DAIHAT","model_3":"7","type_of_body_3":"7","value_including_accessories_an_3":"3000000","year_3":"2001","type_of_cover_3":"Third Party Only","riots_strikes_3":"1","windscreen_3":"1","what_is_your_no_claim_bonus_en_3":"10","is_the_vehicle_fitted_with_any_3":"1","if_no_please_give_particulars_3":"77","where_is_this_car_normally_par_3":"Driveway","tell_us_the_town_estate_or_roa_3":"7","how_do_you_use_this_vehicle_3":"For social, domestic and pleasure purposes"}','motor','other_cars','53','178','3'),
 ('24','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"7","chassis_no_1":"7","make_1":"ACURA","model_1":"7","type_of_body_1":"7","value_including_accessories_an_1":"650000","year_1":"2000","is_the_vehicle_fitted_with_any_1":"0","if_no_please_give_particulars_1":"","where_is_this_car_normally_par_1":"Car Park","tell_us_the_town_estate_or_roa_1":"8","how_do_you_use_this_vehicle_1":"For social, domestic and pleasure purposes","save_cars_2":"save_cars_2","registration_nomark_2":"7","chassis_no_2":"7","make_2":"ASTON","model_2":"8","type_of_body_2":"8","value_including_accessories_an_2":"725000","year_2":"2002","is_the_vehicle_fitted_with_any_2":"1","if_no_please_give_particulars_2":"","where_is_this_car_normally_par_2":"Car Park","tell_us_the_town_estate_or_roa_2":"8","how_do_you_use_this_vehicle_2":"For social, domestic and pleasure purposes","save_cars_3":"save_cars_3","registration_nomark_3":"8","chassis_no_3":"8","make_3":"ALFA","model_3":"7","type_of_body_3":"7","value_including_accessories_an_3":"450000","year_3":"2001","is_the_vehicle_fitted_with_any_3":"1","if_no_please_give_particulars_3":"","where_is_this_car_normally_par_3":"Driveway","tell_us_the_town_estate_or_roa_3":"8","how_do_you_use_this_vehicle_3":"For social, domestic and pleasure purposes"}','motor','other_cars','8','185','3'),
 ('25','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"y","chassis_no_1":"y","make_1":"YUGO","model_1":"y","type_of_body_1":"y","value_including_accessories_an_1":"2400000","year_1":"2000","is_the_vehicle_fitted_with_any_1":"1","if_no_please_give_particulars_1":"","where_is_this_car_normally_par_1":"Driveway","tell_us_the_town_estate_or_roa_1":"7","how_do_you_use_this_vehicle_1":"For social, domestic and pleasure purposes","save_cars_2":"save_cars_2","registration_nomark_2":"7","chassis_no_2":"7","make_2":"AMGEN","model_2":"7","type_of_body_2":"7","value_including_accessories_an_2":"2200000","year_2":"2003","is_the_vehicle_fitted_with_any_2":"0","if_no_please_give_particulars_2":"7","where_is_this_car_normally_par_2":"Driveway","tell_us_the_town_estate_or_roa_2":"7","how_do_you_use_this_vehicle_2":"For social, domestic and pleasure purposes","save_cars_3":"save_cars_3","registration_nomark_3":"7","chassis_no_3":"y","make_3":"AMGEN","model_3":"7","type_of_body_3":"7","value_including_accessories_an_3":"2000000","year_3":"2004","is_the_vehicle_fitted_with_any_3":"0","if_no_please_give_particulars_3":"","where_is_this_car_normally_par_3":"Garaged at home","tell_us_the_town_estate_or_roa_3":"7","how_do_you_use_this_vehicle_3":"For professional and business purposes"}','motor','other_cars','53','186','3'),
 ('26','{"no_of_other_covers":"0"}','pa','step3','8','187','0'),
 ('27','{"no_of_other_covers":"0"}','pa','step3','53','188','0'),
 ('28','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"7","chassis_no_1":"7","make_1":"ALFA","model_1":"7","type_of_body_1":"7","value_including_accessories_an_1":"1200000","year_1":"1999","is_the_vehicle_fitted_with_any_1":"0","if_no_please_give_particulars_1":"7","where_is_this_car_normally_par_1":"Driveway","tell_us_the_town_estate_or_roa_1":"8","save_cars_2":"save_cars_2","registration_nomark_2":"y","chassis_no_2":"y","make_2":"YUGO","model_2":"y","type_of_body_2":"y","value_including_accessories_an_2":"2510000","year_2":"2010","is_the_vehicle_fitted_with_any_2":"0","if_no_please_give_particulars_2":"y","where_is_this_car_normally_par_2":"Driveway","tell_us_the_town_estate_or_roa_2":"7","how_do_you_use_this_vehicle_2":"For social, domestic and pleasure purposes","save_cars_3":"save_cars_3","registration_nomark_3":"7","chassis_no_3":"7","make_3":"DODGE","model_3":"7","type_of_body_3":"7","value_including_accessories_an_3":"10000","year_3":"2001","is_the_vehicle_fitted_with_any_3":"0","if_no_please_give_particulars_3":"7","where_is_this_car_normally_par_3":"Garaged at home","tell_us_the_town_estate_or_roa_3":"8","how_do_you_use_this_vehicle_3":"For social, domestic and pleasure purposes"}','motor','other_cars','53','189','3'),
 ('29','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"12457896","chassis_no_1":"nb n n bn n","make_1":"TOYOTA","model_1":"Carina","type_of_body_1":"Salon","value_including_accessories_an_1":"2700000","year_1":"2004","is_the_vehicle_fitted_with_any_1":"1","if_no_please_give_particulars_1":"","where_is_this_car_normally_par_1":"Car Park","tell_us_the_town_estate_or_roa_1":"Nairobi","how_do_you_use_this_vehicle_1":"For professional and business purposes","save_cars_2":"save_cars_2","registration_nomark_2":"121254587","chassis_no_2":"98974565623","make_2":"TOYOTA","model_2":"Carina","type_of_body_2":"Salon","value_including_accessories_an_2":"2700000","year_2":"2003","is_the_vehicle_fitted_with_any_2":"1","if_no_please_give_particulars_2":"","where_is_this_car_normally_par_2":"Driveway","tell_us_the_town_estate_or_roa_2":"Nairobi","how_do_you_use_this_vehicle_2":"For professional and business purposes","what_is_your_no_claim_bonus_en_1":"0","what_is_your_no_claim_bonus_en_2":"20"}','motor','other_cars','54','190','2'),
 ('30','{"save_other_cars":"save_other_cars","save_cars_1":"save_cars_1","registration_nomark_1":"6","chassis_no_1":"y","make_1":"ALFA","model_1":"6","type_of_body_1":"y","value_including_accessories_an_1":"5000000","year_1":"2000","if_no_please_give_particulars_1":"yy","where_is_this_car_normally_par_1":"Car Park","tell_us_the_town_estate_or_roa_1":"7","how_do_you_use_this_vehicle_1":"For social, domestic and pleasure purposes","save_cars_2":"save_cars_2","registration_nomark_2":"7","chassis_no_2":"8","make_2":"ALFA","model_2":"6","type_of_body_2":"7","value_including_accessories_an_2":"2000000","year_2":"2000","is_the_vehicle_fitted_with_any_2":"0","if_no_please_give_particulars_2":"","where_is_this_car_normally_par_2":"Car Park","tell_us_the_town_estate_or_roa_2":"8","save_cars_3":"save_cars_3","registration_nomark_3":"7","chassis_no_3":"6","make_3":"ASTON","model_3":"8","type_of_body_3":"8","value_including_accessories_an_3":"550000","year_3":"2000","is_the_vehicle_fitted_with_any_3":"0","if_no_please_give_particulars_3":"","where_is_this_car_normally_par_3":"Car Park","tell_us_the_town_estate_or_roa_3":"8","how_do_you_use_this_vehicle_3":"For professional and business purposes","what_is_your_no_claim_bonus_en_1":"20","what_is_your_no_claim_bonus_en_2":"30","what_is_your_no_claim_bonus_en_3":"40"}','motor','other_cars','53','191','3'),
 ('31','{"no_of_other_covers":"0"}','pa','step3','53','194','0'),
 ('32','{"no_of_other_covers":"0"}','pa','step3','53','195','0'),
 ('33','{"no_of_other_covers":"2","other_cover_name1":"cgv","other_cover_occupation1":"jhvjh","other_cover_date_of_birth1":"1990-12-17","other_cover_relationship1":"other","other_cover_passport_no1":"","other_cover_cover_type1":"undefined","other_cover_days1":"7","other_cover_name2":"ffyhygjh","other_cover_occupation2":"hghgjgjh","other_cover_date_of_birth2":"1989-12-02","other_cover_relationship2":"wife","other_cover_passport_no2":"","other_cover_cover_type2":"undefined","other_cover_days2":"7"}','travel','step3','82','238','2'),
 ('34','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Mrs","proposer_surname_1":"Beatrice ","other_names_1":"Nduta Nganga","day_1":"28","month_1":"April","year_1":"1978","gender_1":"Female","age_bracket_1":"31-40","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"kenyan","relation_to_proposer_1":"","occupation_1":""}','medical','dependants','85','242','1'),
 ('35','{"no_of_other_covers":"2"}','travel','step3','82','249','2'),
 ('36','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"jp","other_names_1":"yt","day_1":"12","month_1":"August","year_1":"2007","gender_1":"Male","age_bracket_1":"31-40","id_or_passport_no_1":"133","nhif_no_1":"213451","blood_type_1":"AB","nationality_1":"angolan","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"2 advanced","Ba2_1":"Ba2","Bc2_1":"Bc2","Bd2_1":"Bd2"}','medical','dependants','82','265','1'),
 ('37','{"no_of_covers":"3","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"Ikua","other_names_1":"Janet","day_1":"14","month_1":"January","year_1":"1978","gender_1":"","age_bracket_1":"31-40","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"4 royal","Bc4_1":"Bc4","Bd4_1":"Bd4","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"Ikua","other_names_2":"Peter Daniels","day_2":"5","month_2":"April","year_2":"2010","gender_2":"","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"","nationality_2":"","relation_to_proposer_2":"","occupation_2":"","coreplan_2":"4 royal","Bd4_2":"Bd4","save_dependants_3":"save_dependants_3","title_3":"Mr","proposer_surname_3":"Ikua","other_names_3":"Jasmine","day_3":"17","month_3":"March","year_3":"2013","gender_3":"","age_bracket_3":"1-18","id_or_passport_no_3":"","nhif_no_3":"","blood_type_3":"","nationality_3":"","relation_to_proposer_3":"","occupation_3":"","coreplan_3":"4 royal","Bd4_3":"Bd4"}','medical','dependants','100','271','3'),
 ('38','{"no_of_covers":"2","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"mwaniki","other_names_1":"mary masbeko","day_1":"6","month_1":"April","year_1":"1986","gender_1":"","age_bracket_1":"19-30","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"mwaniki","other_names_2":"lilian haida","day_2":"31","month_2":"May","year_2":"2014","gender_2":"","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"","nationality_2":"","relation_to_proposer_2":"","occupation_2":""}','medical','dependants','101','272','2'),
 ('39','{"no_of_other_covers":"3","other_cover_name1":"","other_cover_occupation1":"","other_cover_date_of_birth1":"","other_cover_relationship1":"","other_cover_passport_no1":"","other_cover_cover_type1":"","other_cover_days1":"","other_cover_name2":"","other_cover_occupation2":"","other_cover_date_of_birth2":"","other_cover_relationship2":"","other_cover_passport_no2":"","other_cover_cover_type2":"","other_cover_days2":"","other_cover_name3":"","other_cover_occupation3":"","other_cover_date_of_birth3":"","other_cover_relationship3":"","other_cover_passport_no3":"","other_cover_cover_type3":"","other_cover_days3":""}','travel','step3','82','276','3'),
 ('40','{"no_of_covers":"0"}','medical','dependants','104','280','0'),
 ('41','{"no_of_other_covers":"2"}','travel','step3','82','282','2'),
 ('42','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"hjhhk","other_names_1":",jk","day_1":"7","month_1":"August","year_1":"1992","gender_1":"","age_bracket_1":"19-30","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"3 executive","Bc3_1":"Bc3"}','medical','dependants','79','284','1'),
 ('43','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"DORINE","other_names_1":"KINA","day_1":"20","month_1":"October","year_1":"1991","gender_1":"Female","age_bracket_1":"19-30","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"WIFE","occupation_1":"","coreplan_1":"3 executive","Ba3_1":"Ba3","Bb1_1":"Bb1","Bb3_1":"Bb3","Bc3_1":"Bc3"}','medical','dependants','105','285','1'),
 ('44','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Mrs","proposer_surname_1":"DORINE","other_names_1":"KINA","day_1":"20","month_1":"October","year_1":"1991","gender_1":"Female","age_bracket_1":"19-30","id_or_passport_no_1":"28971949","nhif_no_1":"","blood_type_1":"","nationality_1":"kenyan","relation_to_proposer_1":"WIFE","occupation_1":"","coreplan_1":"3 executive","Ba3_1":"Ba3","Bb3_1":"Bb3"}','medical','dependants','105','286','1'),
 ('45','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"Test","other_names_1":"Test2","day_1":"4","month_1":"April","year_1":"1996","gender_1":"Female","age_bracket_1":"31-40","id_or_passport_no_1":"215748789","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"2 advanced"}','medical','dependants','8','291','1'),
 ('46','{"no_of_covers":"2","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"Wanja","other_names_1":"Mary Ngotho","day_1":"22","month_1":"February","year_1":"2002","gender_1":"Female","age_bracket_1":"1-18","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"A","nationality_1":"kenyan","relation_to_proposer_1":"Daughter","occupation_1":"Student","coreplan_1":"1 premier","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"Ndila","other_names_2":"Naomi Ngotho","day_2":"26","month_2":"December","year_2":"1965","gender_2":"Female","age_bracket_2":"41-50","id_or_passport_no_2":"7273075","nhif_no_2":"","blood_type_2":"O","nationality_2":"kenyan","relation_to_proposer_2":"Wife","occupation_2":"Teacher","coreplan_2":"1 premier"}','medical','dependants','110','296','2'),
 ('47','{"no_of_covers":"2","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"IDHA","other_names_1":"SABRA SAID","day_1":"5","month_1":"August","year_1":"1982","gender_1":"","age_bracket_1":"31-40","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"3 executive","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"MACKI","other_names_2":"QAMAR HASSAN","day_2":"12","month_2":"May","year_2":"2010","gender_2":"","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"","nationality_2":"","relation_to_proposer_2":"","occupation_2":"","coreplan_2":"3 executive"}','medical','dependants','111','297','2'),
 ('48','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Ms","proposer_surname_1":"jkjkj","other_names_1":"kjkj","day_1":"5","month_1":"May","year_1":"1982","gender_1":"","age_bracket_1":"31-40","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"2 advanced"}','medical','dependants','50','303','1'),
 ('49','{"no_of_covers":"3","save_dependants_1":"save_dependants_1","title_1":"Mrs","proposer_surname_1":"wanza","other_names_1":"dorcas mbuva","day_1":"1","month_1":"May","year_1":"1989","gender_1":"","age_bracket_1":"19-30","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"1 premier","Ba1_1":"Ba1","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"ombwayo ","other_names_2":"jayden jack","day_2":"4","month_2":"September","year_2":"2014","gender_2":"","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"","nationality_2":"","relation_to_proposer_2":"","occupation_2":"","coreplan_2":"1 premier","Ba1_2":"Ba1","save_dependants_3":"save_dependants_3","title_3":"Ms","proposer_surname_3":"wafula","other_names_3":" shirlyn gertrude","day_3":"29","month_3":"November","year_3":"2009","gender_3":"","age_bracket_3":"1-18","id_or_passport_no_3":"","nhif_no_3":"","blood_type_3":"","nationality_3":"","relation_to_proposer_3":"","occupation_3":"","coreplan_3":"1 premier","Ba1_3":"Ba1"}','medical','dependants','116','307','3'),
 ('50','{"no_of_covers":"4","save_dependants_1":"save_dependants_1","title_1":"Mrs","proposer_surname_1":"Gaitho","other_names_1":"Rachel Nyambura Mungai","day_1":"20","month_1":"February","year_1":"1977","gender_1":"","age_bracket_1":"31-40","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"Gaitho","other_names_2":"Kelvin Irungu","day_2":"25","month_2":"January","year_2":"2003","gender_2":"","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"","nationality_2":"","relation_to_proposer_2":"","occupation_2":"","save_dependants_3":"save_dependants_3","title_3":"Mr","proposer_surname_3":"Gaitho","other_names_3":"Alice Nicole Njoki","day_3":"4","month_3":"July","year_3":"2006","gender_3":"","age_bracket_3":"1-18","id_or_passport_no_3":"","nhif_no_3":"","blood_type_3":"","nationality_3":"","relation_to_proposer_3":"","occupation_3":"","save_dependants_4":"save_dependants_4","title_4":"Mr","proposer_surname_4":"Gaitho","other_names_4":"Zawadi Serah Wanjiku","day_4":"4","month_4":"September","year_4":"2010","gender_4":"","age_bracket_4":"1-18","id_or_passport_no_4":"","nhif_no_4":"","blood_type_4":"","nationality_4":"","relation_to_proposer_4":"","occupation_4":""}','medical','dependants','131','329','4'),
 ('51','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"sue","other_names_1":"muthoni","day_1":"4","month_1":"November","year_1":"1985","gender_1":"","age_bracket_1":"19-30","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"1 premier"}','medical','dependants','133','333','1'),
 ('52','{"no_of_covers":"3","save_dependants_1":"save_dependants_1","title_1":"Ms","proposer_surname_1":"Ochieng","other_names_1":"Fiona Fontes","day_1":"29","month_1":"September","year_1":"1999","gender_1":"Female","age_bracket_1":"1-18","id_or_passport_no_1":"","nhif_no_1":"0100497","blood_type_1":"B","nationality_1":"kenyan","relation_to_proposer_1":"Daughter","occupation_1":"Student","coreplan_1":"4 royal","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"Ochieng","other_names_2":"Hillary Hanson","day_2":"12","month_2":"May","year_2":"2004","gender_2":"Male","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"0100497","blood_type_2":"B","nationality_2":"kenyan","relation_to_proposer_2":"Son","occupation_2":"Pupil","coreplan_2":"4 royal","save_dependants_3":"save_dependants_3","title_3":"Mrs","proposer_surname_3":"Odhiambo","other_names_3":"Judith Atieno","day_3":"24","month_3":"December","year_3":"1973","gender_3":"Female","age_bracket_3":"41-50","id_or_passport_no_3":"11632387","nhif_no_3":"","blood_type_3":"O","nationality_3":"kenyan","relation_to_proposer_3":"Wife","occupation_3":"Business woman","coreplan_3":"4 royal"}','medical','dependants','134','334','3'),
 ('53','{"no_of_covers":"3","save_dependants_1":"save_dependants_1","title_1":"Mrs","proposer_surname_1":"kamau","other_names_1":"pauline","day_1":"2","month_1":"February","year_1":"1985","gender_1":"","age_bracket_1":"19-30","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"wife","occupation_1":"","coreplan_1":"1 premier","Ba1_1":"Ba1","Bc1_1":"Bc1","Bd1_1":"Bd1","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"kamau","other_names_2":"alvin","day_2":"1","month_2":"January","year_2":"2011","gender_2":"","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"","nationality_2":"","relation_to_proposer_2":"","occupation_2":"","coreplan_2":"1 premier","Ba1_2":"Ba1","Bc1_2":"Bc1","Bd1_2":"Bd1","save_dependants_3":"save_dependants_3","title_3":"Mr","proposer_surname_3":"kamau","other_names_3":"ian","day_3":"1","month_3":"January","year_3":"2013","gender_3":"","age_bracket_3":"1-18","id_or_passport_no_3":"","nhif_no_3":"","blood_type_3":"","nationality_3":"","relation_to_proposer_3":"","occupation_3":"","coreplan_3":"1 premier","Ba1_3":"Ba1","Bc1_3":"Bc1","Bd1_3":"Bd1"}','medical','dependants','135','337','3'),
 ('54','{"no_of_covers":"2","save_dependants_1":"save_dependants_1","title_1":"Ms","proposer_surname_1":"dscdsc","other_names_1":"dedewxc","day_1":"16","month_1":"June","year_1":"1982","gender_1":"Female","age_bracket_1":"31-40","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"kenyan","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"3 executive","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"dscc","other_names_2":"hmk","day_2":"18","month_2":"August","year_2":"2012","gender_2":"Male","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"","nationality_2":"kenyan","relation_to_proposer_2":"","occupation_2":"","coreplan_2":"3 executive"}','medical','dependants','139','342','2'),
 ('55','{"no_of_covers":"2","save_dependants_1":"save_dependants_1","title_1":"Mrs","proposer_surname_1":"Tuju","other_names_1":"Myra","day_1":"27","month_1":"November","year_1":"1985","gender_1":"Female","age_bracket_1":"19-30","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"kenyan","relation_to_proposer_1":"Wife","occupation_1":"","coreplan_1":"2 advanced","Ba2_1":"Ba2","Bc2_1":"Bc2","Bd2_1":"Bd2","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"Tuju","other_names_2":"Zachary","day_2":"2","month_2":"July","year_2":"2014","gender_2":"Male","age_bracket_2":"1-18","id_or_passport_no_2":"","nhif_no_2":"","blood_type_2":"","nationality_2":"","relation_to_proposer_2":"Son","occupation_2":"","coreplan_2":"2 advanced","Ba2_2":"Ba2","Bc2_2":"Bc2","Bd2_2":"Bd2"}','medical','dependants','140','343','2'),
 ('56','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Ms","proposer_surname_1":"Mururi","other_names_1":"Maurine","day_1":"29","month_1":"June","year_1":"1988","gender_1":"Female","age_bracket_1":"19-30","id_or_passport_no_1":"25990088","nhif_no_1":"N/A","blood_type_1":"O","nationality_1":"kenyan","relation_to_proposer_1":"Wife","occupation_1":"Student","coreplan_1":"1 premier"}','medical','dependants','154','359','1'),
 ('57','{"no_of_covers":"2","save_dependants_1":"save_dependants_1","title_1":"Mr","proposer_surname_1":"sebei","other_names_1":"rebby","day_1":"24","month_1":"December","year_1":"1989","gender_1":"Female","age_bracket_1":"19-30","id_or_passport_no_1":"27784664","nhif_no_1":"2580741","blood_type_1":"O","nationality_1":"kenyan","relation_to_proposer_1":"","occupation_1":"","coreplan_1":"4 royal","save_dependants_2":"save_dependants_2","title_2":"Mr","proposer_surname_2":"lorot","other_names_2":"derick mosop","day_2":"1","month_2":"January","year_2":"2010","gender_2":"Male","age_bracket_2":"1-18","id_or_passport_no_2":"nil","nhif_no_2":"nil","blood_type_2":"AB","nationality_2":"kenyan","relation_to_proposer_2":"son","occupation_2":"nil","coreplan_2":"4 royal"}','medical','dependants','156','361','2'),
 ('58','{"no_of_covers":"0"}','medical','dependants','162','367','0'),
 ('59','{"no_of_covers":"1","save_dependants_1":"save_dependants_1","title_1":"Mrs","proposer_surname_1":"wanjiru","other_names_1":"eva","day_1":"9","month_1":"September","year_1":"1956","gender_1":"","age_bracket_1":"51-59","id_or_passport_no_1":"","nhif_no_1":"","blood_type_1":"","nationality_1":"","relation_to_proposer_1":"","occupation_1":""}','medical','dependants','167','371','1');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_own_company` 
-- 

INSERT INTO `itr_own_company` (`id`, `name`, `email_address`, `postal_address`, `telephone`, `physical_details`, `contact_person`) VALUES ('1','Transafricana Limited','info@bima247.com','57377-00100','254784512','{"location":"Utalii Lane","zipcode":"254","citycounty":"Kiambu","country":"KE"}','');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_personal_cover_pricing` 
-- 

INSERT INTO `itr_personal_cover_pricing` (`id`, `age_bracket`, `class`, `band`, `premium`) VALUES ('2','A1','C1','B1','2500.00'),
 ('3','A1','C1','B2','3600.00'),
 ('4','A1','C1','B3','4300.00'),
 ('5','A1','C1','B4','7530.00'),
 ('6','A1','C1','B5','14100.00'),
 ('7','A1','C1','B6','28230.00'),
 ('8','A1','C1','B1','37300.00'),
 ('9','A1','C2','B1','2500.00'),
 ('10','A1','C2','B2','3600.00'),
 ('11','A1','C2','B3','4300.00'),
 ('12','A1','C2','B4','7530.00'),
 ('13','A1','C2','B5','14100.00'),
 ('14','A1','C2','B6','28230.00'),
 ('15','A1','C2','B7','37300.00'),
 ('16','A2','C1','B1','3300.00'),
 ('17','A2','C1','B2','5100.00'),
 ('18','A2','C1','B3','6300.00'),
 ('19','A2','C1','B4','10530.00'),
 ('20','A2','C1','B5','18100.00'),
 ('21','A2','C1','B6','33230.00'),
 ('22','A2','C1','B7','43300.00'),
 ('23','A2','C2','B1','3750.00'),
 ('24','A2','C2','B2','5900.00'),
 ('25','A2','C2','B3','7130.00'),
 ('26','A2','C2','B4','11880.00'),
 ('27','A2','C2','B5','20310.00'),
 ('28','A2','C2','B6','37050.00'),
 ('29','A2','C2','B7','48230.00'),
 ('30','A3','C1','B1','3650.00'),
 ('31','A3','C1','B2','5650.00'),
 ('32','A3','C1','B3','6930.00'),
 ('33','A3','C1','B4','11580.00'),
 ('34','A3','C1','B5','19910.00'),
 ('35','A3','C1','B6','36550.00'),
 ('36','A3','C1','B7','47630.00'),
 ('37','A3','C2','B1','4300.00'),
 ('38','A3','C2','B2','6500.00'),
 ('39','A3','C2','B3','7840.00'),
 ('40','A3','C2','B4','13070.00'),
 ('41','A3','C2','B5','22340.00'),
 ('42','A3','C1','B6','40750.00'),
 ('43','A3','C2','B7','53050.00');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_policies` 
-- 

INSERT INTO `itr_policies` (`id`, `policy_number`, `customers_id`, `issue_date`, `start_date`, `end_date`, `insurers_id`, `products_id`, `customer_quotes_id`, `status`, `datetime`, `currency_code`, `amount`) VALUES ('1','ESU201601','82','1453849200','1452034800','1483657200','14','7','695','issued','1453895257','ksh','10000'),
 ('11','ESU5487213278','79','1472421600','1471989600','1503525600','14','8','693','issued','1472060136','ksh','7500'),
 ('12','465656646','6','1490137200','1489014000','1490824800','14','1','11','issued','1488984330','ksh','262500'),
 ('13','--not issued--','6','0','1490738400','1489532400','14','1','11','','1488985144','ksh','262500'),
 ('14','--not issued--','6','0','1490738400','1489532400','14','1','11','','1488985231','ksh','262500'),
 ('15','--not issued--','6','0','1490738400','1489532400','14','1','11','','1488985265','ksh','262500'),
 ('16','--not issued--','6','0','1490738400','1489532400','14','1','11','','1488985321','ksh','262500'),
 ('17','--not issued--','3','0','1489705200','1490310000','14','1','16','','1489045764','ksh','384500'),
 ('18','--not issued--','3','0','1489705200','1490310000','14','1','16','','1489049924','ksh','384500'),
 ('19','--not issued--','3','0','1489532400','1490911200','14','1','16','','1489053869','ksh','384500'),
 ('20','--not issued--','3','0','1489532400','1490911200','14','1','16','','1489054197','ksh','384500'),
 ('21','--not issued--','3','0','1490050800','1490738400','14','1','16','','1489070822','ksh','384500'),
 ('22','--not issued--','6','0','1489014000','1490738400','14','1','15','','1489073127','ksh','310000');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_policies_documents` 
-- 

INSERT INTO `itr_policies_documents` (`id`, `policies_id`, `documents_id`, `column_4`) VALUES ('5','20','88','0'),
 ('6','22','89','0'),
 ('7','22','90','0');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_products` 
-- 

INSERT INTO `itr_products` (`id`, `name`, `alias`, `type`, `entity_types_id`, `forms_id`, `multiple_entities`) VALUES ('1','Motor Insurance','motor_insurance','0','4','1','1'),
 ('5','Personal Accident','personal_accident','0','2','25','0'),
 ('7','Travel Insurance','travel_insurance','0','2','37','1'),
 ('8','Domestic Package','domestic_package','0','3','36','0'),
 ('9','Medical Insurance','medical_insurance','0','2','41','1');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_quotes_documents` 
-- 

INSERT INTO `itr_quotes_documents` (`id`, `customer_quotes_id`, `documents_id`) VALUES ('8','699','47'),
 ('21','702','60'),
 ('24','702','63'),
 ('33','715','72'),
 ('34','715','73'),
 ('35','720','74'),
 ('36','20','75'),
 ('37','15','76'),
 ('38','20','77');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_rates` 
-- 

INSERT INTO `itr_rates` (`id`, `rate_name`, `rate_value`, `rate_type`, `rate_category`, `insurer_id`) VALUES ('1','Third Party Only','7500','Fixed','Motor','14'),
 ('2','Third Party Fire and Theft','4.8','Percentage','Motor','14'),
 ('3','Comprehensive','7.5','Percentage','Motor','14'),
 ('4','Riots and Strikes','2.5','Percentage','Motor','14'),
 ('5','Motor Policy Levy','0.25','Percentage','Motor','14'),
 ('6','Section A','1.5','Percentage','Property','14'),
 ('7','Section B','8.0','Percentage','Property','14'),
 ('8','Section C','14','Percentage','Property','14'),
 ('9','Workmen Compensation','500','Fixed','Property','14'),
 ('10','Property Policy Levy','0.25','Percentage','Property','14'),
 ('12','Owners Liability','1000','For any extra 1M','Property','14'),
 ('13','Occupier Liability','1000','For any extra 1M','Property','14'),
 ('14','Terrorism','0.25','Percentage','Motor','14'),
 ('15','Training Levy','0.2','Percentage','Travel','14'),
 ('16','P.H.C.F Fund','0.25','Percentage','Travel','14'),
 ('17','Stamp Duty','40','Fixed','Travel','14'),
 ('18','Windscreen','10','Percentage','Motor','14'),
 ('19','Audio System','10','Percentage','Motor','14'),
 ('20','Passenger Liability','10','Percentage','Motor','14'),
 ('21','Medical Levy','0.20','Percentage','Medical','14'),
 ('22','P.H.C.F Fund','0.25','Percentage','Medical','14'),
 ('23','Stamp Duty','40.00','Fixed','Medical','14'),
 ('29','Test Rate','98.9','Percentage','Motor','14'),
 ('34','Test Rate3','34','Percentage','Motor','14');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_site_activity` 
-- 

INSERT INTO `itr_site_activity` (`id`, `insurance_type`, `hits`, `datetime`) VALUES ('1','motor','333','1425374515'),
 ('2','property','145','1416305415');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_subscriber_data` 
-- 

INSERT INTO `itr_subscriber_data` (`sbid`, `subid`, `step1data`, `step2data`, `step3data`, `status`, `datetime`, `datecompleted`) VALUES ('1','1','{"name_domestic_personal_details":"domestic_personal_details","zebra_honeypot_domestic_personal_details":"","zebra_csrf_token_domestic_personal_details":"34420c9d91f38fd08dae970022715e02","title":"Ms","surname":"Invent","othernames":"Gas 50","occupation":"Christian","dob":"2002-19-02","pin":"A7576432","idpassport":"42378947239","email":"dervismata@gmail.com","mobilenumber":"0790551161","addressbox":"345","addresscode":"5345345","addresstown":"Kajiado","btnsubmit":"Proceed to Property Details >>","step":"1"}','','','domestic_step1','1487765121','0');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_subscribers` 
-- 

INSERT INTO `itr_subscribers` (`subid`, `title`, `name`, `mobile`, `email`, `dob`, `enabled`, `postal_address`, `postal_code`, `town`, `registration_date`) VALUES ('1','','Invent Gas thirty','','Sammy@gmail,com','0000-00-00','0','24','2343243','','2017-02-22 13:33:06');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_tasks` 
-- 

INSERT INTO `itr_tasks` (`id`, `customers_id`, `dategen`, `tasktype`, `subject`, `description`, `priority`, `remainder`, `insurer_agents_id`, `completed`) VALUES ('6','82','1454713200','task','Create quotation for Mr Nyakundi','Create a quotation and send to Mike Michira','1','0','2','1459758343'),
 ('7','100','1454799600','meeting','To call meeting with Ikua George','Schedule meeting with Ikua George','1','1456268400','3','1454946618'),
 ('11','100','1455058800','task','Create quotation for Mr Ikua George','Create quotation and send the quotation to Mr Ikua George','1','1455750000','2','1459758330'),
 ('12','79','1472421600','task','Call Karanja','Call Karanja regarding renewal','1','1472594400','0','1488789809');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_travel_pricing` 
-- 

INSERT INTO `itr_travel_pricing` (`id`, `plan`) VALUES ('1','Africa Basic Plan'),
 ('2','Europe Plus Plan'),
 ('3','Worldwide Basic Plan'),
 ('4','Worldwide Plus Plan'),
 ('5','Worldwide Extra'),
 ('6','Haj and Umrah Plan Basic '),
 ('7','Haj and Umrah Plan Plus'),
 ('8','Haj and Umrah Plan Extra');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_user_profiles` 
-- 

INSERT INTO `itr_user_profiles` (`id`, `name`, `mobile_no`, `email`, `enabled`, `regdate`, `postal_address`, `date_of_birth`, `postal_code`) VALUES ('236','System Administrator','0722958720','sngumo@nerosolutions.com','yes','0','57377','0','0'),
 ('237','Chris Odindo','447932498525','chris@cubicmedia.co.uk','yes','0','0','0','0');

-- --------------------------------------------------------

-- 
-- Dumping data for table `itr_users` 
-- 

INSERT INTO `itr_users` (`id`, `username`, `password`, `accesslevels_id`, `user_profiles_id`, `insurer_agents_id`, `enabled`, `last_login`, `permissions`) VALUES ('53','sngumo','82b56bb5c42214350b67a33d528d1d9e','9','237','2','yes','1453079815',''),
 ('54','admin','c2dd34d95576f2539ae02a1af8e9dfb4','7','236','0','yes','1489583991','');

-- --------------------------------------------------------

-- ------------
-- FOREIGN KEYS
-- ------------
ALTER TABLE `itr_customers` ADD CONSTRAINT `esu_customers_esu_insurer_agents_id_fk` FOREIGN KEY (`insurer_agents_id`) REFERENCES `itr_insurer_agents` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

ALTER TABLE `itr_policies_documents` ADD CONSTRAINT `esu_policies_documents_esu_documents_id_fk` FOREIGN KEY (`documents_id`) REFERENCES `itr_documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `itr_policies_documents` ADD CONSTRAINT `esu_policies_documents_esu_policies_id_fk` FOREIGN KEY (`policies_id`) REFERENCES `itr_policies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;

