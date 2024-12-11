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
    $query = "SELECT * FROM desincorporados";
    $busqueda = $_GET['q'] ?? '';
    $fecha_inicio = $_GET['fecha_inicio'] ?? '';
    $fecha_fin = $_GET['fecha_fin'] ?? '';

    // Aplicar filtros de búsqueda
    $filtros = [];
    if (!empty($busqueda)) {
        $busqueda = mysqli_real_escape_string($db, $busqueda);
        $filtros[] = "(equipo LIKE '%$busqueda%' OR codigo LIKE '%$busqueda%')";
    }
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $fecha_inicio = mysqli_real_escape_string($db, $fecha_inicio);
        $fecha_fin = mysqli_real_escape_string($db, $fecha_fin);
        $filtros[] = "(fecha_ing BETWEEN '$fecha_inicio' AND '$fecha_fin')";
    }

    // Concatenar filtros a la consulta
    if (!empty($filtros)) {
        $query .= " WHERE " . implode(' AND ', $filtros);
    }

    // Consultar DB
    $resultadoConsulta = mysqli_query($db, $query);

    // Manejar el resultado
    $resultado = $_GET['resultado'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        if ($id) {
            // ELIMINAR EL EQUIPO
            $query = "DELETE FROM desincorporados WHERE id = ?";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            $resultado = mysqli_stmt_execute($stmt);

            if ($resultado) {
                header('location: /desincorporados.php?resultado=3');
                exit;
            } else {
                echo 'Error: ' . mysqli_error($db);
            }
        }
    }

    include 'includes/header.php';
?>

<main class="contenedor seccion">
    <h1>Administrador de Equipos Desincorporados</h1>

    <?php if ($resultado == 1): ?>
        <p class="alerta exito">Equipo Creado Correctamente</p>
    <?php elseif ($resultado == 2): ?>
        <p class="alerta exito">Equipo Actualizado Correctamente</p>
    <?php elseif ($resultado == 3): ?>
        <p class="alerta exito">Equipo Eliminado Correctamente</p>
    <?php endif; ?>

    <!-- Formulario de Búsqueda -->
    <form action="actualizar.php" method="GET" class="buscador-form">
        <input class="buscador" type="text" name="q" placeholder="Buscar Equipo..." value="<?php echo htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" class="boton btn-buscar btn-azul"><i class="bi bi-search"></i> Buscar</button>
        <br>
        <label for="fecha_inicio">DESDE:</label>

        <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio, ENT_QUOTES, 'UTF-8'); ?>">
<br>
        <label for="fecha_fin">HASTA:</label>

        <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin, ENT_QUOTES, 'UTF-8'); ?>">
    </form>

    <br>
    <table class="insumos">
        <thead>
            <tr>
                <th>Equipo</th>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Fecha Ingreso</th>
                <th>Fecha desincorporacion</th>
                <th>Area Asignada</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($equipo = mysqli_fetch_assoc($resultadoConsulta)): ?>
                <tr>
                    <td><?php echo $equipo['equipo']?></td>
                    <td><?php echo $equipo['codigo']?></td>
                    <td><?php echo $equipo['descripcion'] ?></td>
                    <td><?php echo $equipo['fecha_ing'] ?></td>
                    <td><?php echo $equipo['fecha_des'] ?></td>
                    <td><?php echo $equipo['area'] ?></td>
                    <td>
                        <form method="POST" class="w-100" onsubmit="return confirmaEliminacion();">
                            <input type="hidden" name="id" value="<?php echo $equipo['id'] ?>">
                            <input type="submit" class="boton-opc1" value="ELIMINAR">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <button class="btn-azul boton">
        <a href="/ingresar-insumo.php">Ingresar Equipo</a>
    </button>

    <button class="btn-azul boton">
        <a href="/actualizar.php">Ver Activos</a>
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