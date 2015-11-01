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

// import Joomla view library
jimport('joomla.application.component.view');

class clownsViewclowns extends JView
{
    function _construct()
    {
        parent::_construct();
    }

    function set($varName, $var)
    {
        $this->$varName = $var;
    }

    // Overwriting JView display method
    function display($tpl = null)
    {

        $this->titolo = $this->get('Titolo');  //Prende Titolo dal model attuale tramite il GET

        $Clowns = $this->modelOpDB->leggiElencoClowns();
        $this->nomi = $Clowns[nomi];
        $this->cognomi = $Clowns[cognomi];
        $this->nomiClowns = $Clowns[nomiClowns];
        $this->mail = $Clowns[mail];
        $this->cell = $Clowns[cell];

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Display the view
        parent::display($tpl);
    }
}

?>