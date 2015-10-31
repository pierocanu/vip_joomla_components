<?php 

/*
 * @package Joomla 1.7
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
			
			$view->setModel($this->getModel('turniservizi'),true);
			
			$view->set('messaggio', ' ');
			$view->set('modelData', $modelData);
			$view->set('modelFunzioni', $modelFunctions);
			$view->set('modelOpDB', $modelOpDB);
			
			parent::display();    
		}
			
		function aggClown(){
				
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'functions.php');
			$modelFunctions=new FunctionsModelFunctions();
	
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'turniservizi.php');
			
			$nomeClown = JRequest::getVar('nome');
			$gg = JRequest::getVar('gg');		
			$mm = JRequest::getVar('mm');
			
			$mm=$modelData->meseFromTextToNum($mm); //trasforma il mese da testuale a numero
			$aa=$modelData->calcolaAnno($mm); //calcola l'anno in base al mese
				
			$aggiunta = $modelOpDB->aggClown($nomeClown,$gg,$mm,$aa);
			
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');	
				
			$view->setModel($this->getModel('turniservizi'),true);
						
			$view->set('modelData', $modelData);
			$view->set('modelFunzioni', $modelFunctions);
			$view->set('modelOpDB', $modelOpDB);
			
			$modelData->meseFromNumToText($mm);
			
			if($aggiunta == 0){ //op. andata a buon fine
				$view->set('nomeClown',$nomeClown);
				$view->set('giorno',$gg);
				$view->set('mese',$modelData->meseFromNumToText($mm));
				
				$view->set('messaggio','clown-aggiunto');
			}  else if($aggiunta == 1){
				$view->set('nomeClown',$nomeClown);
				$view->set('giorno',$gg);
				$view->set('mese',$modelData->meseFromNumToText($mm));
				
				$view->set('messaggio','clown-già-in-servizio');
			} else { //stampa errore generico			
				$view->set('messaggio','errore-clown-non-aggiunto');	 		
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
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'turniservizi.php');
			
			$nomeClown = JRequest::getVar('nome');
			$data = JRequest::getVar('data');
			
			$data = explode(' ',$data);
			$dim=count($data);
			$dim=$dim-1;
			
			$mm=$data[$dim];
			$gg=$data[$dim-1];
			
			$mm=$modelData->meseFromTextToNum($mm); //trasforma il mese da testuale a numero
			$aa=$modelData->calcolaAnno($mm); //calcola l'anno in base al mese
			
			$rimoz = $modelOpDB->rimClown($nomeClown,$gg,$mm,$aa);
			
			
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');	
			
			$view->setModel($this->getModel('turniservizi'),true);
				
			$view->set('modelData', $modelData);
			$view->set('modelFunzioni', $modelFunctions);
			$view->set('modelOpDB', $modelOpDB);
							
			if($rimoz == 0){ //op. andata a buon fine			
				
				$view->set('nomeClown',$nomeClown);
				$view->set('giorno',$gg);
				$view->set('mese',$modelData->meseFromNumToText($mm));
				
				$view->set('messaggio','clown-rimosso');
			} else if($rimoz == 1){ //Errore clown non in servizio			
				
				$view->set('nomeClown',$nomeClown);
				$view->set('giorno',$gg);
				$view->set('mese',$modelData->meseFromNumToText($mm));
				
				$view->set('messaggio','clown-non-in-servizio');
			} else { //stampa errore
				$view->set('messaggio','erroreC-clown-non-rimosso');
			}
			
			parent::display(); 
		//	$view->display();	
		
		}

		function etichettaServizio(){
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'functions.php');
			$modelFunctions=new FunctionsModelFunctions();
	
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'turniservizi.php');
			
			$etichetta = JRequest::getVar('etichetta');
			$data = JRequest::getVar('data');
			
			$data = explode(' ',$data);
			$dim=count($data);
			$dim=$dim-1;
			
			$mm=$data[$dim];
			$gg=$data[$dim-1];
			
			$mm=$modelData->meseFromTextToNum($mm); //trasforma il mese da testuale a numero
			$aa=$modelData->calcolaAnno($mm); //calcola l'anno in base al mese
			
			$etichettatura= $modelOpDB->etichettaServizio($etichetta,$gg,$mm,$aa);
			
			
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');	
			
			$view->setModel($this->getModel('turniservizi'),true);
				
			$view->set('modelData', $modelData);
			$view->set('modelFunzioni', $modelFunctions);
			$view->set('modelOpDB', $modelOpDB);
							
			if($etichettatura == 0){ //op. andata a buon fine (modifica)			
				$view->set('giorno',$gg);	
				$view->set('mese',$modelData->meseFromNumToText($mm));
				$view->set('etichetta',$etichetta);
				$view->set('messaggio','servizio-etichettato');
			} else if($etichettatura == 1){ //op. andata a buon fine (rimozione)			
				$view->set('giorno',$gg);	
				$view->set('mese',$modelData->meseFromNumToText($mm));
				$view->set('messaggio','etichetta-rimossa');
			}else { //stampa errore
				$view->set('messaggio','errore-servizio-non-etichettato');
			}
			
			parent::display(); 
		//	$view->display();	
		
		}

		function modificaNote() {
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'functions.php');
			$modelFunctions=new FunctionsModelFunctions();
	
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'turniservizi.php');
			
			$mm= JRequest::getVar('mm');
			$note = JRequest::getVar('note');
			
			$mm=$modelData->meseFromTextToNum($mm); //trasforma il mese da testuale a numero
			$aa=$modelData->calcolaAnno($mm); //calcola l'anno in base al mese

			$modifica=$modelOpDB->modificaNote($note, $mm , $aa);
			
			
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');	
			
			$view->setModel($this->getModel('turniservizi'),true);
				
			$view->set('modelData', $modelData);
			$view->set('modelFunzioni', $modelFunctions);
			$view->set('modelOpDB', $modelOpDB);
			
			if($modifica == 0) {
				$view->set('mese',$modelData->meseFromNumToText($mm));
				$view->set('messaggio','note-modificata');
				
			} else if($modifica == 1) {
				$view->set('mese',$modelData->meseFromNumToText($mm));
				$view->set('messaggio','note-cancellate');
				
			} else {//stampa errore 
				$view->set('messaggio','errore');
			}
			
			parent::display(); 
			//$view->display();
		}



}

?> 