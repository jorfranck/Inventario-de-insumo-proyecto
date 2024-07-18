<?php
    //validar la url por id
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if(!$id){
        header('location: /actualizar.php');
    }
    
    require 'includes/database.php';
    $db = conectarDB();

    require './includes/funciones.php';
    $auth = estaAutenticado();
    if(!$auth){
        header('location: /inicio.php');
    }

    $consulta = "SELECT * FROM insumos WHERE id = ${id}";
    $resultado = mysqli_query($db, $consulta);
    $insumo = mysqli_fetch_assoc($resultado);

    //$consulta = "SELECT * FROM insumos";
    //$resultado = mysqli_query($db, $consulta);

    //arreglo
    $errores = [];

    $titulo = $insumo['titulo'];
    $tipo = $insumo['tipo'];
    $codigo = $insumo['codigo'];
    // $fecha_ingreso = $insumo['fecha_ingreso'];
    $fecha_cre = $insumo['fecha_cre'];
    $fecha_ven = $insumo['fecha_ven'];
    $retirante = $insumo['retirante'];
    $fecha_ret = $insumo['fecha_ret'];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //echo '<pre-->';
        //var_dump($_POST);
        //echo '';


        $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
        $tipo = mysqli_real_escape_string($db, $_POST['tipo']);
        $codigo = mysqli_real_escape_string($db, $_POST['codigo']);
        // $fecha_ingreso = mysqli_real_escape_string($db, $_POST['fecha_ingreso']);
        $fecha_cre = mysqli_real_escape_string($db, $_POST['fecha_cre']);
        $fecha_ven = mysqli_real_escape_string($db, $_POST['fecha_ven']);
        $retirante = mysqli_real_escape_string($db, $_POST['retirante']);
        $fecha_ret = mysqli_real_escape_string($db, $_POST['fecha_ret']);
        
        if(!$titulo){
            $errores[] = "Debes colocar un Titulo";
        }

        if(!$codigo){
            $codigo[] = "Debes colocar un codigo";
        }
        
        if(!$fecha_cre){
            $errores[] = "Debes colocar la fecha de creacion del insumo";
        }

        if(!$fecha_ven){
            $errores[] = "Debes colocar la fecha de vencimineto del insumo";
        }

        if(empty($errores)){

            //INSERTAR BD
            $query = "UPDATE insumos SET titulo = '${titulo}', tipo = '${tipo}' ,codigo = ${codigo}, fecha_cre = '${fecha_cre}' ,fecha_ven = '${fecha_ven}'";

            if (!empty($retirante)) {
                $query .= ", retirante = '${retirante}'";
            } else {
                $query .= ", retirante = NULL";
            }

            if (!empty($fecha_ret)) {
                $query .= ", fecha_ret = '${fecha_ret}'";
            } else {
                $query .= ", fecha_ret = NULL";
            }

            $query .= " WHERE id = ${id}";

            //echo $query;

            $resultado = mysqli_query($db, $query);

            if($resultado) {
                //Redireccionar a otra pagina
                header('Location: /actualizar.php?resultado=2');
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

        <form method="POST" class="formulario">
                <h2>Actualizar Insumos</h2>

                <label for="titulo">Titulo</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo del Suministro" value="<?php echo $titulo?>">

                <label for="tipo">Tipo</label>
                <br>
                <select name="tipo">
                <option value="">-- Seleccione --</option>
                <option value="Medicamento">Medicamento</option>
                <option value="Insumo">Insumo</option>
                </select>
                <br>

                <label for="codigo">Codigo</label>
                <input type="number" id="codigo" name="codigo" placeholder="Codigo del Producto" value="<?php echo $codigo?>">

                <!-- <label for="fecha_ingreso">Fecha de Ingreso</label>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo $fecha_ingreso?>"> -->

                <label for="fecha_cre">Fecha de Creacion</label>
                <input type="date" id="fecha_cre" name="fecha_cre" value="<?php echo $fecha_cre?>">

                <label for="fecha_ven">Fecha de Vencimiento</label>
                <input type="date" id="fecha_ven" name="fecha_ven" value="<?php echo $fecha_ven?>">

                <fieldset>
                    <legend>Retiro</legend>

                <label for="retirante">Retirante</label>
                <input type="text" id="retirante" name="retirante" placeholder="Nombre del Retirante" value="<?php echo $retirante?>">

                <label for="fecha_ret">Fecha de Retiro</label>
                <input type="date" id="fecha_ret" name="fecha_ret" value="<?php echo $fecha_ret?>">
                </fieldset>

                <input type="submit" class="boton btn-azul" value="Actualizar Insumo">
        </form>
    </main>

<?php
include 'includes/footer.php';
?>