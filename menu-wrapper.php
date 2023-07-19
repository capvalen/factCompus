<?php 
$nomArchivo = basename($_SERVER['PHP_SELF']); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark pl-5" id="menuInfocat">
	<a class="navbar-brand" href="#">
    <img src="images/VirtualCorto.png" width="60" height="60" alt="">
  </a>
  <a class="navbar-brand" href="#">Facturador Infocat </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
		<?php if($nomArchivo=='facturador.php'){ ?>
			<li class="nav-item dropdown d-none">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="icofont-newspaper"></i> Emitir
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="#!" id="AEmitirBoleta"><i class="icofont-ui-note"></i> Boleta</a>
					<a class="dropdown-item" href="#!" id="AEmitirFactura"><i class="icofont-ui-copy"></i> Factura</a>
					<!-- <a class="dropdown-item AEmitirNotas" href="#!" id=""><i class="icofont-layers"></i> Nota de crédito</a>
					<a class="dropdown-item AEmitirNotas d-none" href="#!" id=""><i class="icofont-layers"></i> Nota de débito</a> -->
				</div>
			</li>
		<?php }else{ ?>
		<a class="nav-item nav-link " href="facturador.php" id=""><i class="icofont-group"></i> Facturador</a>
		<?php } ?>
		<li class="nav-item dropdown <?php if( in_array($nomArchivo, ['caja.php', 'compras.php', 'servicio-tecnico.php']) ) echo 'active'; ?>">
			<a class="nav-link dropdown-toggle" href="#!" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="icofont-newspaper"></i> Servicios
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item" href="compras.php" id=""><i class="icofont-paper"></i> Nueva Compra</a>
					<a class="dropdown-item" href="caja.php" id=""><i class="icofont-money"></i> Control de caja</a>
					<a class="dropdown-item " href="servicio-tecnico.php" id=""><i class="icofont-camera"></i> Servicio técnico</a>
					<a class="dropdown-item" href="./POS" id="AEmitirFactura"><i class="icofont-diamond"></i> Ventas POS </a>

			</div>

		</li>
		<?php if($_COOKIE['ckPower']=='1' ): ?>
		<?php endif; ?>

			<li class="nav-item dropdown <?php if( in_array($nomArchivo, ['familias.php', 'productos.php', 'proveedores.php', 'envioFactura.php']) ) echo 'active'; ?>">
				<a class="nav-link dropdown-toggle" href="#!" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="icofont-newspaper"></i> Configuraciones
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
    		  <!-- <a class="dropdown-item" href="#!" id="btnModificarSerie"><i class="icofont-tag"></i> Modificar serie</a> -->
					<!-- <a class="dropdown-item" href="compras.php" target="_blank"><i class="icofont-sale-discount"></i> Compras</a> -->
					<?php if($_COOKIE['crearArchivo']=='0' ): ?>
					<a class="dropdown-item " href="envioFactura.php" id="btnEnviarFactura"><i class="icofont-paper"></i> Enviar facturas</a>
					<?php endif; ?>

					<a class="dropdown-item" href="familias.php" id=""><i class="icofont-keyboard"></i> Familias</a>
					<a class="dropdown-item d-none" href="#!" id="btnModificarPrecios"><i class="icofont-infinite"></i> Modificar precios</a>
					<a class="dropdown-item" href="productos.php" id=""><i class="icofont-grocery"></i> Productos</a>
					<a class="dropdown-item" href="proveedores.php" id=""><i class="icofont-group"></i> Proveedores</a>
					<a class="dropdown-item " href="#!" id="btnModificarUsuarios"><i class="icofont-group"></i> Usuarios</a>
					<a class="dropdown-item " href="#!" id="btnVaciarBandeja"><i class="icofont-infinite"></i> Vaciar bandeja</a>
				
				</div>
			</li>
			<li class="nav-item dropdown <?php if( in_array($nomArchivo, ['reporte_compras.php', 'garantias.php', 'reportes.php', 'inventario-series.php', 'inventario-servicio.php', 'reporte_creditos.php']) ) echo 'active'; ?>">
				<a class="nav-link dropdown-toggle" href="#!" id="navbarReportes" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="icofont-paper"></i> Reportes
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarReportes">
					<a class="dropdown-item " href="reporte_compras.php" id=""><i class="icofont-paper"></i> Compras</a>
					<a class="dropdown-item " href="reporte_creditos.php" id=""><i class="icofont-paper"></i> Créditos</a>
					<a class="dropdown-item " href="garantias.php" id=""><i class="icofont-paper"></i> Garantias</a>
					<a class="dropdown-item " href="inventario-series.php" id=""><i class="icofont-paper"></i> Inventario de series</a>
					<a class="dropdown-item " href="inventario-servicio.php" id=""><i class="icofont-paper"></i> Reportes servicio tec.</a>
					<a class="dropdown-item " href="reportes.php" id=""><i class="icofont-paper"></i> Reportes contables</a>
				</div>
			</li>
			<a class="nav-item nav-link " href="tutoriales.php" id=""><i class="icofont-video"></i> Tutoriales</a>
			
    
      <a class="nav-item nav-link " href="desconectar.php"><i class="icofont-addons"></i> Salir del sistema</a>
    </div>
  </div>
</nav>