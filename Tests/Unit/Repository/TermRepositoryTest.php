<?php

namespace CasianBlanaru\HyphensPlugin\Tests\Unit\Repository;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use CasianBlanaru\HyphensPlugin\Repository\TermRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Result;
use PHPUnit\Framework\MockObject\MockObject;

class TermRepositoryTest extends TestCase
{
    private TermRepository $subject;
    private MockObject $connectionPool;
    private MockObject $queryBuilder;
    private MockObject $expressionBuilder;
    private MockObject $result;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connectionPool = $this->createMock(ConnectionPool::class);
        $this->queryBuilder = $this->createMock(QueryBuilder::class);
        $this->expressionBuilder = $this->createMock(ExpressionBuilder::class);
        $this->result = $this->createMock(Result::class);

        $this->connectionPool->expects($this->once())
            ->method('getQueryBuilderForTable')
            ->with('tx_hype_term')
            ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects($this->atLeastOnce())
            ->method('expr')
            ->willReturn($this->expressionBuilder);

        $this->subject = new TermRepository($this->connectionPool);
    }

    #[Test]
    public function findAllReturnsArrayOfTerms(): void
    {
        $terms = [
            [
                'from' => 'Energieversorgungssysteme',
                'to' => 'Energie|versorgungs|systeme'
            ],
            [
                'from' => 'Automatisierungstechnologien',
                'to' => 'Automatisierungs|technologien'
            ]
        ];

        $this->queryBuilder->expects($this->once())
            ->method('select')
            ->with('from', 'to')
            ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects($this->once())
            ->method('from')
            ->with('tx_hype_term')
            ->willReturn($this->queryBuilder);

        $this->expressionBuilder->expects($this->exactly(2))
            ->method('eq')
            ->willReturnOnConsecutiveCalls('hidden = 0', 'deleted = 0');

        $this->queryBuilder->expects($this->once())
            ->method('where')
            ->willReturn($this->queryBuilder);

        $this->queryBuilder->expects($this->once())
            ->method('execute')
            ->willReturn($this->result);

        $this->result->expects($this->once())
            ->method('fetchAllAssociative')
            ->willReturn($terms);

        $result = $this->subject->findAll();
        $this->assertEquals($terms, $result);
    }
}
