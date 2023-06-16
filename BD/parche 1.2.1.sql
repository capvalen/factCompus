ALTER TABLE `proveedores` ADD `celular` VARCHAR(250) NULL DEFAULT '' AFTER `direccion`, ADD `contacto` VARCHAR(250) NULL DEFAULT '' AFTER `celular`;
INSERT INTO `configuracion` (`idConf`, `confVariable`, `confValor`) VALUES (NULL, 'ticket', 'automatico');
