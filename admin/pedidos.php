```php
<?php

session_start();

if(
    !isset($_SESSION["id"])
    ||
    $_SESSION["tipo"] != "admin"
){
    header("Location: ../login.php");
    exit;
}

require_once("../config/conexao.php");

$sql = "
SELECT
    compras.id,
    compras.comprovante,
    compras.status,

    usuarios.nome AS cliente,

    fotos.nome AS foto

FROM compras

INNER JOIN usuarios
ON compras.usuario_id = usuarios.id

INNER JOIN fotos
ON compras.foto_id = fotos.id

ORDER BY compras.id DESC
";

$pedidos = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Pedidos</title>

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

header{

background:#111;
color:white;

padding:15px 30px;

display:flex;
justify-content:space-between;
align-items:center;
}

header a{

color:white;
text-decoration:none;
background:#333;

padding:10px 15px;

border-radius:5px;
}

.container{

width:95%;
max-width:1200px;

margin:30px auto;
}

.card{

background:white;

padding:20px;

border-radius:10px;

margin-bottom:15px;

box-shadow:
0 0 10px rgba(0,0,0,.1);
}

h2{

margin-bottom:20px;
}

.status{

font-weight:bold;

margin-top:10px;
}

.pendente{
color:#d97706;
}

.aprovado{
color:#16a34a;
}

.recusado{
color:#dc2626;
}

.btn{

display:inline-block;

padding:10px 15px;

text-decoration:none;

color:white;

border-radius:5px;

margin-top:10px;

margin-right:5px;
}

.aprovar{
background:#16a34a;
}

.recusar{
background:#dc2626;
}

.ver{
background:#2563eb;
}

img{

max-width:250px;

margin-top:15px;

border-radius:10px;
}

</style>

</head>

<body>

<header>

<h1>
Pedidos Recebidos
</h1>

<a href="dashboard.php">
Voltar
</a>

</header>

<div class="container">

<h2>
Lista de Pedidos
</h2>

<?php while($pedido = $pedidos->fetch_assoc()){ ?>

<div class="card">

<p>

<strong>Cliente:</strong>

<?php echo $pedido['cliente']; ?>

</p>

<p>

<strong>Foto:</strong>

<?php echo $pedido['foto']; ?>

</p>

<p>

<strong>Comprovante:</strong>

</p>

<img
src="../uploads/comprovantes/<?php echo $pedido['comprovante']; ?>"
>

<p class="status <?php echo $pedido['status']; ?>">

Status:
<?php echo ucfirst($pedido['status']); ?>

</p>

<?php if($pedido['status'] == 'pendente'){ ?>

<a
class="btn aprovar"
href="aprovar.php?id=<?php echo $pedido['id']; ?>&acao=aprovar"
>
Aprovar
</a>

<a
class="btn recusar"
href="aprovar.php?id=<?php echo $pedido['id']; ?>&acao=recusar"
>
Recusar
</a>

<?php } ?>

</div>

<?php } ?>

</div>

</body>

</html>
```
