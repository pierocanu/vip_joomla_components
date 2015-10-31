<?php

/*
 * @package Joomla 1.6
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

/**
* Visualizzaformazioni Model
*/

class FormazioniModelresoconto_formazioni extends JModelItem{	 

	public function getSuffissoDB(){ //ritorna il suffisso del db impostato nel backend, alla creazione della voce nel menu

		$SuffissoDB = JRequest::getString("SuffissoDB");
		return $SuffissoDB;
	}

	public function getTitolo(){ 

			$Titolo = JRequest::getString("Titolo");
			return $Titolo;
	}
	

	public function getFormAggiungiFormaz(){
		$d = new DataModelData();
		$FormAggiungiFormaz=array(					
					'nome'=>'FormAggiungiFormaz',
					'campi' =>	array('type'=>'select', 'label'=>'Data nuova formazione:  ', 'name'=>'gg'),
								array('type'=>'select', 'label'=>'', 'name'=>'mm' ),
								array('type'=>'textarea', 'label'=>'<br />Note sulla formazione:<br /> ', 'name'=>'note'),
								array('type'=>'submit', 'label'=>"<br /> \n <br /> ", 'value'=>'Aggiungi formazione'),
								array('type'=>'hidden', 'name'=>'option', 'value'=>'com_formazioni' ),
								array('type'=>'hidden', 'name'=>'task', 'value'=>'aggFormaz')

					);
		   
		return $FormAggiungiFormaz;
	}
	
	public function getFormRimuoviFormaz(){
		$d = new DataModelData();
		$FormRimuoviFormaz=array(					
					'nome'=>'FormAggiungiFormaz',
					'campi' =>	array('type'=>'select', 'label'=>'Cancella la formazione del', 'name'=>'data' ),
								array('type'=>'submit', 'label'=>"<br /> \n <br /> ", 'value'=>'Cancella formazione'),
								array('type'=>'hidden', 'name'=>'option', 'value'=>'com_formazioni' ),
								array('type'=>'hidden', 'name'=>'task', 'value'=>'rimFormaz')

					);
		   
		return $FormRimuoviFormaz;
	}
	
	
	public function getFormModificaNoteFormaz(){
		$d = new DataModelData();
	
		$FormModificaFormaz=array(					
					'nome'=>'FormModificaFormaz',
					'campi' =>	array('type'=>'select', 'label'=>'Modifica le note sulla formazione del:', 'name'=>'data' ),
								array('type'=>'textarea', 'label'=>': <br/>',  'name'=>'note', 'value'=>'' , 'width'),
								array('type'=>'submit', 'label'=>"<br /> \n <br /> ", 'value'=>'Modifica'),
								array('type'=>'hidden', 'name'=>'option', 'value'=>'com_formazioni' ),
								array('type'=>'hidden', 'name'=>'task', 'value'=>'modNoteFormaz')

					);
		   
		return $FormModificaFormaz;
	}
	
	

	public function getFormModificaComunicaz(){
		
		$FormModificaComunicaz=array(					
					'nome'=>'FormModificaComunicaz',
					'campi' => array('type'=>'textarea', 'label'=>'Digita il testo: <br/>',  'name'=>'comunicazioni', 'value'=>'' , 'width'),
						       array('type'=>'submit', 'label'=>"<br /> \n <br /> ", 'value'=>'Modifica'),					   
							   array('type'=>'hidden', 'name'=>'option', 'value'=>'com_formazioni' ),
							   array('type'=>'hidden', 'name'=>'task', 'value'=>'modComunicaz')
					);
			
		   
		return $FormModificaComunicaz;
	
	}


}



?>