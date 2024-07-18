<?php
    require 'includes/database.php';
    $db = conectarDB();
    require './includes/funciones.php';

    $auth = estaAutenticado();
    if(!$auth){
        header('location: /inicio.php');
    }

    //escribir el query base
    $query = "SELECT * FROM insumos";

    //obtener el término de búsqueda si existe
    $busqueda = $_GET['q'] ?? '';


    $query_count = "SELECT tipo, COUNT(*) as cantidad FROM insumos WHERE fecha_ret IS NULL AND retirante IS NULL GROUP BY tipo";
    $resultadoConsultaCount = mysqli_query($db, $query_count);


    //aplicar filtro de búsqueda si se ingresó un término
    if (!empty($busqueda)) {
        $query .= " WHERE titulo LIKE '%$busqueda%' OR tipo LIKE '%$busqueda%' OR codigo LIKE '%$busqueda%'";
    }

    //consultar DB
    $resultadoConsulta = mysqli_query($db, $query);

    //mensaje condicional
    $resultado = $_GET['resultado'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if ($id) {
            // ELIMINAR LA INSUMO
            $query = "DELETE FROM insumos WHERE id = ${id}";
            $resultado = mysqli_query($db, $query);

            if ($resultado) {
                header('location: /actualizar.php?resultado=3');
            }
        }
    }

    include 'includes/header.php';
?>

<main class="contenedor seccion">
    <h1>Administrador de Insumos</h1>

    <?php if (intval($resultado == 1)): ?>
        <p class="alerta exito">Suministro Creado Correctamente</p>
    <?php elseif (intval($resultado == 2)): ?>
        <p class="alerta exito">Suministro Actualizado Correctamente</p>
    <?php elseif (intval($resultado == 3)): ?>
        <p class="alerta exito">Suministro Eliminado Correctamente</p>
    <?php endif; ?>

    <section class="section">
    <form action="actualizar.php" method="GET" class="buscador-form">
        <input  type="text" name="q" placeholder="Buscar Suministro..." value="<?php echo isset($_GET['q']) ? $_GET['q'] : ''; ?>">
        <button type="submit" class="boton btn-buscar btn-azul"><i class="bi bi-search"></i></button>
    </form>

    <table class="insumos-cantidad">
        <thead>
        <tr>
                <th>Suministro</th>
                <th>Cantidad</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($count = mysqli_fetch_assoc($resultadoConsultaCount)) : ?>
                <tr>
                <td><?php echo $count['tipo']; ?></td>
                <td><?php echo $count['cantidad']; ?></td>
                </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </section>
    <br>
    <table class="insumos">

        <thead>
            <tr>
                <th>ID</th>
                <th>Suministro</th>
                <th>Tipo</th>
                <th>Codigo</th>
                <th>Fecha Ingreso</th>
                <th>Fecha de creación</th>
                <th>Fecha de vencimiento</th>
                <th>Retirante</th>
                <th>Fecha de Retiro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody> <!-- Mostrar los resultados -->
            <?php while ($insumo = mysqli_fetch_assoc($resultadoConsulta)): ?>
                <tr>
                    <td><?php echo $insumo['id']; ?></td>
                    <td><?php echo $insumo['titulo']; ?></td>
                    <td><?php echo $insumo['tipo']; ?></td>
                    <td><?php echo $insumo['codigo']; ?></td>
                    <td><?php echo $insumo['fecha_ingreso']; ?></td>
                    <td><?php echo $insumo['fecha_cre']; ?></td>
                    <td><?php echo $insumo['fecha_ven']; ?></td>
                    <td><?php echo $insumo['retirante']; ?></td>
                    <td><?php echo $insumo['fecha_ret']; ?></td>
                    <td>
                        <form method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $insumo['id']; ?>">
                            <input type="submit" class="boton-opc1" value="Eliminar">
                        </form>
                        <a href="/actualizar-ins.php?id=<?php echo $insumo['id']; ?>" class="boton-opc2">Actualizar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    
    <button class="btn-azul boton">
        <a href="/ingresar-insumo.php">Ingresar Insumo</a>
    </button>
</main>

<?php
    include 'includes/footer.php';
?>