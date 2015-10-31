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

// import Joomla view library
jimport('joomla.application.component.view');

class FormazioniViewgestisci_formazioni extends JView
{	
	function _construct(){
		parent::_construct();
	}
	
	
	function set($varName, $var){
		$this->$varName=$var;
		}
	
	// Overwriting JView display method
	function display($tpl = null){
		
			$this->titolo = $this->get('Titolo');  //Prende Titolo dal model attuale tramite il GET
				
			$this->num_giorno_attuale = $this->modelData->getInfo('NumGiornoAttuale');						
			
			$this->num_mese_attuale = $this->modelData->getInfo('NumMeseAttuale');
			$this->mese_attuale = $this->modelData->getInfo('MeseAttuale');
			$this->anno_attuale = $this->modelData->getInfo('AnnoAttuale');	
			
			$this->num_mese_prossimo = $this->modelData->getInfo('NumMeseProssimo');
			$this->mese_prossimo = $this->modelData->getInfo('MeseProssimo');
			$this->anno_mese_prossimo = $this->modelData->getInfo('AnnoMeseProssimo');	
			
			$this->num_mese_successivo_al_prossimo = $this->modelData->getInfo('NumMeseSuccessivoAlProssimo');
			$this->mese_successivo_al_prossimo = $this->modelData->getInfo('MeseSuccessivoAlProssimo');
			$this->anno_mese_successivo_al_prossimo = $this->modelData->getInfo('AnnoMeseSuccessivoAlProssimo');	

			$this->giorni_settimana = $this->modelData->getInfo('GiorniSettimana');
			$this->mesi_anno = $this->modelData->getInfo('MesiAnno');		
			
			$this->dati_formazioni = $this->modelOpDB->leggiFormazioni($this->num_mese_attuale,$this->anno_attuale,$this->num_mese_successivo_al_prossimo,$this->anno_mese_successivo_al_prossimo);
			$this->comunicazioni = $this->modelOpDB->leggiComunicazioni();
			
			
			// Check for errors.
			if (count($errors = $this->get('Errors')))
			{
				JError::raiseError(500, implode('<br />', $errors));
				return false;
			}
			
			// Display the view
			parent::display($tpl);
	}
	
}
	
?>