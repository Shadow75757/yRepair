<?php

    //iniciamos uma sessao
    session_start();

    //verificar se o utilizador esta com login efetuado, se sim encaminhar para a pagina home.php
    if(isset($_SESSION["login"]) && $_SESSION["login"] === true) {

        header("location: home.php");
        exit;
    }

    //carregar o ficheiro db.php responsável pelo acesso à bd
    include_once("db.php");

    //verificar se há um post
    if($_SERVER["REQUEST_METHOD"] == "POST") {

        //verificar os campos username e password
        if(empty($_POST["username"]) || empty($_POST["password"])) {

            //redirecionamos para a página index.php com o código de erro = 1
            header("location: index.php?erro=1");
        }
        else {

            //definimos as variáveis
            $username = $_POST["username"];
            $password = $_POST["password"];

            //consulta à base de dados
            $query = "select * from utilizadores where username='$username'";

            //executar a consulta 
            $resultado = mysqli_query($conexao, $query);

            //se o resultado resultar num true...
            if($resultado) {

                $utilizador = mysqli_fetch_row($resultado);
                $idUtilizador = $utilizador[0];
                $usernameUtilizador = $utilizador[1];
                $passwordUtilizador = $utilizador[2];

                //verificar a password
                if(password_verify($password, $passwordUtilizador)) {

                    //se a password estiver correta iniciamos uma sessão
                    session_start();

                    //guardar dados em variaveis de sessao
                    $_SESSION["login"] = true;
                    $_SESSION["id"] = $idUtilizador;
                    $_SESSION["username"] = $usernameUtilizador;

                    //redirecionamos para a pagina home.php
                    header("location: home.php");
                }
                else {

                    //no caso da password inválida redirecionamos para o index.php com erro 2
                    header("location: index.php?erro=2");
                }
            }

            //fechar a ligação ao mysql
            mysqli_close($conexao);
        }
    }

?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <title>yRepair - Gestão de Reparações</title>
    <!-- jQuery -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom CSS neste caso para diminuir o tamanho do container em ecrãs de menor resolução -->
    <style>

        @media screen and (min-width: 900px) {

            .container {
                max-width: 40% !important;
            }
        }

    </style>

    <body>

        <div class="container mt-3 p-4 bg-light">

            <h2>Bem-vindo à área reservada</h2>
            <p>Introduza os seus dados de login.</p>

            <form action="index.php" method="post">

                <div class="form-group">

                    <label>Username:</label>
                    <input type="text" name="username" class="form-control" value="">

                </div>

                <div class="form-group">

                    <label>Password:</label>
                    <input type="password" name="password" class="form-control">

                </div>

                <div class="form-group">

                    <input type="submit" class="btn btn-info" value="Login">

                </div>

            </form>

            <?php

                //tratar as mensagens de erro
                if(!empty($_GET["erro"])) {

                    $erro = $_GET["erro"];

                    //em função do código do erro apresentamos uma mensagem
                    switch($erro) {

                        case 1:
                            $erro_descricao = "Username e/ou password vazio/inválido!";
                            break;
                        
                        case 2:
                            $erro_descricao = "Username e/ou password incorreto!";
                            break;
                    }
                }

                //apresentar a mensagem de erro
                if(isset($erro)) {
                ?>
                    <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong><?=$erro_descricao?></strong>
                    </div>
                <?php
                }
                ?>

        </div>

    </body>

<head>
</html>