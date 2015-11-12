<?php

/*
 * @package Joomla 1.7
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component clowns
 * @copyright Copyright (C) Piero Canu 
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>


<script type="text/javascript">

    window.addEvent('domready', function () {
        $$('.hasTip').each(function (el) {
            var title = el.get('title');
            if (title) {
                var parts = title.split('::', 2);
                el.store('tip:title', parts[0]);
                el.store('tip:text', parts[1]);
            }
        });
        var JTooltips = new Tips($$('.hasTip'), {maxTitleChars: 50, fixed: false});
    });
    function keepAlive() {
        var myAjax = new Request({method: "get", url: "index.php"}).send();
    }
    window.addEvent("domready", function () {
        keepAlive.periodical(840000);
    });
</script>


<h1 style="text-align:center; font-size:28px;">
    <?php  echo $this->titolo; ?>
</h1>

<?php

if (isset($this->actionResult) && $this->actionResult >= -2)
{
    switch ($this->actionResult)
    {
        // Messaggio di conferma clown aggiunto in servizio
        case OpDBModelOpDB::AGG_CLOWN_CONFIRMED:

            $nomeClownMsg = $this->nomeClown;
            if (($this->nome != null && $this->nome != "") || ($this->cognome != null && $this->cognome != ""))
            {
                $nomeClownMsg .= " (";
                if ($this->nome != null && $this->nome != "")
                {
                    $nomeClownMsg .= $this->nome;
                }

                if ($this->cognome != null && $this->cognome != "")
                {
                    if ($this->nome != null && $this->nome != "")
                    {
                        $nomeClownMsg .= " ";
                    }
                    $nomeClownMsg .= $this->cognome;
                }
                $nomeClownMsg .= ")";
            }

            $cssTitoloClass = "titoloMessaggio";
            $titoloMessaggio = "Clown aggiunto";
            $sottoTitoloMessaggio = "Il clown <span class=\"datiMessaggio\">" . $nomeClownMsg .
                "</span> &eacute; stato inserito nell'elenco.";
            break;

        // Messaggio di conferma clown rimosso da un servizio
        case OpDBModelOpDB::RIM_CLOWN_CONFIRMED:

            $cssTitoloClass = "titoloMessaggio";
            $titoloMessaggio = "Clown rimosso";
            $sottoTitoloMessaggio = "Il clown <span class=\"datiMessaggio\">" . $this->nomeClown .
                "</span> &eacute; stato rimosso";
            break;

        // Messaggio di conferma clown rimosso da un servizio
        case OpDBModelOpDB::MOD_CLOWN_CONFIRMED:

            $cssTitoloClass = "titoloMessaggio";
            $titoloMessaggio = "Clown modificato";
            $sottoTitoloMessaggio = "Il dati del clown <span class=\"datiMessaggio\">" . $this->nomeClown .
                "</span> sono stati modificati come richiesto.";
            break;

        // Errore clown già in elenco
        case OpDBModelOpDB::AGG_CLOWN_ALREADY_PRESENT:

            $cssTitoloClass = "titoloErrore";
            $titoloMessaggio = "Errore: clown già in elenco";
            $sottoTitoloMessaggio = "Il clown " . "<span class=\"datiMessaggio\">" . $this->nomeClown .
                "</span> o la mail <span class=\"datiMessaggio\">" . $this->mail . "</span> sono già stati inseriti in elenco.";
            break;

        // Errore clown non in elenco
        case OpDBModelOpDB::RIM_CLOWN_NOT_FOUND || OpDBModelOpDB::MOD_CLOWN_NOT_FOUND :

            $cssTitoloClass = "titoloErrore";
            $titoloMessaggio = "Errore: clown non trovato!";
            $sottoTitoloMessaggio = "Il clown " . "<span class=\"datiMessaggio\">" . $this->nomeClown .
                "</span> non sembra inserito nell'elenco!!";
            break;

        // Errore manca qualche dato
        case ClownsController::ACTION_CLOWN_INSUFFICIENT_DATA:

            $cssTitoloClass = "titoloErrore";
            $titoloMessaggio = "Errore: dati insufficienti!";
            $sottoTitoloMessaggio = "Devi compilare almeno il campo " .
                "<span class=\"datiMessaggio\">Nome clown</span> e la rispettiva <span class=\"datiMessaggio\">E-mail</span>.";
            break;

        // Messaggio di errore generico
        case ClownsController::ACTION_RESULT_ERROR:

            $cssTitoloClass = "titoloErrore";
            $titoloMessaggio = "Errore!!";
            $sottoTitoloMessaggio = "Qualcosa non è andato per il verso giusto!! Per favore riprova, e se il
                problema persiste contatta l'amministratore. Grazie";
            break;
    }

    ?>

    <div align="center">
        <div class="<?php echo($cssTitoloClass); ?>"><?php echo($titoloMessaggio); ?></div>
        <p class="sottoTitoloMessaggio"><?php echo($sottoTitoloMessaggio); ?></p>
        <br/>
    </div>

    <?php

}

//Visualizza elenco clowns


$ids = $this->ids;
$nomiClowns = $this->nomiClowns;
$nomi = $this->nomi;
$cognomi = $this->cognomi;
$mails = $this->mails;
$cell = $this->cell;
$statiSocio = $this->statiSoci;
$vips = $this->vips;

$countNomi = count($nomiClowns);

$statiSocioDispIds = $this->statiSocioDispIds;
$statiSocioDispNomi = $this->statiSocioDispNomi;

$vipDisponibiliIds = $this->vipDisponibiliIds;
$vipDisponibiliNomi = $this->vipDisponibiliNomi;

?>

<!-- VISUALIZZA NOMI DI TUTTI I CLOWNS IN DB -->
<?php if ($nomiClowns == NULL || $countNomi == 0)
{ ?>

    <p style="margin-bottom: 4px; font-size: 18px;">Nell'elenco sono presenti i seguenti
        <b><?php echo(sizeOf($nomiClowns)); ?></b> clown:</p>

<?php } ?>

<div class="elencoClown">

    <?php if ($nomiClowns == NULL || $countNomi == 0)
    { ?>

        <p>Nessun clown in elenco.</p>

    <?php } else
    {

        /* <span class="hasTip" title="INFORMAZIONI: ::Alberto &lt;br /&gt;Lupoa&lt;br /&gt;lupo.alberto@t.it &lt;br /&gt;320998876"> PROVA</span> */

        /* Add the tips for each name */
        for ($n = 0; $n < $countNomi; $n++)
        { ?>

            <span class="hasTip" title="INFORMAZIONI:
			<?php if ($nomiClowns[$n] != NULL)
            {
                echo "<b>" . $nomiClowns[$n];
                $statoSocioId = $statiSocio[$n];
                if ($statoSocioId != NULL)
                {
                    for ($i = 0; $i < count($statiSocioDispIds); $i++)
                    {
                        if ($statiSocioDispIds[$i] == $statoSocioId)
                        {
                            $statoSocioNome = $statiSocioDispNomi[$i];
                            break;
                        }
                    }
                    echo " (" . $statoSocioNome . ") ";
                }
                echo "</b>" . "&lt;br /&gt;";
            } ?>
			<?php if ($nomi[$n] != NULL)
            {
                echo $nomi[$n] . "&lt;br /&gt;";
            } ?>
			<?php if ($cognomi[$n] != NULL)
            {
                echo $cognomi[$n] . "&lt;br /&gt;";
            } ?>
			<?php if ($mails[$n] != NULL)
            {
                echo $mails[$n] . "&lt;br /&gt;";
            } ?>
			<?php if ($cell[$n] != NULL)
            {
                echo $cell[$n];
            } ?>
			
			">
		
			<?php echo $nomiClowns[$n];
            if ($n + 1 < $countNomi && $nomiClowns[$n + 1] != null)
            {
                echo ",";
            }
            ?>
		</span>
            <?php
        }
        echo "<br /> ";
        echo "<br /> ";

    }

    ?>

</div>


<!-- VISUALIZZA FORM DI GESTIONE-->
<div class="containerGestione">

    <?php
    function createJsArray($array)
    {
        $list = "";
        $tot = count($array);
        for ($cnt = 0; $cnt < $tot; $cnt++)
        {
            $list .= "\"$array[$cnt]\"";
            if ($cnt + 1 < $tot)
            {
                $list .= ",";
            }
        }
        echo $list;
    }

    ?>

    <!-- Aggiunta clown-->
    <div class="formGestione">
        <h4 class="titoloFormGestione">Aggiungi un clown nell'elenco</h4>

        <p class="sottoTitoloFormGestione">Inserisci i dati e clicca <b>Aggiungi clown</b>. <br/> Devi inserire almeno
            il nome clown e la mail, che dovranno essere unici.</p>

        <form name="FormAggiungiClown" method="post">

            <label for="nomeClownToAdd"> Nome Clown: </label>
            <input type="text" name="nomeClownToAdd" value="" style="font-weight:bold;"/>

            <br/>
            <label for="mailClownToAdd"> Indirizzo e-mail:</label>
            <input type="text" name="mailClownToAdd" value="" style="font-weight:bold;"/>


            <br/>
            <br/>
            <label for="nomeToAdd"> Nome: </label>
            <input type="text" name="nomeToAdd" value="" style="font-weight:bold;"/>

            <br/>
            <label for="cognomeToAdd"> Cognome: </label>
            <input type="text" name="cognomeToAdd" value="" style="font-weight:bold;"/>


            <br/>
            <label for="cellClownToAdd"> Cellulare:</label>
            <input type="text" name="cellClownToAdd" value="" style="font-weight:bold;"/>


            <br/>
            <label for="statoSocioToAdd"> Stato socio: </label>
            <select id="statoSocioToAdd" name="statoSocioToAdd" style="font-weight:bold;">
                <?php
                for ($n = 0; $n < count($statiSocioDispNomi); $n++)
                {
                    echo("<option value=\"$statiSocioDispIds[$n]\">$statiSocioDispNomi[$n]</option>");
                }
                ?>
            </select>


            <br/>
            <label for="vipToAdd"> Vip di appartenenza:</label>
            <select id="vipToAdd" name="vipToAdd" style="font-weight:bold;">
                <?php
                for ($n = 0; $n < count($vipDisponibiliNomi); $n++)
                {
                    echo("<option value=\"$vipDisponibiliIds[$n]\">$vipDisponibiliNomi[$n]</option>");
                }
                ?>
            </select>

            <br/>
            <br/>
            <input type="submit" name="" value="Aggiungi clown" style="font-weight:bold;"/>
            <input type="hidden" name="option" value="com_clowns"/>
            <input type="hidden" name="task" value="gestioneclowns.aggClown"/>


        </form>
    </div>


    <!-- Rimozione clown-->
    <div class="formGestione">
        <h4 class="titoloFormGestione">Rimuovi un clown dall'elenco</h4>

        <p class="sottoTitoloFormGestione">Seleziona il clown da rimuovere.</p>

        <form name="FormRimuoviClown" method="post">

            <label for="nomeClown"> Rimuovi il clown:</label>
            <select id="idToRemove" name="idToRemove" style="font-weight:bold;">
                <?php
                for ($n = 0; $n < $countNomi; $n++)
                {
                    echo("<option value=\"$ids[$n]\">$nomiClowns[$n]</option>");
                }
                ?>
            </select>

            <br/>
            <br/>
            <input type="submit" name="" value="Rimuovi clown" style="font-weight:bold;"/>
            <input type="hidden" name="option" value="com_clowns" style="font-weight:bold;"/>
            <input type="hidden" name="task" value="gestioneclowns.rimClown" style="font-weight:bold;"/>

        </form>
    </div>


    <!-- Modifica clown-->
    <div class="formGestione">
        <h4 class="titoloFormGestione">Modifica i dati di un clown</h4>

        <p class="sottoTitoloFormGestione">Seleziona il clown da modificare e inserisci i nuovi dati. Lascia vuoti i
            campi da non modificare che resteranno invariati. Se vuoi cancellare un campo... </p>

        <form name="FormModificaClown" method="post">

            <script>
                function changeValuesToModify() {
                    var indexSelected = document.getElementById("selNomeClown").selectedIndex;

                    // Id that will be updated
                    var ids = new Array();
                    ids = [<?php createJsArray($ids)?>];
                    document.getElementById("idToUpdate").value = ids[indexSelected];

                    var nomiClown = new Array();
                    nomiClown = [<?php createJsArray($nomiClowns)?>];
                    document.getElementById("modNomeClown").value = nomiClown[indexSelected];

                    var mails = new Array();
                    mails = [<?php createJsArray($mails)?>];
                    document.getElementById("modMail").value = mails[indexSelected];

                    var nomi = new Array();
                    nomi = [<?php createJsArray($nomi)?>];
                    document.getElementById("modNome").value = nomi[indexSelected];

                    var cognomi = new Array();
                    cognomi = [<?php createJsArray($cognomi)?>];
                    document.getElementById("modCognome").value = cognomi[indexSelected];

                    var cellulari = new Array();
                    cellulari = [<?php createJsArray($cell)?>];
                    document.getElementById("modCell").value = cellulari[indexSelected];

                    var statiSociIds = new Array();
                    statiSociIds = [<?php createJsArray($statiSocio)?>];
                    var statoSocioId = statiSociIds[indexSelected];

                    var optionsSoci = document.getElementById("modStatoSocio").options;
                    for (var i = 0; i < optionsSoci.length; i++) {

                        var optionSocioValue = parseInt(optionsSoci[i].value);
                        if (statoSocioId == optionSocioValue) {
                            document.getElementById("modStatoSocio").selectedIndex = i;
                            break;
                        }

                    }

                    var vipsIds = new Array();
                    vipsIds = [<?php createJsArray($vips)?>];
                    var vipsId = vipsIds[indexSelected];

                    var optionsVips = document.getElementById("modVip").options;
                    for (var i = 0; i < optionsVips.length; i++) {

                        var optionVipValue = parseInt(optionsVips[i].value);
                        if (vipsId == optionVipValue) {
                            document.getElementById("modVip").selectedIndex = i;
                            break;
                        }

                    }
                }
            </script>

            <label for="nomeClown"> Modifica il clown:</label>
            <select id="selNomeClown" name="nomeClownCurrent" onchange="changeValuesToModify()" style="font-weight:bold;">
                <?php
                for ($n = 0; $n < $countNomi; $n++)
                {
                    echo "<option>$nomiClowns[$n]</option>";
                }
                ?>
            </select>


            <br/>
            <label for="modNomeClown"> Nuovo nome Clown: </label>
            <input id="modNomeClown" type="text" name="nomeClownNew" value="<?php echo($nomiClowns[0]); ?>"
                   style="font-weight:bold;"/>

            <br/>
            <label for="modMail"> Indirizzo e-mail:</label>
            <input id="modMail" type="text" name="mailClownNew" value="<?php echo($mails[0]); ?>"
                   style="font-weight:bold;"/>

            <br/>
            <br/>
            <label for="modNome"> Nome: </label>
            <input id="modNome" type="text" name="nomeNew" value="<?php echo($nomi[0]); ?>" style="font-weight:bold;"/>

            <br/>
            <label for="modCognome"> Cognome: </label>
            <input id="modCognome" type="text" name="cognomeNew" value="<?php echo($cognomi[0]); ?>"
                   style="font-weight:bold;"/>

            <br/>
            <label for="modCell"> Cellulare:</label>
            <input id="modCell" type="text" name="cellClownNew" value="<?php echo($cell[0]); ?>"
                   style="font-weight:bold;"/>

            <br/>
            <label for="modStatoSocio"> Stato socio: </label>
            <select id="modStatoSocio" name="statoSocioNew" style="font-weight:bold;">
                <?php
                for ($n = 0; $n < count($statiSocioDispNomi); $n++)
                {
                    $opt = "<option value=\"$statiSocioDispIds[$n]\"as";
                    if ($statiSocioDispIds[$n] == $statiSocio[0])
                    {
                        $opt .= "selected";
                    }
                    $opt .= "> $statiSocioDispNomi[$n] </option>";
                    echo($opt);
                }
                ?>
            </select>


            <br/>
            <label for="modVip"> Vip di appartenenza:</label>
            <select id="modVip" name="vipNew" style="font-weight:bold;">
                <?php
                for ($n = 0; $n < count($vipDisponibiliNomi); $n++)
                {
                    $opt = "<option value=\"$vipDisponibiliIds[$n]\" ";
                    if ($vipDisponibiliIds[$n] == $vips[0])
                    {
                        $opt .= "selected";
                    }

                    $opt .= "> $vipDisponibiliNomi[$n] </option>";

                    echo($opt);
                }
                ?>
            </select>

            <br/>
            <br/>
            <input type="submit" name="" value="Modifica i dati" style="font-weight:bold;"/>
            <input type="hidden" id="nomeClownCurrent" name="nomeClownCurrent" value="<?php echo($nomiClowns[0]); ?>"/>
            <input type="hidden" id="idToUpdate" name="idToUpdate" value="<?php echo($ids[0]); ?>"/>
            <input type="hidden" name="option" value="com_clowns"/>
            <input type="hidden" name="task" value="gestioneclowns.modClown"/>


        </form>


    </div>

</div>

<br/>
<br/>
<br/>



