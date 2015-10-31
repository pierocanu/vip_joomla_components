<?php 

/*
 * @package Joomla 1.7
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component clowns
 * @copyright Copyright (C) Piero Canu 
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */


// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');


class  ClownsController extends JController{
	
		function display(){ //default task
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
	
			$vName	= JRequest::getCmd('view', 'categories');
			JRequest::setVar('view', $vName);
			$view = $this ->getView($vName,'html');	
			
			$view->setModel($this->getModel('clowns'),true);
			
			$view->set('messaggio', '');
			$view->set('modelOpDB', $modelOpDB);
			
			parent::display();    
		}
			
		function aggClown(){
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
				
			$model = $this->getModel('clowns');
					
			$nome = JRequest::getVar('nome');
			$cognome = JRequest::getVar('cognome');
			$nomeClown = JRequest::getVar('nomeClown');
			$mailClown = JRequest::getVar('mailClown');
			$cellClown = JRequest::getVar('cellClown');
			
			if(!isset($nomeClown)){echo "ASSDS";}
			
			$aggiunta = $modelOpDB->aggClown($nome,$cognome,$nomeClown,$mailClown,$cellClown);
			
			$view = $this ->getView($vName,'html');	
			
			if( $aggiunta == 0){  //op. andata a buon fine
				$view->set('nome',ucfirst($nome));
				$view->set('cognome',ucfirst($cognome));
				$view->set('nomeClown',ucfirst($nomeClown));
				$view->set('modelOpDB', $modelOpDB);
				
				$view->set('messaggio','clown-aggiunto');
			} else if($aggiunta == 1){//clown già presente
				$view->set('nome',ucfirst($nome));
				$view->set('cognome',ucfirst($cognome));
				$view->set('nomeClown',ucfirst($nomeClown));
				$view->set('mailClown',ucfirst($mailClown));
				$view->set('modelOpDB', $modelOpDB);
				
				$view->set('messaggio','clown-già-in-elenco');
			} else if($aggiunta == 2){//clown già presente
				$view->set('nome',ucfirst($nome));
				$view->set('cognome',ucfirst($cognome));
				$view->set('nomeClown',ucfirst($nomeClown));
				$view->set('mailClown',ucfirst($mailClown));
				$view->set('modelOpDB', $modelOpDB);
				
				$view->set('messaggio','dati-insufficienti');
			} 
			
			else {			//stampa errore
				$view->set('messaggio','errore-clown-non-aggiunto');
			} 
			
			parent::display(); 
			//$view->display();
		}
			
		function rimClown(){
			require_once(JPATH_COMPONENT.DS.'models'.DS.'opdb.php');
			$modelOpDB=new OpDbModelOpDB;
				
			$model = $this->getModel('clowns');
			
			$nomeClown = JRequest::getVar('nomeClown');
		
			$rimoz = $modelOpDB->rimClown($nomeClown);
									
			$view = $this ->getView($vName,'html');		
			
			
			if( $rimoz == 0){  //op. andata a buon fine
				
				$view->set('nomeClown',ucfirst($nomeClown));
				$view->set('modelOpDB', $modelOpDB);
				$view->set('messaggio','clown-rimosso');
			} else if($rimoz == 1){//clown non presente
				
				$view->set('nomeClown',ucfirst($nomeClown));
				$view->set('modelOpDB', $modelOpDB);
				$view->set('messaggio','clown-non-presente');
			} 
			
			else {	//stampa errore
				$view->set('messaggio','errore-clown-non-rimosso');
				
				
			} 
						
			parent::display(); 
		//	$view->display();	
		
		}

}

?> 