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
            <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
                <div class="logo">
                    <a hef="admin.html">
                        <img src="../assets/img/logoIporaFarmaa3peq.png" alt="merkery_logo" class="hidden-xs hidden-sm">
                        <img src="https://consultaremedios.com.br/assets/logos/logo_default-17ab6834258c29870f364a777d12cca917f79ff88aceb6b9c4f3b89ac8c0a53f.svg" alt="merkery_logo" class="visible-xs visible-sm circle-logo">
                    </a>
                </div>
                <div class="navi">
                    <ul>
                        <li>
                            <a href="index.php">
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
                            <h3>Minha Conta</h3>
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
                                                    <a href="#" style="color:#333">Minha Conta</a>
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

                <!-- Conteudo da página -->
                <div class="row" 
                style="
                    padding: 20px 100px;
                ">
                    <!-- Menssagens de resposta as alterações -->
                    <p class="alert info alert-info" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 's1' ? '' : 'style="display:none"'); ?>>
                        Os dados da farmácia foram alterados com sucesso!
                        <button data="s1">X</button>
                    </p>
                    <p class="alert info alert-danger" role="alert" <?php echo (isset($_GET['i']) && $_GET['i'] == 'e1' ? '' : 'style="display:none"'); ?>>
                        Não foi possível alterar os dados da farmácia!
                        <button data="e1">X</button>
                    </p>

                    <h4>Informações sobre a farmácia</h4>
                    <hr/>
                    <form action="../atualizar_farmacia.php" method="post" role="form" style="
                        width: 100%;
                    ">

                        <label>Dados da farmácia </label>
                        <div class="form-group">
                            <input type="text" name="nome" id="nome" tabindex="1" class="form-control" placeholder="Nome" value="<?php echo $farmacia_logada->nome ;?>" disabled>
                        </div>
                        <div class="form-group">
                            <input type="text" name="cnpj" id="cnpj" class="form-control" placeholder="CNPJ" onkeyup="mascara('##.###.###/####-##',this,event,true)" maxlength="18" value="<?php echo $farmacia_logada->cnpj ;?>" disabled>
                        </div>
                        <div class="form-group">
                            <input type="text" name="telefone" id="telefone" tabindex="1" class="form-control" placeholder="Telefone" value="<?php echo $farmacia_logada->telefone ;?>" required onkeyup="mascara('(##) ####-####',this,event,true)" maxlength="14" disabled>
                        </div>
                        <br>

                        <label>Endereço da farmácia</label>
                        <div class="form-group">
                            <input type="text" name="rua" id="rua" tabindex="1" class="form-control" placeholder="Rua" value="<?php echo $farmacia_logada->endereco->rua ;?>" required disabled>
                        </div>
                        <div class="form-group">
                            <input type="number" name="numero" id="numero" tabindex="1" class="form-control" placeholder="Nº" value="<?php echo $farmacia_logada->endereco->numero ;?>" required disabled>
                        </div>
                        <div class="form-group">
                            <input type="text" name="bairro" id="bairro" tabindex="1" class="form-control" placeholder="Bairro" value="<?php echo $farmacia_logada->endereco->setor ;?>" required disabled>
                        </div>
                        <div class="form-group">
                            <input type="text" name="latitude" id="latitude" tabindex="1" class="form-control" placeholder="Latitude" value="<?php echo $farmacia_logada->endereco->latitude ;?>" required disabled>
                        </div>
                        <div class="form-group">
                            <input type="text" name="longitude" id="longitude" tabindex="1" class="form-control" placeholder="Longitude" value="<?php echo $farmacia_logada->endereco->longitude ;?>" required disabled> 
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-9"></div>
                                <div class="col-sm-3">
                                    <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-green btn-block" value="Salvar Alterações" style="display:none" />
                                </div>
                            </div>
                        </div>
                    </form>

                    <h4>Configurações Adicionais</h4>
                    <hr/>
                    <h5 style="font-weight:bold">Alterar Conta</h5>
                    <p>Para alterar os dados da farmácia, siga as instruções abaixo.</p>
                    <p class="alert alert-info" role="alert">
                        Para alterar sua conta click em "Alterar"
                        <button id="btn-alterar">Alterar</button>
                    </p>
                    <hr/>
                    <h5 style="font-weight:bold">Excluir Conta</h5>
                    <p>
                        Atenção, ao realizar este passo você perderá acesso imediato a sua conta, bem como a todos os produtos já cadastrados!
                         Se mesmo assim deseja prosseguir, siga as instruções abaixo.
                    </p>
                    <p class="alert alert-danger" role="alert">
                        Para excluir sua conta click em "Excluir"
                        <button id="btn-excluir" data-target="#exampleModal" data-toggle="modal">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
             
    
    <!-- Modal exclusao de elementos -->
    <div class="modal" tabindex="-1" role="dialog" id="exampleModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">ATENÇÃO</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p>
                A ação que estás prestes a realizar é permanente. <b>Deseja realmente excluir esta conta da FARMÁCIA?</b>
            </p>
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
        const closeAlerts = document.querySelectorAll('.info button');

        for (let i = 0; i < closeAlerts.length; i++) {
            closeAlerts[i].addEventListener('click', function(){
                
                let elemento = this.getAttribute('data');

                window.location = document.URL.replace(`?i=${elemento}`, '');
            });
        }

        //Recuperando dados da farmacia
        const formulario = document.querySelectorAll('form input');

        document.getElementById('btn-alterar').addEventListener('click', function(){
            
            // habilitando campos de texto
            for(let i = 0; i < formulario.length; i++) {
                formulario[i].removeAttribute('disabled');
                
                // mostrando botão salvar
                if(formulario[i].type == 'submit') {
                    formulario[i].removeAttribute('style');
                }
            }
        });

        // Confirmação de exclusão de conta
        document.getElementById('confirmar-exclusao').addEventListener('click', function(){
            window.location = '../excluir_farmacia.php';
        });
    </script>

    <script src="../assets/js/mascara.min.js"></script>
   
</body>
</html>