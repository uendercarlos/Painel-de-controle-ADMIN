<?php
    session_start();
   
    if(!isset($_SESSION['session_farma'])) {
        header('Location: pages/login.php');
        exit;
    }

    if(!isset($_GET['id']) || empty($_GET['id'])) {
        header('Location: pages/index.php');
        exit;
    }

    $medicamento_selecionado = null;
    
    $id = $_GET['id'];
    
    require_once 'buscar_medicamentos.php';

    foreach($medicamentos_desativados as $medicamento) {
        if($medicamento->id == $id) {
            $medicamento_selecionado = $medicamento;
        }
    }
   
    // Montando array com dados
    $dados = array(
        "id" => $medicamento_selecionado->id,
        "nome" => $medicamento_selecionado->nome,
        "principioAtivo" => $medicamento_selecionado->principioAtivo,
        "concentracao" => $medicamento_selecionado->concentracao,
        "formaFarmaceutica" => $medicamento_selecionado->formaFarmaceutica,
        "registroAnvisa" => $medicamento_selecionado->registroAnvisa,
        "farmacia" => $medicamento_selecionado->farmacia,
        "detentorRegistro" => $medicamento_selecionado->detentorRegistro,
        "categoria" => $medicamento_selecionado->categoria,
        "preco" => $medicamento_selecionado->preco,
        "quantidade" => $medicamento_selecionado->quantidade,
        "imagens" => $medicamento_selecionado->imagens
    );

    // Convertendo o array em json
    $dadosJSON = json_encode($dados); 
     
    // Montando a requisição                                                                                                                
    $ch = curl_init('https://menorprecomedicamentoipora.herokuapp.com/adminAut/ativarproduto');
    
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

    if($header[1] == "200" || $header[1] == "201") {
        header('Location: pages/index.php?i=s4');
        exit;
    }
  
    header('Location: pages/index.php?i=e4');
    exit;
?>