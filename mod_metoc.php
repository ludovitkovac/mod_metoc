<?php

defined('_JEXEC') or die('Unauthorized access');

$layout = $params->get('layout', 'default');
require JModuleHelper::getLayoutPath('mod_metoc', $layout);                     // show file default.php
