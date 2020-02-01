<?php
    session_start();
   
    if(!isset($_SESSION['session_farma'])) {
        header('Location: pages/login.php');
        exit;
    }

    // Recuperando dados do usuário logado
    $ch = curl_init('https://menorprecomedicamentoipora.herokuapp.com/administrador/administrador');
    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',
        "Authorization:Bearer " . $_SESSION['session_farma']                                                                                
    ));                                                             
                                                                                                                    
    $response = curl_exec($ch);
    curl_close($ch);

    // Convertendo json para objeto
    $farmacia_logada = json_decode($response);

    if(
        !isset($_POST['nome']) &&
        !isset($_POST['preco']) &&
        !isset($_POST['concentracao']) &&
        !isset($_POST['quantidade']) &&
        !isset($_POST['registro_anvisa']) &&
        !isset($_POST['principio_ativo']) &&
        !isset($_POST['forma_farmaceutica']) &&
        !isset($_POST['detentor_registro']) &&
        !isset($_POST['categoria'])
    ) {

        header('Location: pages/index.php?i=4');
        exit;
    }
    
    //Recuperando categorias selecionadas
    $categorias = array();

    foreach($_POST['categoria'] as $categoria) {
       $categorias[] = array("id" => $categoria);
    }

    //formatando preco para ser salvo
    $preco = $_POST['preco'];

    if( substr($preco, -3, 1) == "," ) {
        $preco = str_replace(".", "", $preco);
        $preco = str_replace(",", ".", $preco);
    }

    // Montando array com dados
    $dados = array(
        "id" => $_POST['id'],
        "nome" => $_POST['nome'],
        "principioAtivo" => $_POST['principio_ativo'],
        "concentracao" => $_POST['concentracao'],
        "formaFarmaceutica" => $_POST['forma_farmaceutica'],
        "registroAnvisa" => $_POST['registro_anvisa'],
        "detentorRegistro" => $_POST['detentor_registro'],
        "categoria" => $categorias,
        "preco" => $preco,
        "quantidade" => $_POST['quantidade']
    );

    // Convertendo o array em json
    $dadosJSON = json_encode($dados); 
     
    // Montando a requisição                                                                                                                
    $ch = curl_init('https://menorprecomedicamentoipora.herokuapp.com/adminAut/produto');                                                                      
    
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

         // Recuperando as informações da imagem, como o nome e caminho
         $filename = $_FILES['imagem']['name'];
         $filedata = $_FILES['imagem']['tmp_name'];

        // Cadastrando imagen no medicamento se houver
        if(!empty($filedata)) {

            // Recuperando o id
            $id = $_POST['id'];

            $file = ['file' => new \CURLFile($filedata, 'image/jpg', $filename)];

            $ch = curl_init("https://menorprecomedicamentoipora.herokuapp.com/imagem/admin/addimgprod/{$id}");
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $file);                                                                  
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data"));                                                                                                              
            curl_setopt($ch, CURLOPT_HEADER, 1); 
            
            $response = curl_exec($ch);
            curl_close($ch);   
        }
        header('Location: pages/index.php?i=s3');
        exit;
    }
    header('Location: pages/index.php?i=e3');
    exit;
?>