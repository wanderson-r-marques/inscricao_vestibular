<?php
session_start();
if ($_SESSION['valida']) {
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
    <div class="py-5 text-center"
        style="background-image: url('https://static.pingendo.com/cover-bubble-dark.svg');background-size:cover;">
        <div class="container">
            <div class="row">
                <div class="bg-white p-5 mx-auto col-md-8 col-10">
                    <h3 class="display-3">PARABÉNS</h3>
                    <p class="mb-3 lead">INSCRIÇÃO REALIZADA COM SUCESSO</p>
                    <p class="mb-4">Enviamos a confirmação para o seu E-mail de cadastro. Desejamos boa sorte!</p> <a
                        class="btn btn-outline-primary" href="index.html">Voltar</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
<?php

} else {
    echo 'HOUVE UM ERRO NO SERVIDOR';
}

$_SESSION['valida'] = false;

?>