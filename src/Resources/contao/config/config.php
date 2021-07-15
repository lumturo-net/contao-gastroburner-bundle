<?php
// Fragen / Jobs / Antworten / Punkte
// Frontend modules
$GLOBALS['FE_MOD']['miscellaneous']['gastroburner'] = 'Lumturo\ContaoGastroburnerBundle\Module\GastroburnerModule';
$GLOBALS['FE_MOD']['miscellaneous']['gastroburnerapplyform'] = 'Lumturo\ContaoGastroburnerBundle\Module\GastroburnerApplyFormModule';
$GLOBALS['FE_MOD']['miscellaneous']['gastroburnerapplyformmap'] = 'Lumturo\ContaoGastroburnerBundle\Module\GastroburnerApplyFormMapModule';
$GLOBALS['FE_MOD']['miscellaneous']['gastroburnerregisterform'] = 'Lumturo\ContaoGastroburnerBundle\Module\GastroburnerRegistrationModule';
$GLOBALS['FE_MOD']['miscellaneous']['gastroburnerpersonaldata'] = 'Lumturo\ContaoGastroburnerBundle\Module\GastroburnerPersonalDataModule';
$GLOBALS['FE_MOD']['miscellaneous']['gastroburnercompanies'] = 'Lumturo\ContaoGastroburnerBundle\Module\GastroburnerCompanies';
$GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaogastroburner/js/gastroburner.js';
$GLOBALS['TL_CSS'][] = 'bundles/contaogastroburner/css/gastroburner.min.css';
// Backend modules
$GLOBALS['BE_MOD']['gastroburner']['tl_question'] = array(
    'tables' => array('tl_question'),
//    'icon' => 'system/modules/genius/assets/images/teacher.png',
//    'stylesheet' => '/system/modules/genius/assets/css/backend/eval.css',
    'javascript' => array(
        // kriege error in edit-Maske, wenn jquery geladen wird...
        // '/assets/jquery/core/1.11.3/jquery.min.js',
        // '/system/modules/mhb_events/assets/js/infoday.js',
    )
);
$GLOBALS['BE_MOD']['gastroburner']['tl_apply'] = array(
    'tables' => array('tl_apply'),
//    'icon' => 'system/modules/genius/assets/images/teacher.png',
//    'stylesheet' => '/system/modules/genius/assets/css/backend/eval.css',
);
$GLOBALS['BE_MOD']['gastroburner']['tl_company'] = array(
    'tables' => array('tl_member'),
);
// gibt immer wieder Probleme mit Safari und Request-Token
define('BYPASS_TOKEN_CHECK', 1);
