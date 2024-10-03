<?php

    if($_SERVER["REQUEST_METHOD"] == "GET" && (isset($_GET["codigoReparacao"]))) {

        include_once("admin/db.php");

        $codigoReparacao = $_GET["codigoReparacao"];

        //vamos efetuar uma consulta / query na tabela clientes da bd
        $query = "select * from reparacoes where codigo_reparacao='$codigoReparacao'";

        //executamos a consulta
        $resultado = mysqli_query($conexao, $query);

        //obtemos uma variável com o número de registos
        $registos = mysqli_num_rows($resultado);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>yRepair - Consulta de Estado da Reparação</title>

    <!-- jQuery -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <!--Font awesome  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    
    <!-- Custom CSS do Projeto -->
    <link href="style.css" rel="stylesheet">
</head>
<body>

<section class="search-banner text-white py-5 my-5 form-arka-plan" id="search-banner">
    
    <div class="container py-5 my-5">
        <div class="row text-center pb-4">
            <div class="col-md-12">
                <h1 class="text-white">yRepair</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card caixa-pesquisa">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                        </div>
                        <p class="font-weight-light text-dark">Introduza o código da sua reparação:</p>
                        <form method="GET" action="">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group ">
                                        <input type="text" class="form-control" id="codigoReparacao" name="codigoReparacao" placeholder="Exemplo: SK23X" value="<?php if(isset($codigoReparacao)) {echo $codigoReparacao;} ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-warning  pl-5 pr-5">Consultar</button>
                                </div>
                            </div>
                        </form>
                     
                            <?php
                                if(isset($registos)) {

                                    if($registos > 0) {
                                        
                                        $reparacao = mysqli_fetch_row($resultado);

                                        echo "<p class='text-dark'><b>Estado da reparação:</b></p>";

                                        switch($reparacao[6]) {
                                            case 1:
                                                $estado = array("primary", "Em aberto");
                                                break;
                                            case 2:
                                                $estado = array("dark", "Em aprovação");
                                                break;
                                            case 3:
                                                $estado = array("warning", "Em reparação");
                                                break;
                                            case 4:
                                                $estado = array("info", "Pronto para entrega");
                                                break;
                                            case 5:
                                                $estado = array("success", "Entregue");
                                                break;
                                            case 6:
                                                $estado = array("danger", "Cancelada");
                                                break;
                                        }

                                    ?>
                                        <button type="button" class="btn btn-<?=$estado[0]?>"><?=$estado[1]?></button>
                                        <span class="badge badge-light"><?=$reparacao[7]?></span>
                                    <?php

                                    } else {
                                        echo "<p class='text-dark'><b>Código de reparação inválido!</b></p>";
                                    }
                                }

                        ?>
                                
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>

</body>
</html>