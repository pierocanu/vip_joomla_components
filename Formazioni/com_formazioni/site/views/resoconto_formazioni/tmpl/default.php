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
?>

	<h1 style="text-align:center; font-size:28px;"><?php echo $this->Titolo;?> </h1>

	<style>
	#containerVisMesi{ 
		color:blue;
		text-align:center;
		padding:5px;
		max-width:1310px;
		margin: 0 auto;
		margin-bottom:20px;
	}
	
	.visualizzamese{
		text-align:center;
		background-color:#FFF;
		border: solid 1px;
		padding:10px;
		margin:auto;
		padding-left:15px;
		position:relative;
		max-width:410px;
		min-height:125px;
	}
	.titoloTabella{
	border:solid 2px #333;
	background-color:#0D3;
	color:#333;
	font-size:28px;
	margin-top:5px;
	padding:3px;
	}
	
	.titoloComunicazioni{
		font-family:Tahoma, Geneva, sans-serif;
		font-size:24px;
		text-decoration:underline;
		}
	
	.comunicazioni{
		background: #00cc66;
		border:solid 1px;
		display:block;
		padding:4px;
		width:90%;
		margin:0 auto;
		}
		
		/* Classi per colorare i turni */
	.giorni {
		color:#F00;
		font-weight:bold;
		}
	
	.extra{
		background-color:#FAFF8A;}
		
	.color{
		display:block;
		background-color:#DDF8FF;}	
		
	</style>

<?php
	
	$Mesi=$this->Data->getInfo('MesiAnno');
	
	$html = "<div id=\"containerVisMesi\"> \n";
	$html .= "\t<div class=\"visualizzamese\"> \n\t\t<h2 class=\"titoloTabella\">$this->meseAttuale </h2> \n\t\t<br />\n";
	$html .= $this->Funzioni->stampaFormaz($this->SuffissoDB,$this->NumMeseAttuale,$this->annoAttuale);
	$html .= "\t\t</div> \n\t\t\n";
	$html .="</div>\n\n";
	echo $html ;
	
	$date = $this->Funzioni->infoFormazioni("dateNonFormattate",01,2011,12,2011,$this->SuffissoDB);
	$presenze = $this->Funzioni->infoFormazioni("presenze",01,2011,12,2011,$this->SuffissoDB);
	
	$html='';
	for($n=0;$date[$n];$n++){
		
		$html .= $date[$n]." ";
		$html .= $presenze->$date[$n]."<br />";
			
		}
		

	
	//	 $this->Funzioni->infoFormazioni("contaPresenze",01,2011,12,2011,$this->SuffissoDB,"Angora");//CONTINUA QUI

		
	//echo $html;
	
	?>	
    <style>
		tr.intestazione{background-color:#09C; }
		td.dateIntestazione{font-size:12px;border-bottom:solid 1px; }
		td.totPresenze{font-size:12px; font-weight:bold; color:black;}
		td.tot{font-size:12px; color:black;}
	</style>
    
    <div style="padding:5px; width:620px; overflow:scroll; border: solid 2px black;">
        <table >
            
             <?php 
			 $html = $this->Funzioni->stampaResoconti(01,2011,12,2011,$this->SuffissoDB); 
			 echo $html;	
			 ?>  
               
            
        </table>
	</div>

<br />
<br />
<br />



