<?php

namespace EzSystems\EzPlatformLinkManager\Tests\Core\Persistence\Legacy\URL\Query\CriterionHandler;

use EzSystems\EzPlatformLinkManager\Core\Persistence\Legacy\URL\Query\CriterionHandler;
use PHPUnit\Framework\TestCase;

abstract class CriterionHandlerTest extends TestCase
{
    abstract public function testAccept();

    abstract public function testHandle();

    /**
     * Check if critetion handler accepts specyfied criterion class.
     *
     * @param CriterionHandler $handler
     * @param string $criterionClass
     */
    protected function assertHandlerAcceptsCriterion(CriterionHandler $handler, $criterionClass)
    {
        $this->assertTrue($handler->accept($this->createMock($criterionClass)));
    }

    /**
     * Check if critetion handler rejects specyfied criterion class.
     *
     * @param CriterionHandler $handler
     * @param string $criterionClass
     */
    protected function assertHandlerRejectsCriterion(CriterionHandler $handler, $criterionClass)
    {
        $this->assertFalse($handler->accept($this->createMock($criterionClass)));
    }
}
