<?php

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao;

use Contao\CoreBundle\Exception\InternalServerErrorException;
use Contao\Database\Result;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Provide methods to handle newsletters.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class GastroburnerMember extends Backend
{
    public static function test() {
        
    }
    public function uploadLogo($varValue, $User, $modulePersonalData)
    {
        $a = 0;
    }
}

class_alias(GastroburnerMember::class, 'GastroburnerMember');
