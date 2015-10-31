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

// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
* VisualizzaTurniServizi Model
*/

class TurniServiziModelGestisci_turni extends JModelItem{	 

	public function getSuffissoDB(){ //ritorna il suffisso del db impostato nel backend, alla creazione della voce nel menu

		$SuffissoDB = JRequest::getString("SuffissoDB");
		return $SuffissoDB;
	}

	public function getTitolo(){ 

			$Titolo = JRequest::getString("Titolo");
			return $Titolo;
	}

	public function getFormAggiungiClown(){
		$d = new DataModelData();
		$FormAggiungiClown=array(					
					'nome'=>'FormAggiungiClown',
					'campi' => array('type'=>'text', 'label'=>'il clown ', 'name'=>'nome'),
								array('type'=>'select', 'label'=>' nel servizio del ', 'name'=>'gg'),
								array('type'=>'select', 'label'=>' ', 'name'=>'mm' ),
								array('type'=>'checkbox', 'prelabel'=>"<br /> \n <br />", 'label'=>' Servizio "non ordinario"? ' ,'name'=>'altroCheck'),
								array('type'=>'text', 'label'=>'', 'name'=>'altro', 'value'=>'(specifica)'),
								array('type'=>'submit', 'label'=>"<br /> \n <br /> ", 'value'=>'Aggiungi clown!'),
								array('type'=>'hidden', 'name'=>'option', 'value'=>'com_turniservizi' ),
								array('type'=>'hidden', 'name'=>'task', 'value'=>'aggClown')

					);
		   
		return $FormAggiungiClown;
	}
	
	public function getFormRimuoviClown(){
		$d = new DataModelData();
		$FormRimuoviClown=array(					
					'nome'=>'FormRimuoviClown',
					'campi' => array('type'=>'text', 'label'=>'Rimuovi il clown ', 'name'=>'nome'),
								array('type'=>'select', 'label'=>'dal servizio di', 'name'=>'data'),
								array('type'=>'submit', 'label'=>"<br /> \n <br /> ", 'value'=>'Rimuovi clown!'),
								array('type'=>'hidden', 'name'=>'option', 'value'=>'com_turniservizi' ),
								array('type'=>'hidden', 'name'=>'task', 'value'=>'rimClown')

					);
		   
		return $FormRimuoviClown;
	
	}

	public function getFormModificaNote(){
		
		$FormModificaNote=array(					
					'nome'=>'FormModificaNote',
					'campi' => array('type'=>'textarea', 'label'=>'Note: <br/>',  'name'=>'note', 'value'=>'Nuovo testo delle note' , 'width'),
						       array('type'=>'select', 'label'=>' <br /> Mese: ', 'name'=>'mm' ),
							   array('type'=>'submit', 'label'=>"<br /> \n <br /> ", 'value'=>'Modifica'),					   
							   array('type'=>'hidden', 'name'=>'option', 'value'=>'com_turniservizi' ),
							   array('type'=>'hidden', 'name'=>'task', 'value'=>'modificaNote')
					);
			
		   
		return $FormModificaNote;
	
	}


}



?>