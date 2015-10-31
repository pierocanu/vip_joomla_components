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

class FunctionsModelFunctions extends JModelItem{ //tutte le funzioni, comprese quelle che preparano l'output per view

	
	
	function stampaFormaz($SuffissoDB,$numMese,$anno){

		$dbModel = new OpDbModelOpDb();
		$Data = new DataModelData();

		$formazioni = $dbModel->leggiFormaz($SuffissoDB,$numMese,$anno);
		

		if($formazioni==NULL){
			return "Date formazioni non ancora inserite";}
		
		else {
			$html='';
			$Sett=$Data->getInfo('GiorniSettimana');
			$giorniMese=$Data->days_in_month($numMese, $anno);
			$t=0;
			for($n=0;$n<=$giorniMese;$n++){
				if($formazioni[$n]!='0' && $formazioni[$n]!=NULL){
					$g=$Sett[date("w",strtotime("$anno-$numMese-$n"))];
					$html.="\t\t\t";
					if($t%2==0){$html.="<span class=\"color\">";}
					$html.="<span class=\"giorni\">".$n." ".$g."</span> ";
					if(strcasecmp($formazioni[$n],"noNote")=='0'){
						$html.="\n\t\t\t<br /> \n";
						}else{
							$html.=$formazioni[$n]."\n\t\t\t<br /> \n";
						}
					if($t%2==0){$html.="</span>";}
					$t++;
				}
			}
			return $html;
		}
		
	}

	function infoFormazioni($info,$meseDa,$annoDa,$meseA,$annoA,$SuffissoDB,$clown=NULL){
		
		$dbModel = new OpDbModelOpDb();
		$Data = new DataModelData();
		
		$formazioni=$dbModel->leggiFormazioni($meseDa,$annoDa,$meseA,$annoA,$SuffissoDB);

		if($info=="dateNonFormattate"){
			return $formazioni[0];
			}
			

		else if($info=="date"){
			
			$tmp=$formazioni[0];
			$mesi=$Data->getInfo('MesiAnno');
			
			for ($n=0;$tmp[$n];$n++){
				$dataTmp=explode('-',$tmp[$n]);
				if($dataTmp[1]<=9){ 
					$tmp[$n]=$dataTmp[2]." ".$mesi[substr($dataTmp[1],1)];
					} else {
						$tmp[$n]=$dataTmp[2]." ".$mesi[$dataTmp[1]];
						}
				}
			
			return $tmp;
			}
			

		else if($info=="presenze"){

			$formaz=$formazioni[0];
			$presenze=$formazioni[2];
			
			for ($n=0;$formaz[$n];$n++){
				$ret->$formaz[$n]=$presenze[$n];
			}
							
			return $ret;
			}
		else if($info=="contaPresenze"){
			
			$ret=$dbModel->contaPresenze($clown,$meseDa,$annoDa,$meseA,$annoA,$SuffissoDB);
			return $ret;
			}
		return 1;
		}
		


	function stampaComunicaz($SuffissoDB){

		$dbModel = new OpDbModelOpDb();

		$comunicazioni= $dbModel->leggiComunicazioni($SuffissoDB);
		
		return $comunicazioni;
		}

	function stampaResoconti($meseDa,$annoDa,$meseA,$annoA,$SuffissoDB){
		$dbModel = new OpDbModelOpDb();
		
		$date=$this->infoFormazioni("dateNonFormattate",$meseDa,$annoDa,$meseA,$annoA,$SuffissoDB);
		$nomi=$this->infoFormazioni("presenze",$meseDa,$annoDa,$meseA,$annoA,$SuffissoDB);
		$clowns=$dbModel->leggiClowns($SuffissoDB);
		
		$html="<tr class=\"intestazione\"> \n";
		$html.='<td> Nome clown</td>';
		
		$arrayMesi=array('Gen','Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic');
		for($n=0;$date[$n];$n++){
			$d=explode('-',$date[$n]);
			$num=$d[1];
			if($num<=9){$num=substr($num,1);} 
			$html.="<td class=\"dateIntestazione\"> $d[2] $arrayMesi[$num] $d[0]</td> \n"; 
			}
		$html.="</tr> \n<tr>";
		
		
		
		for($n=0;$clowns[$n]!=NULL;$n++){
			$html.="<td>".$clowns[$n]."</td>";
			$presenze=$dbModel->calcolaPresenze($clowns[$n],$meseDa,$annoDa,$meseA,$annoA,$SuffissoDB);
			
			for($t=0;$date[$t];$t++){
					$html.="<td>".$presenze->$date[$t]."</td>";
				}
			$html.= "</tr>";
			
		}
				
		$html.= "<tr>";
		$html.="<td class=\"totPresenze\">Totale presenze</td>";
		for($n=0;$date[$n];$n++){
			$temp=explode(' ',$nomi->$date[$n]);
			if($nomi->$date[$n]=="noPresenze"){$count=0;}else{$count=count($temp);}
			$html.="<td  class=\"tot\">".$count."</td>";
			}
		$html.= "</tr>";
		
		return $html;	
		
		
	}
	


}

?>