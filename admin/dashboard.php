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

$fotos = $conn->query(
    "SELECT * FROM fotos ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Painel do Fotógrafo</title>

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

header h1{
    font-size:22px;
}

.menu{
    display:flex;
    gap:10px;
}

.menu a{

    text-decoration:none;

    background:#333;
    color:white;

    padding:10px 15px;

    border-radius:5px;
}

.menu a:hover{
    background:#555;
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

    margin-bottom:20px;

    box-shadow:
    0 0 10px rgba(0,0,0,.1);
}

h2{
    margin-bottom:15px;
}

input{

    width:100%;

    padding:12px;

    margin-bottom:10px;

    border:1px solid #ccc;

    border-radius:5px;
}

button{

    background:#111;

    color:white;

    border:none;

    padding:12px 20px;

    border-radius:5px;

    cursor:pointer;
}

button:hover{
    background:#333;
}

.grid{

    display:grid;

    grid-template-columns:
    repeat(auto-fill,minmax(250px,1fr));

    gap:20px;
}

.foto{

    background:white;

    border-radius:10px;

    overflow:hidden;

    box-shadow:
    0 0 10px rgba(0,0,0,.1);
}

.foto img{

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

.excluir{

    display:block;

    width:100%;

    text-align:center;

    text-decoration:none;

    background:#dc2626;

    color:white;

    padding:10px;

    border-radius:5px;
}

.excluir:hover{
    background:#b91c1c;
}

</style>

</head>

<body>

<header>

<h1>
Painel do Fotógrafo
</h1>

<div class="menu">

<a href="pedidos.php">
Pedidos
</a>

<a href="../logout.php">
Sair
</a>

</div>

</header>

<div class="container">

<div class="card">

<h2>
Bem-vindo,
<?php echo $_SESSION["nome"]; ?>
</h2>

<p>
Gerencie suas fotos e vendas.
</p>

</div>

<div class="card">

<h2>
Adicionar Nova Foto
</h2>

<form
action="upload.php"
method="POST"
enctype="multipart/form-data"
>

<input
type="text"
name="nome"
placeholder="Nome da foto"
required
>

<input
type="number"
step="0.01"
name="preco"
placeholder="Preço"
required
>

<input
type="file"
name="imagem"
required
>

<button type="submit">
Publicar Foto
</button>

</form>

</div>

<div class="card">

<h2>
Fotos Publicadas
</h2>

<div class="grid">

<?php while($foto = $fotos->fetch_assoc()){ ?>

<div class="foto">

<img
src="../uploads/fotos/<?php echo $foto['imagem']; ?>"
>

<div class="info">

<h3>
<?php echo $foto['nome']; ?>
</h3>

<div class="preco">
R$
<?php echo number_format(
$foto['preco'],
2,
',',
'.'
); ?>
</div>

<a
class="excluir"
href="excluir-foto.php?id=<?php echo $foto['id']; ?>"
onclick="return confirm('Deseja excluir esta foto?')"
>
Excluir Foto
</a>

</div>

</div>

<?php } ?>

</div>

</div>

</div>

</body>

</html>
```
