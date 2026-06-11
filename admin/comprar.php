```php
<?php

session_start();

if(!isset($_SESSION["id"])){

    header("Location: login.php");
    exit;
}

require_once("config/conexao.php");

if(!isset($_GET["id"])){

    header("Location: index.php");
    exit;
}

$idFoto = intval($_GET["id"]);

$stmt = $conn->prepare(
    "SELECT * FROM fotos WHERE id = ?"
);

$stmt->bind_param("i", $idFoto);
$stmt->execute();

$resultado = $stmt->get_result();

if($resultado->num_rows == 0){

    die("Foto não encontrada.");
}

$foto = $resultado->fetch_assoc();

$msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(!isset($_FILES["comprovante"])){

        $msg = "Selecione um comprovante.";

    }else{

        $arquivo = $_FILES["comprovante"];

        if($arquivo["error"] == 0){

            $extensao = pathinfo(
                $arquivo["name"],
                PATHINFO_EXTENSION
            );

            $nomeArquivo =
            uniqid("comp_", true)
            . "."
            . $extensao;

            $destino =
            "uploads/comprovantes/"
            . $nomeArquivo;

            if(
                move_uploaded_file(
                    $arquivo["tmp_name"],
                    $destino
                )
            ){

                $pedido = $conn->prepare(
                    "INSERT INTO compras
                    (
                        usuario_id,
                        foto_id,
                        comprovante,
                        status
                    )
                    VALUES
                    (
                        ?, ?, ?, 'pendente'
                    )"
                );

                $pedido->bind_param(
                    "iis",
                    $_SESSION["id"],
                    $idFoto,
                    $nomeArquivo
                );

                $pedido->execute();

                $msg =
                "Comprovante enviado com sucesso. Aguarde aprovação.";

            }else{

                $msg =
                "Erro ao salvar comprovante.";
            }
        }
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

<title>Comprar Foto</title>

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

max-width:900px;

margin:30px auto;

background:white;

padding:25px;

border-radius:10px;

box-shadow:
0 0 10px rgba(0,0,0,.1);
}

img{

width:100%;

max-height:500px;

object-fit:cover;

border-radius:10px;
}

h1{

margin:20px 0 10px;
}

.preco{

font-size:28px;

font-weight:bold;

color:green;

margin-bottom:20px;
}

.pix{

background:#f1f1f1;

padding:15px;

border-radius:10px;

margin-bottom:20px;
}

input{

width:100%;

padding:12px;

margin-top:10px;

margin-bottom:15px;
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

.msg{

margin-bottom:20px;

font-weight:bold;

color:green;
}

</style>

</head>

<body>

<div class="container">

<?php if($msg != ""){ ?>

<div class="msg">
<?php echo $msg; ?>
</div>

<?php } ?>

<img
src="uploads/fotos/<?php echo $foto['imagem']; ?>"
alt=""
>

<h1>
<?php echo $foto['nome']; ?>
</h1>

<div class="preco">

R$
<?php echo number_format(
$foto['preco'],
2,
',',
'.'
); ?>

</div>

<div class="pix">

<h3>Pague via PIX</h3>

<p>

Chave PIX:

<strong>
ceepplg.comissao.2026@gmail.com
</strong>

</p>

</div>

<form
method="POST"
enctype="multipart/form-data"
>

<label>

Envie o comprovante:

</label>

<input
type="file"
name="comprovante"
required
>

<button type="submit">

Enviar Comprovante

</button>

</form>

</div>

</body>

</html>
```
