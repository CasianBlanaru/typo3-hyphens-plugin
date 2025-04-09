<?php
namespace CasianBlanaru\HyphensPlugin\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use CasianBlanaru\HyphensPlugin\Middleware\HyphenatorMiddleware;
use CasianBlanaru\HyphensPlugin\Parser\HyphenParser;
use CasianBlanaru\HyphensPlugin\Repository\TermRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PHPUnit\Framework\MockObject\MockObject;

class HyphenatorTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/ca_hyphens_plugin'
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importDataSet(__DIR__ . '/../Fixtures/Database/pages.xml');
    }

    #[Test]
    public function middlewareProcessesRequestCorrectly(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $handler = $this->createMock(RequestHandlerInterface::class);

        $middleware = new HyphenatorMiddleware(
            $this->createMock(HyphenParser::class),
            $this->createMock(TermRepository::class)
        );

        $response = $middleware->process($request, $handler);

        $this->assertNotNull($response);
    }
}
