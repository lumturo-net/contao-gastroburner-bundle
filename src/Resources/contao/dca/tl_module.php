<?php

// Add palette to tl_module
$GLOBALS['TL_DCA']['tl_module']['palettes']['gastroburner'] = '{title_legend},name,headline,type;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
// Registrierung
$GLOBALS['TL_DCA']['tl_module']['palettes']['gastroburnerregisterform']  = '{title_legend},name,headline,type;{config_legend},editable,newsletters,disableCaptcha;{account_legend},reg_groups,reg_allowLogin,reg_assignDir;{redirect_legend},jumpTo;{email_legend},reg_activate;{template_legend:hide},memberTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';
