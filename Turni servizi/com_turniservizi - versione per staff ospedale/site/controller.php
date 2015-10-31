<?php 

/*
 * @package Joomla 1.6
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component turni servizi
 * @copyright Copyright (C) Piero Canu 
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');


class  TurniServiziController extends JController{
	
		function display(){ //default task
				
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'functions.php');
			$modelFunctions=new FunctionsModelFunctions();
	
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
	
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');	
			
			$view->set('messaggio', ' ');
			$view->set('Data', $modelData);
			$view->set('Funzioni', $modelFunctions);
			
			parent::display();    
		}
			
		function aggClown(){
				
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData;
						
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
				
			require_once(JPATH_COMPONENT.DS.'models'.DS.'functions.php');
			$modelFunctions=new FunctionsModelFunctions();				
				
			
			$mesi=$modelData->getInfo('MesiAnno'); 
			$model = $this->getModel('gestisci_turni');
			$SuffissoDB = $model->getSuffissoDB();
		
			$nomeClown = JRequest::getVar('nome');
			$gg = JRequest::getVar('gg');		
			$mm = JRequest::getVar('mm');
			
			for($m=1; $m<=12; $m++){ //trasforma il mese da testuale a numero
				if($mesi[$m] == $mm){
					$mm = $m;		
				}
			}
			
			if($mm == $modelData->getInfo('NumMeseAttuale') ){ //Calcola l'anno
				$aa = $modelData->getInfo('AnnoAttuale');
			} else if($mm == $modelData->getInfo('NumMeseProssimo')){
				$aa = $modelData->getInfo('AnnoMeseProssimo');	
			} 
			
			if ( JRequest::getVar('altroCheck') == 'on'){
				$altro = JRequest::getVar('altro');
				}
	
			$aggiunta = $modelOpDB->aggClown($SuffissoDB,$nomeClown,$gg,$mm,$aa,$altro);
			
			$view = $this ->getView('gestisci_turni','html');	
			$view->set('Data', $modelData);
			$view->set('Funzioni', $modelFunctions);	
			$view->set('Titolo', $model->get('Titolo'));			
			
			if($nomeClown == '' || $nomeClown == ' ' || $aggiunta != 0){ //stampa errore
				$view->set('messaggio','erroreClownNonAggiunto');
			} else { //op. andata a buon fine			
				$info = array('Titolo'=>$Titolo,'nomeClown'=>$nomeClown,'giorno'=>$gg,'mese'=>$mesi[$mm]);			
				$view->set('info',$info);
				$view->set('messaggio','clown_aggiunto');
			} 
			
			parent::display(); 
			//$view->display();
		}
			
		function rimClown(){
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'functions.php');
			$modelFunctions=new FunctionsModelFunctions();
	
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
				
			
			$mesi=$modelData->getInfo('MesiAnno'); 
			$model = $this->getModel('gestisci_turni');
			
			$SuffissoDB = $model->getSuffissoDB();
		
			$nomeClown = JRequest::getVar('nome');
			$data = JRequest::getVar('data');
			
			$data = explode(' ',$data);
			$gg=$data[1];
			$mm=$data[2];
			
			for($m=1; $m<=12; $m++){ //trasforma il mese da testuale a numero
				if($mesi[$m] == $mm){
					$mm = $m;		
				}
			}
			
			if($mm == $modelData->getInfo('NumMeseAttuale') ){ //Calcola l'anno
				$aa = $modelData->getInfo('AnnoAttuale');
			} else if($mm == $modelData->getInfo('NumMeseProssimo')){
				$aa = $modelData->getInfo('AnnoMeseProssimo');	
			} 
			
	
			$rimoz = $modelOpDB->rimClown($SuffissoDB,$nomeClown,$gg,$mm,$aa);
			
			$view = $this->getView('gestisci_turni','html');			
			$view->set('Data', $modelData);
			$view->set('Funzioni', $modelFunctions);
							
			if($rimoz == 0){ //op. andata a buon fine			
				$info = array('Titolo'=>$Titolo,'nomeClown'=>$nomeClown,'giorno'=>$gg,'mese'=>$mesi[$mm]);
				$view->set('info',$info);	
				$view->set('messaggio','clown_rimosso');
			} else { //stampa errore
				$view->set('messaggio','erroreClownNonRimosso');
			}
			
			parent::display(); 
		//	$view->display();	
		
		}
		
		function inviaRichiestaInserimento() {
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'functions.php');
			$f=new FunctionsModelFunctions();
			
			$nome = JRequest::getVar('nomeClown');
			$giorno = JRequest::getVar('gg');
			$mese= JRequest::getVar('mm');
			$aggiuntivo=JRequest::getVar('agg');
			$mail=JRequest::getVar('mail');			
			$mailClown=JRequest::getVar('mailClown');	
			
			$invio=$f->inviaMailRichiesta($nome,$mailClown,$giorno,$mese,$aggiuntivo,$mail);
			
			$model =& $this->getModel('visualizza_turni');
			$view =& $this->getView('visualizza_turni', 'html');
			
			$view->set('Data', $modelData);
			$view->set('Funzioni', $f);
			
			if($invio == 0) {
				$view->set('messaggio','mail_inviata');
			} else {//stampa errore
				$view->set('messaggio','erroreMail');
			}
			
			parent::display(); 
			//$view->display();
		}

		function modificaNote() {
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'functions.php');
			$f = new FunctionsModelFunctions();
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;

			$model = $this->getModel('gestisci_turni');
			$view = $this->getView('gestisci_turni','html');

			$note = JRequest::getVar('note');
			$mese= JRequest::getVar('mm');
			$mesi = $modelData->getInfo('MesiAnno');
			$SuffissoDB = $model->getSuffissoDB();
			
			for($m=0; $m<=12; $m++){ //trasforma il mese da testuale a numero
				if($mesi[$m] == $mese){
					$mm = $m;		
				}
			}

			//Calcola l'anno
			if($mm == $modelData->getInfo('NumMeseAttuale') ){
				$aa = $modelData->getInfo('AnnoAttuale');
			} else if($mm == $modelData->getInfo('NumMeseProssimo')){
				$aa = $modelData->getInfo('AnnoMeseProssimo');	
			}
				
			$modifica=$modelOpDB->modificaNote($SuffissoDB, $note, $mm , $aa);
			
			$view->set('Data', $modelData);
			$view->set('Funzioni', $f);
			
			if($modifica == 0) {
				$view->set('messaggio','nota_modificata');
				
			} else {//stampa errore
				$view->set('messaggio','errore');
			}
			
			parent::display(); 
			//$view->display();
		}



}

?> 