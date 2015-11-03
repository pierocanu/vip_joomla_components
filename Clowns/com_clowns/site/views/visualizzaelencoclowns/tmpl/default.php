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

<p style="margin-bottom: 4px; font-size: 18px;"><?php echo $this->testo; ?> </p>
<br/>


<div class="elencoClown">

    <?php


    //Visualizza elenco clowns

    $nomiClowns = $this->nomiClowns;
    $nomi = $this->nomi;
    $cognomi = $this->cognomi;
    $mail = $this->mail;
    $cell = $this->cell;


    if ($nomiClowns == NULL) {
        ?>
        <p">Nessun clown in elenco.<p/>
        <?php
    } else {
        ?>

        <p> Totale clowns: <?php echo(sizeOf($nomiClowns)); ?> </p>

        <table>
            <tbody>
            <tr>
                <th>Clown</th>
                <th>Nome e cognome</th>
                <th>Indirizzo e-mail</th>
                <th>Numero di telefono</th>
            </tr>

            <?php
            for ($n = 0; $n < sizeof($nomiClowns); $n++) {

                ?>
                <tr <?php if ($n % 2 == 0) {
                    echo "class=\"\"";
                } ?>>

                    <td><span class="nomeclown"><?php echo "$nomiClowns[$n]"; ?></span></td>
                    <td><?php echo $nomi[$n] . " " . $cognomi[$n]; ?></td>
                    <td><a class="mail" href="mailto:<?php echo $mail[$n]; ?>"><?php echo $mail[$n]; ?></a></td>
                    <td><span class="cell"> <?php if ($cell[$n] != '0') {
                                echo $cell[$n];
                            } ?></span></td>

                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

        <br/>
        <br/>

        <?php
    }
    ?>

</div>

<br/>
<br/>
<br/>



