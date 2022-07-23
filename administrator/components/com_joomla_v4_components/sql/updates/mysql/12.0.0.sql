ALTER TABLE `#__joomla_v4_components_details`
    ADD COLUMN `catid` int(11) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__joomla_v4_components_details`
    ADD COLUMN `state` tinyint(3) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__joomla_v4_components_details`
    ADD KEY `idx_catid` (`catid`);
