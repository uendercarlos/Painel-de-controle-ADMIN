<?php
    session_start();
    
    if(!isset($_SESSION['session_farma'])) {
        header('Location: pages/login.php');
        exit;
    }

    if(isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];

        $ch = curl_init("https://menorprecomedicamentoipora.herokuapp.com/adminAut/produto/{$id}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");                                                                
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json", 
            "Authorization:Bearer " . $_SESSION['session_farma']
        ));                                                                                                              
        curl_setopt($ch, CURLOPT_HEADER, 1);                                                                                                              
                                                                                                                        
        $response = curl_exec($ch);
        curl_close($ch);
        
        $response = explode(' ', $response);

        if($response[1] == '200' || $response[1] == '201') {
            header('Location: pages/index.php?i=s2');
            exit;
        }
    }

    header('Location: pages/index.php?i=e2');
    exit;
?>