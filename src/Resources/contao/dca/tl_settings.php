<?php


$GLOBALS['TL_DCA']['tl_settings']['fields']['gastroburner_applyform_page'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['gastroburner_applyform_page'],
    'inputType' => 'pageTree',
    'eval' => array('mandatory' => TRUE, 'tl_class' => 'clr'),
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['gastroburner_applyform_bcc'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['gastroburner_applyform_bcc'],
    'inputType' => 'text',
    'eval' => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w100', 'rgxp' => 'email'),
);

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{gastroburner_config},gastroburner_applyform_page,gastroburner_applyform_bcc';
