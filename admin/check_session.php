<?php

    //verificar se existe um login efetuado, senão encaminha para a pagina de login

    //iniciamos a sessão
    session_start();

    if($_SESSION["login"] != true) {

        header("location: index.php");
        exit;
    }

?>