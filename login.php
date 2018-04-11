<?php session_start();

if (isset($_SESSION['usuario'])){
  header ('Location: index.php');
}

$errores = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $usuario = filter_var(strtolower($_POST['usuario']),FILTER_SANITIZE_STRING );
  $password = $_POST['password'];
  $password = hash('sha512' ,  $password);

  try{
    $conexion = new PDO('mysql:host=localhost;dbname=login', 'esmanar', '2042854esmailyn');
  }catch(PDOException $e){
    echo 'ERRO: ' . $e->getMessage();
  }

  $statement = $conexion->prepare('SELECT * FROM usuarios WHERE USUARIO = :usuario AND PASS = :password');
  $statement->execute(array(':usuario' => $usuario, ':password' => $password));
  $resultado = $statement->fetch();

  if($resultado != false){

    $_SESSION['usuario'] = $usuario;
    header('Location: index.php');
  }else{
    $errores .= '<li>El usuario o la contraseña no coinciden</li>';
  }
}

require ('views/login.view.php');

?>