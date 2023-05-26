CREATE TABLE `marcas` (`id` INT NOT NULL AUTO_INCREMENT , `marca` VARCHAR(250) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `marcas` ADD `activo` INT NULL DEFAULT '1' AFTER `marca`;
ALTER TABLE `productos` ADD `idMarca` INT NOT NULL DEFAULT '1' AFTER `prodActivo`, ADD `idLinea` INT NOT NULL DEFAULT '1' AFTER `idMarca`, ADD `idFamilia` INT NOT NULL DEFAULT '-1' AFTER `idLinea`, ADD `idSubFamilia` INT NOT NULL DEFAULT '-1' AFTER `idFamilia`;
ALTER TABLE `productos` ADD `series` INT NOT NULL DEFAULT '0' COMMENT '0=si; 1=no' AFTER `idUnidad`;
CREATE TABLE `factpc`.`proveedores` (`id` INT NOT NULL , `razonsocial` VARCHAR(250) NOT NULL , `ruc` VARCHAR(11) NOT NULL , `direccion` VARCHAR(250) NULL DEFAULT '' , `activo` INT NOT NULL ) ENGINE = InnoDB;
ALTER TABLE `proveedores` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `proveedores` CHANGE `activo` `activo` INT(11) NULL DEFAULT '1';
