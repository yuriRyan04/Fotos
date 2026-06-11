<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Fotógrafo Store</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f5f5f5;
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
    font-size:24px;
}

nav{
    display:flex;
    gap:10px;
}

button{
    cursor:pointer;
}

.btn{
    background:#111;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:5px;
}

.btn:hover{
    background:#333;
}

.container{
    width:90%;
    max-width:1200px;
    margin:20px auto;
}

.form-box{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

.form-box h2{
    margin-bottom:15px;
}

input{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border:1px solid #ccc;
    border-radius:5px;
}

.hidden{
    display:none;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:20px;
}

.card{
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

.photo-container{
    position:relative;
}

.photo-container img{
    width:100%;
    height:250px;
    object-fit:cover;
}

.watermark{
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%) rotate(-30deg);
    color:rgba(255,255,255,.7);
    font-size:32px;
    font-weight:bold;
    pointer-events:none;
}

.card-body{
    padding:15px;
}

.price{
    color:green;
    font-size:22px;
    font-weight:bold;
    margin:10px 0;
}

.admin-panel{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

.admin-panel h2{
    margin-bottom:15px;
}

.status{
    margin-top:10px;
    font-weight:bold;
}

</style>
</head>
<body>

<header>
    <h1>📸 Fotógrafo Store</h1>

    <nav>
        <button class="btn" onclick="mostrarLogin()">Entrar</button>
        <button class="btn" onclick="mostrarCadastro()">Cadastrar</button>
        <button class="btn hidden" id="logoutBtn" onclick="logout()">Sair</button>
    </nav>
</header>

<div class="container">

    <!-- Login -->
    <div id="loginBox" class="form-box hidden">
        <h2>Login</h2>

        <input type="email" id="loginEmail" placeholder="Email">
        <input type="password" id="loginSenha" placeholder="Senha">

        <button class="btn" onclick="login()">Entrar</button>
    </div>

    <!-- Cadastro -->
    <div id="cadastroBox" class="form-box hidden">
        <h2>Cadastro</h2>

        <input type="text" id="cadNome" placeholder="Nome">
        <input type="email" id="cadEmail" placeholder="Email">
        <input type="password" id="cadSenha" placeholder="Senha">

        <button class="btn" onclick="cadastrar()">Cadastrar</button>
    </div>

    <!-- Painel Admin -->
    <div id="adminPanel" class="admin-panel hidden">
        <h2>Painel do Fotógrafo</h2>

        <input type="text" id="fotoNome" placeholder="Nome da foto">
        <input type="number" id="fotoPreco" placeholder="Preço">

        <input type="file" id="fotoArquivo">

        <button class="btn" onclick="adicionarFoto()">
            Publicar Foto
        </button>
    </div>

    <h2 style="margin-bottom:20px;">Galeria de Fotos</h2>

    <div class="grid" id="galeria"></div>

</div>

<script>

const admin = {
    email: "admin@fotografo.com",
    senha: "123456"
};

let usuarioLogado = null;

let fotos = JSON.parse(localStorage.getItem("fotos")) || [
    {
        id:1,
        nome:"Pôr do Sol",
        preco:25,
        imagem:"https://picsum.photos/500/300?random=1"
    },
    {
        id:2,
        nome:"Praia",
        preco:35,
        imagem:"https://picsum.photos/500/300?random=2"
    }
];

renderizarGaleria();

function mostrarLogin(){
    document.getElementById("loginBox").classList.remove("hidden");
    document.getElementById("cadastroBox").classList.add("hidden");
}

function mostrarCadastro(){
    document.getElementById("cadastroBox").classList.remove("hidden");
    document.getElementById("loginBox").classList.add("hidden");
}

function cadastrar(){

    const nome = document.getElementById("cadNome").value;
    const email = document.getElementById("cadEmail").value;
    const senha = document.getElementById("cadSenha").value;

    if(!nome || !email || !senha){
        alert("Preencha todos os campos.");
        return;
    }

    const usuarios =
        JSON.parse(localStorage.getItem("usuarios")) || [];

    usuarios.push({
        nome,
        email,
        senha
    });

    localStorage.setItem(
        "usuarios",
        JSON.stringify(usuarios)
    );

    alert("Cadastro realizado com sucesso!");
}

function login(){

    const email =
        document.getElementById("loginEmail").value;

    const senha =
        document.getElementById("loginSenha").value;

    if(email === admin.email && senha === admin.senha){

        usuarioLogado = admin;

        document
            .getElementById("adminPanel")
            .classList.remove("hidden");

        document
            .getElementById("logoutBtn")
            .classList.remove("hidden");

        alert("Login do fotógrafo realizado.");

        return;
    }

    const usuarios =
        JSON.parse(localStorage.getItem("usuarios")) || [];

    const usuario =
        usuarios.find(u =>
            u.email === email &&
            u.senha === senha
        );

    if(usuario){

        usuarioLogado = usuario;

        document
            .getElementById("logoutBtn")
            .classList.remove("hidden");

        alert("Login realizado!");

    }else{

        alert("Email ou senha incorretos.");
    }
}

function logout(){

    usuarioLogado = null;

    document
        .getElementById("adminPanel")
        .classList.add("hidden");

    document
        .getElementById("logoutBtn")
        .classList.add("hidden");

    alert("Logout realizado.");
}

function renderizarGaleria(){

    const galeria =
        document.getElementById("galeria");

    galeria.innerHTML = "";

    fotos.forEach(foto => {

        galeria.innerHTML += `
        <div class="card">

            <div class="photo-container">

                <img src="${foto.imagem}" alt="${foto.nome}">

                <div class="watermark">
                    AMOSTRA
                </div>

            </div>

            <div class="card-body">

                <h3>${foto.nome}</h3>

                <div class="price">
                    R$ ${Number(foto.preco).toFixed(2)}
                </div>

                <button class="btn"
                    onclick="comprar(${foto.id})">
                    Comprar
                </button>

            </div>

        </div>
        `;
    });
}

function comprar(id){

    if(!usuarioLogado){

        alert("Faça login primeiro.");
        return;
    }

    const comprovante = prompt(
        "Digite o nome do comprovante:"
    );

    if(!comprovante) return;

    alert(
        "Pagamento aprovado (simulação).\nDownload liberado."
    );

    const foto =
        fotos.find(f => f.id === id);

    const link =
        document.createElement("a");

    link.href = foto.imagem;
    link.download = foto.nome + ".jpg";

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function adicionarFoto(){

    const nome =
        document.getElementById("fotoNome").value;

    const preco =
        document.getElementById("fotoPreco").value;

    const arquivo =
        document.getElementById("fotoArquivo")
        .files[0];

    if(!nome || !preco || !arquivo){

        alert("Preencha todos os campos.");
        return;
    }

    const reader = new FileReader();

    reader.onload = function(e){

        fotos.push({
            id:Date.now(),
            nome:nome,
            preco:preco,
            imagem:e.target.result
        });

        localStorage.setItem(
            "fotos",
            JSON.stringify(fotos)
        );

        renderizarGaleria();

        alert("Foto publicada com sucesso!");
    };

    reader.readAsDataURL(arquivo);
}

</script>

</body>
</html>
