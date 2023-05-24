ALTER TABLE `fact_cabecera` CHANGE `tipDocUsuario` `tipDocUsuario` INT(11) NOT NULL DEFAULT '0' COMMENT '0 sin DNI, 1 Dni, 6 RUC, 7 pasaporte';

ALTER TABLE `fact_cabecera` ADD `esContado` INT NULL DEFAULT '1' COMMENT '1=contado, 2= credito' AFTER `serieBaja`;
ALTER TABLE `fact_cabecera` ADD `adelanto` FLOAT NULL DEFAULT '0' AFTER `esContado`;
