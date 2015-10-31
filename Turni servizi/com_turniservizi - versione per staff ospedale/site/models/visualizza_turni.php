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

class TurniServiziModelVisualizza_turni extends JModelItem{	 

	public function getSuffissoDB(){ //ritorna il suffisso del db impostato nel backend, alla creazione della voce nel menu

		$SuffissoDB = JRequest::getString("SuffissoDB");
		return $SuffissoDB;
	}
	
	public function getTitolo(){ 

			$Titolo = JRequest::getString("Titolo");
			return $Titolo;
	}
	
	public function getMailStaff(){

		$mail = JRequest::getString("MailStaff");
		return $mail;
	}
	
	public function getFormRichiestaInserimento(){

		require_once(JPATH_COMPONENT.DS.'models'.DS.'data.php');
		$d=new DataModelData();	
		$mail = $this->getMailStaff();
		
		$FormRichiestaInserimento=array(					
					'nome'=>'FormRichiestaInserimento',
					'campi' => array('type'=>'text', 'label'=>'Sono ',  'name'=>'nomeClown', 'value'=>'(Inserisci il tuo nome)'),
								array('type'=>'text', 'label'=>', la mia mail è: ',  'name'=>'mailClown', 'value'=>'(Inserisci la tua mail)'),
								array('type'=>'select', 'label'=>' <br />e vorrei iscrivermi per il turno del', 'name'=>'gg' ),
								array('type'=>'select', 'label'=>' ', 'name'=>'mm', 'value'=>'(Inserisci il tuo nome)' , 'option' => array($d->getInfo(MeseAttuale),$d->getInfo(MeseProssimo))),
								array('type'=>'textarea', 'label'=>'<br /> <br /> <span style="font-size:14px;">(Per comunicazioni aggiuntive puoi compilare questo campo, che verrà aggiunto alla mail):</span> ', 'name'=>'agg'),
								array('type'=>'submit', 'label' =>'<br /> <br />', 'name'=>'Invia', 'value' => 'Invia'),
								array('type'=>'hidden', 'name'=>'option', 'value'=>'com_turniservizi' ),
								array('type'=>'hidden', 'name'=>'task', 'value'=>'inviaRichiestaInserimento'),
								array('type'=>'hidden', 'name'=>'mail', 'value'=>$mail),

					);
			
		   
		return $FormRichiestaInserimento;
	
	}
	


}



?>