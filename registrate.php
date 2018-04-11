<?php session_start();

if (isset($_SESSION['usuario'])){
  header ('Location: index.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $usuario = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
  $password = $_POST['password'];
  $password2 = $_POST['password2'];

  $errores = '';

  if (empty($usuario) or empty($password) or empty($password2)){
    $errores .= '<li>Por favor rellena todos los datos correctamente</li>';
  } else{
    try{
      $conexion = new PDO('mysql:host=localhost;dbname=login', 'esmanar' , '2042854esmailyn');
    }catch(PDOException $e){
      echo 'ERROR: ' . $e->getMessage();
    }

    $statement = $conexion->prepare('SELECT * FROM usuarios WHERE USUARIO = :usuario LIMIT 1');
    $statement->execute(array(':usuario' => $usuario));
    // RECUERDA QUE FETCH RETORNA FALSO SI NO ENCUENTRA NADA POR LO QUE SE PUEDE HACER EL REGISTRO DEL USUARIO, ANIMAL.
    $resultado = $statement->fetch();

    if($resultado != false){
      $errores .= '<li>El nombre de usuario ya existe</li>';
    }

    $password = hash('sha512', $password);
    $password2 = hash('sha512', $password2);

    if($password != $password2){
      $errores .= '<li>Las contraseñas deben ser iguales</li>';
    }
  }

  if($errores == ''){
    $statement = $conexion->prepare('INSERT INTO usuarios (ID, USUARIO, PASS) VALUES (null, :usuario, :pass)');
    $statement->execute(array(':usuario' => $usuario, ':pass' => $password));
    
    header('Location: login.php');
  }
}








require ('views/registrate.view.php');
?>