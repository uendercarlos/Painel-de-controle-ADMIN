<?php
	session_start();

	if(isset($_SESSION['session_farma'])) {
		header('Location: index.php');
		exit;
	}

	// Recuperando dados dos cookies caso existam
	$usuario = '';
	$senha = '';
	$lembrar = '';

	if(isset($_COOKIE['cookie_farma'])) {
		$cookie = explode(' ', $_COOKIE['cookie_farma']);

		$usuario = $cookie[0];
		$senha = $cookie[1];
		$lembrar = $cookie[2];
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Logar no Sistema</title>

	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<link href="../assets/css/styleLogin.css" rel="stylesheet">
	
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="../assets/js/mascara.min.js"></script>
	<script src="../assets/js/styleLogin.js"></script>
</head>
<body>
	<div class="container">

		<h2 style="text-align:center; margin:0;color:#555;margin-bottom:30px">Login</h2>
		<p class="alert alert-success message" role="alert" <?php echo ((isset($_GET['i']) && $_GET['i'] == 2) ? '' : 'style="display:none"');?>>
			Cadastro Realizado com sucesso!
			<button>X</button>
		</p>
		<p class="alert alert-warning message" role="alert" <?php echo ((isset($_GET['t']) && $_GET['t'] == 1) ? '' : 'style="display:none"');?>>
			Você foi deslogado por inatividade!
			<button>X</button>
		</p>

		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
				<div class="panel panel-login">
					<div class="panel-heading">
						
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12 col-md-12">
								
								<!-- Formulario de login -->
								<form id="login-form" action="../logar.php" method="post" role="form" style="
									display: block;
								">
				
									<p Style="color: crimson; font-size: 14px;">
										<?php echo (isset($_GET['i']) && $_GET['i'] == 1 ? 'Email e/ou senha inválido!' : ''); ?>
									</p>
									
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Nome de usuário" value="<?=$usuario?>" required>
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Senha" value="<?=$senha?>" required>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
												<input type="checkbox" tabindex="3" class="" name="remember" id="remember" value="SIM" 
												<?php echo ($lembrar == 'SIM' ? 'checked' : '');?>>
												<label for="remember"> Lembrar senha</label>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
												<div class="text-center">
													<a href="esqueceu_senha.php" tabindex="5" class="forgot-password">Esqueceu a senha?</a>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-green btn-block" value="Entrar">
											</div>
										</div>
									</div>
								</form>

								<!-- Formulario de cadastro -->
								<form id="register-form" action="../cadastrar_farmacia.php" method="post" role="form" style="display: none;">
									
									<label>Dados de acesso</label>
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Nome de usuário" value="">
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Senha">
									</div>
									<div class="form-group">
										<input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirmar senha">
									</div>
									<br>

									<label>Dados da farmácia </label>
									<div class="form-group">
										<input type="text" name="nome" id="nome" tabindex="1" class="form-control" placeholder="Nome" value="">
									</div>
									<div class="form-group">
										<input type="text" name="cnpj" id="cnpj" class="form-control" placeholder="CNPJ" onkeyup="mascara('##.###.###/####-##',this,event,true)" maxlength="18">
									</div>
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="E-mail" value="" required>
									</div>
									<div class="form-group">
										<input type="text" name="telefone" id="telefone" tabindex="1" class="form-control" placeholder="Telefone" value="" required onkeyup="mascara('(##) ####-####',this,event,true)" maxlength="14">
									</div>
									<br>

									<label>Endereço da farmácia</label>
									<div class="form-group">
										<input type="text" name="rua" id="rua" tabindex="1" class="form-control" placeholder="Rua" value="" required>
									</div>
									<div class="form-group">
										<input type="number" name="numero" id="numero" tabindex="1" class="form-control" placeholder="Nº" value="" required>
									</div>
									<div class="form-group">
										<input type="text" name="bairro" id="bairro" tabindex="1" class="form-control" placeholder="Bairro" value="" required>
									</div>
									<div class="form-group">
										<input type="text" name="latitude" id="latitude" tabindex="1" class="form-control" placeholder="Latitude" value="" required>
									</div>
									<div class="form-group">
										<input type="text" name="longitude" id="longitude" tabindex="1" class="form-control" placeholder="Longitude" value="" required>
									</div>
									<!-- <div class="form-group">
										<input type="complemento" name="complemento" id="complemento" tabindex="1" class="form-control" placeholder="Complemento" value="">
									</div> -->
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-green btn-block" value="Cadastrar">
											</div>
										</div>
									</div>
								</form>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		const fecharMenssagem = document.querySelectorAll('.message button');
		const menssagens = document.querySelectorAll('.message');

		for (let i = 0; i < fecharMenssagem.length; i++) {
			fecharMenssagem[i].addEventListener('click', function(){
				fecharAvisos();
			});
		}

		function fecharAvisos () {
			
			for (let i = 0; i < menssagens.length; i++) {
				menssagens[i].setAttribute('style', 'display:none');
			}
		}
	</script>
</body>
</html>