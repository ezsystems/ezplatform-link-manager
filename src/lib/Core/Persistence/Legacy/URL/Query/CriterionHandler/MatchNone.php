<?php

namespace EzSystems\EzPlatformLinkManager\Core\Persistence\Legacy\URL\Query\CriterionHandler;

use EzSystems\EzPlatformLinkManager\API\Repository\Values\Query;
use EzSystems\EzPlatformLinkManager\API\Repository\Values\Query\Criterion;
use EzSystems\EzPlatformLinkManager\Core\Persistence\Legacy\URL\Query\CriteriaConverter;
use EzSystems\EzPlatformLinkManager\Core\Persistence\Legacy\URL\Query\CriterionHandler;
use eZ\Publish\Core\Persistence\Database\SelectQuery;

class MatchNone implements CriterionHandler
{
    /**
     * {@inheritdoc}
     */
    public function accept(Criterion $criterion)
    {
        return $criterion instanceof Query\MatchNone;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(CriteriaConverter $converter, SelectQuery $query, Criterion $criterion)
    {
        return $query->expr->not($query->bindValue('1'));
    }
}
