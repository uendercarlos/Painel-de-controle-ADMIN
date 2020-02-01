<?php
    session_start();

    if(!isset($_SESSION['session_farma'])) {
        header('Location: pages/login.php');
        exit;
    }
                                                                                                                    
    $ch = curl_init('https://menorprecomedicamentoipora.herokuapp.com/administrador/delete');                                                                      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");                                                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);                                                                   
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        "Authorization:Bearer " . $_SESSION['session_farma'])                                                                       
        );                                                                                                                   
                                                                                                                    
    $response = curl_exec($ch);
    curl_close($ch);

    $header = explode(' ', $response);

    if($header[1] == "200" || $header[1] == "201") {
        unset($_SESSION['session_farma']);
        setcookie('coockie_farma', null, 1);
        
        header('Location: pages/login.php?info=exclude');
        exit;
    }

    header('Location: pages/farmacia.php?info=falha');
    exit;
?>