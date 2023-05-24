ALTER TABLE `fact_series` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
INSERT INTO `configuracion` (`idConf`, `confVariable`, `confValor`) VALUES (NULL, 'verCantidad', '0')
INSERT INTO `configuracion` (`idConf`, `confVariable`, `confValor`) VALUES (NULL, 'servidorCasa', 'http://localhost/pluginSunat');
