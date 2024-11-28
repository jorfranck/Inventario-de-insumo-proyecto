<?php
require 'includes/database.php';
$db = conectarDB();

$errores = [];

//autenticar usuario
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //echo '<pre>';
    //var_dump($_POST);
    //echo '</pre>';
    //exit;

    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if(!$username){
        $errores[] = "El usuario es obligatorio o no es valido";
    }

    if(!$password){
        $errores[] = "El password es obligatorio o no es valido";
    }

    if(empty($errores)){

        //revisar si el usuario existe
        $query = "SELECT * FROM usuarios WHERE username  = '${username}' ";
        $resultado = mysqli_query($db, $query);

        if($resultado->num_rows){           
            
                //REVISAR SI EL PASSWORD ES CORRECTO
                $usuario = mysqli_fetch_assoc($resultado);
                
                //VERIFICAR SI EL PASSWORD ES CORRECTO O NO
                $auth = password_verify($password, $usuario['password']);

                if($auth){
                    //el usuario esta autenticado
                    session_start();

                    //llenar el arreglo de la sesion
                    $_SESSION['usuario'] = $usuario['username'];
                    $_SESSION['login'] = true;

                    header('location: /interfaz.php');

                }
                else{
                    $errores[] = "El password es incorrecto";
                }
        }
        else{
            $errores[] = "NO EXISTE EL USUARIO";
        }
    }
}

include 'includes/header.php';
?>
        <?php   foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php   endforeach; ?>
        
        <main class="contenedor">
            <h2>Usuario y Password</h2>
            <form method="POST" class="formulario" >  
                <img src="img/Logo-Hospital.png" alt="Logo-Hopital">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" placeholder="Tu nombre de usuario" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Tu contraseña" required>

                <input type="submit" class="boton btn-azul" value="Iniciar">
            </form>
        </main>

<?php
include 'includes/footer.php';
?>

