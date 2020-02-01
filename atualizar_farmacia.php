<?php
    session_start();
   
    if(!isset($_SESSION['session_farma'])) {
        header('Location: pages/login.php');
        exit;
    }

    if(
        !isset($_POST['nome']) &&
        !isset($_POST['cnpj']) &&
        !isset($_POST['rua']) &&
        !isset($_POST['numero']) &&
        !isset($_POST['setor']) &&
        !isset($_POST['latitude']) &&
        !isset($_POST['longitude']) &&
        !isset($_POST['telefone'])
    ) {
        header('Location: pages/farmacia.php');
        exit;
    }
    
    // Montando array com dados
    $dados = array(
        "cnpj" => $_POST['cnpj'], 
        "endereco" => array(
            "rua" => $_POST['rua'],
            "numero" => $_POST['numero'],
            "setor" => $_POST['bairro'],
            "latitude" => $_POST['latitude'],
            "longitude" => $_POST['longitude']
        ), 
        "nome" => $_POST['nome'], 
        "telefone" => $_POST['telefone']
    );

    // Convertendo o array em json
    $dadosJSON = json_encode($dados); 
     
    // Montando a requisição                                                                                                                
    $ch = curl_init('https://menorprecomedicamentoipora.herokuapp.com/administrador/edita');                                                                      
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dadosJSON);                                                                  
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json", 
        "Authorization:Bearer " . $_SESSION['session_farma']
    ));                                                                                                              
    curl_setopt($ch, CURLOPT_HEADER, 1);                                                                                                              
    
    // Executa a requisição e armazena a resposta
    $response = curl_exec($ch);
    curl_close($ch);

    $header = explode(' ', $response);

    var_dump($header);

    if($header[1] == "200" || $header[1] == "201") {

        header('Location: pages/farmacia.php?i=s1');
        exit;
    }
    header('Location: pages/farmacia.php?i=e1');
    exit;
?>