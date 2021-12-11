<?php
session_start();
require_once 'conexao.php';
$con = conectar();
date_default_timezone_set('America/Recife');

if (isset($_POST['token']) && $_POST['token'] == '9638527413571598426') {

    $cpf = $_POST['cpf'];
    $nis = $_POST['nis'];
    $ip_acesso = $_SERVER['REMOTE_ADDR'];



    try {

        $query = "INSERT INTO `candidatos_isencoes` (           
            
            `cpf`,
            `nis`,
            `ip`
                      )
          VALUES
            (             
              '$cpf',
              '$nis',
              '$ip_acesso'
             
            )";


        $smtp = $con->prepare($query);

        if ($smtp->execute()) {
            $_SESSION['valida'] = true;

            // Envio de Laudo
            if (isset($_FILES['declaracao'])) {
                $laudo = $_FILES['declaracao'];


                $ext = strtolower(substr($laudo['name'], -4)); //Pegando extensão do arquivo
                $new_name = $cpf . $ext; //Definindo um novo nome para o arquivo
                $dir = 'arquivos/declaracoes/'; //Diretório para uploads

                if (move_uploaded_file($laudo['tmp_name'], $dir . $new_name)) //Fazer upload do arquivo
                {

                    $query = "UPDATE candidatos_isencoes SET declaracao = :laudo WHERE cpf = :cpf";
                    $smtp = $con->prepare($query);
                    $dirLaudo = $dir . $new_name;
                    $smtp->bindParam(':laudo', $dirLaudo);
                    $smtp->bindParam(':cpf', $cpf);
                    $smtp->execute();
                }
            }


            // Envio de email
            require_once('phpmailer/PHPMailerAutoload.php');
            include_once 'email.php';

            // Pega a descrição do cargo
            $query = "SELECT * FROM candidatos  WHERE cpf = '$cpf'";
            $smtp = $con->prepare($query);
            $smtp->execute();
            $linha = $smtp->fetch(PDO::FETCH_OBJ);
            $email = $linha->emaiil;



            if (emailIsencao("$email", "$nome", "$cpf")) {
            }

            $msg = '<div class="alert alert-success"><b>PARABÉNS!!! Enviamos a confiirmação para o seu E-mail de Isenção.</b></div>';
            header('Location: confirmacaoIsencao.php');
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="https://static.pingendo.com/bootstrap/bootstrap-4.3.1.css">
</head>

<body>
    <?= $msg ?? '' ?>
    <div class="py-5 bg-warning">
        <div class="container">
            <div class="row">
            </div>
            <div class="row">
                <div class="col-md-4 p-3"> <img class="img-fluid d-block" src="images/logo_palmares.png"> </div>
                <div class="col-md-4 p-3"> <img class="img-fluid d-block" src="images/logo_saude.png"> </div>
                <div class="col-md-4 p-3"> <img class="img-fluid d-block mx-auto mt-5" src="images/logo_aemasul.png" height="auto"> </div>
            </div>
        </div>
    </div>

    <div class="py-5 text-center">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-12 col-12">
                    <h1>Solicitação de Isenção da taxa de Inscrição do Processo Seletivo</h1>
                    <p class="mb-3">Preencha os dados corretamente</p>
                    <form class="text-left" action="isencao.php" method="post" enctype="multipart/form-data">


                        <input type="hidden" name="token" value="9638527413571598426">
                        <div class="form-group col-md-4">
                            <div class="form-check"><label for="form19">CPF</label> <input type="text" required name="cpf" class="form-control cpf" id="form19"></div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div wm-laudo class="custom-file  pb-4">
                                <input type="file" name="declaracao" required class="custom-file-input " id="customFile">
                                <label class="custom-file-label" for="customFile">Declaração de isenção</label>
                                <hr>
                                <label for="form20">Número do NIS</label>
                                <input type="text" name="nis" required class="form-control" id="form20">
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="form-check">
                                <input name="termos" class="form-check-input" type="checkbox" id="form21" value="on" required>
                                <label class="form-check-label" for="form21"> Eu aceito os
                                    <a target="_blank" href="docs/EDITAL 0001.2021  - PROCESSO SIMPLIFICADO ACS - PALMARES.pdf">Termos e Condições</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Inscrever-se</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.3.1.slim.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="js/jquery.mask.min.js"></script>
    <script>
        $(function() {
            // Máscaras
            $('.cpf').mask('999.999.999-99')
            $('.cep').mask('99999-999')
            $('.tel').mask('(99) 9-9999-9999')
            $('.data').mask('99/99/9999')


            $('[wm-deficiencia]')
                .change(function() {
                    let selecionado = false

                    laudo = $('[wm-laudo]')

                    d1 = $('[wm-deficiencia]').is(':checked')

                    if (d1) {
                        laudo.removeClass('d-none')
                        laudo.children().attr('required', true)
                    } else {
                        laudo.children().attr('required', false)
                        laudo.addClass('d-none')
                    }
                })

            $('[wm-isencao_input]').change(function() {
                let selecionado = false

                isencao = $('[wm-isencao]')
                i1 = $('[wm-isencao_input]').is(':checked')

                if (i1) {
                    isencao.removeClass('d-none')
                    isencao.children().attr('required', true)
                } else {
                    isencao.children().attr('required', false)
                    isencao.addClass('d-none')
                }
            })
        })
    </script>
</body>

</html>