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

if($_SERVER["REQUEST_METHOD"] != "POST"){
    header("Location: dashboard.php");
    exit;
}

$nome = trim($_POST["nome"]);
$preco = floatval($_POST["preco"]);

if(
    empty($nome)
    ||
    empty($preco)
    ||
    !isset($_FILES["imagem"])
){
    die("Dados inválidos.");
}

$arquivo = $_FILES["imagem"];

if($arquivo["error"] != 0){
    die("Erro no upload.");
}

$permitidos = [
    "image/jpeg",
    "image/jpg",
    "image/png",
    "image/webp"
];

if(!in_array($arquivo["type"], $permitidos)){
    die("Formato não permitido.");
}

$extensao = pathinfo(
    $arquivo["name"],
    PATHINFO_EXTENSION
);

$novoNome =
uniqid("foto_", true)
. "."
. $extensao;

$destino =
"../uploads/fotos/"
. $novoNome;

if(
    move_uploaded_file(
        $arquivo["tmp_name"],
        $destino
    )
){

    $stmt = $conn->prepare(
        "INSERT INTO fotos
        (nome, preco, imagem)
        VALUES (?, ?, ?)"
    );

    $stmt->bind_param(
        "sds",
        $nome,
        $preco,
        $novoNome
    );

    $stmt->execute();

    header(
        "Location: dashboard.php"
    );

    exit;

}else{

    die(
        "Não foi possível salvar a imagem."
    );
}
?>
```
