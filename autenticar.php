<?php
    
    if(!isset($json) || empty($json)) {
        header('Location: pages/index.php');
        exit;
    }

    // Configurando a requisicao
	$ch = curl_init('https://menorprecomedicamentoipora.herokuapp.com/administrador/autenticar');                                                                      
	
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($json))                                                                       
	);                                                                                                                   
	
	$response = curl_exec($ch);
	
	// Extraindo o header da resposta em STRING da API
	$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $headerSize);

	/* 
	* Quebrando a String do header para pegar o STATOS da requisicao e o TOKEN
	* o resultado é um array
	* onde na posicao 1 terá o STATUS
	* e na posicao 4 o TOKEN, caso a requisiçao tenha STAUTS 200
	*/
	$header = explode(' ', $header);
?>