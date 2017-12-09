<?php

namespace EzSystems\EzPlatformLinkManager\Tests\Core\Persistence\Legacy\URL\Query;

use eZ\Publish\Core\Persistence\Database\Expression;
use eZ\Publish\Core\Persistence\Database\SelectQuery;
use EzSystems\EzPlatformLinkManager\API\Repository\Values\Query\Criterion;
use EzSystems\EzPlatformLinkManager\Core\Persistence\Legacy\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLinkManager\Core\Persistence\Legacy\URL\Query\CriterionHandler;
use PHPUnit\Framework\TestCase;

class CriteriaConverterTest extends TestCase
{
    public function testConvertCriteriaSuccess()
    {
        $fooCriterionHandler = $this->createMock(CriterionHandler::class);
        $barCriterionHandler = $this->createMock(CriterionHandler::class);

        $criteriaConverter = new CriteriaConverter([
            $fooCriterionHandler,
            $barCriterionHandler,
        ]);

        $barCriterion = $this->createMock(Criterion::class);

        $selectQuery = $this->createMock(SelectQuery::class);
        $expression = $this->createMock(Expression::class);

        $fooCriterionHandler
            ->expects($this->once())
            ->method('accept')
            ->with($barCriterion)
            ->willReturn(false);

        $fooCriterionHandler
            ->expects($this->never())
            ->method('handle');

        $barCriterionHandler
            ->expects($this->once())
            ->method('accept')
            ->with($barCriterion)
            ->willReturn(true);

        $barCriterionHandler
            ->expects($this->once())
            ->method('handle')
            ->with($criteriaConverter, $selectQuery, $barCriterion)
            ->willReturn($expression);

        $this->assertEquals($expression, $criteriaConverter->convertCriteria(
            $selectQuery, $barCriterion
        ));
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotImplementedException
     */
    public function testConvertCriteriaFailure()
    {
        $criteriaConverter = new CriteriaConverter();
        $criteriaConverter->convertCriteria(
            $this->createMock(SelectQuery::class),
            $this->createMock(Criterion::class)
        );
    }
}
