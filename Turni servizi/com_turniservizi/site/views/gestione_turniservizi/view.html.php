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

// import Joomla view library
jimport('joomla.application.component.view');

class TurniServiziViewgestione_turniservizi extends JView
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
			$this->num_mese_attuale=$this->modelData->getInfo('NumMeseAttuale');
			$this->num_mese_prossimo=$this->modelData->getInfo('NumMeseProssimo');
			$this->mese_attuale = $this->modelData->getInfo('MeseAttuale');
			$this->mese_prossimo = $this->modelData->getInfo('MeseProssimo');
			$this->anno_attuale = $this->modelData->getInfo('AnnoAttuale');	
			$this->anno_mese_prossimo = $this->modelData->getInfo('AnnoMeseProssimo');
			
			$this->giorni_mese_attuale = $this->modelData->days_in_month($this->num_mese_attuale, $this->anno_attuale);
			$this->giorni_mese_prossimo = $this->modelData->days_in_month($this->num_mese_prossimo, $this->anno_mese_prossimo);
			
			$this->servizi_mese_attuale=$this->modelFunzioni->servizi($this->num_mese_attuale, $this->anno_attuale);
			$this->servizi_mese_prossimo=$this->modelFunzioni->servizi($this->num_mese_prossimo, $this->anno_mese_prossimo);
			
			$this->presenze_mese_attuale=$this->modelOpDB->presenzeServizi($this->num_mese_attuale, $this->anno_attuale);
			$this->presenze_mese_attuale_formattato=$this->modelFunzioni->formattaPresenze($this->presenze_mese_attuale);
			
			$this->presenze_mese_prossimo=$this->modelOpDB->presenzeServizi($this->num_mese_prossimo, $this->anno_mese_prossimo);
			$this->presenze_mese_prossimo_formattato=$this->modelFunzioni->formattaPresenze($this->presenze_mese_prossimo);
			
			$this->note_mese_attuale=$this->modelOpDB->leggiNote($this->num_mese_attuale, $this->anno_attuale);
			$this->note_mese_prossimo=$this->modelOpDB->leggiNote($this->num_mese_prossimo, $this->anno_mese_prossimo);

			$Clowns = $this->modelOpDB->leggiElencoClowns();
			$this->nomi =  $Clowns['nomi'];
			$this->cognomi =  $Clowns['cognomi'];
			$this->nomiClowns =  $Clowns['nomiClowns'];
			$this->mail =  $Clowns['nomiClowns'];
			
			
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