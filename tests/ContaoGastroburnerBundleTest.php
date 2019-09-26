<?php

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace Lumturo\ContaoGastroburnerBundle\Tests;

use Lumturo\ContaoGastroburnerBundle\ContaoGastroburnerBundle;
use PHPUnit\Framework\TestCase;

class ContaoGastroburnerBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new ContaoSkeletonBundle();

        $this->assertInstanceOf('Lumturo\ContaoGastroburnerBundle', $bundle);
    }
}
