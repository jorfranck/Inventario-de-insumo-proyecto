<?php
    require './includes/funciones.php';
    $auth = estaAutenticado();
    if(!$auth){
        header('location: /inicio.php');
    }

    include 'includes/header.php';
?>

    <main>
        <div class="div-menu">
            <ul>
                <img src="img/Logo-Hospital.png" alt="Logo-Hopital">
                <a href="ingresar-insumo.php" class="opc">Ingresar Equipos <i class="bi bi-clipboard-plus-fill"></i></a>
                <a href="actualizar.php" class="opc">Modificar / Eliminar <i class="bi bi-clipboard-x-fill"></i></a>
                <a href="listar-insumos.php" class="opc">Listar Equipos <i class="bi bi-list-check"></i></a>
                <a href="buscar-insumo.php" class="opc">Buscar Equipos <i class="bi bi-search"></i></a>
                <?php if($auth):  ?>
                    <a href="/cerrar-sesion.php">Cerrar Sesion </a>
                <?php endif; ?>
            </ul>
        </div>
    </main>

<?php
    include 'includes/footer.php';
?>