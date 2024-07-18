<?php
require 'includes/database.php';
$db = conectarDB();

require './includes/funciones.php';
$auth = estaAutenticado();
if (!$auth) {
    header('location: /inicio.php');
}

$errores = [];

$titulo = '';
$tipo = '';
$codigo = '';
$fecha_ingreso = '';
$fecha_cre = '';
$fecha_ven = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
    $tipo = mysqli_real_escape_string($db, $_POST['tipo']);
    $codigo = mysqli_real_escape_string($db, $_POST['codigo']);
    $fecha_ingreso = mysqli_real_escape_string($db, $_POST['fecha_ingreso']);
    $fecha_cre = mysqli_real_escape_string($db, $_POST['fecha_cre']);
    $fecha_ven = mysqli_real_escape_string($db, $_POST['fecha_ven']);

    // Validar si el código ya ha sido ingresado anteriormente
    $query = "SELECT COUNT(*) as total FROM insumos WHERE codigo = '$codigo'";
    $resultado = mysqli_query($db, $query);

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $total = $fila['total'];

        if ($total --> 0) {
            // Mostrar mensaje de error
            $errores[] = "El código $codigo ya ha sido ingresado anteriormente. 'Verifique el Codigo'";
        }
    }

    if (!$titulo) {
        $errores[] = "Debes colocar un Titulo";
    }

    if (!$codigo) {
        $errores[] = "Debes colocar un Codigo de Insumo";
    }

    if (!$tipo) {
        $errores[] = "Debes colocar un tipo";
    }

    if (!$fecha_cre) {
        $errores[] = "Debes colocar la fecha de creacion del insumo";
    }

    if (!$fecha_ven) {
        $errores[] = "Debes colocar la fecha de vencimineto del insumo";
    }

    if (empty($errores)) {
        //INSERTAR BD
        $query = "INSERT INTO insumos (titulo, tipo, codigo, fecha_ingreso, fecha_cre, fecha_ven) VALUES ('$titulo', '$tipo', $codigo, '$fecha_ingreso', '$fecha_cre', '$fecha_ven') ";

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

                <label for="titulo">Titulo</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo del Suministro" value="<?php echo $titulo?>">

                <label for="tipo">Tipo de Suministro</label>
                <br>
                    <select name="tipo">
                        <option value="">-- Seleccione --</option>
                        <option value="Medicamento">Medicamento</option>
                        <option value="Insumo">Insumo</option>
                    </select>
                <br>

                <label for="codigo">Codigo</label>
                <input type="number" id="codigo" name="codigo" placeholder="Codigo del Producto" value="<?php echo $codigo?>">

                <!-- <label for="fecha_ingreso">Fecha de Ingreso</label> -->
                <input type="hidden" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo date('Y-m-d')?>">

                <label for="fecha_cre">Fecha de Creacion</label>
                <input type="date" id="fecha_cre" name="fecha_cre" value="<?php echo $fecha_cre?>">

                <label for="fecha_ven">Fecha de Vencimiento</label>
                <input type="date" id="fecha_ven" name="fecha_ven" value="<?php echo $fecha_ven?>">

                <input type="submit" class="boton btn-azul" value="Ingresar Insumo">
        </form>
    </main>
<?php
    include 'includes/footer.php';
?>