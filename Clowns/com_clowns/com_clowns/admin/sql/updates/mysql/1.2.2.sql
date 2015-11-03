ALTER TABLE `#__clowns` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;

ALTER TABLE `#__clowns` ADD `Stato_Socio` INT NOT NULL DEFAULT '1'; 

ALTER TABLE `#__clowns` ADD `Vip` TEXT NOT NULL DEFAULT '1';

CREATE TABLE IF NOT EXISTS #__stati_socio_disponibili(
		`id` VARCHAR(128), 
		`Nome` VARCHAR(128) 
);

INSERT INTO #__stati_socio_disponibili` (`id`, `Nome`) VALUES ('1', 'Socio attivo'), ('2', 'Socio non attivo'), ('3', 'Clown joy');

CREATE TABLE IF NOT EXISTS #__vip_disponibili(
		`id` VARCHAR(128), 
		`Nome` VARCHAR(128) 
);

INSERT INTO #__vip_disponibili` (`id`, `Nome`) VALUES ('1', 'Vip Sassari'), ('2', 'Vip Cagliari');
