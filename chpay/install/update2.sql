ALTER TABLE `pre_channel`
ADD COLUMN `mode` int(1) DEFAULT 0;

ALTER TABLE `pre_channel`
ADD COLUMN `daytop` int(10) DEFAULT 0,
ADD COLUMN `daystatus` int(1) DEFAULT 0;

ALTER TABLE `pre_user`
ADD COLUMN `channelinfo` text DEFAULT NULL;

ALTER TABLE `pre_group`
ADD COLUMN `settle_open` int(1) DEFAULT 0,
ADD COLUMN `settle_type` int(1) DEFAULT 0,
ADD COLUMN `settings` text DEFAULT NULL;