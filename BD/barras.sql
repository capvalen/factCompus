INSERT INTO `comprobante` (`idComprobante`, `compDescripcion`, `comActivo`) VALUES ('-1', 'Proforma', '1');
ALTER TABLE `fact_cabecera` CHANGE `comprobanteEmitido` `comprobanteEmitido` INT(11) NULL DEFAULT '0' COMMENT '1 emitido, 0 sin emitir/2 baja/ 3 Enviado sunat/ 4 borrado';






CREATE TABLE `consorcio`.`barras` ( `idBarra` INT NOT NULL AUTO_INCREMENT , `idProducto` INT NOT NULL , `barra` VARCHAR(50) NOT NULL , `activo` INT NOT NULL COMMENT '1activo, 0 no activo' , PRIMARY KEY (`idBarra`)) ENGINE = InnoDB;