<?php
require 'includes/database.php';
$db = conectarDB();

require './includes/funciones.php';
$auth = estaAutenticado();
if (!$auth) {
        header('location: /inicio.php');
}

// query para mostrar los insumos activos y no retirados en db
$query = "SELECT * FROM insumos WHERE fecha_ret IS NULL AND retirante IS NULL";

// consultar DB
$resultadoConsulta = mysqli_query($db, $query);

// query para juntar los insumos por titulo y cantidad 
$query_count = "SELECT titulo, COUNT(*) as cantidad FROM insumos WHERE fecha_ret IS NULL AND retirante IS NULL GROUP BY titulo";

// consultar DB
$resultadoConsultaCount = mysqli_query($db, $query_count);

include 'includes/header.php'; 
?>


<main class="contenedor seccion">
        <h1>Listado de Insumos Activos</h1>

        <table class="insumos">
        <thead>
        <tr>
                <th>ID</th>
                <th>Suministro</th>
                <th>Tipo</th>
                <th>Codigo</th>
                <th>Fecha Ingreso</th>
                <th>Fecha de creación</th>
                <th>Fecha de vencimiento</th>
        </tr>
        </thead>
        <tbody>
                <?php while ($insumo = mysqli_fetch_assoc($resultadoConsulta)) : ?>
                <tr>
                        <td><?php echo $insumo['id']; ?></td>
                        <td><?php echo $insumo['titulo']; ?></td>
                        <td><?php echo $insumo['tipo']; ?></td>
                        <td><?php echo $insumo['codigo']; ?></td>
                        <td><?php echo $insumo['fecha_ingreso']; ?></td>
                        <td><?php echo $insumo['fecha_cre']; ?></td>
                        <td><?php echo $insumo['fecha_ven']; ?></td>
                </tr>
                <?php endwhile; ?>
        </tbody>
</table>

<h2>Cantidad de Suministro Totales</h2>

<table class="insumos">
        <thead>
        <tr>
                <th>Suministro</th>
                <th>Cantidad</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($count = mysqli_fetch_assoc($resultadoConsultaCount)) : ?>
                <tr>
                <td><?php echo $count['titulo']; ?></td>
                <td><?php echo $count['cantidad']; ?></td>
                </tr>
        <?php endwhile; ?>
        </tbody>
</table>
</main>

<?php 
include 'includes/footer.php';
?>