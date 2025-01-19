ALTER TABLE `products` ADD `publish_days` INT NOT NULL DEFAULT '0' AFTER `current_stock`;
ALTER TABLE `products` ADD `published_at` DATE NULL DEFAULT NULL AFTER `current_stock`;
