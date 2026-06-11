```php
<?php

session_start();
require_once("config/conexao.php");

$msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = trim($_POST["email"]);
    $senha = md5($_POST["senha"]);

    $stmt = $conn->prepare(
        "SELECT * FROM usuarios
        WHERE email = ?
        AND senha = ?"
    );

    $stmt->bind_param(
        "ss",
        $email,
        $senha
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    if($resultado->num_rows > 0){

        $usuario =
        $resultado->fetch_assoc();

        $_SESSION["id"] =
        $usuario["id"];

        $_SESSION["nome"] =
        $usuario["nome"];

        $_SESSION["email"] =
        $usuario["email"];

        $_SESSION["tipo"] =
        $usuario["tipo"];

        if($usuario["tipo"] == "admin"){

            header(
                "Location: admin/dashboard.php"
            );

            exit;
        }

        header("Location: index.php");
        exit;

    }else{

        $msg =
        "Email ou senha incorretos.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Login</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial, Helvetica, sans-serif;
}

body{
background:#f4f4f4;
}

.container{
width:100%;
min-height:100vh;

display:flex;
justify-content:center;
align-items:center;
}

.box{

width:400px;

background:white;

padding:30px;

border-radius:10px;

box-shadow:
0 0 10px rgba(0,0,0,.1);
}

h1{

text-align:center;
margin-bottom:20px;
}

input{

width:100%;

padding:12px;

margin-bottom:15px;

border:1px solid #ccc;

border-radius:5px;
}

button{

width:100%;

padding:12px;

background:#111;

color:white;

border:none;

border-radius:5px;

cursor:pointer;
}

button:hover{

background:#333;
}

.link{

margin-top:15px;

text-align:center;
}

.link a{

text-decoration:none;

color:#111;
}

.msg{

margin-bottom:15px;

text-align:center;

font-weight:bold;

color:red;
}

</style>

</head>

<body>

<div class="container">

<div class="box">

<h1>Entrar</h1>

<?php if($msg != ""){ ?>

<div class="msg">
<?php echo $msg; ?>
</div>

<?php } ?>

<form method="POST">

<input
type="email"
name="email"
placeholder="Email"
required
>

<input
type="password"
name="senha"
placeholder="Senha"
required
>

<button type="submit">
Entrar
</button>

</form>

<div class="link">

Não possui conta?

<a href="cadastro.php">
Cadastrar
</a>

</div>

<div class="link">

<a href="index.php">
Voltar para a galeria
</a>

</div>

</div>

</div>

</body>

</html>
```
