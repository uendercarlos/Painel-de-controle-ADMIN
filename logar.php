<?php 
	session_start();
	
	$usuario = (isset($_POST['username'])) ? $_POST['username'] : '';
	$senha = (isset($_POST['password'])) ? $_POST['password'] : '';
	$lembrete = (isset($_POST['remember'])) ? $_POST['remember'] : '';
	
	/* 
	* Redireciona para login caso um dos campos esteja vazio,
	* e retorna uma variavel 'i' via GET para informar que houve uma falha no login
	*/
	if (empty($usuario) || empty($senha)){		
		header('Location: pages/login.php?i=1');
		exit;
	}

	// Cria um array com dados do login e converte para JSON
	$login = array('login' => $usuario, 'senha' => $senha);
	$json = json_encode($login);  

	require_once 'autenticar.php';

	if($header[1] == "200") {
		
		$token = explode(' ', $header[6]);
		$token = str_replace('Content-Length:', "", $token[0]);

		$_SESSION['session_farma'] = trim($token);
		$_SESSION['autenticacao'] = $usuario . " " . $senha;

		echo $token . "<br>";

		//Verificando se a farmacia está ativa
		$ch = curl_init('https://menorprecomedicamentoipora.herokuapp.com/administrador/administrador');
    
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                      
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(        
			"Content-Type: application/json",
			"Authorization:Bearer " . $_SESSION['session_farma']
		));                                                             
																														
		$response = curl_exec($ch);
		curl_close($ch);
	
		// Convertendo json para objeto
		$farmacia_logada = json_decode($response);

		var_dump($response);
	
		if($farmacia_logada) {

			// Caso a caixa lembrar senha esteja marcada cria um cookie com validade de 7 dias
			if($lembrete) {
				setcookie('cookie_farma', $usuario.' '.$senha.' '.$lembrete, time() + (60 * 60 * 24 * 7));
			}

			header('Location: pages/index.php');
			exit;
		}
		else {
			unset($_SESSION['session_farma']);
			unset($_SESSION['autenticacao']);
		}
	}
	
	header('Location: pages/login.php?i=1');
	exit;
?>