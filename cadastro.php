```php
<?php

session_start();
require_once("config/conexao.php");

$msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = md5($_POST["senha"]);

    $verifica = $conn->prepare(
        "SELECT id FROM usuarios WHERE email = ?"
    );

    $verifica->bind_param("s", $email);
    $verifica->execute();

    $resultado = $verifica->get_result();

    if($resultado->num_rows > 0){

        $msg = "Este email já está cadastrado.";

    }else{

        $stmt = $conn->prepare(
            "INSERT INTO usuarios
            (nome,email,senha,tipo)
            VALUES (?,?,?,'cliente')"
        );

        $stmt->bind_param(
            "sss",
            $nome,
            $email,
            $senha
        );

        if($stmt->execute()){

            $msg = "Cadastro realizado com sucesso!";

        }else{

            $msg = "Erro ao cadastrar.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Cadastro</title>

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

    text-align:center;
    margin-top:15px;
}

.link a{

    text-decoration:none;
    color:#111;
}

.msg{

    margin-bottom:15px;
    text-align:center;

    font-weight:bold;

    color:green;
}

.erro{

    color:red;
}

</style>

</head>

<body>

<div class="container">

<div class="box">

<h1>Criar Conta</h1>

<?php if($msg != ""){ ?>

<div class="msg">
<?php echo $msg; ?>
</div>

<?php } ?>

<form method="POST">

<input
type="text"
name="nome"
placeholder="Nome completo"
required
>

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
Cadastrar
</button>

</form>

<div class="link">

Já possui conta?

<a href="login.php">
Entrar
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
