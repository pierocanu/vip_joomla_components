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


<h1 style="text-align:center; font-size:28px;"><?php echo $this->titolo; ?> </h1>

<?php switch ($this->messaggio) {
    case "clown-aggiunto": //Messaggio di conferma clown aggiunto in servizio
        ?>

        <div align="center">
            <div class="titoloMessaggio">Clown aggiunto.</div>
            <p class="sottoTitoloMessaggio"> Il clown <span class="datiMessaggio"><?php echo $this->nomeClown; ?>
                    (<?php echo $this->nome;
                    echo " ";
                    echo $this->cognome; ?>)</span> &eacute; stato inserito nell'elenco. </p>
            <br/>
        </div>

        <?php break;

    case "clown-rimosso": //Messaggio di conferma clown rimosso da un servizio
        ?>

        <div>
            <div class="titoloMessaggio">Clown rimosso.</div>
            <p class="sottoTitoloMessaggio"> Il clown <span
                    class="datiMessaggio"><?php echo $this->nomeClown ?></span> &eacute; stato rimosso dall'elenco. </p>
            <br/>
        </div>

        <?php break;

    case "clown-già-in-elenco": //Errore clown già in elenco
        ?>

        <div>
            <div class="titoloErrore">Errore: clown già in elenco</div>
            <p class="sottoTitoloMessaggio"> Il clown <span class="datiMessaggio"><?php echo $this->nomeClown ?></span>
                o la mail <span class="datiMessaggio"><?php echo $this->mailClown ?></span> sono già stati inseriti
                nell'elenco. </p>
            <br/>
        </div>

        <?php break;

    case "clown-non-in-elenco": //Errore clown non in elenco
        ?>

        <div>
            <div class="titoloErrore">Errore: clown non trovato!</div>
            <p class="sottoTitoloMessaggio"> Il clown <span class="datiMessaggio"><?php echo $this->nomeClown ?></span>
                non sembra inserito nell'elenco!! </p>
            <br/>
        </div>

        <?php break;

    case "dati-insufficienti": //Errore manca qualche dato
        ?>

        <div>
            <div class="titoloErrore">Errore: dati insufficienti!</div>
            <p class="sottoTitoloMessaggio"> Devi compilare almeno il campo <span
                    class="datiMessaggio">Nome clown</span> e la rispettiva <span class="datiMessaggio">E-mail</span>.
            </p>
            <br/>
        </div>

        <?php break;
    case "errore-clown-non-aggiunto" || "errore-clow-non-rimosso": ?>

        <div>

            <div class="titoloErrore">Errore...</div>
            <p class="sottoTitoloMessaggio">Non è stato
                possibile <?php if ($this->messaggio == 'errore-clown-non-aggiunto') {
                    echo 'aggiungere';
                } else if ($this->messaggio == 'errore-clow-non-rimosso') {
                    echo 'rimuovere';
                } ?> il clown da te richiesto.</p>

            <p class="sottoTitoloMessaggio">Per favore riprova, se il problema persiste contatta l'amministratore </p>

            <p class="sottoTitoloMessaggio">Grazie!</p>
            <br/>
            <br/>
        </div>
        <?php break;

    case "errore": //Messaggio di errore generico
        ?>

        <div>

            <div class="titoloErrore"> Errore!!</div>
            <br/>

            <p class="sottoTitoloMessaggio"> Qualcosa non è andato per il verso giusto!! Per favore riprova, e se il
                problema persiste contatta l'amministratore. Grazie </p>
            <br/>
            <br/>
        </div>

        <?php break;
}

//Visualizza elenco clowns

$nomiClowns = $this->nomiClowns;
$nomi = $this->nomi;
$cognomi = $this->cognomi;
$mail = $this->mail;
$cell = $this->cell;


?>

<p style="margin-bottom: 4px; font-size: 18px;">Nell'elenco sono presenti i seguenti
    <b><?php echo(sizeOf($nomiClowns)); ?></b> clown:</p>
<div class="elencoClown">

    <?php


    if ($nomiClowns == NULL) {
        ?>
        <p">Nessun clown in elenco.<p/>
        <?php
    } else {

        for ($n = 0; $nomiClowns[$n]; $n++) {

            ?>

            <!-- 		<span class="hasTip" title="INFORMAZIONI: ::Alberto &lt;br /&gt;Lupoa&lt;br /&gt;lupo.alberto@t.it &lt;br /&gt;320998876"> PROVA</span> -->

            <span class="hasTip" title="INFORMAZIONI : ::
			<?php if ($nomiClowns[$n] != NULL) {
                echo "<b>" . $nomiClowns[$n] . "</b>" . "&lt;br /&gt;";
            } ?>
			<?php if ($nomi[$n] != NULL) {
                echo $nomi[$n] . "&lt;br /&gt;";
            } ?>
			<?php if ($cognomi[$n] != NULL) {
                echo $cognomi[$n] . "&lt;br /&gt;";
            } ?>
			<?php if ($mail[$n] != NULL) {
                echo $mail[$n] . "&lt;br /&gt;";
            } ?>
			<?php if ($cell[$n] != NULL) {
                echo $cell[$n];
            } ?>
			
			">
		
			<?php echo $nomiClowns[$n];
            if ($nomiClowns[$n + 1]) {
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

    <!-- Aggiunta clown-->

    <div class="formGestione">
        <h4 class="titoloFormGestione">Aggiungi un clown nell'elenco</h4>

        <p class="sottoTitoloFormGestione">Inserisci i dati e clicca Aggiungi clown. <br/> Devi inserire almeno il nome
            clown e la mail.</p>

        <form name="FormAggiungiClown" method="post">


            <label for="nome clown"> Nome Clown: </label>
            <input type="text" name="nomeClown" value="" style="font-weight:bold;"/>

            <br/>
            <label for="Mail"> Indirizzo e-mail:</label>
            <input type="text" name="mailClown" value="" style="font-weight:bold;"/>


            <br/>
            <br/>
            <label for="nome"> Nome: </label>
            <input type="text" name="nome" value="" style="font-weight:bold;"/>

            <br/>
            <label for="cognome"> Cognome: </label>
            <input type="text" name="cognome" value="" style="font-weight:bold;"/>


            <br/>
            <label for="Cell"> Cellulare:</label>
            <input type="text" name="cellClown" value="" style="font-weight:bold;"/>


            <br/>
            <br/>
            <input type="submit" name="" value="Aggiungi clown!" style="font-weight:bold;"/>
            <input type="hidden" name="option" value="com_clowns" style="font-weight:bold;"/>
            <input type="hidden" name="task" value="aggClown" style="font-weight:bold;"/>


        </form>
    </div>


    <!-- Rimozione clown-->

    <div class="formGestione">
        <h4 class="titoloFormGestione">Rimuovi un clown dall'elenco</h4>

        <p class="sottoTitoloFormGestione">Seleziona il clown da rimuovere.</p>

        <form name="FormRimuoviClown" method="post">

            <label for="nomeClown"> Rimuovi il clown:</label>
            <select name="nomeClown" style="font-weight:bold;">
                <?php
                for ($n = 0; $n < count($nomiClowns); $n++) {
                    echo "<option>$nomiClowns[$n]</option>";
                }
                ?>
            </select>

            <br/>
            <br/>
            <input type="submit" name="" value="Rimuovi clown!" style="font-weight:bold;"/>
            <input type="hidden" name="option" value="com_clowns" style="font-weight:bold;"/>
            <input type="hidden" name="task" value="rimClown" style="font-weight:bold;"/>

        </form>
    </div>

</div>

<br/>
<br/>
<br/>



