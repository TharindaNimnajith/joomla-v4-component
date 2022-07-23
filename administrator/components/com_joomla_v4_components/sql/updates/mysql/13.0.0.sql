ALTER TABLE `#__joomla_v4_components_details` ADD COLUMN  `published` tinyint(1) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__joomla_v4_components_details` ADD COLUMN  `publish_up` datetime AFTER `alias`;

ALTER TABLE `#__joomla_v4_components_details` ADD COLUMN  `publish_down` datetime AFTER `alias`;

ALTER TABLE `#__joomla_v4_components_details` ADD KEY `idx_state` (`published`);
