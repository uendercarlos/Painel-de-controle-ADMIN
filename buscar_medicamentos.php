<?php
    if(!isset($_SESSION['session_farma'])) {
        header('Location: pages/login.php');
        exit;
    } 

    $base_url = "https://menorprecomedicamentoipora.herokuapp.com";
    $url_complemento = "/administrador/listarprodutos";

    // Busca por nome ou principio ativo
    if(isset($_GET['busca']) && !empty($_GET['busca'])) {
        
        $nome = $_GET['busca'];
        $nome = str_replace(' ', "%20", $nome);
        $url_complemento = "/adminAut/consultarproduto/?nome={$nome}";
        
        $ch = curl_init($base_url.$url_complemento);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',
            "Authorization:Bearer " . $_SESSION['session_farma']                                                                               
        )); 

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
       
        if(!$response) {
            $url_complemento = "/adminAut/consultarproduto/?principioAtivo={$nome}";
            
            $ch = curl_init($base_url.$url_complemento);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',
                "Authorization:Bearer " . $_SESSION['session_farma']                                                 
            )); 

            $resposta = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($resposta);

        }
    }
    else {
        $ch = curl_init($base_url.$url_complemento);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',
            "Authorization:Bearer " . $_SESSION['session_farma']                                                                               
        ));                                                             
                                                                                                                        
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
    }


    $medicamentos_ativos = array();
    $medicamentos_desativados = array();

    // Preenche array com os medicamentos da farmacia
    if($response) {
        foreach($response as $medicamento) {
    
            if(!$medicamento->removedAt) {
                array_push($medicamentos_ativos, $medicamento);
            }
            else {
                array_push($medicamentos_desativados, $medicamento);
            }
        }
    }

    // Ordenando array do ultimo para o primeiro elemento
    usort($medicamentos_ativos, function ($a, $b){

        return (($a->id > $b->id) ? -1 : 1);
    });
	
	
?>