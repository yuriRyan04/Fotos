```php
<?php

session_start();

if(!isset($_SESSION["id"])){

    header("Location: ../login.php");
    exit;
}

require_once("../config/conexao.php");

if(!isset($_GET["id"])){

    die("Foto inválida.");
}

$idFoto = intval($_GET["id"]);
$idUsuario = $_SESSION["id"];

$sql = "
SELECT

    compras.id,

    compras.status,

    fotos.imagem,
    fotos.nome

FROM compras

INNER JOIN fotos
ON compras.foto_id = fotos.id

WHERE

    compras.usuario_id = ?
    AND compras.foto_id = ?
    AND compras.status = 'aprovado'

LIMIT 1
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $idUsuario,
    $idFoto
);

$stmt->execute();

$resultado = $stmt->get_result();

if($resultado->num_rows == 0){

    die(
        "Você não possui acesso a esta foto."
    );
}

$foto = $resultado->fetch_assoc();

$arquivo =
"../uploads/fotos/"
. $foto["imagem"];

if(!file_exists($arquivo)){

    die("Arquivo não encontrado.");
}

$extensao =
pathinfo(
    $arquivo,
    PATHINFO_EXTENSION
);

header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header(
    "Content-Disposition: attachment; filename=\""
    . $foto["nome"]
    . "."
    . $extensao
    . "\""
);

header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");
header("Content-Length: " . filesize($arquivo));

readfile($arquivo);

exit;

?>
```
