<?php
require_once 'conexao.php';
$con = conectar();
date_default_timezone_set('America/Recife');

if (isset($_POST['token']) && $_POST['token'] == '9638527413571598426') {
    $nome = $_POST['nome'];
    $rg = $_POST['rg'];
    $org_rg = $_POST['orgao_expedidor'];
    $uf_rg = $_POST['orgao_uf'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['nascimento'];
    $sexo = $_POST['sexo'];
    $cep = $_POST['cep'];
    $endereco = $_POST['endereco'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $email = $_POST['email'];
    $celular = $_POST['whatsapp'];
    $cadastro_cargo = $_POST['cargo'];
    $cadastro_unidade = $_POST['unidade'];
    $cadastro_deficiencia = $_POST['deficiencia'];
    $ip_acesso = $_SERVER['REMOTE_ADDR'];



    try {
        $query = "INSERT INTO `candidatos` (           
            `nome`,
            `rg`,
            `org_rg`,
            `uf_rg`,
            `data_nascimento`,
            `sexo`,
            `cpf`,
            `cep`,
            `endereco`,
            `cidade`,
            `estado`,
            `bairro`,
            `celular`,
            `email`,
            `ip_acesso`,
            `cadastro_deficiencia`,
            `cadastro_cargo`,
            `cadastro_local`
          )
          VALUES
            (             
              '$nome',
              '$rg',
              '$org_rg',
              '$uf_rg',
              '$data_nascimento',
              '$sexo',
              '$cpf',
              '$cep',
              '$endereco',
              '$cidade',
              '$estado',
              '$bairro',
              '$celular',
              '$email',
              '$ip_acesso',
              '$cadastro_deficiencia',
              '$cadastro_cargo',
              '$cadastro_unidade'
            )";

        $smtp = $con->prepare($query);
        if ($smtp->execute()) {

            // Envio de Laudo
            if (isset($_FILES['laudo'])) {
                $laudo = $_FILES['laudo'];


                $ext = strtolower(substr($laudo['name'], -4)); //Pegando extensão do arquivo
                $new_name = $cpf . $ext; //Definindo um novo nome para o arquivo
                $dir = 'arquivos/laudos/'; //Diretório para uploads

                if (move_uploaded_file($laudo['tmp_name'], $dir . $new_name)) //Fazer upload do arquivo
                {

                    $query = "UPDATE candidatos SET laudo = :laudo WHERE cpf = :cpf";
                    $smtp = $con->prepare($query);
                    $dirLaudo = $dir . $new_name;
                    $smtp->bindParam(':laudo', $dirLaudo);
                    $smtp->bindParam(':cpf', $cpf);
                    if ($smtp->execute()) {
                        exit;
                        header('Location: confirmacao.php');
                    }
                }
            }


            // Envio de email
            require_once('phpmailer/PHPMailerAutoload.php');
            include 'email.php';
            email("$email", "$nome", "$cpf");
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

    <div class="py-5 text-center">
        <div class="container">
            <div class="row">
                <div class="mx-auto col-lg-12 col-12">
                    <h1>Inscrição do Processo Seletivo</h1>
                    <p class="mb-3">Preencha os dados corretamente</p>
                    <form class="text-left" action="cadastro.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="token" value="9638527413571598426">
                        <div class="form-group"> <label for="form16">Nome completo</label> <input type="text" required
                                class="form-control" name="nome" id="form16"> </div>

                        <div class="form-row">
                            <div class="form-group col-md-4"> <label for="form19">RG (Identidade)</label>
                                <input name="rg" required type="text" class="form-control" id="form19">
                            </div>
                            <div class="form-group col-md-4"> <label for="form20">Orgão Expedidor</label> <input
                                    required name="orgao_expedidor" type="text" class="form-control" id="form20"> </div>
                            <div class="form-group col-md-4"> <label for="form20">Orgão UF</label> <input type="text"
                                    required name="orgao_uf" class="form-control" id="form20"> </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4"> <label for="form19">CPF</label> <input type="text"
                                    required name="cpf" class="form-control cpf" id="form19"> </div>
                            <div class="form-group col-md-4"> <label for="form20">Data de nascimento</label> <input
                                    required name="nascimento" type="date" class="form-control" id="form20"> </div>
                            <div class="form-group col-md-4"> <label for="form20">Sexo</label>
                                <select required name="sexo" class="form-control">
                                    <option value="M">Masculino</option>
                                    <option value="F">Feminino</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="form16">CEP</label>
                            <input type="text" required name="cep" wm-cep class="form-control cep" id="form16">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4"> <label for="form19">Endereço</label> <input type="text"
                                    required name="endereco" class="form-control" id="form19"> </div>
                            <div class="form-group col-md-4"> <label for="form20">Número</label> <input type="text"
                                    required name="numero" class="form-control" id="form20"> </div>
                            <div class="form-group col-md-4"> <label for="form20">Complemento</label>
                                <input type="text" name="complemento" class="form-control" id="form20">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4"> <label for="form19">Bairro</label> <input type="text"
                                    required name="bairro" class="form-control" id="form19"> </div>
                            <div class="form-group col-md-4"> <label for="form20">Cidade</label> <input type="text"
                                    required name="cidade" class="form-control" id="form20"> </div>
                            <div class="form-group col-md-4"> <label for="form20">Estado</label>
                                <select name="estado" required class="form-control">
                                    <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
                                    <option value="EX">Estrangeiro</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-7"> <label for="form19">E-mail</label> <input type="email"
                                    required name="email" class="form-control" id="form19"> </div>
                            <div class="form-group col-md-5"> <label for="form20">Whatsapp</label> <input type="text"
                                    required name="whatsapp" class="form-control tel" id="form20"> </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4"> <label for="form19">Cargo</label> <select required
                                    name="cargo" class="form-control">

                                    <?php
                                    $query = "SELECT * FROM `cargos` ORDER BY descricao ASC";
                                    $smtp = $con->prepare($query);
                                    $smtp->execute();
                                    $linhas = $smtp->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($linhas as $linha) {
                                    ?>
                                    <option value="<?= $linha->cargo ?>"><?= $linha->descricao ?></option>
                                    <?php } ?>

                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="form20">Unidade de atendimento</label>
                                <select name="unidade" required class="form-control">
                                    <?php
                                    $query = "SELECT * FROM `cadastros_unidades` ORDER BY descricao ASC";
                                    $smtp = $con->prepare($query);
                                    $smtp->execute();
                                    $linhas = $smtp->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($linhas as $linha) {
                                    ?>
                                    <option value="<?= $linha->cadastro_unidade ?>"><?= $linha->descricao ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4"> <label for="form20">Pessoa com deficiência</label> <br>


                                <?php
                                $query = "SELECT * FROM `cadastros_deficiencias` ORDER BY descricao ASC";
                                $smtp = $con->prepare($query);
                                $smtp->execute();
                                $linhas = $smtp->fetchAll(PDO::FETCH_OBJ);
                                foreach ($linhas as $linha) {
                                ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" name="deficiencia" wm-deficiencia type="checkbox"
                                        id="inlineCheckbox1" value="<?= $linha->cadastro_deficiencia ?>">
                                    <label class="form-check-label"
                                        for="inlineCheckbox1"><?= $linha->descricao ?></label>
                                </div>
                                <?php } ?>



                                <div wm-laudo class="custom-file d-none">
                                    <input type="file" class="custom-file-input" name="laudo" id="customFile">
                                    <label class="custom-file-label" for="customFile">Envie o laudo médico</label>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="form-row">
                            <div class="form-group col-md-6"> <label for="form19">Senha</label> <input type="password"
                                    required name="senha" class="form-control" id="form19" placeholder="••••"> </div>
                            <div class="form-group col-md-6"> <label for="form20">Confirmar senha</label> <input
                                    required type="password" name="senha" class="form-control" id="form20"
                                    placeholder="••••">
                            </div>
                        </div> -->

                        <!-- <div class="form-group">
                            <div class="form-check"> <input name="isencao" class="form-check-input" type="checkbox"
                                    wm-isencao_input id="form21" value="1">
                                <label class="form-check-label" for="form21"> Solicito
                                    isenção da taxa
                                    de inscrição </label>
                            </div>
                            <div wm-isencao class="custom-file d-none pb-4">
                                <input type="file" class="custom-file-input " id="customFile">
                                <label class="custom-file-label" for="customFile">Declaração de isenção</label>
                                <hr>
                                <label for="form20">Número do NIS</label>
                                <input type="text" name="nis" class="form-control" id="form20">

                            </div>
                        </div> -->

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