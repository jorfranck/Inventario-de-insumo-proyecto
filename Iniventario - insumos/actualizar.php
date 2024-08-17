<?php
    require 'includes/database.php';
    $db = conectarDB();
    require './includes/funciones.php';

    $auth = estaAutenticado();
    if(!$auth){
        header('location: /inicio.php');
        exit;
    }

    // Preparar la consulta
    $query = "SELECT * FROM equipos";
    $busqueda = $_GET['q'] ?? '';

    // Aplicar filtro de búsqueda si se ingresó un término
    if (!empty($busqueda)) {
        $busqueda = mysqli_real_escape_string($db, $busqueda);
        $query .= " WHERE equipo LIKE '%$busqueda%' OR codigo LIKE '%$busqueda%'";
    }

    // Consultar DB
    $resultadoConsulta = mysqli_query($db, $query);

    // Manejar el resultado
    $resultado = $_GET['resultado'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        if ($id) {
            // ELIMINAR EL EQUIPO
            $query = "DELETE FROM equipos WHERE id = ?";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            $resultado = mysqli_stmt_execute($stmt);

            if ($resultado) {
                header('location: /actualizar.php?resultado=3');
                exit;
            } else {
                echo 'Error: ' . mysqli_error($db);
            }
        }
    }

    include 'includes/header.php';
?>

<main class="contenedor seccion">
    <h1>Administrador de Equipos</h1>

    <?php if ($resultado == 1): ?>
        <p class="alerta exito">Suministro Creado Correctamente</p>
    <?php elseif ($resultado == 2): ?>
        <p class="alerta exito">Suministro Actualizado Correctamente</p>
    <?php elseif ($resultado == 3): ?>
        <p class="alerta exito">Suministro Eliminado Correctamente</p>
    <?php endif; ?>

    <form action="actualizar.php" method="GET" class="buscador-form">
        <input class="buscador" type="text" name="q" placeholder="Buscar Equipo..." value="<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" class="boton btn-buscar btn-azul"><i class="bi bi-search"></i></button>
    </form>

    <br>
    <table class="insumos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Equipo</th>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Fecha Ingreso</th>
                <th>Retirante</th>
                <th>Fecha de Retiro</th>
                <th>Area Asignada</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($equipo = mysqli_fetch_assoc($resultadoConsulta)): ?>
                <tr>
                    <td><?php echo $equipo['id']?></td>
                    <td><?php echo $equipo['equipo']?></td>
                    <td><?php echo $equipo['codigo']?></td>
                    <td><?php echo $equipo['descripcion'] ?></td>
                    <td><?php echo $equipo['fecha_ing'] ?></td>
                    <td><?php echo $equipo['retirante'] ?></td>
                    <td><?php echo $equipo['fecha_ret'] ?></td>
                    <td><?php echo $equipo['area'] ?></td>
                    <td>
                        <form method="POST" class="w-100" onsubmit="return confirmaEliminacion();">
                            <input type="hidden" name="id" value="<?php echo $equipo['id'] ?>">
                            <input type="submit" class="boton-opc1" value="Eliminar">
                        </form>
                        <a href="/actualizar-ins.php?id=<?php echo $equipo['id']?>" 
                        class="boton-opc2">Actualizar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <button class="btn-azul boton">
        <a href="/ingresar-insumo.php">Ingresar Equipo</a>
    </button>
</main>

<script>
    function confirmaEliminacion() {
        return confirm('¿Estás seguro de que quieres eliminar este equipo?');
    }
</script>

<?php
    include 'includes/footer.php';
?>
