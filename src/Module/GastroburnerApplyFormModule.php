<?php

namespace Lumturo\ContaoGastroburnerBundle\Module;

use Contao\Validator;

class GastroburnerApplyFormModule extends \Module
{
    /**
     * @var string
     */
    protected $strTemplate = 'mod_gastroburner_applyform';

    /**
     * Displays a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template = new \BackendTemplate('be_wildcard');

            $template->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['gastroburnerapplyform'][0]) . ' ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $template->parse();
        }

        return parent::generate();
    }

    /**
     * Generates the module.
     */
    protected function compile()
    {
        $arrErrors = [];
        $boolThankyou = false;

        if ($this->Input->post('action') == 'apply') {
            $arrValues = [];
            if (!strlen($this->Input->post('name'))) {
                $arrErrors['name'] = true;
            } else {
                $arrValues['name'] = $this->Input->post('name');
            }
            $strEmail = $this->Input->post('email');
            if (!strlen($strEmail) || !Validator::isEmail($strEmail)) {
                $arrErrors['email'] = true;
            } else {
                $arrValues['email'] = $this->Input->post('email');
            }

            $arrErrors['jobs'] = true;
            foreach (['restaurant', 'cook', 'hotelcleaner', 'hotelmanager', 'gastro'] as $strName) {
                $boolVal = boolval($this->Input->post($strName));
                if ($boolVal) {
                    unset($arrErrors['jobs']);
                    $arrValues[$strName] = ((boolval($this->Input->post($strName))) ? '1' : '');
                }
            }

            if (!count($arrErrors)) {
                $arrValues['tstamp'] = time();
                $this->Database->prepare('INSERT INTO tl_apply %s')->set($arrValues)->execute();
                $boolThankyou = true;
            }
        }

        foreach (['restaurant', 'cook', 'hotelcleaner', 'hotelmanager', 'gastro'] as $strName) {
            $this->Template->{$strName} = (($this->Input->post($strName)) ? true : false);
        }
        $this->Template->thank_you = $boolThankyou;
        $this->Template->errors = $arrErrors;
        $this->Template->url = $this->getApplyFormPageUrl();
    }

    protected function getApplyFormPageUrl()
    {
        $intPageId = $GLOBALS['TL_CONFIG']['gastroburner_applyform_page'];

        if ($intPageId) {
            $objApplyPage = \PageModel::findById($intPageId);
            if ($objApplyPage) {
                $strApplyUrl = \Controller::generateFrontendUrl($objApplyPage->row());
                return $strApplyUrl;
            }
        } else {
            throw new \Exception('Bitte erst in den Settings die Bewerbungsseite setzen');
        }
    }
}
