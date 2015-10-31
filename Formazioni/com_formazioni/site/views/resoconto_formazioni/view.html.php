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

// import Joomla view library
jimport('joomla.application.component.view');

class FormazioniViewresoconto_formazioni extends JView
{	
	function _construct(){ // se la variabile pag ha valore diverso viene visualizzata sul sito un contenuto diverso
		parent::_construct();
	}
	
	
	function set($varName, $var){
		$this->$varName=$var;
		}
	
	// Overwriting JView display method
	function display($tpl = null){
		
		
			$this->Titolo = $this->get('Titolo');  //Prende Titolo dal model attuale tramite il GET
			$this->SuffissoDB = $this->get('suffissoDB');
			
			$this->numGiornoAttuale = $this->Data->getInfo('NumGiornoAttuale');						
			$this->NumMeseAttuale = $this->Data->getInfo('NumMeseAttuale');
			$this->meseAttuale = $this->Data->getInfo('MeseAttuale');
			$this->annoAttuale = $this->Data->getInfo('AnnoAttuale');	

			$this->mesiAnno = $this->Data->getInfo('MesiAnno');		

			
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