<?php 

/*
 * @package Joomla 1.7
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component formazioni
 * @copyright Copyright (C) Piero Canu 
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');


class  FormazioniController extends JController{
		
		
		function display(){ //default task
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
		
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
			
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');
			
			$view->setModel($this->getModel('formazioni'),true);
			
			$view->set('modelData', $modelData);
			$view->set('modelOpDB', $modelOpDB);
			$view->set('messaggio', '');
			
			parent::display();    
		}
			
		function aggFormaz(){
				
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
		
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
			
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');
			
			$view->setModel($this->getModel('formazioni'),true);		
				
			$view = $this->getView('gestisci_formazioni','html');	
					
			$gg = JRequest::getVar('gg');		
			$mm = JRequest::getVar('mm');
			$note = JRequest::getVar('note');
			
			$mm=$modelData->meseFromTextToNum($mm); //trasforma il mese da testuale a numero			
			$aa=$modelData->calcolaAnno($mm); //Calcola l'anno
			
			$aggiunta = $modelOpDB->aggFormaz($gg,$mm,$aa,$note);
			
			
			$view->set('modelData', $modelData);
			$view->set('modelOpDB', $modelOpDB);
			
			if($aggiunta == 0){ //op. andata a buon fine 
				$view->set('data_formazione',"$gg ".$modelData->meseFromNumToText($mm)." $aa");
				$view->set('messaggio','formazione-aggiunta');
			} else if($aggiunta == 1){ //stampa errore formazione già presente
				$view->set('data_formazione',"$gg ".$modelData->meseFromNumToText($mm)." $aa");
				$view->set('messaggio','errore-formazione-gia-inserita');
			} else { //stampa errore		
				$view->set('messaggio','errore-formazione-non-aggiunta');
			}
			
			parent::display(); 
			//$view->display();
		}
		
		function rimFormaz(){
				
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
		
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
			
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');
			
			$view->setModel($this->getModel('formazioni'),true);
			
			$data = JRequest::getVar('data');
			$data_array = explode(' ',$data);
			
			$gg=$data_array[0];
			$mm=$data_array[1];
			
			$mm=$modelData->meseFromTextToNum($mm); //trasforma il mese da testuale a numero
			$aa=$modelData->calcolaAnno($mm); //Calcola l'anno
			
			$rimoz = $modelOpDB->rimFormaz($gg,$mm,$aa);
			
			
			$view->set('modelData', $modelData);
			$view->set('modelOpDB', $modelOpDB);
					
			
			if($rimoz == 0){ //op. andata a buon fine 
				$view->set('data_formazione',"$gg ".$modelData->meseFromNumToText($mm)." $aa");
				$view->set('messaggio','formazione-rimossa');
			} else if($rimoz == 1){ //stampa errore formazione non presente
				$view->set('data_formazione',"$gg ".$modelData->meseFromNumToText($mm)." $aa");
				$view->set('messaggio','errore-formazione-non-trovata');
			} else { //stampa errore		
				$view->set('messaggio','errore-formaz-non-rimossa');
			}
			
			parent::display(); 
			//$view->display();
		}
			
		function modNoteFormaz() {
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
		
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
			
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');
			
			$view->setModel($this->getModel('formazioni'),true);
			
			$data = JRequest::getVar('data');
			$data_array = explode(' ',$data);


			$view = $this->getView('gestisci_formazioni','html');	

			$note = JRequest::getVar('note');
			$data = JRequest::getVar('data');

			$tmp=explode(' ',$data);//Riformatta la data
			$gg = $tmp[0];
			$mm = $tmp[1];
			
			$mm=$modelData->meseFromTextToNum($mm); //trasforma il mese da testuale a numero			
			$aa=$modelData->calcolaAnno($mm); //Calcola l'anno

			
			if($mm<=9){$mm='0'.$mm;} //aggiungi uno zero al mese se necessario
			
			$modifica=$modelOpDB->modificaNoteFormaz("note", "$aa-$mm-$gg", $note);
			
			$view->set('modelData', $modelData);
			$view->set('modelOpDB', $modelOpDB);
			
			if($modifica == 0){ //op. andata a buon fine 
				$view->set('data_formazione',"$gg ".$modelData->meseFromNumToText($mm)." $aa");
				$view->set('messaggio','note-modificate');
			} else if($modifica == 1){ //op. andata a buon fine 
				$view->set('data_formazione',"$gg ".$modelData->meseFromNumToText($mm)." $aa");
				$view->set('messaggio','note-cancellate');
			}else { //stampa errore		
				$view->set('messaggio','errore-note-non-modificate');
			}
			
			parent::display(); 
			//$view->display();
		}

	function modComunicaz() {
			
			require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
			$modelData=new DataModelData();	
		
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
			
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');
			
			$view->setModel($this->getModel('formazioni'),true);
			
			$comunicazioni = JRequest::getVar('comunicazioni');

			$modifica=$modelOpDB->modificaComunicaz($comunicazioni);
			
			$view->set('modelData', $modelData);
			$view->set('modelOpDB', $modelOpDB);
			
			if($modifica == 0) {
				$view->set('messaggio','comunicaz-modificate');
				
			} else if($modifica == 1) {
				$view->set('messaggio','comunicaz-cancellate');
				
			}else {//stampa errore
				$view->set('messaggio','errore-comunicaz-non-modificate');
			}
			
			parent::display(); 
			//$view->display();
		}

}

?> 