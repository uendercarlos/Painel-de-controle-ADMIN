<?php
    session_start();

    if(!isset($_SESSION['session_farma'])) {
        header('Location: login.php');
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

    /* Esta verificação serve para ver se o token da farmacia ainda é valido
    * caso não seja o usuário é deslogado por inatividade 
    */
    if(!$farmacia_logada) {
        unset($_SESSION['session_farma']);
        unset($_SESSION['autenticacao']);
        header('Location: login.php?t=1');
        exit;
    }
    
    // Renovando token
    $dadosAutenticacao = explode(' ', $_SESSION['autenticacao']);
    $auth = array(
        "login" => $dadosAutenticacao[0],
        "senha" => $dadosAutenticacao[1]
    );

    $json = json_encode($auth);

    require_once '../autenticar.php';

    if($header[1] == "200") {
        $token = explode(' ', $header[6]);
        $token = str_replace('Content-Length:', "", $token[0]);

        $_SESSION['session_farma'] = trim($token);
    }
    
    $medicamento_selecionado = null;
    if(isset($_GET['id'])) {

        $id = $_GET['id'];

        $ch = curl_init("https://menorprecomedicamentoipora.herokuapp.com/produto/{$id}");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',
            "Authorization:Bearer " . $_SESSION['session_farma']                                                                               
        ));                                                             
                                                                                                                        
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
        
        if($response->farmacia->id == $farmacia_logada->id){
            $medicamento_selecionado = $response;
        }
    }

    //Recuperando categorias
    $categorias = array();

    $ch = curl_init("https://menorprecomedicamentoipora.herokuapp.com/categoria");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                         
    ));                                                             
                                                                                                                    
    $response = curl_exec($ch);
    curl_close($ch);

    $categorias = json_decode($response);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Área administrativa</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="../assets/css/styleAdmin.css" rel="stylesheet">
    
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="../assets/js/styleAdmin.js"></script>
</head>

<body class="home">
    <div class="container-fluid display-table">
        <div class="row display-table-row">
            <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation" >
                <div class="logo">
                    <a hef="admin.html">
                       <img src="../assets/img/logoIporaFarmaa3peq.png" alt="merkery_logo" class="hidden-xs hidden-sm">
                        <img src="https://consultaremedios.com.br/assets/logos/logo_default-17ab6834258c29870f364a777d12cca917f79ff88aceb6b9c4f3b89ac8c0a53f.svg" alt="merkery_logo" class="visible-xs visible-sm circle-logo">
                    </a>
                </div>
                <div class="navi">
                    <ul>
                        <li class="active">
                            <a href="#">
                                <i><img src="../assets/img/add.png" alt="" srcset=""></i>
                                <span class="hidden-xs hidden-sm"><?php echo($medicamento_selecionado ? 'Alterar' : 'Adicionar');?></span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php">
                                <i><img src="../assets/img/list.png" alt="" srcset=""></i>
                                <span class="hidden-xs hidden-sm">Medicamentos</span>
                            </a>
                        </li>
                        <br><br>
                        <li>
                            <a href="index.php">
                                <i><img src="../assets/img/back.png" alt="" srcset=""></i>
                                <span class="hidden-xs hidden-sm">Voltar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-10 col-sm-11 display-table-cell v-align withe">
                <!--<button type="button" class="slide-toggle">Slide Toggle</button> -->
                <div class="row">
                    <header>
                        <div class="col-md-7">
                            <nav class="navbar-default pull-left">
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="offcanvas" data-target="#side-menu" aria-expanded="false">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>
                            </nav>
                            <!-- Titulo da pagina -->
                            <h3><?php echo ($medicamento_selecionado ? 'Alterar Medicamento' : 'Novo Medicamento');?></h3>
                        </div>
                        <div class="col-md-5">
                            <div class="header-rightside">
                                <ul class="list-inline header-top pull-right">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><img src="../assets/img/user-icon.png" alt="user">
                                            <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <div class="navbar-content">
                                                    <span><?php echo $farmacia_logada->nome;?></span><br>
                                                    <a href="farmacia.php" style="color:#333">Minha Conta</a>
                                                    <div class="divider">
                                                    </div>
                                                    <a href="../sair.php" class="btn btn-sm btn-block btn-primary active">Sair</a>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </header>
                </div>
                <div class="row col-md-12 col-md-offset-2 custyle withe" style="margin: 0; padding: 20px 0 80px 0">
                    
                    <form 
                    class="col-md-10 col-md-offset-1" 
                    action="<?php echo ($medicamento_selecionado ?  '../atualizar_medicamento.php' : '../cadastrar_medicamento.php');?>" 
                    method="post"
                    enctype="multipart/form-data">

                        <p style="font-size: 16px" class="alert alert-info">
                            Todos os itens com ' * ' posterior ao nome do campo são de preenchimento obrigatório!
                        </p>

                        <input type="text" name="id" value="<?php echo ($medicamento_selecionado ? $medicamento_selecionado->id : '');?>" hidden>
                        
                        <div class="form-group">
                            <label for="nome">Nome do medicamento*</label>
                            <input id="nome" value="<?php echo ($medicamento_selecionado ? $medicamento_selecionado->nome : '');?>" class="form-control" type="text" placeholder="Digite o nome" name="nome" required>
                        </div>

                        <div class="form-group">
                            <label for="ativo">Princípio Ativo*</label>
                            <input id="ativo" value="<?php echo ($medicamento_selecionado ? $medicamento_selecionado->principioAtivo : '');?>" class="form-control" type="text" placeholder="Digite o princípio ativo" name="principio_ativo">
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="anvisa">MS*</label>
                                <input id="anvisa" value="<?php echo ($medicamento_selecionado ? $medicamento_selecionado->registroAnvisa : '');?>" class="form-control" type="number" placeholder="Digite o MS" name="registro_anvisa">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="detentor">Laboratório*</label>
                                <input id="detentor" value="<?php echo ($medicamento_selecionado ? $medicamento_selecionado->detentorRegistro : '');?>" class="form-control" type="text" placeholder="Digite o nome do Laboratório" name="detentor_registro">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="concentracao">Concentração*</label>
                            <input id="concentracao" value="<?php echo ($medicamento_selecionado ? $medicamento_selecionado->concentracao : '');?>" class="form-control" type="text" placeholder="Digite a concentração" name="concentracao">
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label for="preco">Preço*</label>
                                <input id="preco" onKeyPress="return(moeda(this,'.',',',event))" value="<?php echo ($medicamento_selecionado ? $medicamento_selecionado->preco : '');?>" class="form-control" type="text" placeholder="R$ 0,00" name="preco">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label for="quantidade">Quantidade*</label>
                                <input id="quantidade" value="<?php echo ($medicamento_selecionado ? $medicamento_selecionado->quantidade : '');?>" class="form-control" type="number" placeholder="Ex: 1" name="quantidade">
                            </div>
                        </div>
            
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="categoria">Categorias*</label>
                                <select id="categoria" class="form-control" name="categoria[]" multiple required>
                                    <option disabled>Selecione uma ou mais categorias</option>
                                    <?php foreach($categorias as $categoria): ?>
                                
                                        <option 
                                            value="<?php echo $categoria->id;?>"
                                            <?php 
                                                if($medicamento_selecionado) {
                                                    foreach($medicamento_selecionado->categoria as $cat) {
                                                        if ($cat->id == $categoria->id){
                                                            echo "selected";
                                                        }
                                                    }
                                                }
                                            ?>><?php echo $categoria->nome;?>
                                        </option>
                                    
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                  
                        <div class="form-group">
                            <label for="forma">Forma Farmacêutica*</label>
                            <input id="forma" value="<?php echo ($medicamento_selecionado ? $medicamento_selecionado->formaFarmaceutica : '');?>" class="form-control" type="text" placeholder="Informações sobre a forma farmacêutica" name="forma_farmaceutica">
                        </div>
                        <div class="input-group mb-3">
                        <div class="input-group-prepend">
                                <span class="input-group-text"><b>Imagem</b></span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="inputGroupFile01" name="imagem">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="submit" value="Salvar" class="btn btn-green btn-block">
                            </div>
                            <div class="col-md-6">
                                <input type="reset" value="Limpar" class="btn btn-withe btn-block">
                            </div>
                        </div>
                      
                    </form>
                </div>
            </div>
        </div>
    </div>        

    <script src="../assets/js/mascaraMoeda.js"></script> 
</body>
</html>