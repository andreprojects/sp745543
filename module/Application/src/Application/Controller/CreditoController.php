<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Session\Container;

class CreditoController extends AbstractActionController {
    
    /**
     *
     * @var EntityManager
     */
    protected $em;
    
     /*
     * @return EntityManager
     */

    protected function getEm() {
        if (null === $this->em)
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        return $this->em;
    }
	
	
	private function clearStr($str) {

		if (!get_magic_quotes_gpc()) {

			$str = addslashes($str);

		}

		return $str;

	} 
    
	public function indexAction(){
		
		//$request = $this->getRequest();
		//$post 	 = $request->getPost();
		$code = $_POST['notificationCode'];
	    $token = "5720961BD8974653AF78CCA47901C6F3";
	    $email = "andrework@gmail.com";
	    
	    $url = "https://ws.pagseguro.uol.com.br/v2/transactions/notifications/" . $code . "?email=" . $email . "&token=" . $token;
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    $result_xml = curl_exec($curl);
	    curl_close($curl);
		
		if(!empty($result_xml)){
			$obj_result = simplexml_load_string($result_xml);
        	
			$id_user 		= $obj_result->items->item->id;
			$valor_credito 	= (float)$obj_result->grossAmount;	
			$cod_status 	= $obj_result->status;
			if($cod_status == 3)
			{
				$repository = $this->getEm()->getRepository("Application\Entity\Users");
				$obj_records = $repository->findById($id_user);
		
		        if(!empty($obj_records))
		        {
					$records_user['id'] = $obj_records->id;
					$records_user['credito'] = $obj_records->credito + $valor_credito;
					$service_user = $this->getServiceLocator()->get("service_register");
					
					$log_name = "keep".date("dm").".log";
					$log_msg = "keep wait credito id_user:".$records_user['id']." credito atual:".$obj_records->credito." credito+:".$valor_credito. " date:".date("d-m-Y H:i:s")."|\n"; 
					@file_put_contents("./data/files/logs/".$log_name, $log_msg , FILE_APPEND );		
					$service_user->update($records_user);
					$log_msg = "keep complete credito id_user:".$records_user['id']." credito atual:".$obj_records->credito." credito+:".$valor_credito. " date:".date("d-m-Y H:i:s")."|\n";
					@file_put_contents("./data/files/logs/".$log_name, $log_msg , FILE_APPEND );		
					
					
					//log para movimentação id_user + tipo: C + valor
					file_put_contents("./data/files/logbcash.txt", $_POST, FILE_APPEND );		
					
				}
			}
			
		}
		exit;
	}

	public function consoleAction(){
		
		$output = shell_exec('git pull origin master');
		echo "<pre>$output</pre>";
		echo phpinfo();
		exit;	
	}
	
	public function listuserallAction(){
		
		$repository = $this->getEm()->getRepository("Application\Entity\Users");
        $objRecordUser = $repository->findAllUser();
					
		
		echo "<pre>";
		foreach ($objRecordUser as $key => $value) {
			echo "Nome: ".$value->nome."<br> Email: ".$value->email."<br> ID:".$value->id."<br><br>";
		}
		//var_dump($recordsBaseUser);
		
		echo "</pre>";
		exit();
		
	}
	
    public function paypalAction() {
		
        $request = $this->getRequest();
		$post = $request->getPost();
		
		$postdata = 'cmd=_notify-validate';

		if(!empty($_POST))
		{
			
			foreach ($_POST as $key => $value) {
				$str_post .= $key.":".$value."|";
				$valued    = $this->clearStr($value);
				$postdata .= "&$key=$valued";
	
			}
		
			//$str_post = implode("|", $_POST);
		
			file_put_contents("./data/files/logbcash.txt", $str_post, FILE_APPEND );
			
			$curl = curl_init();
	
			curl_setopt($curl, CURLOPT_URL, "https://www.paypal.com/cgi-bin/webscr");
	
			curl_setopt($curl, CURLOPT_POST, true);
	
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
	
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	
			curl_setopt($curl, CURLOPT_HEADER, false);
	
			curl_setopt($curl, CURLOPT_TIMEOUT, 20);
	
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
			$result = trim(curl_exec($curl));
	
			curl_close($curl);
		
		
			file_put_contents("./data/files/logbcash.txt", "|RESPOSTA: ".$result, FILE_APPEND );
		
			if(trim($result)=="VERIFIED"){
				/*
				$id_user = $_POST['produto_codigo_1'];
				$valor_credito = $_POST['produto_valor_1'];	
				$cod_status = $_POST['cod_status'];
				
				if($cod_status == 0)
				{
					$repository = $this->getEm()->getRepository("Application\Entity\Users");
					$obj_records = $repository->findById($id_user);
			
			        if(!empty($obj_records))
			        {
						$records_user['id'] = $obj_records->id;
						$records_user['credito'] = $obj_records->credito + $valor_credito;
						$service_user = $this->getServiceLocator()->get("service_register");
						$service_user->update($records_user);
					}
				}
				* */
				 
					
			}
		
		}
		
		exit;
    }
	
	

}
