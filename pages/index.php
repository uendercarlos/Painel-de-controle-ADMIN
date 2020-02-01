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

    // Buscar todos os medicamentos para listagem
    require_once '../buscar_medicamentos.php';    
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
            <div class="col-md-4 col-lg-4 col-sm-1 hidden-xs display-table-cell v-align box" 
            id="navigation">
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
                                <i><img src="../assets/img/list.png" alt="" srcset=""></i>
                                <span class="hidden-xs hidden-sm">Medicamentos</span>
                            </a>
                        </li>
                        <li>
                            <a href="cadastro.php">
                                <i><img src="../assets/img/add.png" alt="" srcset=""></i>
                                <span class="hidden-xs hidden-sm">Adicionar</span>
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
            <div class="col-md-8 col-lg-8 col-sm-11 display-table-cell v-align withe">
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
                            <div style="width: 100%;display:flex; align-items:center; justify-content:space-between">
                                <h3>Todos os medicamentos</h3>
                            </div>
                        </div>
                        <div class="col-md-5 col-lg-5">
                            <div style="width: 100%;display:flex; align-items:center; justify-content:flex-end">
                               
                                <!-- Campo de busca -->
                                <div class="search">
                                    <form method="get">
                                        <button type="submit"><img src="../assets/img/search.png" alt=""></button>
                                        <input type="text" placeholder="Consultar Medicamento" name="busca" id="search">
                                    </form>
                                </div>
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
                        </div>
                    </header>
                </div>

                <div class="row" style="padding: 20px 0">
                    <div class="container">
                        <p class="alert alert-success" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 's1' ? '' : 'style="display:none"'); ?>>
                            Medicamento cadastrado com sucesso!
                            <button data="s1">X</button>
                        </p>
                        <p class="alert alert-info" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 's2' ? '' : 'style="display:none"'); ?>>
                            Medicamento desabilitado com sucesso!
                            <button data="s2">X</button>
                        </p>
                        <p class="alert alert-info" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 's3' ? '' : 'style="display:none"'); ?>>
                            Medicamento alterado com sucesso!
                            <button data="s3">X</button>
                        </p>
                        <p class="alert alert-info" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 's4' ? '' : 'style="display:none"'); ?>>
                            O medicamento foi habilitado com sucesso!
                            <button data="s4">X</button>
                        </p>
                        <p  class="alert alert-danger" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 'e1' ? '' : 'style="display:none"'); ?>>
                            Não foi possível cadastrar o medicamento!
                            <button data="e1">X</button>
                        </p>
                        <p  class="alert alert-danger 2" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 'e2' ? '' : 'style="display:none"'); ?>>
                            Não foi possível desabilitar o medicamento!
                            <button data="e2">X</button>
                        </p>
                        <p  class="alert alert-danger 2" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 'e3' ? '' : 'style="display:none"'); ?>>
                            Não foi possível alterar os dados do medicamento!
                            <button data="e3">X</button>
                        </p>
                        <p  class="alert alert-danger 2" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 'e4' ? '' : 'style="display:none"'); ?>>
                            Não foi possível habilitar o medicamento!
                            <button data="e4">X</button>
                        </p>
                        
                        <div class="row col-lg-12 col-md-12" style="margin: 0">
                            <ul class="nav nav-tabs">
                                <li id="frameAtivos" role="presentation" class="active"><a href="">Habilitados</a></li>
                                <li id="frameDesativos" role="presentation" class=""><a href="">Desabilitados</a></li>
                            </ul>
                            <br>

                            <!-- Tabela de itens ativos -->
                            <div class="table-responsive">

                            <table id="tableAtivos" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="active">
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Forma Farmacêutica</th>
                                        <th>Concentração</th>
                                        <th>Laboratório</th>
                                        <th>MS</th>
                                        <th>Princípio Ativo</th>
                                        <th>Preço</th>
                                        <th>Quantidade</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($medicamentos_ativos as $medicamento): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                                if(isset($medicamento->imagens[0]->id)) {
                                                    $id = $medicamento->imagens[0]->id;
                                                    ?>
                                                    <img src="https://menorprecomedicamentoipora.herokuapp.com/imagem/<?php echo $id;?>" width="50px">
                                                    <?php
                                                } else { 
                                                    ?>
                                                    <img src="../assets/img/default.jpg" width="50px">
                                                    <?php
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $medicamento->nome; ?></td>
                                        <td><?php echo $medicamento->formaFarmaceutica; ?></td>
                                        <td><?php echo $medicamento->concentracao; ?></td>
                                        <td><?php echo $medicamento->detentorRegistro; ?></td>
                                        <td><?php echo $medicamento->registroAnvisa; ?></td>
                                        <td><?php echo $medicamento->principioAtivo; ?></td>
                                        <td><?php echo number_format($medicamento->preco, 2, ",", "."); ?></td>
                                        <td><?php echo $medicamento->quantidade; ?></td>
                                        <td class="text-center">
                                            <a 
                                                class='btn btn-primary btn-xs' 
                                                href="cadastro.php?id=<?php echo $medicamento->id;?>"
                                                style="width: 70px; margin-bottom: 3px">
                                                    <span class="glyphicon glyphicon-edit"></span>Alterar</a> 
                                            
                                            <a 
                                                href="#"
                                                id="<?php echo $medicamento->id; ?>" 
                                                data-target="#exampleModal"
                                                value="<?php echo $medicamento->nome; ?>" 
                                                class="deletar btn btn-danger btn-xs"
                                                data-toggle="modal"
                                                style="width: 80px">
                                                    <span class="glyphicon glyphicon-remove"></span>Desabilitar</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            </div>

                            <!-- Tabela de itens desativados -->
                            <table id="tableDesativos" class="table table-bordered table-responsible custab" style="display:none">
                                <thead>
                                    <tr class="active">
                                    <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Forma Farmacêutica</th>
                                        <th>Concentração</th>
                                        <th>Detentor do Registro</th>
                                        <th>Registro Anvisa</th>
                                        <th>Princípio Ativo</th>
                                        <th>Preço</th>
                                        <th>Quantidade</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($medicamentos_desativados as $medicamento): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                                if(isset($medicamento->imagens[0]->id)) {
                                                    $id = $medicamento->imagens[0]->id;
                                                    ?>
                                                    <img src="https://menorprecomedicamentoipora.herokuapp.com/imagem/<?php echo $id;?>" width="50px">
                                                    <?php
                                                } else { 
                                                    ?>
                                                    <img src="../assets/img/default.jpg" width="50px">
                                                    <?php
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $medicamento->nome; ?></td>
                                        <td><?php echo $medicamento->formaFarmaceutica; ?></td>
                                        <td><?php echo $medicamento->concentracao; ?></td>
                                        <td><?php echo $medicamento->detentorRegistro; ?></td>
                                        <td><?php echo $medicamento->registroAnvisa; ?></td>
                                        <td><?php echo $medicamento->principioAtivo; ?></td>
                                        <td><?php echo number_format($medicamento->preco, 2, ",", "."); ?></td>
                                        <td><?php echo $medicamento->quantidade; ?></td>
                                        <td class="text-center">
                                            <a class='btn btn-green btn-md' href="../ativar_medicamento.php?id=<?php echo $medicamento->id;?>">
                                                <span class="glyphicon glyphicon-edit"></span> 
                                                Habilitar
                                            </a> 
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal exclusao de elementos -->
    <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Esta ação requer confirmação</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Deseja realmente desabilitar o medicamento? <span id="nome-medicamento"></span></p>
            </div>
            <div class="modal-footer">
                <a id="confirmar-exclusao" href="#" class="btn btn-primary">Confirmar</a>
                <a class="btn btn-default" data-dismiss="modal">Cancelar</a>
            </div>
            </div>
        </div>
    </div>

    <script>

        // Controle dos aletar de ações
        const closeAlerts = document.querySelectorAll('.container .alert button');

        for (let i = 0; i < closeAlerts.length; i++) {
            closeAlerts[i].addEventListener('click', function(){
                
                let elemento = this.getAttribute('data');

                window.location = document.URL.replace(`?i=${elemento}`, '');
            });
        }

        // Parte de exclusão de medicamentos
        const buttons = document.querySelectorAll('td .deletar');
        const nomeMedicamento = document.getElementById('nome-medicamento');
        const confirmar = document.getElementById('confirmar-exclusao');

        for(let i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', function(e){
                
                let nome = buttons[i].getAttribute('value');
                let id =  buttons[i].getAttribute('id');

                nomeMedicamento.innerHTML = '"'+nome+'"';

                confirmar.setAttribute('href', `../excluir_medicamento.php?id=${id}`);
            });
        }

        // Controle da tabela de medicamentos (itens ativos e desativados)
        
        //recuperando itens do menu 
        const optionAtivo = document.getElementById('frameAtivos');
        const optionDesativos = document.getElementById('frameDesativos');

        //recuperando as tabelas
        const tableAtiva = document.getElementById('tableAtivos');
        const tableDesativo = document.getElementById('tableDesativos');

        optionAtivo.addEventListener('click', function(e){
            e.preventDefault();


            // Alterando visibilidade das tabelas
            tableAtiva.removeAttribute('style');
            tableDesativo.setAttribute('style', 'display:none');

            // Alterando estado dos botões do menu
            optionAtivo.setAttribute('class', 'active');
            optionDesativos.removeAttribute('class');
        });

        optionDesativos.addEventListener('click', function(e){
            e.preventDefault();

            tableDesativo.removeAttribute('style');
            tableAtiva.setAttribute('style', 'display:none');

            // Alterando estado dos botões do menu
            optionDesativos.setAttribute('class', 'active');
            optionAtivo.removeAttribute('class');
        });

    </script>
</body>
</html>