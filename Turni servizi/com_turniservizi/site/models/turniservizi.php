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

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class TurniServiziModelTurniServizi extends JModelItem{	 

	public function getTitolo(){ 

			$Titolo = JRequest::getString("Titolo");
			return $Titolo;
	}
	
	public function getSuffissoDB(){ //ritorna il suffisso del db impostato nel backend, alla creazione della voce nel menu

		$SuffissoDB = JRequest::getString("SuffissoDB");
		return $SuffissoDB;
	}

	public function getMailStaff(){

		$mail = JRequest::getString("MailStaff");
		return $mail;
	}
	
	public function getMailBcc(){
		$mail=JRequest::getString("MailBcc");
		return $mail;
	}
	
	public function getServizi(){ 

			$servizi=array_fill(0,6,0);
			
			if(JRequest::getInt('Lun') == 1){$servizi['0']='Lun';} else if(JRequest::getInt('Lun') == 2){$servizi['0']='UltimoLun';}
			if(JRequest::getInt('Mar') == 1){$servizi['1']='Mar';} else if(JRequest::getInt('Mar') == 2){$servizi['1']='UltimoMar';}
			if(JRequest::getInt('Mer') == 1){$servizi['2']='Mer';} else if(JRequest::getInt('Mer') == 2){$servizi['2']='UltimoMer';}
			if(JRequest::getInt('Gio') == 1){$servizi['3']='Gio';} else if(JRequest::getInt('Gio') == 2){$servizi['3']='UltimoGio';}
			if(JRequest::getInt('Ven') == 1){$servizi['4']='Ven';} else if(JRequest::getInt('Ven') == 2){$servizi['4']='UltimoVen';}
			if(JRequest::getInt('Sab') == 1){$servizi['5']='Sab';} else if(JRequest::getInt('Sab') == 2){$servizi['5']='UltimoSab';}
			if(JRequest::getInt('Dom') == 1){$servizi['6']='Dom';} else if(JRequest::getInt('Dom') == 2){$servizi['6']='UltimoDom';}						
			
			return $servizi;
	}
	

}



?>