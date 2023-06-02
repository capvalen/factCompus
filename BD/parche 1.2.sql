CREATE TABLE `marcas` (`id` INT NOT NULL AUTO_INCREMENT , `marca` VARCHAR(250) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `marcas` ADD `activo` INT NULL DEFAULT '1' AFTER `marca`;
ALTER TABLE `productos` ADD `idMarca` INT NOT NULL DEFAULT '1' AFTER `prodActivo`, ADD `idLinea` INT NOT NULL DEFAULT '1' AFTER `idMarca`, ADD `idFamilia` INT NOT NULL DEFAULT '-1' AFTER `idLinea`, ADD `idSubFamilia` INT NOT NULL DEFAULT '-1' AFTER `idFamilia`;
ALTER TABLE `productos` ADD `series` INT NOT NULL DEFAULT '0' COMMENT '0=si; 1=no' AFTER `idUnidad`;
CREATE TABLE `factpc`.`proveedores` (`id` INT NOT NULL , `razonsocial` VARCHAR(250) NOT NULL , `ruc` VARCHAR(11) NOT NULL , `direccion` VARCHAR(250) NULL DEFAULT '' , `activo` INT NOT NULL ) ENGINE = InnoDB;
ALTER TABLE `proveedores` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `proveedores` CHANGE `activo` `activo` INT(11) NULL DEFAULT '1';
ALTER TABLE `compras` CHANGE `idComprobante` `idComprobante` INT(11) NOT NULL COMMENT 'cambio por el JSON de compras.php';
ALTER TABLE `compras` CHANGE `compFecha` `fecha` DATE NOT NULL;
ALTER TABLE `compras` CHANGE `compSerie` `serie` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `compras` CHANGE `compFechaRegistro` `registro` DATETIME NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `compras` CHANGE `compCambioMoneda` `cambioMoneda` FLOAT NOT NULL;

ALTER TABLE `compras` CHANGE `comObs` `observaciones` VARCHAR(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '';
ALTER TABLE `compras` CHANGE `compActivo` `activo` INT(11) NOT NULL;
ALTER TABLE `compras` CHANGE `compExonerado` `exonerado` FLOAT NULL DEFAULT '0';
ALTER TABLE `compras` CHANGE `compSubTotal` `subTotal` FLOAT NULL DEFAULT '0';
ALTER TABLE `compras` CHANGE `compTotal` `total` FLOAT NULL DEFAULT '0';
ALTER TABLE `compras` ADD `idOrigen` INT NOT NULL AFTER `idCompra`;
ALTER TABLE `compras` ADD `bultos` INT NULL DEFAULT '1' AFTER `total`;

ALTER TABLE `compras_detalle` CHANGE `comdCantidad` `cantidad` FLOAT NOT NULL;
ALTER TABLE `compras_detalle` CHANGE `comdPrecioUnit` `precioUnitario` FLOAT NOT NULL;
ALTER TABLE `compras_detalle` CHANGE `comdSubTotal` `subTotal` FLOAT NOT NULL;
ALTER TABLE `compras` CHANGE `compIgv` `igv` FLOAT NULL DEFAULT '0';
ALTER TABLE `compras` CHANGE `activo` `activo` INT(11) NULL DEFAULT '1';
ALTER TABLE `compras` CHANGE `idMoneda` `idMoneda` INT(11) NULL DEFAULT '1' COMMENT '1=efectivo';
ALTER TABLE `compras` CHANGE `cambioMoneda` `cambioMoneda` FLOAT NULL DEFAULT '0';
ALTER TABLE `compras_detalle` CHANGE `idUnidad` `idUnidad` INT(11)
ALTER TABLE `compras_detalle` CHANGE `idGravado` `idGravado` INT(11) NULL DEFAULT '1' COMMENT '0=no, 1=si';
 NULL DEFAULT '1' COMMENT 'niu';
ALTER TABLE `compras_detalle` CHANGE `precioUnitario` `precioUnitario` FLOAT NULL DEFAULT '0';
ALTER TABLE `compras_detalle` CHANGE `subTotal` `subTotal` FLOAT NULL DEFAULT '0';
ALTER TABLE `compras_detalle` ADD `serie` VARCHAR(250) NULL DEFAULT '' AFTER `idUnidad`;

ALTER TABLE `compras_detalle` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
