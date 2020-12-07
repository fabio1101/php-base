<?php
/**
 * @author 
 * @namespace CORE
 */

namespace CORE; 

/**
 * class Link
 * query link segun id de categoria y/o campain
 * 
 * debe mostrar los contenidos segun la categoria 
 * 
 */
use MVC\Model;
use MVC\Utils;
use CORE\Content;
//use CORE\LoadsProfile as LoadsProfile;
use \PDO;
use CORE\InfoUser;
use CORE\widget;

class Email {//extends LoadsProfile{
	
	
	public $ArrayLinks;	
	public $instanciacontent;
	public $instanciauserinfo;

	
	public function __construct() {
		
		$this->model	        = new Model();
		$this->instanciacontent = new Content();
		$this->instanciauserinfo = new InfoUser();
		
	}
	
	/**
	 * @method	 getFile(	$id_categ	=	NULL, $iduser = NULL)
	 * @param 	 String $id_categ	:: identifier of category
	 * @param    String $iduser	:: identifier of user
	 * @abstract get Links of some category or/and user
	 */
	public function getLink (	$id_categ	=	NULL,  $currentUser = NULL){ 
		
		
		$iduser 		=	 NULL;
		//$sqlprofile     =   $this->getProfile($currentUser);
		$sqlprofile		=	'profile.name = "publico"';
		
		if(isset($currentUser)){
		
			if($currentUser->authenticUser() ) {
				$iduser = $currentUser->getID(); // false o null si no esta logeado
			}
		
			if(isset($iduser)){
					
				$profile= $currentUser->profileUser($iduser);
				//print_r($profile);
				foreach ($profile as $key=> $value){
		
					(($key==0)? $sqlprofile	= 'profile.name ='.'"'.$value['name'].'"': $sqlprofile .=' or profile.name ='.'"'.$value['name'].'"');
		
				}
			}
		}
			 
		(!isset($id_categ)?  $idcategory	= '' :$idcategory= "category.id = '$id_categ' and"  );
		
		  $sth = $this->model->db->prepare("SELECT links.* from links INNER JOIN category 
		  									ON (category.id = links.id_category) INNER JOIN  category_profile 
		  								    ON (category.id= category_profile.category_id)  INNER JOIN  profile 
		  								    ON (category_profile.profile_id= profile.id)	
										  	WHERE   $idcategory links.status= '1' and category.status = '1' and ($sqlprofile) ");
		  $sth->setFetchMode(PDO::FETCH_ASSOC); 
		  $sth->execute(); 
		  $this->ArrayLinks	=	$sth->fetchALL();
		  //print_r($sth);
		 // print_r($this->ArrayLinks);
	}
	
	
	/**
	 * @method		showFiles()											
	 * @abstract	show files about category or user profile
	 */
	
	public function showLinks(){
	
		$htmllink = '';
		
		foreach ($this->ArrayLinks as $key=>$value){
						
			 $htmllink .=' <div class="campo-center"><h3><a href="/category" >'.$value['LinkName'].'</a></h3></div>';
					
		}
		
		//echo $htmllink; 
		return $htmllink;
			
 	}
 

	 public function listsinformation($id_email=NULL){
	 
	 	(!isset($id_email)? $restsql='' :$restsql = 'WHERE maillists.id ='.$id_email);
	 	$sth = $this->model->db->prepare("SELECT maillists.* from maillists $restsql");
			   $sth->setFetchMode(PDO::FETCH_ASSOC); 
			   $sth->execute(); 
			   $ArrayLists	=	$sth->fetchALL();
	 	
	 	return $ArrayLists;
	 		
	 }
 
	 public function getlists(){
	 
	 	 $informacion = $this->listsinformation();
		 $htmlcode = '<br>';
		 $cont=0;
		 	// print_r('<pre>');
		 //print_r($informacion);die;
		 $informacion = $this->instanciauserinfo->filtrarinformacionuser($informacion,'restringir');
		 $datos=$informacion[1];
		 $informacion=$informacion[0];
		 foreach ($informacion as $key=> $value){
		 
		 //	$namecat				= $this->instanciacontent->getnamescategory($value['ID_Category']);
		 //	print_r($namecat);die;
		 	
		 	(($value['Status']=='1')? $value['Status']='Activo': $value['Status']='Inactivo');
		 
		 	$_SESSION['CONTENIDOS_LISTS'][$value['ID']] = array($value);
		 
		 	$htmlcode .='<div class="information ui-widget ui-widget-content ui-corner-all" id="maillists-'.$value['ID'].'">
		 				<img src="/public/icons/mail.png" width="48">
					    <span class="item">'.Utils::encode($value['Name']).'</span><br><span class="item">Estado : '.$value['Status'].'</span><br><div class="item_des ui-widget ui-widget-content ui-corner-all" title="Listas">Descripci&oacute;n:&nbsp;'.Utils::encode($value['Description']).'</div>
		 				</div>';
		 
		 	$htmlcode .= ((++$cont%3)== 0 )? '<div style="width:100%; height:20px;float:left"></div>' : '';
		 	
		 }
		 
		 return $htmlcode.'<script>'.$datos.'</script>';
		 
	 				
	 }
 
 /**
  * @method	especificContent()
  * @param string $categoria
  * @return string
  */
	public function  especificlist($id_list,$editar=NULL){
	 	
	 	$htmlcode= '';
	 	
	 	if(!isset($editar)){
	 	
	 		$datoscontenido=$_SESSION['CONTENIDOS_LISTS'][$id_list];
	 	
	 	}else{
	 		$datoscontenido = $this->listsinformation($id_list) ;
	 		//$namecat				= $this->instanciacontent->getnamescategory($datoscontenido[0]['ID_Category']);
	 		//$datoscontenido[0]['ID_Category'] = $namecat[0]['Name'];
	 		(($datoscontenido[0]['Status']=='1')? $datoscontenido[0]['Status']= 'Activa': $datoscontenido[0]['Status']= 'Inactivo');
	 	
	 	}
	 
	 	foreach ( $datoscontenido as $key => $value) {
	 		$htmlcategoria = new widget('Nombre: '.Utils::encode($value['Name']));
	 		$htmlcategoria->item('Descripci&oacute;n', Utils::encode($value['Description']));
	 		$htmlcategoria->item('Estado', Utils::encode($value['Status']) );	 		
	 	}
	 		 			
	 	return '<br />'.$htmlcategoria->out();
	 
	 }
 
				
	 public function  especificmails($id_list,$editar=NULL){
	 
	 	$htmlcode= '';
	 	
	 	$sth = $this->model->db->prepare("  SELECT 	mails.SendDate,
	 												campain.Name,
	 												mails.ID,
	 												mails.Title,
	 												mails.Priority,
	 												mails.Body,
	 												mails.Attachments,
	 												mails.ID_MailList
	 										FROM mails 
	 										LEFT JOIN maillists ON (maillists.ID = mails.ID_MailList) 
	 										LEFT JOIN campain ON (campain.ID = mails.ID_Campain)
											WHERE maillists.ID = $id_list ;");
		$sth->setFetchMode(PDO::FETCH_ASSOC); 
		$sth->execute(); 
		
		$data	=	$sth->fetchALL();
		
		foreach ($data  as $k => $value){
			
			if($data[$k]['Attachments'] && $data[$k]['Attachments'] != ' '){
				$data[$k]['Attachments'] = '<div><img style="width:20%" src="/public/images/adjunto.png"></div>';
			}
			
			if($data[$k]['Priority'] == '1')
				$data[$k]['Priority'] = 'Alta';
			if($data[$k]['Priority'] == '3')
				$data[$k]['Priority'] = 'Media';
			if($data[$k]['Priority'] == '5')
				$data[$k]['Priority'] = 'Baja';
			
			if(!$data[$k]['SendDate'])
				$data[$k]['SendDate'] = '<div class="sendemail" id="mails-'.$data[$k]['ID'].'-'.$data[$k]['ID_MailList'].'" ><img style="width:20%" src="/public/images/email.png" alt="Logo Asemtur"></div>';
		}
		
	 	$out = new CustomCode('tabla_mails');
	 	$out->part(array('FORM','TABLA'));
	 	$out->bassign('D', $data, 'TABLA');
	 	$out->inject('TABLA', 'FORM');
	 	return $out->printOut('FORM');
	 
	 }

	 public function mails($e_mails, $infoMail)
	 {
	 	$config= new Configs();
	 	$config->LoadConfig();
	 	$datos=$config->MyConfigs;
	 	$soportename = $datos['name']; 
	 	$servidor    = $datos['servidor'];
	 	$puerto 	 = $datos['puerto'];
	 	$passw		 = $datos['passw']; 
	 	$ssl		 = $datos['ssl'];
	 	$soportemail = $datos['emailsoporte'];
	 		
	 	//require_once '/lib/swift-mailer/lib/swift_required.php';
	 	foreach ($e_mails as $mail){
	 	
		 	$transport = \Swift_SmtpTransport::newInstance($servidor, $puerto,($ssl=='ssl')?'true':'')
		 	->setUsername($soportename)
		 	->setPassword($passw);
		 	
		 	//Creamos el mailer pasandole el transport con la configuracian de gmail
			$mailer = \Swift_Mailer::newInstance($transport);
		 
			//Creamos el mensaje
			$message = \Swift_Message::newInstance($infoMail[0]['Title'])
			            ->setFrom(array($soportemail => $soportename))
			            ->setTo($mail['Email'])
			            ->setBody($infoMail[0]['Body'].PHP_EOL);
				 
			//Enviamos
			$result = $mailer->send($message);
			
	 	}
	 }
	 
	 /**
	  * 
	  * @param unknown_type $maillist
	  * @param unknown_type $email
	  */
 

	 public function getinfomails($email, $maillist)
	 {
	 	date_default_timezone_set('America/Bogota');
	 	$date = date('Y-m-d H:i:s');
	 	$sth = $this->model->db->prepare("UPDATE mails SET SendDate = '$date' WHERE ID = $email");
	 	//print_r($sth);die;
	 	$sth->execute();
	 	
	 	
	 	$sth = $this->model->db->prepare("SELECT emailsusers.Email
	 										FROM maillist_emailusers 
	 										LEFT JOIN  emailsusers ON (maillist_emailusers.ID_EmailUsers = emailsusers.id)
											WHERE maillist_emailusers.ID_MailList = $maillist ");
	 	
		$sth->setFetchMode(PDO::FETCH_ASSOC); 
		$sth->execute(); 
		$data = $sth->fetchALL();
		
		$sth = $this->model->db->prepare("SELECT mails.Title,
												 mails.Body
											FROM mails
											WHERE mails.ID = $email ");
		 
		$sth->setFetchMode(PDO::FETCH_ASSOC);
		$sth->execute();
		$infoMail = $sth->fetchALL();
		
		$this->mails($data, $infoMail);
	 }

	
}
	
	
	

	