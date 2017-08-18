
<html>
    <head>
    <title>Extrator</title>  
    </head>
    <body background="images/fundo2.jpg" style="background-repeat: no-repeat">
    <html>
    </div>
    </body>
  </html>

<?php
session_start();

ini_set('max_execution_time', 86400);

require_once( 'Facebook/autoload.php' );

header("Content-type: text; charset=utf-8"); 

	function my_file_get_contents( $site_url ){
	$ch = curl_init();
	$timeout = 300; // set to zero for no timeout
	curl_setopt ($ch, CURLOPT_URL, $site_url);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	ob_start();
	curl_exec($ch);
	curl_close($ch);
	$file_contents = ob_get_contents();
	ob_end_clean();
	return $file_contents;
}


$fb = new Facebook\Facebook([
  'app_id' => '146665105881348',
  'app_secret' => 'f9372680d1c1bc99855e8b823579dda8',
  'default_graph_version' => 'v2.9',
]);  
  
$helper = $fb->getRedirectLoginHelper();  
$_SESSION['FBRLH_state']=$_GET['state'];
  
try {  
  $accessToken = $helper->getAccessToken();  
} catch(Facebook\Exceptions\FacebookResponseException $e) {  
  // When Graph returns an error  
  
  echo 'Graph returned an error: ' . $e->getMessage();  
  exit;  
} catch(Facebook\Exceptions\FacebookSDKException $e) {  
  // When validation fails or other local issues  

  echo 'Facebook SDK returned an error: ' . $e->getMessage();  
  exit;  
}  


try {
  // Get the Facebook\GraphNodes\GraphUser object for the current user.
  // If you provided a 'default_access_token', the '{access-token}' is optional.
  $response = $fb->get('/me?fields=id,name', $accessToken->getValue());

} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'ERROR: Graph ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'ERROR: validation fails ' . $e->getMessage();
  exit;
}

$delimitador = "\t";
$cerca = '"';
$cont = 0;
$fp = fopen("comment_message_TAG.txt",'wb');
$f = fopen('uploads/Dados.tab', 'r');


if ($f) {

    // Ler cabecalho do arquivo
    $cabecalho = fgetcsv($f, 0, $delimitador, $cerca);
    echo "<br>";
    // Enquanto nao terminar o arquivo
    while (!feof($f)) {

        // Ler uma linha do arquivo
        $linha = fgetcsv($f, 0, $delimitador, $cerca);
        if (!$linha) {
            continue;
        }

        // Montar registro com valores indexados pelo cabecalho
        $registro = array_combine($cabecalho, $linha);

        // Obtendo o comment_id
        $authID = $registro['comment_id'];
        echo "<br> Processando comentário ID: " . $registro['comment_id'].PHP_EOL ;
        
		        $urlFace        = "https://graph.facebook.com/$authID?fields=message_tags,id&=comments&access_token=$accessToken";
		        $jsonDados      = my_file_get_contents($urlFace);
		        $jsonObject     = json_decode($jsonDados,true);

							
							$texto = $registro['comment_message'];			
					        if (isset ($jsonObject ['message_tags'])) {	
						        foreach ($jsonObject ['message_tags'] as $value){
							        if (isset($value["name"])){
                            				$texto =  $texto . " xtagx ";
							        }
						        }	fwrite($fp,$texto . "\r\n");				
				          	} else fwrite($fp,$texto . "\r\n");

                    
    }
    echo "<br><font color='#0000FF'> ARQUIVO comment_message_TAG CRIADO COM SUCESSO! ACESSE A PASTA DA APLICAÇÃO</font><br>";
    echo "<br><font color='#0000FF'> Todos as marcações dos comentários foram trocadas para xtagx </font><br>";
    fclose($fp);
    fclose($f);
    
}


