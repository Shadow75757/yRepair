<?php


    //vamos verificar se temos umget
    if($_SERVER["REQUEST_METHOD"] == "GET") {

        //vamos verificar se e passado um valor no id e se e mesmo é numerico (ex: id=1)
        if(isset($_GET["id"]) && is_numeric($_GET["id"])) {

            //incluimos o ficheiro db.phpque faz a ligação a base de dados mysql
            include_once("db.php");

            //definimos a variavel idTarefa
            $idReparacao = $_GET["id"];

            //efetuamos uma consulta select apenas para a tarefa com o respectivo id
            $query = "select * from reparacoes where id_reparacao=$idReparacao";

            //executamos a consulta
            $resultado = mysqli_query($conexao, $query);

            //obtemos uma variavel com o numero de registos encontrados na consulta
            $registos = mysqli_num_rows($resultado);  
            
            //vamos retornar uma variavel tarefa com o resultado em formato de array
            $reparacao = mysqli_fetch_row($resultado);
            $codigoReparacao = $reparacao[1];
            $idCliente = $reparacao[2];
            $equipamentoReparacao = $reparacao[3];
            $imeiReparacao = $reparacao[4];
            $obsReparacao = $reparacao[5];
            $estadoReparacao = $reparacao[6];
            $obsEstadoReparacao = $reparacao[7];

        } else {

            //se não temos um id colocamos as variaveis em empty
            $idReparacao = "";
            
            //gerar codigo de reparacao
            $codigoReparacao = "";
            $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            for($i = 0; $i <= 5; $i++) {
                $index = rand(0, strlen($caracteres) - 1);
                $codigoReparacao .= $caracteres[$index];
            }

            $idCliente = "";
            $equipamentoReparacao = "";
            $imeiReparacao = "";
            $obsReparacao = "";
            $estadoReparacao = "";
            $obsEstadoReparacao = "";
        }
    }
        
    //vamos verificar se existe um POST (que é quando o botao editar / guardar e carregado)
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //incluimos o ficheiro db.php
        include_once("db.php");

        //de outra forma com if ternário
        $idReparacao = (!empty($_POST["idReparacao"])) ? $_POST["idReparacao"] : "";
        $codigoReparacao = (!empty($_POST["codigoReparacao"])) ? $_POST["codigoReparacao"] : "";
        $idCliente = (!empty($_POST["idCliente"])) ? $_POST["idCliente"] : "";
        $equipamentoReparacao = (!empty($_POST["equipamentoReparacao"])) ? $_POST["equipamentoReparacao"] : "";
        $imeiReparacao = (!empty($_POST["imeiReparacao"])) ? $_POST["imeiReparacao"] : "";
        $obsReparacao = (!empty($_POST["obsReparacao"])) ? $_POST["obsReparacao"] : "";
        $estadoReparacao = (!empty($_POST["estadoReparacao"])) ? $_POST["estadoReparacao"] : "";
        $obsEstadoReparacao = (!empty($_POST["obsEstadoReparacao"])) ? $_POST["obsEstadoReparacao"] : "";


        
        //inserir uma nova tarefa ou editar uma existente
        //se tivermos um ID estamos a editar, sem ID estamos a adicionar uma nova
        if(empty($idReparacao)) {  

            //query para inserir nova tarefa
            $query = "insert into reparacoes (codigo_reparacao, id_cliente, equipamento, imei, obs, estado, obs_estado) values('$codigoReparacao', '$idCliente', '$equipamentoReparacao', '$imeiReparacao', '$obsReparacao', '$estadoReparacao', '$obsEstadoReparacao')";

            //executamos a consulta
            $resultado = mysqli_query($conexao, $query); 

            //se o resultado retornar um true encaminhamos para a página tarefas com msg=1
            if($resultado) {
                header("location: home.php?msg=1");
            }

        } else {

            //query para editar tarefa existente
            $query = "update reparacoes set equipamento = '$equipamentoReparacao', imei='$imeiReparacao', obs='$obsReparacao', estado='$estadoReparacao', obs_estado='$obsEstadoReparacao' where id_reparacao=$idReparacao";
            
            //executamos a consulta 
            $resultado = mysqli_query($conexao, $query);

            //se o resultado retornar um true encaminhamos a pagina para o tarefas.php com uma msg=2
            if($resultado) {

                header("location: home.php?msg=2");
            }

            $codigoReparacao = $resultado["codigo_reparacao"];
        }
    }

    //incluimos o ficheiro header.inc.php
    include_once("header.inc.php");

?>

<div class="container">
    <h2>Reparação</h2>
    <hr>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="codigoReparacao" class="col-sm-3 col-form-label">Código Reparacão</label>
            <div class="col-sm-7">
            <input type="text" name="codigoReparacao" id="codigoReparacao" class="form-control" value="<?=$codigoReparacao?>" readonly required>
            </div>
        </div>
        <div class="form-group row">
            <label for="idCliente" class="col-sm-3 col-form-label">Cliente</label>
            <div class="col-sm-7 text-left">
                <select class="form-control" name="idCliente" id="idCliente" required>
                <option value="">Selecione o cliente</option>
                <?php
                    include_once("db.php");
                    
                    $query = "select * from clientes";

                    //executamos a consulta
                    $resultado = mysqli_query($conexao, $query);
            
                    //obtemos uma variavel com o numero de registos encontrados na consulta
                    $registos = mysqli_num_rows($resultado);

                    if(!empty($registos)) {
                        while($cliente = mysqli_fetch_assoc($resultado)) {  ?>   
                            <option value="<?=$cliente["id_cliente"]?>" <?php if($idCliente==$cliente["id_cliente"]) echo "selected"; ?>><?=$cliente["nome"]?></option>
                    <?php  
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="equipamentoReparacao" class="col-sm-3 col-form-label">Equipamento</label>
            <div class="col-sm-7">
            <input type="text" name="equipamentoReparacao" id="equipamentoReparacao" class="form-control" placeholder="Exemplo: iPhone 13 Pro Azul" value="<?=$equipamentoReparacao?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="imeiReparacao" class="col-sm-3 col-form-label">IMEI</label>
            <div class="col-sm-7">
            <input type="text" pattern="[0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]" name="imeiReparacao" id="imeiReparacao" class="form-control" placeholder="N.º de 15 digítos" value="<?=$imeiReparacao?>" required>
            </div>
        </div>
        <div class="form-group row">
            <label for="obsReparacao" class="col-sm-3 col-form-label">Observações</label>
            <div class="col-sm-7">
            <textarea type="text" name="obsReparacao" id="obsReparacao" class="form-control" placeholder="Indicar problemas do equipamento (ecrã partido; não carrega; áudio danificado; ...)" value="" required><?=$obsReparacao?></textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-5 text-left">
                <label for="estadoReparacao" class="col-sm-3 col-form-label" style="padding-left: 0;">Estado</label>
                    <select class="form-control" name="estadoReparacao" id="estadoReparacao" required>
                    <option value="">Selecione o estado</option> 
                    <option value="1" <?php if($estadoReparacao==1) echo "selected"; ?>>Em aberto</option>
                    <option value="2" <?php if($estadoReparacao==2) echo "selected"; ?>>Em aprovação</option>
                    <option value="3" <?php if($estadoReparacao==3) echo "selected"; ?>>Em reparação</option>
                    <option value="4" <?php if($estadoReparacao==4) echo "selected"; ?>>Pronto para entrega</option>
                    <option value="5" <?php if($estadoReparacao==5) echo "selected"; ?>>Entregue</option>
                    <option value="6" <?php if($estadoReparacao==6) echo "selected"; ?>>Cancelada</option>
                    </select>
            </div>

            <div class="col-5 text-left">
                <label for="descricaoReparacao" class="col-sm-3 col-form-label" style="max-width: 60%; padding-left: 0;">Obs. do Estado</label>
                    <input type="text" name="obsEstadoReparacao" id="obsEstadoReparacao" class="form-control" value="<?=$obsEstadoReparacao?>">
            </div>
        </div>  
        <div class="form-group row">
        <div class="col-sm-2">
                <input type="hidden" name="idReparacao" value="<?=$idReparacao?>">
                <button type="submit" name="enviar" class="btn btn-dark">Guardar</button>&nbsp<a href="home.php"><button type="button" class="btn btn-light">Voltar</button></a>
            </div>
        </div>
    </form>
</div>

<?php
//incluímos o ficheiro footer.inc.php
include_once("footer.inc.php");
?>