ALTER TABLE `#__joomla_v4_components_details` ADD COLUMN `checked_out` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__joomla_v4_components_details` ADD COLUMN `checked_out_time` datetime AFTER `alias`;

ALTER TABLE `#__joomla_v4_components_details` ADD KEY `idx_checkout` (`checked_out`);
