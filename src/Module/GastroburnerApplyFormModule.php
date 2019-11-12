<?php

namespace Lumturo\ContaoGastroburnerBundle\Module;

use Contao\Database;
use Contao\Validator;

class GastroburnerApplyFormModule extends \Module
{
    /**
     * @var string
     */
    protected $strTemplate = 'mod_gastroburner_applyform.v1';

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
        } else {
            $GLOBALS['TL_JAVASCRIPT'][] = 'https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js';
            // // $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaogastroburner/js/require.js';
            // $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaogastroburner/js/map.js';
        }

        return parent::generate();
    }

    /**
     * Generates the module.
     */
    protected function compile()
    {
        $arrErrors = [];
        $arrPost = [
            'companies' => [],
            'hidden_companies' => [],
        ];
        // $boolThankyou = false;

        if ($this->Input->post('action') == 'apply') {
            $arrPostKeys = array_keys($_POST);
            foreach ($arrPostKeys as $strKey) {
                $arrPost[$strKey] = $this->Input->post($strKey);
            }
            $arrValues = [];
            if (!strlen($this->Input->post('name'))) {
                $arrErrors['name'] = true;
            } else {
                $arrValues['name'] = $this->Input->post('name');
            }
            if (!strlen($this->Input->post('vorname'))) {
                $arrErrors['vorname'] = true;
            } else {
                $arrValues['vorname'] = $this->Input->post('vorname');
            }
            $strEmail = $this->Input->post('email');
            if (!strlen($strEmail) || !Validator::isEmail($strEmail)) {
                $arrErrors['email'] = true;
            } else {
                $arrValues['email'] = $this->Input->post('email');
            }
            $arrValues['beschreibung'] = $arrPost['beschreibung'];

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
                // $this->sendMails($arrPost);
                $this->redirect('vielen-dank.html');
                // $boolThankyou = true;
            }
        }

        foreach (['restaurant', 'cook', 'hotelcleaner', 'hotelmanager', 'gastro'] as $strName) {
            $this->Template->{$strName} = (($this->Input->post($strName)) ? true : false);
        }

        // $arrCompleteCompanies = Database::getInstance()->prepare('SELECT * FROM tl_company ORDER BY shortname;')->execute()->fetchAllAssoc();
        // $arrCompanies = array();
        // foreach ($arrCompleteCompanies as $arrCompany) {
        //     unset($arrCompany['tstamp']);
        //     $arrCompanies[$arrCompany['id']] = $arrCompany;
        // }

        $this->Template->post = $arrPost;
        // $this->Template->companies = $arrCompanies;
        // $this->Template->thank_you = $boolThankyou;
        $this->Template->errors = $arrErrors;
        $this->Template->url = $this->getApplyFormPageUrl();
    }

    /**
     * sende Mails an alle angehakten Betriebe.
     */
    protected function sendMails($arrPost)
    {
        if (!count($arrPost['hidden_companies'])) {
            return;
        }

        $arrCompanies = Database::getInstance()->prepare('SELECT * FROM tl_company WHERE  id in (?) ORDER BY shortname;')->execute(implode(',', $arrPost['hidden_companies']))->fetchAllAssoc();
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
