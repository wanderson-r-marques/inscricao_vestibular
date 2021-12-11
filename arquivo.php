<?php
session_start();
require_once 'conexao.php';
$con = conectar();
date_default_timezone_set('America/Recife');

if (isset($_POST['token']) && $_POST['token'] == '9638527413571598426') {
    
    $cpf = $_POST['cpf'];
   
     $ip_acesso = $_SERVER['REMOTE_ADDR'];
     $data_inclusao = date('Y-m-d H:i:s');

    try {
        $query = "INSERT INTO `arquivos` (
            `cpf`,
            `data_inclusao`,
            `ip_acesso`
                      )
          VALUES
            (             
              '$cpf',
              '$data_inclusao',
              '$ip_acesso'
             
            )";

        $smtp = $con->prepare($query);

        if ($smtp->execute()) {
            $_SESSION['valida'] = true;

            // Envio de Laudo
            if ($_SESSION['valida']) { 

                $arqEnviado = false;

                if(isset($_FILES['rg']) && $_FILES['rg']['name'] != ''){
                    $rg = $_FILES['rg'];
                    $ext = strtolower(substr($rg['name'], -4)); //Pegando extensão do arquivo
                    $new_name = $cpf . $ext; //Definindo um novo nome para o arquivo
                    $dir = 'arquivos/rg/'; //Diretório para uploads
                    move_uploaded_file($rg['tmp_name'], $dir . $new_name);
                    $dir_rg = $dir . $new_name;
                    $arqEnviado = true;
                }

                
                if(isset($_FILES['acpf']) && $_FILES['acpf']['name'] != ''){
                    $acpf = $_FILES['acpf'];
                    $ext = strtolower(substr($acpf['name'], -4)); //Pegando extensão do arquivo
                    $new_name = $cpf . $ext; //Definindo um novo nome para o arquivo
                    $dir = 'arquivos/cpf/'; //Diretório para uploads
                    move_uploaded_file($acpf['tmp_name'], $dir . $new_name);
                    $dir_cpf = $dir . $new_name;
                    $arqEnviado = true;
                }

                if(isset($_FILES['residencia']) && $_FILES['residencia']['name'] != ''){
                    $residencia = $_FILES['residencia'];
                    $ext = strtolower(substr($residencia['name'], -4)); //Pegando extensão do arquivo
                    $new_name = $cpf . $ext; //Definindo um novo nome para o arquivo
                    $dir = 'arquivos/residencia/'; //Diretório para uploads
                    move_uploaded_file($residencia['tmp_name'], $dir . $new_name);
                    $dir_residencia = $dir . $new_name;
                    $arqEnviado = true;
                }

                if(isset($_FILES['eleitoral']) && $_FILES['eleitoral']['name'] != ''){
                    $eleitoral = $_FILES['eleitoral'];
                    $ext = strtolower(substr($eleitoral['name'], -4)); //Pegando extensão do arquivo
                    $new_name = $cpf . $ext; //Definindo um novo nome para o arquivo
                    $dir = 'arquivos/quitacao/'; //Diretório para uploads
                    move_uploaded_file($eleitoral['tmp_name'], $dir . $new_name);
                    $dir_eleitoral = $dir . $new_name;
                    $arqEnviado = true;
                }

                if ($arqEnviado){ //Fazer upload do arquivo                

                     $query = "UPDATE arquivos SET rg_arquivo = '$dir_rg', cpf_arquivo = '$dir_cpf', residencia_arquivo = '$dir_residencia', quitacao_arquivo = '$dir_eleitoral'  WHERE cpf = :cpf";
                    $smtp = $con->prepare($query); 
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
            $email = $linha->email;

            

            if (emailArquivos("$email")) {
                $msg = '<div class="alert alert-success"><b>PARABÉNS!!! Enviamos a confirmação para o seu E-mail de Arquivos.</b></div>';
            }

            
            header('Location: confirmacao.php');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
        type="text/css">
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
                <div class="col-md-4 p-3"> <img class="img-fluid d-block mx-auto mt-5" src="images/logo_aemasul.png"
                        height="auto"> </div>
            </div>
        </div>
    </div>

    <div class="py-5">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-12 col-12">
                    <h1>Envio de Documentos Exigidos pelo edital</h1>
                    <p class="mb-3">Preencha os dados corretamente</p>
                    <form class="text-left" action="arquivo.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="token" value="9638527413571598426">
                    
                        <div class="form-group col-md-4"> <label for="form19">CPF</label> 
                            <input type="text" required name="cpf" class="form-control cpf" id="form19"> 
                        </div>
                      <br>
                        <div class="form-group">
                            <div class="form-check">                                 
                                <label class="form-check-label" for="form22"> Envio RG </label>
                            </div>                        
                            <div wm-isencao class="custom-file pb-4">
                                <input type="file" class="custom-file-input" name="rg" id="customFile1">
                                <label class="custom-file-label" for="customFile">Envio da Imagem do RG</label>                                
                            </div>
                        </div>            
<hr>
                        <div class="form-group"> 
                            <div class="form-check">                            
                                <label class="form-check-label" for="form22"> Envio CPF </label>
                            </div>                        
                            <div wm-isencao class="custom-file pb-4">
                                <input type="file" class="custom-file-input " name="acpf" id="customFile2">
                                <label class="custom-file-label" for="customFile">Envio da Imagem do CPF</label>
                                
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="form-check">                                 
                                <label class="form-check-label" for="form23"> Envio Comprovante de Residência </label>
                            </div>
                        
                            <div wm-isencao class="custom-file  pb-4">
                                <input type="file" class="custom-file-input" name="residencia" id="customFile3">
                                <label class="custom-file-label" for="customFile">Envio da Imagem do Comprovante de Residência</label>
                                                          

                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="form-check"> 
                                <label class="form-check-label" for="form24"> Envio Comprovante de Quitação Eleitoral </label>
                            </div>
                        
                            <div wm-isencao class="custom-file pb-4">
                                <input type="file" class="custom-file-input " name="eleitoral" id="customFile4">
                                <label class="custom-file-label" for="customFile">Envio da Imagem do Comprovante de Quitação Eleitoral</label>
                                                           

                            </div>
                        </div>
                        <hr> 
                        <div class="form-group ">
                            <div class="form-check"> <input name="termos" class="form-check-input" type="checkbox"
                                    id="form21" value="on" required> <label class="form-check-label" for="form21"> Eu
                                    aceito os
                                    <a target="_blank"
                                        href="docs/EDITAL 0001.2021  - PROCESSO SIMPLIFICADO ACS - PALMARES.pdf">Termos
                                        e Condições</a> </label> </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Inscrever-se</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.3.1.slim.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
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