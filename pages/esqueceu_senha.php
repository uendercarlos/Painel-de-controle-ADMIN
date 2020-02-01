<?php

    $emailEnviado = false;
    $falhaDeEnvio = false;

    if(isset($_POST['email']) && !empty($_POST['email'])) {
        
        $email = $_POST['email'];

        $dados = array(
            'email' => $email
        );

        $json = json_encode($dados);

        // Configurando requisição
        $ch = curl_init('https://menorprecomedicamentoipora.herokuapp.com/administrador/recuperarsenha');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);      
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

        // Enviando a requisição
        $response = curl_exec($ch);

        $info = curl_getinfo($ch);

        if($info['http_code'] == 201) {
            $emailEnviado = true;
        }
        else{
            $falhaDeEnvio = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Esqueceu a senha</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="../assets/css/styleRecuperaSenha.css">
</head>
<body>
    <h2>Recuperar Senha</h2>
    <p class="alert alert-info message" role="alert" <?php echo ($emailEnviado ? '' : 'style="display:none"');?>>
        O email foi enviado!
        <button data="success">voltar ao login</button>
    </p>
    <p class="alert alert-warning message" role="alert" <?php echo ($falhaDeEnvio ? '' : 'style="display:none"');?>>
        Não foi possível enviar o email!
        <button data="error">Tentar novamente</button>
    </p>
    <div class="container">
        <p>
            Entre com o endereço de email vinculado a sua conta de adminstrador,
            para receber o email de recuperação.
        </p>
        <form method="post">
            <div class="form-group">
                <label for="email">Digite seu email</label>
                <input class="form-control" id="email" type="email" placeholder="exemplo@email.com" name="email" required>
            </div>
            <input class="btn btn-green" type="submit" value="Enviar">
        </form>
        <a href="login.php">Voltar ao login</a>
    </div>

    <script>
        const alertas = document.querySelectorAll('.alert button')
            
            
        for (let i = 0; i < alertas.length; i++) {

            alertas[i].addEventListener('click', function(){

                if (this.getAttribute('data') == 'error') {
                    window.location = '';
                }
                else if(this.getAttribute('data') == 'success') {
                    window.location = 'login.php';
                }
            });
        }
    </script>
</body>
</html>