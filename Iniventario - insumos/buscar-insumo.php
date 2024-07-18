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

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Obtener el código ingresado por el usuario
        $codigo = $_POST['codigo'];

        // Validar que el código sea un número entero
        if (filter_var($codigo, FILTER_VALIDATE_INT)) {
        // Escribir el query con la cláusula WHERE para buscar insumos por código
        $query = "SELECT * FROM insumos WHERE codigo = $codigo";

        // Consultar la base de datos
        $resultadoConsulta = mysqli_query($db, $query);

        // Si se encontraron resultados
        if (mysqli_num_rows($resultadoConsulta) == 0) {
                $mensaje = 'Resultados de la búsqueda: No lacalizado';
        } else {
                $mensaje = 'Se localizo este insumo ';
        }
        } else {
                $mensaje = 'El código ingresado no es válido.';
        }
}

include 'includes/header.php'; 
?>;

<main class="contenedor seccion">
        <form action="" method="POST" class="formulario">
        <h1>Búsqueda de Insumos</h1>

        <label for="codigo">Código de Insumo:</label>
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