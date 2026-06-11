```php
<?php

session_start();

if(
    !isset($_SESSION["id"])
){
    header("Location: ../login.php");
    exit;
}

require_once("../config/conexao.php");

$idUsuario = $_SESSION["id"];

$sql = "
SELECT

    compras.id,
    compras.status,

    fotos.id AS foto_id,
    fotos.nome,
    fotos.preco,
    fotos.imagem

FROM compras

INNER JOIN fotos
ON compras.foto_id = fotos.id

WHERE compras.usuario_id = ?

ORDER BY compras.id DESC
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $idUsuario
);

$stmt->execute();

$compras = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Minhas Compras</title>

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

.grid{

display:grid;

grid-template-columns:
repeat(auto-fill,minmax(280px,1fr));

gap:20px;
}

.card{

background:white;

border-radius:10px;

overflow:hidden;

box-shadow:
0 0 10px rgba(0,0,0,.1);
}

.card img{

width:100%;
height:220px;

object-fit:cover;
}

.info{

padding:15px;
}

.preco{

color:green;

font-size:20px;

font-weight:bold;

margin:10px 0;
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

display:block;

width:100%;

text-align:center;

padding:12px;

text-decoration:none;

border-radius:5px;

margin-top:15px;

color:white;
}

.download{
background:#16a34a;
}

.download:hover{
background:#15803d;
}

</style>

</head>

<body>

<header>

<h1>
Minhas Compras
</h1>

<a href="../index.php">
Voltar
</a>

</header>

<div class="container">

<div class="grid">

<?php while($compra = $compras->fetch_assoc()){ ?>

<div class="card">

<img
src="../uploads/fotos/<?php echo $compra['imagem']; ?>"
alt=""
>

<div class="info">

<h3>
<?php echo $compra['nome']; ?>
</h3>

<div class="preco">

R$
<?php echo number_format(
$compra['preco'],
2,
',',
'.'
); ?>

</div>

<div class="status <?php echo $compra['status']; ?>">

Status:
<?php echo ucfirst($compra['status']); ?>

</div>

<?php if($compra['status'] == 'aprovado'){ ?>

<a
class="btn download"
href="download.php?id=<?php echo $compra['foto_id']; ?>"
>
Baixar Foto
</a>

<?php } ?>

</div>

</div>

<?php } ?>

</div>

</div>

</body>

</html>
```
