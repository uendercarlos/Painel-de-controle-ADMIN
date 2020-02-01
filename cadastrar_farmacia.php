<?php

if($_POST['password'] != $_POST['confirm-password']) {
    echo "<script>alert('Senhas não coincidem!');</script>";
    echo "<script>window.location = '/Admin/login.php'</script>";
} else {

    $data = array(
        "cnpj" => $_POST['cnpj'], 
        "email" => $_POST['email'],
        "endereco" => array(
            "rua" => $_POST['rua'],
            "numero" => $_POST['numero'],
            "setor" => $_POST['bairro'],
            "latitude" => $_POST['latitude'],
            "longitude" => $_POST['longitude']
        ), 
        "login" => $_POST['username'],
        "nome" => $_POST['nome'], 
        "senha" => $_POST['password'], 
        "telefone" => $_POST['telefone']
    );

    $data_string = json_encode($data);  
                                                                                                                    
    $ch = curl_init('https://menorprecomedicamentoipora.herokuapp.com/administrador/cadastrarfarmacia/');                                                                      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);                                                                   
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
        );                                                                                                                   
                                                                                                                    
    $response = curl_exec($ch);
    
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $headerSize);
    curl_close($ch);

    $header = explode(' ', $header);

    if($header[1] == "200" || $header[1] == "201") {

        header('Location: pages/login.php?i=2');
        exit;
    }
    header('Location: pages/login.php');
    exit;
}
?>