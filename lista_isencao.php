<?php 

if (isset($_GET['excel'])) {


  if ($_GET['excel'] == 1) {


    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=candidatos.xls"); 
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo "Some Text";
  }
}
?>


<?php

session_start();
require_once 'conexao.php';
$con = conectar();
date_default_timezone_set('America/Recife');

$query = "SELECT 
a.`cadidato`,
a.`nome`,
e.nis,
a.`cpf`,
a.`celular`,
CONCAT('https://wa.me/55',REPLACE(REPLACE(REPLACE(REPLACE( a.celular, '(', '' ),')',''),'-',''),' ','')    ) link_celular,
a.`email`,
d.`descricao` AS deficiencia,
CONCAT('https://palmares.saude.isolucoes.inf.br/',e.`declaracao`) AS comprovante_isencao,
c.`descricao` AS cargo,
b.`descricao` AS unidade_escolha



FROM candidatos               	   a
JOIN cadastros_unidades       	   b ON a.`cadastro_local`       = b.`cadastro_unidade`
JOIN cargos                   	   c ON a.`cadastro_cargo`       = c.`cargo`
LEFT JOIN cadastros_deficiencias   d ON a.`cadastro_deficiencia` = d.`cadastro_deficiencia`
JOIN candidatos_isencoes           e ON a.`cpf`                  = e.cpf

ORDER BY a.nome";


$exec = $con->prepare($query);
$exec->execute();
$qtd = $exec->rowCount();

?>



<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
  <script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css"
  rel="stylesheet" type="text/css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
  <div class="section section-warning">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="page-header">
            <h1>Lista de solicitação de Isenção de Taxa&nbsp;
              <small>Candidatos do conscurso de agente de Saúde de Palmares</small>
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h2><?php echo $qtd; ?> Lista de Isenção</h2>
          <a href="?excel=1"><i class="fa fa-3x fa-file-excel-o fa-fw pull-right text-success"></i></a>
          <br><br>
          
          <table class="table table-bordered table-condensed table-hover table-striped">

            <thead  class="thead-dark">
              <tr>

                <th>Inscrição</th>              
                <th>Nome</th>              
                <th>CPF</th>
                <th>NIS</th>
                <th>Celular</th>
                <th>Clique para WhatsApp</th>
                <th>Deficiencia</th>
                <th>Cargo</th>
                <th>Unidade Escolhida</th>
                <th>E-mail</th>
                <th>Declaração da Isenção</th>

              </tr>
            </thead>
            <tbody>

              <?php 

              $linhas = $exec->fetchAll(PDO::FETCH_OBJ);            
              foreach ($linhas as $linha) {  
                ?>


                <tr>

                  <td><?= $linha->cadidato;  ?></td>
                  <td><?= $linha->nome;  ?></td>
                  <td><?= $linha->cpf;  ?></td>
                  <td><?= $linha->nis;  ?></td>
                  <td><?= $linha->celular;  ?></td>
                  <td name="$linha->link_celular"><a target="_blank" href="<?= $linha->link_celular ?>"> Fala no WhatsApp </a></td> 
                  <td><?= $linha->deficiencia;  ?></td>
                  <td><?= $linha->cargo;  ?></td>
                  <td><?= $linha->unidade_escolha;  ?></td>
                  <td><?= $linha->email;  ?></td>
                  
                  <?php if ($linha->comprovante_isencao != '') : ?>
                       <td name="$linha->comprovante_isencao"><a target="_blank" href="<?= $linha->comprovante_isencao ?>"> Declaração </a></td> 
                  <?php endif ?>

                  
                  


                </tr>


                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <footer class="section section-warning">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <h1>AEMASUL</h1>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="col-md-12 hidden-lg hidden-md hidden-sm text-left">
                <a href="#"><i class="fa fa-3x fa-fw fa-instagram text-inverse"></i></a>
                <a href="#"><i class="fa fa-3x fa-fw fa-twitter text-inverse"></i></a>
                <a href="#"><i class="fa fa-3x fa-fw fa-facebook text-inverse"></i></a>
                <a href="#"><i class="fa fa-3x fa-fw fa-github text-inverse"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </body>

  </html>