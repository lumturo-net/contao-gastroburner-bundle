<?php

namespace Lumturo\ContaoGastroburnerBundle\Module;

use Exception;
use Contao\PageModel;
use Contao\FilesModel;
use Contao\Controller;
use Contao\Database;
use Contao\Email;
use Contao\Validator;
use Contao\FrontendTemplate;

class GastroburnerApplyFormMapModule extends \Contao\Module
{
    /**
     * @var string
     */
    // protected $strTemplate = 'mod_gastroburner_applyform';
    protected $strTemplate = 'mod_gastroburner_applyformmap';

    /**
     * Displays a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template = new \BackendTemplate('be_wildcard');

            $template->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['gastroburnerapplyformmap'][0]) . ' ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $template->parse();
        } else {
            // $GLOBALS['TL_JAVASCRIPT'][] = 'https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js';
            // // $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaogastroburner/js/require.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaogastroburner/js/leaflet.js';
            // $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaogastroburner/js/list.min.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaogastroburner/js/list.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaogastroburner/js/map.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.js';
            $GLOBALS['TL_JAVASCRIPT'][] = 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/debug.addIndicators.min.js';
            $GLOBALS['TL_CSS'][] = 'bundles/contaogastroburner/css/companies.min.css';
        }

        return parent::generate();
    }

    /**
     * Generates the module.
     */
    protected function compile()
    {
        global $objPage;

        $arrErrors = [];
        $arrPost = [
            'companies' => [],
            'hidden_companies' => [],
        ];

        // Berufe kommen per GET und POST
        foreach (['restaurant', 'cook', 'hotelcleaner', 'hotelmanager', 'gastro'] as $strName) {
            $this->Template->{$strName} = (($this->Input->post($strName) || ($this->Input->get($strName))) ? true : false);
        }

        $arrPostKeys = array_keys($_POST);
        foreach ($arrPostKeys as $strKey) {
            $arrPost[$strKey] = $this->Input->post($strKey);
        }

        if ($this->Input->post('action') == 'apply') {
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
                $this->sendMails($arrPost);
                $this->redirect('vielen-dank.html');
            }
        }

        $arrFields = [
            'id',
            'company',
            'street',
            'postal',
            'city',
            'lat',
            'lon',
            'email',
            'shortname',
            'shortdesc',
            'restaurant',
            'cook',
            'hotelcleaner',
            'hotelmanager',
            'gastro',
            'companyLogo',
            'website'
        ];

        $arrCompanies = [];
        if(!empty($arrPost['hidden_companies'])) {
            $clause = implode(',', array_fill(0, count($arrPost['hidden_companies']), '?'));
            $arrCompaniesCollection = Database::getInstance()
                                              ->prepare('SELECT ' . implode(', ', $arrFields) . ' FROM tl_member WHERE disable=\'\' AND show_in_frontend=\'1\' AND id IN(' . $clause . ') ORDER BY shortname;')
                                              ->execute($arrPost['hidden_companies'])
                                              ->fetchAllAssoc();
        } else {
            $arrCompaniesCollection = Database::getInstance()
                                              ->prepare('SELECT ' . implode(', ', $arrFields) . ' FROM tl_member WHERE disable=\'\' AND show_in_frontend=\'1\' ORDER BY shortname;')
                                              ->execute()
                                              ->fetchAllAssoc();
        }

        foreach ($arrCompaniesCollection as $arrCompany) {
            $objLogo = FilesModel::findOneBy('uuid', $arrCompany['companyLogo']);
            $arrCompany['shortdesc'] = preg_replace("!([\b\t\n\r\f\"\\'])!", "", $arrCompany['shortdesc']);
            $arrCompany['shortname'] = preg_replace("!([\b\t\n\r\f\"\\'])!", "", $arrCompany['shortname']);
            $arrCompany['company']   = preg_replace("!([\b\t\n\r\f\"\\'])!", "", $arrCompany['company']);
            $arrCompany['companyLogo'] = $objLogo->path ?? 'https://via.placeholder.com/170x100.png&text=Hotel-Logo';
            $arrCompanies[$arrCompany['id']] = $arrCompany;
        }

        $this->Template->post = $arrPost;
        $this->Template->companies = $arrCompanies;
        $this->Template->hiddenCompanies = $arrPost['hidden_companies'];
        $this->Template->errors = $arrErrors;
        $this->Template->url = Controller::generateFrontendUrl($objPage->row()); //$this->getApplyFormPageUrl();
    }

    /**
     * sende Mails an alle angehakten Betriebe.
     */
    protected function sendMails($arrPost)
    {
        if (!count($arrPost['hidden_companies'])) {
            return;
        }

        $clause = implode(',', array_fill(0, count($arrPost['hidden_companies']), '?'));
        $arrCompanies = Database::getInstance()
                                ->prepare('SELECT * FROM tl_member WHERE  id in (' . $clause . ') ORDER BY shortname;')
                                ->execute($arrPost['hidden_companies'])
                                ->fetchAllAssoc();

        foreach ($arrCompanies as $arrCompany) {
            $objEmail = new Email();
            $objEmail->charset = 'utf-8';
            $objEmail->subject = 'Bewerber von der Webseite Gastroburner.de';
            $objEmail->from = 'service@gastroburner.de';
            $objHtmlMailTemplate = new FrontendTemplate('mail_gastroburner_application');
            $objHtmlMailTemplate->post = $arrPost;
            $objHtmlMailTemplate->company = $arrCompany;
            $objEmail->html = $objHtmlMailTemplate->parse();
            $objEmail->sendTo($arrCompany['email']);
        }
    }

    protected function getThankyouFormPageUrl()
    {
        $intPageId = $GLOBALS['TL_CONFIG']['gastroburner_applyform_page'];

        if ($intPageId) {
            $objApplyPage = PageModel::findById($intPageId);
            if ($objApplyPage) {
                $strApplyUrl = Controller::generateFrontendUrl($objApplyPage->row());
                return $strApplyUrl;
            }
        } else {
            throw new Exception('Bitte erst in den Settings die Bewerbungsseite setzen');
        }
    }
}
