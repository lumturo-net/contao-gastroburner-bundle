<?php


$GLOBALS['TL_DCA']['tl_settings']['fields']['gastroburner_applyform_page'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['gastroburner_applyform_page'],
    'inputType' => 'pageTree',
    'eval' => array('mandatory' => TRUE)
);

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{gastroburner_config},gastroburner_applyform_page';
