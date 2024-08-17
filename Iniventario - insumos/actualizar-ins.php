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

    $consulta = "SELECT * FROM equipos WHERE id = ${id}";
    $resultado = mysqli_query($db, $consulta);
    $equipos = mysqli_fetch_assoc($resultado);

    //$consulta = "SELECT * FROM insumos";
    //$resultado = mysqli_query($db, $consulta);

    //arreglo
    $errores = [];

    $equipo = $equipos['equipo'];
    $codigo = $equipos['codigo'];
    $descripcion = $equipos['descripcion'];
    // $fecha_ingreso = $insumo['fecha_ingreso'];
    $retirante = $equipos['retirante'];
    $fecha_ret = $equipos['fecha_ret'];
    $area = $equipos['area'];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //echo '<pre>';
        //var_dump($_POST);
        //echo '<pre>';


        $titulo = mysqli_real_escape_string($db, $_POST['equipo']);
        $codigo = mysqli_real_escape_string($db, $_POST['codigo']);
        $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
        // $fecha_ingreso = mysqli_real_escape_string($db, $_POST['fecha_ingreso']);
        $retirante = mysqli_real_escape_string($db, $_POST['retirante']);
        $fecha_ret = mysqli_real_escape_string($db, $_POST['fecha_ret']);
        $area = mysqli_real_escape_string($db, $_POST['area']);
        
        if(!$titulo){
            $errores[] = "Debes colocar un Titulo";
        }

        if(!$codigo){
            $codigo[] = "Debes colocar un codigo";
        }
        

        if(empty($errores)){

            //INSERTAR BD
            $query = "UPDATE equipos SET equipo = '${equipo}',codigo = ${codigo}, descripcion = '${descripcion}'";

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

            if (!empty($area)) {
                $query .= ", area = '${area}'";
            } else {
                $query .= ", area = NULL";
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
                <h2>Actualizar Equipos</h2>

                <label for="equipo">Titulo</label>
                <input type="text" id="equipo" name="equipo" placeholder="Titulo del equipo" value="<?php echo $equipo?>">

                <label for="codigo">Codigo</label>
                <input type="number" id="codigo" name="codigo" placeholder="Codigo del Producto" value="<?php echo $codigo?>">

                <label for="descripcion">Descripcion</label><br>
                <textarea name="descripcion" id="descripcion" placeholder="Descripcion del equipo">
                <?php echo $descripcion?>
                </textarea><br>

                <!-- <label for="fecha_ingreso">Fecha de Ingreso</label>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo $fecha_ingreso?>"> -->

                <fieldset>
                    <legend>Retiro</legend>

                <label for="retirante">Retirante</label>
                <input type="text" id="retirante" name="retirante" placeholder="Nombre del Retirante" value="<?php echo $retirante?>">

                <label for="area">Area Asignada</label>
                <input type="text" id="area" name="area" placeholder="Nombre del Retirante" value="<?php echo $area?>">

                <label for="fecha_ret">Fecha de Retiro</label>
                <input type="date" id="fecha_ret" name="fecha_ret" value="<?php echo $fecha_ret?>">
                </fieldset>

                <input type="submit" class="boton btn-azul" value="Actualizar Insumo">
        </form>
    </main>

<?php
include 'includes/footer.php';
?>