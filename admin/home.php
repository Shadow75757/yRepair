<?php

    //incluimos o ficheiro header.inc.php
    include_once("header.inc.php");

    //incluimos o ficheiro db.php
    include_once("db.php");

    //verificar se temos um argumento pagina no GET
    if(isset($_GET["pagina"])) {

        $pagina = $_GET["pagina"];
    } else {

        $pagina = 1;
    }

    //definir o número de registos por pagina
    $nRegistosPagina = 10;
    $regInicial = ($pagina - 1) * $nRegistosPagina;

    $query = "select count(*) from reparacoes";
    $resultado = mysqli_query($conexao, $query);
    $totalRegistos = mysqli_fetch_array($resultado)[0];
    $totalPaginas = ceil($totalRegistos / $nRegistosPagina);

    //echo "Total Registos: $totalRegistos <br>";
    //echo "Total Paginas: $totalPaginas";



    //vamos efetuar uma consulta/query na tabela de tarefas da bd
    // $query = "select * from reparacoes limit $regInicial, $nRegistosPagina";

    $query = "SELECT r.id_reparacao, r.codigo_reparacao, r.id_cliente, r.equipamento, r.estado, c.nome AS nomeCliente from reparacoes r JOIN clientes c ON r.id_cliente = c.id_cliente limit $regInicial, $nRegistosPagina";

    //executamos a consulta
    $resultado = mysqli_query($conexao, $query);

    //obtemos uma variavel  com o numero de registos
    $registos = mysqli_num_rows($resultado);

?>

<div class="container">

    <?php

        //mensagens de erro e de sucesso
        if(!empty($_GET["msg"])) {

            $msg = $_GET["msg"];

            //em função do codigo da msg vamos mostrar uma informação
            switch($msg) {

                case 1: 
                    $info = "Reparação inserida com sucesso.";
                    $alert = "alert-success";
                    break;
                case 2:
                    $info = "Reparação atualizada com sucesso.";
                    $alert = "alert-info";
                    break;
                case 3:
                    $info = "Reparação removida com sucesso.";
                    $alert = "alert-danger";
                    break;
                case 4:
                    $info = "O ID não existe na base de dados!";
                    $alert = "alert-danger";
            }
        }

        //se a variavel $info tiver um valor vamos mostrar no ecra 
        if(isset($msg)) {

            ?>

                <div class="alert <?=$alert?> alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong><?=$info?></strong>
                </div>

            <?php
        }

    ?>

    <div class ="row">
        <div class="col-6">
            <h2>Reparações</h2>  
        </div>
        <div class="col-6 text-right">
            <a href="reparacao.php"><button type="button" class="btn btn-dark" >+ Nova Reparação</button></a>
            <a href="home.php"><button type="button" class="btn btn-light">Atualizar</button></a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Código Rep.</th>
                <th scope="col">Cliente</th>
                <th scope="col">Equipamento</th>
                <th scope="col">Estado</th>
                <th scope="col">Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php

                //codigo para listar os registos encontrados na tabela tarefas
                if(!empty($registos)) {

                    while($reparacao = mysqli_fetch_assoc($resultado)) {

            ?>
            <tr>
                <td scope="row"><?=$reparacao["codigo_reparacao"]?></td> 
                <td scope="row"><?=$reparacao["nomeCliente"]?></td> 
                <td scope="row"><?=$reparacao["equipamento"]?></td> 
                <td scope="row">
                    <?php
                        //mostrar um botão em função do estado concluido

                        //definicao de variaveis 
                        $idReparacao = $reparacao["id_reparacao"];
                        $estado = $reparacao["estado"];

                        //mostrar o botão em função do valor $estado
                        switch($estado) {

                            case 1:
                                echo "<a href=\"tarefa_estado.php?id=$idReparacao&estado=$estado\">";
                                echo "<button type=\"button\" class=\"btn btn-primary btn-sm\">Em aberto</button>";
                                echo "</a>";
                                break;
                            case 2:
                                echo "<a href=\"tarefa_estado.php?id=$idReparacao&estado=$estado\">";
                                echo "<button type=\"button\" class=\"btn btn-dark btn-sm\">Em aprovação</button>";
                                echo "</a>";
                                break;
                            case 3:
                                echo "<a href=\"tarefa_estado.php?id=$idReparacao&estado=$estado\">";
                                echo "<button type=\"button\" class=\"btn btn-warning btn-sm\">Em reparação</button>";
                                echo "</a>";
                                break;
                            case 4:
                                echo "<a href=\"tarefa_estado.php?id=$idReparacao&estado=$estado\">";
                                echo "<button type=\"button\" class=\"btn btn-info btn-sm\">Pronto para entrega</button>";
                                echo "</a>";
                                break;
                            case 5:
                                echo "<a href=\"tarefa_estado.php?id=$idReparacao&estado=$estado\">";
                                echo "<button type=\"button\" class=\"btn btn-success btn-sm\">Entregue</button>";
                                echo "</a>";
                                break;
                            case 6:
                                echo "<a href=\"tarefa_estado.php?id=$idReparacao&estado=$estado\">";
                                echo "<button type=\"button\" class=\"btn btn-danger btn-sm\">Cancelado</button>";
                                echo "</a>";
                                break;
                        }
                    ?>
                </td>
                <td scope="row">
                    <a href="reparacao_ver.php?id=<?=$reparacao["id_reparacao"]?>"><button type="button" class="btn btn-dark btn-sm mr-1"><i class="fa fa-print text-white"></i></a>
                    <a href="reparacao.php?id=<?=$reparacao["id_reparacao"]?>"><button type="button" class="btn btn-dark btn-sm mr-1"><i class="fa fa-pencil text-white"></i></a>
                    <a href="reparacao_remover.php?id=<?=$reparacao["id_reparacao"]?>" onclick="javascript:return confirm('Deseja remover o registo');"><button type="button" class="btn btn-dark btn-sm mr-1"><i class="fa fa-trash text-white"></i></a>
                </td> 
            </tr>
            <?php
                //fecho do if e do while
                    }
                }

            ?>
        </tbody>
    </table>

    <nav aria-label="paginacao">
        <ul class="pagination">
            <li class="page-item <?php if($pagina <=1) { echo "disabled"; } ?>">
                <a class="page-link" href="<?php if($pagina <= 1) { echo "#"; } else { echo "?pagina=".($pagina-1); } ?>">Anterior</a>
            </li>
            <?php
                //ciclo para efetuar a paginação 1, 2, 3...
                for($i = 1; $i <= $totalPaginas; $i++) {
            ?>
            <li class="page-item <?php if($pagina == $i) { echo "active"; } ?>">
                <a class="page-link" href="?pagina=<?=$i?>"><?=$i?></a>
            </li>
            <?php
                }
            ?>
            <li class="page-item <?php if($pagina == $totalPaginas) { echo "disabled"; } ?>">
                <a class="page-link" href="<?php if($pagina != $totalPaginas) { echo "?pagina=".($pagina+1); } ?>">Próxima</a>
            </li>
        </ul>
    </nav>

</div>

<?php

    //incluimos o ficheiro footer.inc.php
    include_once("footer.inc.php");

?>