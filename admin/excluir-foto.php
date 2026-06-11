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

if(!isset($_GET["id"])){

    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare(
    "SELECT imagem
    FROM fotos
    WHERE id = ?"
);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$resultado = $stmt->get_result();

if($resultado->num_rows == 0){

    header("Location: dashboard.php");
    exit;
}

$foto = $resultado->fetch_assoc();

$caminho =
"../uploads/fotos/"
. $foto["imagem"];

if(file_exists($caminho)){

    unlink($caminho);
}

$delete = $conn->prepare(
    "DELETE FROM fotos
    WHERE id = ?"
);

$delete->bind_param(
    "i",
    $id
);

$delete->execute();

header(
    "Location: dashboard.php"
);

exit;
?>
```
