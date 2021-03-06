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

class clownsViewvisualizzaelencoclowns extends JView
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
        $this->titolo = JFactory::getApplication()->input->get('title', '', 'TEXT');

        $statiSocioDisponibili = $this->modelOpDB->getStatiSocioDisponibili();
        $this->statiSocioDispIds = $statiSocioDisponibili['ids'];
        $this->statiSocioDispNomi = $statiSocioDisponibili['nomi'];
        if(!isset($this->statoSocioToView))
        {
            $this->statoSocioToView = -1;
        }

        $vipDisponibili = $this->modelOpDB->getVipDisponibili();
        $this->vipDisponibiliIds = $vipDisponibili['ids'];
        $this->vipDisponibiliNomi = $vipDisponibili['nomi'];
        if(!isset($this->vipToView))
        {
            $this->vipToView = -1;
        }


        $clowns = $this->modelOpDB->leggiElencoClownsConFiltri($this->statoSocioToView, $this->vipToView);
        $this->ids = $clowns['ids'];
        $this->nomi = $clowns['nomi'];
        $this->cognomi = $clowns['cognomi'];
        $this->nomiClowns = $clowns['nomiClowns'];
        $this->mails = $clowns['mails'];
        $this->cell = $clowns['cell'];
        $this->statiSoci = $clowns['statiSoci'];
        $this->vips = $clowns['vips'];

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Display the view
        parent::display($tpl);
    }

}

?>