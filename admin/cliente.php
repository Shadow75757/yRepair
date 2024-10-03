<?php

    //vamos verificar se temos umget
    if($_SERVER["REQUEST_METHOD"] == "GET") {

        //vamos verificar se e passado um valor no id e se e mesmo é numerico (ex: id=1)
        if(isset($_GET["id"]) && is_numeric($_GET["id"])) {

            //incluimos o ficheiro db.phpque faz a ligação a base de dados mysql
            include_once("db.php");

            //definimos a variavel idTarefa
            $idCliente = $_GET["id"];

            //efetuamos uma consulta select apenas para a tarefa com o respectivo id
            $query = "select * from clientes where id_cliente=$idCliente";

            //executamos a consulta
            $resultado = mysqli_query($conexao, $query);

            //obtemos uma variavel com o numero de registos encontrados na consulta
            $registos = mysqli_num_rows($resultado);  
            
            //vamos retornar uma variavel tarefa com o resultado em formato de array
            $cliente = mysqli_fetch_row($resultado);
            $nomeCliente = $cliente[1];
            $moradaCliente = $cliente[2];
            $telemovelCliente = $cliente[3];
            $emailCliente = $cliente[4];
            $obsCliente = $cliente[5];
        } else {

            //se não temos um id colocamos as variaveis em empty
            $idCliente = "";
            $nomeCliente = "";
            $moradaCliente = "";
            $telemovelCliente = "";
            $emailCliente = "";
            $obsCliente = "";
        }
    }

    //vamos verificar se existe um POST (que é quando o botao editar / guardar e carregado)
    if($_SERVER["REQUEST_METHOD"] == "POST") {
               
        //incluimos o ficheiro db.php
        include_once("db.php");

        //vamos verificar se os campos estão preenchidos e definimos as variaveis
        if(!empty($_POST["nomeCliente"])) {
            $nomeCliente = $_POST["nomeCliente"];
        } else {
            $nomeCliente = "";
        }

        //de outra forma com if ternário
        $idCliente = (!empty($_POST["idCliente"])) ? $_POST["idCliente"] : "";
        $moradaCliente = (!empty($_POST["moradaCliente"])) ? $_POST["moradaCliente"] : "";
        $telemovelCliente = (!empty($_POST["telemovelCliente"])) ? $_POST["telemovelCliente"] : "";
        $emailCliente = (!empty($_POST["emailCliente"])) ? $_POST["emailCliente"] : "";
        $obsCliente = (!empty($_POST["obsCliente"])) ? $_POST["obsCliente"] : "";
        
        //inserir uma nova tarefa ou editar uma existente
        //se tivermos um ID estamos a editar, sem ID estamos a adicionar uma nova
        if(empty($idCliente)) {

            //query para inserir nova tarefa
            $query = "insert into clientes (nome, morada, telemovel, email, obs) values('$nomeCliente', '$moradaCliente', '$telemovelCliente', '$emailCliente', '$obsCliente')";

            //executamos a consulta 
            $resultado = mysqli_query($conexao, $query);

            //se o resultado retornar um true encaminhamos para a página tarefas com msg=1
            if($resultado) {
                header("location: clientes.php?msg=1");
            }

        } else {

            //query para editar tarefa existente
            $query = "update clientes set nome='$nomeCliente', morada='$moradaCliente', telemovel='$telemovelCliente', email='$emailCliente', obs='$obsCliente' where id_cliente=$idCliente";
            
            //executamos a consulta 
            $resultado = mysqli_query($conexao, $query);

            //se o resultado retornar um true encaminhamos a pagina para o tarefas.php com uma msg=2
            if($resultado) {

                header("location: clientes.php?msg=2");
            }
        }
    }

    //incluimos o ficheiro header.inc.php
    include_once("header.inc.php");

?>

<div class="container">
    <h2>Cliente</h2> 
    <hr>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="nomeCliente" class="col-sm-3 col-form-label">Nome</label>
            <div class="col-sm-7">
            <input type="text" name="nomeCliente" id="nomeCliente" class="form-control" placeholder="Nome" value="<?=$nomeCliente?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="moradaCliente" class="col-sm-3 col-form-label">Morada</label>
            <div class="col-sm-7">
            <input type="text" name="moradaCliente" id="moradaCliente" class="form-control" placeholder="Morada" value="<?=$moradaCliente?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="telemovelCliente" class="col-sm-3 col-form-label">Telemóvel</label>
            <div class="col-sm-7">
            <input type="text" name="telemovelCliente" id="telemovelCliente" class="form-control" placeholder="Telemóvel" value="<?=$telemovelCliente?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="emailCliente" class="col-sm-3 col-form-label">E-mail</label>
            <div class="col-sm-7">
            <input type="text" name="emailCliente" id="emailCliente" class="form-control" placeholder="E-mail" value="<?=$emailCliente?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="obsCliente" class="col-sm-3 col-form-label">Obs.</label>
            <div class="col-sm-7">
            <input type="text" name="obsCliente" id="obsCliente" class="form-control" placeholder="Observações" value="<?=$obsCliente?>">
            </div>
        </div>
        <div class="col-sm-2">
                <input type="hidden" name="idCliente" value="<?=$idCliente?>">
                <button type="submit" name="enviar" class="btn btn-dark">Guardar</button>&nbsp<a href="clientes.php"><button type="button" class="btn btn-light">Voltar</button></a>
            </div>
        </div>
    </form>
</div>

<?php

    //incluimos o ficheiro footer.inc.php
    include_once("footer.inc.php");

?>