ALTER TABLE `#__joomla-v4-components_details` ADD COLUMN  `language` char(7) NOT NULL DEFAULT '*' AFTER `alias`;

ALTER TABLE `#__joomla-v4-components_details` ADD KEY `idx_language` (`language`);
