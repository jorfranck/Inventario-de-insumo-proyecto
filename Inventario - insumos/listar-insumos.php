<?php
require 'includes/database.php';
$db = conectarDB();

require './includes/funciones.php';
$auth = estaAutenticado();
if (!$auth) {
        header('location: /inicio.php');
}

// query para mostrar los insumos activos y no retirados en db
// $query = "SELECT * FROM equipos WHERE fecha_ret IS NULL AND retirante IS NULL ";
$query = "SELECT * FROM equipos WHERE fecha_ret IS NULL AND retirante IS NULL AND condicion_equipo LIKE '%ACTIVO%'";

// consultar DB
$resultadoConsulta = mysqli_query($db, $query);

// query para juntar los EQUIPOS por titulo y cantidad 
$query_count = "SELECT equipo, COUNT(*) as cantidad FROM equipos WHERE condicion_equipo = 'ACTIVO' GROUP BY equipo";

// consultar DB
$resultadoConsultaCount = mysqli_query($db, $query_count);

include 'includes/header.php'; 
?>


<main class="contenedor seccion">
        <h1>Listado de Equipos sin Retirar</h1>

        <table class="insumos">
        <thead>
        <tr>
                <th>Suministro</th>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Fecha Ingreso</th>
        </tr>
        </thead>
        <tbody>
                <?php while ($equipo = mysqli_fetch_assoc($resultadoConsulta)) : ?>
                <tr>
                        <td><?php echo $equipo['equipo']; ?></td>
                        <td><?php echo $equipo['codigo']; ?></td>
                        <td><?php echo $equipo['descripcion']; ?></td>
                        <td><?php echo $equipo['fecha_ing']; ?></td>
                </tr>
                <?php endwhile; ?>
        </tbody>
</table>

<h2>Cantidad de Equipos Totales</h2>

<table class="insumos">
        <thead>
        <tr>
                <th>Equipos</th>
                <th>Cantidad</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($count = mysqli_fetch_assoc($resultadoConsultaCount)) : ?>
                <tr>
                <td><?php echo $count['equipo']; ?></td>
                <td><?php echo $count['cantidad']; ?></td>
                </tr>
        <?php endwhile; ?>
        </tbody>
</table>
</main>

<?php 
include 'includes/footer.php';
?>