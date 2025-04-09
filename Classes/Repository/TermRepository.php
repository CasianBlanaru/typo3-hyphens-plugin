<?php

namespace CasianBlanaru\HyphensPlugin\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TermRepository
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    ) {
    }

    /**
     * Findet alle aktiven Trennungsterme
     *
     * @return array
     */
    public function findAll(): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_hype_term');

        return $queryBuilder
            ->select('from', 'to')
            ->from('tx_hype_term')
            ->where(
                $queryBuilder->expr()->eq('hidden', 0),
                $queryBuilder->expr()->eq('deleted', 0)
            )
            ->execute()
            ->fetchAllAssociative() ?? [];
    }
}
