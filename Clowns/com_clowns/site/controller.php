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

// import Joomla controller library
jimport('joomla.application.component.controller');


class  ClownsController extends JController
{
    function display()
    {
        // Default task
        require_once(JPATH_COMPONENT . DS . 'models' . DS . 'opdb.php');

        $vName = JRequest::getCmd('view', 'categories');
        JRequest::setVar('view', $vName);
        $view = $this->getView($vName, 'html');

        $view->setModel($this->getModel('clowns'), true);
        $view->set('modelOpDB', new OpDbModelOpDB);

        parent::display();
    }
}

?> 