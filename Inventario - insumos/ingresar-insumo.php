<?php
require 'includes/database.php';
$db = conectarDB();

require './includes/funciones.php';
$auth = estaAutenticado();
if (!$auth) {
    header('location: /inicio.php');
}

$errores = [];

$equipo = '';
$codigo = '';
$fecha_ing = '';
$descripcion = '';
$condicion_equipo = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipo = mysqli_real_escape_string($db, $_POST['equipo']);
    $codigo = mysqli_real_escape_string($db, $_POST['codigo']);
    $fecha_ing = mysqli_real_escape_string($db, $_POST['fecha_ing']);
    $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
    $condicion_equipo = mysqli_real_escape_string($db, $_POST['condicion_equipo']);

    // Validar si el código ya ha sido ingresado anteriormente
    $query = "SELECT COUNT(*) as total FROM equipos WHERE codigo = '$codigo'";
    $resultado = mysqli_query($db, $query);

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $total = $fila['total'];

        if ($total --> 0) {
            // Mostrar mensaje de error
            $errores[] = "El código $codigo ya ha sido ingresado anteriormente. 'Verifique el Codigo'";
        }
    }

    if (!$equipo) {
        $errores[] = "Debes colocar un Titulo";
    }

    if (!$codigo) {
        $errores[] = "Debes colocar un Codigo de Equipo";
    }

    if (!$descripcion) {
        $errores[] = "Debes colocar una descripcion del Equipo";
    }

    if(strlen( $descripcion ) < 10){
        $errores[] = "Debes colocar una Descripcion y tener mas de 10 caracteres";
    }


    if (empty($errores)) {
        //INSERTAR BD
        $query = "INSERT INTO equipos (equipo, codigo, fecha_ing, descripcion, condicion_equipo) VALUES ('$equipo', $codigo, '$fecha_ing', '$descripcion', '$condicion_equipo') ";

        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            //Redireccionar a otra pagina
            header('Location: /actualizar.php?resultado=1');
        }
    }
}

include 'includes/header.php';
?>
    <main>
        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
        <form method="POST" action="" class="formulario">
                <h2>Ingresar Insumos</h2>

                <label for="equipo">Titulo</label>
                <input type="text" id="equipo" name="equipo" placeholder="Titulo del equipo" value="<?php echo $equipo?>">

                <label for="codigo">Codigo</label>
                <input type="number" id="codigo" name="codigo" placeholder="Codigo del Producto" value="<?php echo $codigo?>">

                <label for="descripcion">Descripcion</label><br>
                <textarea name="descripcion" id="descripcion" placeholder="Descripcion del equipo">
                <?php echo $descripcion?>
                </textarea>

                    <select hidden name="condicion_equipo" id="condicion_equipo">
                        <option value="ACTIVO">ACTIVO</option>
                    </select>
                    <br>

                <!-- <label for="fecha_ingreso">Fecha de Ingreso</label> -->
                <input type="hidden" id="fecha_ing" name="fecha_ing" value="<?php echo date('Y-m-d')?>">

                <input type="submit" class="boton btn-azul" value="Ingresar Insumo">
        </form>
    </main>
<?php
    include 'includes/footer.php';
?>