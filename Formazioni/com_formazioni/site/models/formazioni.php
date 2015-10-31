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

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');


class FormazioniModelFormazioni extends JModelItem{	 

	public function getTitolo(){ 

			$Titolo = JRequest::getString("Titolo");
			return $Titolo;
	}
	
	public function getSuffissoDB(){ //ritorna il suffisso del db impostato nel backend, alla creazione della voce nel menu

		$SuffissoDB = JRequest::getString("SuffissoDB");
		return $SuffissoDB;
	}

}



?>