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
    $fecha_ing = $equipos['fecha_ing'];
    $retirante = $equipos['retirante'];
    $fecha_ret = $equipos['fecha_ret'];
    $area = $equipos['area'];
    $condicion_equipo = $equipos['condicion_equipo'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $titulo = mysqli_real_escape_string($db, $_POST['equipo']);
        $codigo = mysqli_real_escape_string($db, $_POST['codigo']);
        $descripcion = mysqli_real_escape_string($db, $_POST['descripcion']);
        $fecha_ing = mysqli_real_escape_string($db, $_POST['fecha_ing']);
        $retirante = mysqli_real_escape_string($db, $_POST['retirante']);
        $fecha_ret = mysqli_real_escape_string($db, $_POST['fecha_ret']);
        $area = mysqli_real_escape_string($db, $_POST['area']);
        $condicion_equipo = mysqli_real_escape_string($db, $_POST['condicion_equipo']);
        
        if (!$titulo) {
            $errores[] = "Debes colocar un Título";
        }

        if (!$codigo) {
            $errores[] = "Debes colocar un código";
        }

        if (!$descripcion) {
            $errores[] = "Debes colocar una descripción";
        }
    
        if (empty($errores)) {
            // Obtener el valor actual de condicion_equipo

            $consulta_condicion_actual = "SELECT condicion_equipo FROM equipos WHERE id = ${id}";
            $resultado_condicion = mysqli_query($db, $consulta_condicion_actual);
            $condicion_actual = mysqli_fetch_assoc($resultado_condicion)['condicion_equipo'];
    
            // Si el estado cambio a DESINCORPORADO, primero insertamos en la tabla desincorporados

            if ($condicion_actual === 'ACTIVO' && $condicion_equipo === 'DESINCORPORADO') {
                $fecha_ing = $equipos['fecha_ing'] ;
                $fecha_des = date('Y-m-d'); 
            
                $query_desincorporados = "INSERT INTO desincorporados (equipo, codigo, descripcion, fecha_ing, area, fecha_des) VALUES ('${titulo}', ${codigo}, '${descripcion}', '${fecha_ing}', '${area}', '${fecha_des}')";
            
                $resultado_desincorporados = mysqli_query($db, $query_desincorporados);
            
                if ($resultado_desincorporados) {
                    // Si fue exitosa la inserción, eliminar de la tabla `equipos`
                    $query_eliminar = "DELETE FROM equipos WHERE id = ${id}";
                    $resultado_eliminar = mysqli_query($db, $query_eliminar);
            
                    if ($resultado_eliminar) {
                        // Redireccionar con éxito
                        header('Location: /actualizar.php?resultado=2');
                        exit;
                    } else {
                        $errores[] = "Error al eliminar el equipo de la tabla principal.";
                    }
                } else {
                    $errores[] = "Error al insertar en la tabla de desincorporados.";
                }
            } else {

                // Actualizar la tabla equipos solo si no se cambia a DESINCORPORADO

                $query = "UPDATE equipos SET equipo = '${titulo}', codigo = ${codigo}, descripcion = '${descripcion}',condicion_equipo = '${condicion_equipo}'";

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
                $resultado = mysqli_query($db, $query);
    
                if ($resultado) {
                    // Redireccionar con éxito
                    header('Location: /actualizar.php?resultado=2');
                    exit;
                } else {
                    $errores[] = "Error al actualizar el equipo.";
                }
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
                <h2>Actualizar Equipo</h2>

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
                        <legend>RETIRO</legend>
                        <label for="retirante">Retirante</label>
                        <input type="text" id="retirante" name="retirante" placeholder="Nombre del Retirante" value="<?php echo $retirante?>">

                        <label for="area">Area Asignada</label>
                        <input type="text" id="area" name="area" placeholder="Nombre del Retirante" value="<?php echo $area?>">

                        <label for="fecha_ret">Fecha de Retiro</label>
                        <input type="date" id="fecha_ret" name="fecha_ret" value="<?php echo $fecha_ret?>">
                </fieldset>
                
                <fieldset>
                    <legend>CONDICIÓN DEL EQUIPO</legend>
                    <select name="condicion_equipo" id="condicion_equipo">
                        <option disabled>Seleccione</option>
                        <option value="ACTIVO" <?php echo $condicion_equipo === 'ACTIVO' ? 'selected' : ''; ?>>ACTIVO</option>
                        <option value="DESINCORPORADO" <?php echo $condicion_equipo === 'DESINCORPORADO' ? 'selected' : ''; ?>>DESINCORPORADO</option>
                    </select>
                </fieldset>

                <input type="submit" class="boton btn-azul" value="Actualizar Equipo">
        </form>
    </main>

<?php
include 'includes/footer.php';
?>