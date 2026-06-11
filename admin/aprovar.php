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

if(
    !isset($_GET["id"])
    ||
    !isset($_GET["acao"])
){
    header("Location: pedidos.php");
    exit;
}

$id = intval($_GET["id"]);
$acao = $_GET["acao"];

if(
    $acao != "aprovar"
    &&
    $acao != "recusar"
){
    header("Location: pedidos.php");
    exit;
}

$status =
($acao == "aprovar")
? "aprovado"
: "recusado";

$stmt = $conn->prepare(
    "UPDATE compras
    SET status = ?
    WHERE id = ?"
);

$stmt->bind_param(
    "si",
    $status,
    $id
);

$stmt->execute();

header("Location: pedidos.php");
exit;

?>
```
