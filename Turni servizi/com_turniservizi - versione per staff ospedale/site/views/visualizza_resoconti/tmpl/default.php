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
?>


<br />
<br />

<h1 style="text-align:center; font-size:28px;"><?php echo $this->Titolo;?> </h1>
<style>
.titoloMessaggio{
	display:block; 
	background-color:#06C; 
	width: 80%; 
	margin:auto; 
	text-align:center; 
	color:white; 
	font-size:28px;
	}
	
.titoloErrore{
	display:block; 
	background-color:black; 
	width: 80%; 
	margin:auto; 
	text-align:center; 
	color:red; 
	font-size:28px;
	}

</style>

<?php if(strcmp($this->messaggio , "mail_inviata")== '0'):  //Messaggio di conferma invio mail (per inserimento turno) ?>		
<div align="center">
    <br />
    <div class="titoloMessaggio">Mail inviata!</div>
    <br />
    <p> Il tuo messaggio è stato correttamente inviato, a breve lo staff provvederà alla tua richiesta. Grazie</p>
    <br />
</div>
<?php endif; ?>

<?php if($this->messaggio  == 'erroreMail'): ?>

<div align=\"center\">
    <br />
    <br />
    <br />
    <br />
	<div class="titoloErrore">Errore...</div>
    <br />
    <p>Non è stato possibile inviare la tua richiesta...</p>
    <p>Potrebbe essere un errore momentaneo, per favore riprova più tardi e se si ripresenta contatta l'amministratore.</p>
    <p>Grazie!</p>
    <br />
</div>	
<?php endif; ?>

<?php if($this->messaggio  == 'errore'): //Messaggio di errore generico ?>

<div align=\"center\">
    <br />
    <br />
    <br />
    <br />
	<div class="titoloErrore"> ERRORE!! </div>
    <br />
    <p > Qualcosa non è andato per il verso giusto!! Per favore riprova, e se il problema persiste contatta l'amministratore. Grazie </p>
    <br />
</div>				
<?php endif; ?>

	
	<style>
	#containerVisMesi{ 
		text-align:center;
		padding:5px;
		max-width:1310px;
		margin: 0 auto;
		margin-bottom:20px;
	}
	
	.visualizzamese{
	text-align:left;
	background-color:#FFF;
	border: solid 1px;
	padding:10px;
	margin:auto;
	padding-left:15px;
	position:relative;
	max-width:610px;
	min-height:320px;
	}
	.titoloTabella{
	border:solid 2px #333;
	background-color:#0D3;
	color:#333;
	font-size:28px;
	margin-top:5px;
	}
	
	.note{
		background: #00cc66;
		border:solid 1px;
		display:block;
		padding:4px;
		width:92%;
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
	
	.numOld{color:#999; 
		font-size:10px;}
	
	.num{color:#999; 
		font-size:14px;}
	
	.uno{color:black}		
	.bis{color:blue}
	.recidivo{color:fuchsia}
	.urka{color:red;}
	.eroe{color:gold;}
	
	</style> 
    
<?php
	$html .= "<div id=\"containerVisMesi\"> \n";
	$html .= "\t<div class=\"visualizzamese\"> \n\t\t<h2 class=\"titoloTabella\">$this->meseAttuale </h2> \n\t\t<br />\n";
	$serviziNelMese = $this->Funzioni->getServiziNelMese($this->NumMeseAttuale,$this->annoAttuale);
	$html .= $this->Funzioni->stampaMese($this->NumMeseAttuale,$this->annoAttuale,$serviziNelMese,$this->Suffisso);
	$html .= "\t\t</div> \n\t\t<br />\n";
	echo $html ;
	
//Note
	echo "<div style=\"text-align:left; text-decoration:underline;\">NOTE per i servizi di ".$this->meseAttuale,": </div><br />";
	$html = "<div class=\"note\">";
	$html .= $this->Funzioni->stampaNote($this->NumMeseAttuale,$this->annoAttuale,$this->Suffisso);
	$html .= "</div> \n<br /> \n<br />";
	echo $html;
	
	$html = "\n\t<div class=\"visualizzamese\"> \n\t\t<h2 class=\"titoloTabella\">$this->meseProssimo </h2> \n\t\t<br />\n";
	$serviziNelMeseProssimo = $this->Funzioni->getServiziNelMese($this->NumMeseProssimo,$this->annoMeseProssimo);
	$html .= $this->Funzioni->stampaMese($this->NumMeseProssimo,$this->annoMeseProssimo,$serviziNelMeseProssimo,$this->Suffisso);
	$html .= "\t</div> \n\t<br />\n";
	$html .="</div>\n\n";
	echo $html;	

//Note
	echo "<div style=\"text-align:left; text-decoration:underline;\">NOTE per i servizi di ".$this->meseProssimo,": </div><br />";
	$html = "<div class=\"note\">";
	$html .= $this->Funzioni->stampaNote($this->NumMeseProssimo,$this->annoMeseProssimo,$this->Suffisso);
	$html .= "</div> \n<br /> \n<br />";
	echo $html;


	?>
	
	<style>
	/* Form */

	.FormRichiestaInserimento{
		color:black;
		border:solid 2px #003;
		background-color:#FC6;
		text-align:center;
		font-size:18px;
		padding:15px;
		margin: 0 auto;
		}
	.textar{
		width="55px;"
		}
	</style>

	<?php
	echo '<p style="clear:both; padding-top:25px; margin:0; text-align:center; color:#000; font-size:20px; font-weight:normal;" >Iscrizione ai turni:</p>
			
		<div style="clear:both; text-align:center; color:black; margin-top:0; font-size:14px;">Puoi iscriverti al turno compilando i campi qui sotto e premenedo INVIA <br />
		(Verr&aacute; inviata una mail allo staff ospedale che proveder&aacute; ad insirerti in calendario.) </div>
		
		<br />';
	
	$form = '<form name="%s" method="post" id="%s">';
	$lab = ' %s ';//'<label for="%s">%s</label>';
	$in = '<input type="%s" name="%s" value="%s" style="font-weight:bold;"/>'; 
	$hid = '<input type="%s" name="%s" value="%s" >';
	$sel = '<select name="%s" style="font-weight:bold;"> ';
	$selEnd = '</select>';
	$opt = '<option>%s</option> ';
	$optSelected = '<option selected= "selected" >%s</option> ';
	$tarea = '<textarea name="%s" rows="%s" cols="%s"  style="font-weight:bold; text-align:center; ">Nessuna comunicazione aggiuntiva.</textarea>';
	$sub = '<br />
			<br />
			<input type="%s" name="%s" style="color:blue; font-weight:bold; font-family:\'Comic Sans MS\', \'AR CHRISTY\', Calibri, arial;"/>'; 
	
	
	$html = '<div class="FormRichiestaInserimento">';
	$html .= sprintf($form, $this->FormRichiestaInserimento['nome'], $this->FormRichiestaInserimento['id']); 
	foreach ($this->FormRichiestaInserimento as $campo){
		
		switch($campo['type']){
			
			case text:
				$html = $html.sprintf($lab, $campo['label']).sprintf($in, $campo['type'], $campo['name'], $campo['value']); 
				break;
				
			case hidden:
				$html = $html.sprintf($in, $campo['type'], $campo['name'], $campo['value']); 
				break;	
				
			case submit:
				$html = $html.sprintf($lab, $campo['label']).sprintf($in, $campo['type'], $campo['name'], $campo['value']);  
				break;		
				
			case select:
				$html = $html.sprintf($lab, $campo['label']).sprintf($sel, $campo['name']);
				
				if($campo['name'] == 'gg'){
					for($n=1; $n<=31 ; $n++){
						
						if($n == $this->numGiornoAttuale){$html = $html.sprintf($optSelected, $n);}
						else {$html = $html.sprintf($opt, $n);}
						}
				}
				
				if($campo['name'] == 'mm'){
					$html = $html.sprintf($opt, $this->meseAttuale);
					$html = $html.sprintf($opt, $this->meseProssimo);
									
				}
				
				$html = $html.$selEnd;
				
				break;
				
			case textarea:
				$html = $html.sprintf($lab, $campo['label']).sprintf($tarea, $campo['name'],5,45);
				break;			
					
			}
	
		}
	$html .= '</form> 
			</div>'; 
	
	echo $html ;
	
?>

<br />
<br />
<br />



