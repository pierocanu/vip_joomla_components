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

<?php if(strcmp($this->messaggio , "clown_aggiunto") == '0'): //Messaggio di conferma clown aggiunto in servizio ?> 
<div align="center">
    <br />
    <div class="titoloMessaggio">Clown aggiunto.</div>
    <br />
    <p > Il clown <span style="font-weight:bolder; font-size:20px;"><?php echo $this->nomeClown ?></span> &eacute; stato inserito il giorno <span style="font-weight:bolder; font-size:20px;"><?php echo $this->giorno.' '.$this->mese ;?></span>. </p>
    <br />
</div>
<?php endif; ?>
	
<?php if($this->messaggio  == 'clown_rimosso'): //Messaggio di conferma clown rimosso da un servizio ?>

<div align=\"center\">
    <br />
	<div class="titoloMessaggio">Clown rimosso.</div>
    <br />
    <p > Il clown <span style="font-weight:bolder; font-size:20px;"><?php echo $this->nomeClown ?></span> &eacute; stato rimosso dal giorno <span style="font-weight:bolder; font-size:20px;"><?php echo $this->giorno.' '.$this->mese ;?></span>. </p>
    <br />
</div>	
<?php endif; ?>

<?php if($this->messaggio  == 'nota_modificata'): //Messaggio di conferma clown rimosso da un servizio ?>

<div align=\"center\">
    <br />
	<div class="titoloMessaggio">Nota modificata.</div>
    <br />
    <p > La nota è stata modificata.</span>. </p>
    <br />
</div>	
<?php endif; ?>

<?php if($this->messaggio  == 'erroreClownNonAggiunto' || $this->pag == 'erroreClownNonRimosso'): ?>

<div align=\"center\">
    <br />
    <br />
    <br />
    <br />
	<div class="titoloErrore">Errore...</div>
    <br />
    <p>Non è stato possibile <?php if($this->pag == 'erroreClownNonAggiunto') {echo 'aggiungere' ;} else if($this->pag == 'erroreClownNonRimosso') {echo 'rimuovere';}?> il clown da te richiesto.</p>
    <p>Sembra che tu abbia sbagliato qualcosa, ad esempio il nome del clown o la data, per favore controlla e riprova, se il problema persiste contatta l'amministratore </p>
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



//Visualizza form di gestione
	
	?>
	<style>
	/* Gestione turni */
	
	#containerGestione{
		display:block;
		border:solid 2px ;
		background-color:#FC6;
		padding:5px;
		margin: 0 auto;
		margin-bottom:20px;
		min-height:350px;
		}
	
	
	.formGestione{
		color:black;
		border:solid 2px #666;
		/*float:left;*/
		background-color:#ABEAF8;
		width: 90%;
		min-height: 150px;
		margin: 10px;
		margin-left:auto;
		margin-right:auto; 
		padding: 20px;
		}
		
	.istruzioniGestione{
		color:black;
		text-indent:20px;
		padding-left: 22px;
		padding-right: 22px;	
		}

	</style>       
    
	<?php
	$html = "<div id=\"containerGestione\"> \n";
	
	$form = "<form name=\"%s\" method=\"post\">\n";
	$lab = "\t\t %s ";
	$in = "\n\t\t<input type=\"%s\" name=\"%s\" value=\"%s\" style=\"font-weight:bold;\"/>\n"; 
	$inCheck = "\n\t\t<input type=\"%s\" name=\"%s\" style=\"font-weight:bold;\"/>\n"; 
	$hid = "\n\t\t<input type=\"%s\" name=\"%s\" value=\"%s\" >";
	$sel = "\n\t\t <select name=\"%s\" style=\"font-weight:bold;\">";
	$selEnd = "\n\t\t</select>\n";
	$optGroup = "<optgroup label=\"%s\"></optgroup>";
	$opt = "\n\t\t<option>%s</option> ";
	$optSelected = "\n\t\t<option selected= \"selected\" >%s</option> ";
	$sub = "\n\t\t<br /> \n<br /> \n<input type=\"%s\" name=\"%s\" style=\"color:blue; font-weight:bold; font-family:\'Comic Sans MS\', \'AR CHRISTY\', Calibri, arial;\" />"; 
	$textarea = "<textarea name=\"%s\" cols=\"45\" rows=\"10\">%s</textarea>";
	
	
	//Aggiunta clown
	$html .= "\t<div class=\"formGestione\"> \n\t\t<h4 style=\"clear:both; text-align:center; text-decoration:underline; color:#000; font-size:24px; font-weight:normal;\">Aggiungi clown</h4>\n\n";

	$html .= "\t\t".sprintf($form, $this->FormAggiungiClown['nome']); 
	foreach ($this->FormAggiungiClown as $campo){
		
		switch($campo['type']){
			
			case text :
				$html = $html.sprintf($lab, $campo['label']).sprintf($in, $campo['type'], $campo['name'], $campo['value']); 
				break;
				
			case checkbox :
				$html = $html.sprintf($lab, $campo['prelabel']).sprintf($inCheck, $campo['type'], $campo['name'] ).sprintf($lab, $campo['label']); 
				break;	
				
			case submit:
				$html = $html.sprintf($lab, $campo['label']).sprintf($in, $campo['type'], $campo['name'], $campo['value']);  
				break;				
								
			case hidden:
				$html = $html.sprintf($in, $campo['type'], $campo['name'], $campo['value']); 
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

			}
	
	}
	$html = $html."\n\t\t</form> \n\t</div>\n ";
	echo $html ;
	
		//Rimozione clown
	$html = "\t<div class=\"formGestione\"> \n\t\t<h4 style=\"clear:both; text-align:center; text-decoration:underline; color:#000; font-size:24px; font-weight:normal;\">Rimuovi clown</h4>\n\n";


	$html .= sprintf($form, $this->FormRimuoviClown['nome']); 
	foreach ($this->FormRimuoviClown as $campo){
		
		switch($campo['type']){
			
			case text :
				$html = $html.sprintf($lab, $campo['label']).sprintf($in, $campo['type'], $campo['name'], $campo['value']); 
				break;
				
			case checkbox :
				$html = $html.sprintf($lab, $campo['label']).sprintf($in, $campo['type'], $campo['name'], $campo['value']); 
				break;	
				
			case submit:
				$html = $html.sprintf($lab, $campo['label']).sprintf($in, $campo['type'], $campo['name'], $campo['value']);  
				break;				
								
			case hidden:
				$html = $html.sprintf($in, $campo['type'], $campo['name'], $campo['value']); 
				break;			
				
			case select:
				$html = $html.sprintf($lab, $campo['label']).sprintf($sel, $campo['name']);
		
				if($campo['name'] == 'data'){
					$opzioni=$this->Funzioni->StampaDateMese($this->NumMeseAttuale,$this->annoAttuale,$serviziNelMese,$this->Suffisso);
					
					$html = $html.sprintf($optGroup, $this->meseAttuale);
					$t=0;
					for($m=0;$opzioni[$m]!=NULL;$m++){
						
						$html = $html.sprintf($opt, $opzioni[$m]);
						
					}
					
					$opzioni=$this->Funzioni->StampaDateMese($this->NumMeseProssimo,$this->annoMeseProssimo, $serviziNelMese,$this->Suffisso);
							
					$html = $html.sprintf($optGroup, $this->meseProssimo);
					$t=0;
					for($m=0;$opzioni[$m]!=NULL;$m++){
						
						$html = $html.sprintf($opt, $opzioni[$m]);
						
					}
				
														
				}
				
				$html = $html.$selEnd;

				break;


			
					
			}
			
			
	
	}
	$html = $html."\n\t\t</form> \n\t</div>\n";
	echo $html ;
	
	
	//Modifica note
	$html = "\t<div class=\"formGestione\"> \n\t\t<h4 style=\"clear:both; text-align:center; text-decoration:underline; color:#000; font-size:24px; font-weight:normal;\">Modifica note</h4>\n\n";


	$html .= sprintf($form, $this->FormModificaNote['nome']); 
	foreach ($this->FormModificaNote as $campo){
		
		switch($campo['type']){
			
			case textarea :
				$html = $html.sprintf($lab, $campo['label']).sprintf($textarea, $campo['name'], $campo['value']); 
				break;						
				
			case submit:
				$html = $html.sprintf($lab, $campo['label']).sprintf($in, $campo['type'], $campo['name'], $campo['value']);  
				break;				
								
			case hidden:
				$html = $html.sprintf($in, $campo['type'], $campo['name'], $campo['value']); 
				break;			
						
			case select:
				$html = $html.sprintf($lab, $campo['label']).sprintf($sel, $campo['name']);				
				
				if($campo['name'] == 'mm'){
					$html = $html.sprintf($opt, $this->meseAttuale);
					$html = $html.sprintf($opt, $this->meseProssimo);
				}
				
				$html = $html.$selEnd;
				break;
						
		}
			
			
	
	}
	$html = $html."\n\t\t</form> \n\t</div>\n";
	echo $html ;
	

	?>		
    <style>
		.istruzioniGestione{
			text-align:justify;
			color:black;
			text-indent:20px;
			padding-left: 22px;
			padding-right: 22px;	
		}
    </style>
    
	<p></p>
    <p class="istruzioniGestione">
        <span style="font-weight:bold;">Istruzioni:</span>
        <br />-Per aggiungere/rimuovere un clown da un turno basta inserire il suo nome , UNO ALLA VOLTA.
        <br />-Non inserire il numero di servizi in quel mese del clown, il numero verr&aacute; visualizzato in automatico.
        <br />-Per modificare un servizio "extra" spuntare la casella e inserire il servizio (ad es. gnr).		
    </p>
        
    
    <p style="margin-right: 22px; padding-right: 22px; text-align:right; color:black;">-Piero-</p>

	</div> 
	
<br />
<br />
<br />



