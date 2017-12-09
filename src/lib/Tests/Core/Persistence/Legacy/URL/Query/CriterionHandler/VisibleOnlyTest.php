<?php

namespace EzSystems\EzPlatformLinkManager\Tests\Core\Persistence\Legacy\URL\Query\CriterionHandler;

use eZ\Publish\Core\Persistence\Database\Expression;
use eZ\Publish\Core\Persistence\Database\SelectQuery;
use EzSystems\EzPlatformLinkManager\Core\Persistence\Legacy\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLinkManager\Core\Persistence\Legacy\URL\Query\CriterionHandler\VisibleOnly as VisibleOnlyHandler;
use EzSystems\EzPlatformLinkManager\API\Repository\Values\Query\Criterion\VisibleOnly;
use EzSystems\EzPlatformLinkManager\API\Repository\Values\Query\Criterion;

class VisibleOnlyTest extends CriterionHandlerTest
{
    /**
     * {@inheritdoc}
     */
    public function testAccept()
    {
        $handler = new VisibleOnlyHandler();

        $this->assertHandlerAcceptsCriterion($handler, VisibleOnly::class);
        $this->assertHandlerRejectsCriterion($handler, Criterion::class);
    }

    /**
     * {@inheritdoc}
     */
    public function testHandle()
    {
        $criterion = new VisibleOnly();
        $expected = 'ezurl.id IN (SUBQUERY)';

        $expr = $this->createMock(Expression::class);
        $expr
            ->expects($this->once())
            ->method('in')
            ->with('ezurl.id', '(SUBQUERY)')
            ->willReturn($expected);

        $query = $this->createMock(SelectQuery::class);
        $query->expr = $expr;

        $converter = $this->createMock(CriteriaConverter::class);

        $handler = $this
            ->getMockBuilder(VisibleOnlyHandler::class)
            ->setMethods(['getVisibleOnlySubQuery'])
            ->getMock();
        $handler
            ->expects($this->once())
            ->method('getVisibleOnlySubQuery')
            ->with($query)
            ->willReturn('(SUBQUERY)');

        $actual = $handler->handle($converter, $query, $criterion);

        $this->assertEquals($expected, $actual);
    }
}
