<?php
require 'includes/database.php';
$db = conectarDB();

require './includes/funciones.php';
$auth = estaAutenticado();
if(!$auth){
        header('location: /inicio.php');
}

// Inicializar variables
$resultadoConsulta = null;
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Obtener el código ingresado por el usuario
        $codigo = $_POST['codigo'];

        if (filter_var($codigo, FILTER_VALIDATE_INT)) {

        // query para buscar equipos por código

        $query = "SELECT * FROM equipos WHERE codigo = $codigo";

        // Consultar la base de datos
        $resultadoConsulta = mysqli_query($db, $query);

        //nencontraron resultados
        if (mysqli_num_rows($resultadoConsulta) == 0) {
                $mensaje = 'Resultados de la búsqueda: No lacalizado';
        } else {
                $mensaje = 'Se localizo este Equipo ';
        }
        } else {
                $mensaje = 'El código ingresado no es válido.';
        }
}

include 'includes/header.php'; 
?>;

<main class="contenedor seccion">
        <form action="" method="POST" class="formulario">
        <h1>Búsqueda de Equipo</h1>

        <label for="codigo">Código de Equipo:</label>
        <input type="text" id="codigo" name="codigo" required="">
        <button type="submit" class="boton btn-azul">Buscar</button>
        </form>

        <?php if ($resultadoConsulta) : ?>
        <p class="exito alerta"><?php echo $mensaje; ?></p>

        <table class="insumos">
                <thead>
                <tr>
                        <th>ID</th>
                        <th>Medicamento</th>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th>Fecha Ingreso</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($equipo = mysqli_fetch_assoc($resultadoConsulta)) : ?>
                        <tr>
                        <td><?php echo $equipo['id']; ?></td>
                        <td><?php echo $equipo['equipo']; ?></td>
                        <td><?php echo $equipo['codigo']; ?></td>
                        <td><?php echo $equipo['descripcion']; ?></td>
                        <td><?php echo $equipo['fecha_ing']; ?></td>
                        </tr>
                <?php endwhile;?>
                </tbody>
        </table>

        <?php elseif ($mensaje) : ?>
        <p><?php echo $mensaje;?></p>
        <?php endif;?>
        
</main>

<?php 
include 'includes/footer.php';
?>