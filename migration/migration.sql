CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'user'),
(2, 'admin');
-------------------------------------------------------

CREATE TABLE IF NOT EXISTS `permission` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(50) NOT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`permission_id`, `permission_name`) VALUES
(1, 'view'),
(2, 'edit'),
(3, 'delete'),
(4, 'all'),
(5, 'add');

-------------------------------------------------------

CREATE TABLE IF NOT EXISTS `resource` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(50) NOT NULL,
  PRIMARY KEY (`resource_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`resource_id`, `resource_name`) VALUES
(1, 'details'),
(2, 'dashboard');

-------------------------------------------------------

CREATE TABLE IF NOT EXISTS `role_resource_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `resource_id` (`resource_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `role_resource_permission`
--

INSERT INTO `role_resource_permission` (`id`, `role_id`, `resource_id`, `permission_id`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 2),
(3, 1, 1, 3),
(4, 1, 2, 1),
(5, 2, 1, 4),
(6, 2, 2, 4),
(7, 1, 2, 2);


------------------------------------------------------

ALTER TABLE  `employee` ADD  `roleId` INT( 11 ) NOT NULL DEFAULT  '1';
------------------------------------------------------
//ADD ADMIN ACCOUNT// password is admin

INSERT INTO `employee` (`eid`, `prefix`, `firstName`, `middleName`, `lastName`, `gender`, `dob`, `mobile`, `landline`, `email`, `password`, `maritalStatus`, `employment`, `employer`, `photo`, `note`, `roleId`) VALUES
(70, 'mr', 'admin', '', '', 'm', '0000-00-00', '', '', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'married', 'employed', '', '', '', 1);

------------------------------------------------------

ALTER TABLE  `employee` ADD  `stackId` INT NOT NULL DEFAULT  '0';
------------------------------------------------------












