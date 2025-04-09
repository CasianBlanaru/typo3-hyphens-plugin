<?php

declare(strict_types=1);

namespace TPWD\Hyphens\Tests\Unit\Middleware;

use TPWD\Hyphens\Middleware\HyphensMiddleware;
use TPWD\Hyphens\Parser\HyphenParser;
use TPWD\Hyphens\Repository\TermRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class HyphensMiddlewareTest extends UnitTestCase
{
    protected $middleware;
    protected $parserProphecy;
    protected $termRepositoryProphecy;
    protected $requestProphecy;
    protected $handlerProphecy;
    protected $responseProphecy;
    protected $streamProphecy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parserProphecy = $this->prophesize(HyphenParser::class);
        $this->termRepositoryProphecy = $this->prophesize(TermRepository::class);
        $this->requestProphecy = $this->prophesize(ServerRequestInterface::class);
        $this->handlerProphecy = $this->prophesize(RequestHandlerInterface::class);
        $this->responseProphecy = $this->prophesize(ResponseInterface::class);
        $this->streamProphecy = $this->prophesize(StreamInterface::class);

        $this->middleware = new HyphensMiddleware(
            $this->parserProphecy->reveal(),
            $this->termRepositoryProphecy->reveal()
        );
    }

    /**
     * @test
     */
    public function processReturnsUnmodifiedResponseWhenNoTermsFound(): void
    {
        $this->termRepositoryProphecy->fetchAll()->willReturn(null);

        $this->handlerProphecy
            ->handle($this->requestProphecy->reveal())
            ->willReturn($this->responseProphecy->reveal());

        $result = $this->middleware->process(
            $this->requestProphecy->reveal(),
            $this->handlerProphecy->reveal()
        );

        self::assertSame($this->responseProphecy->reveal(), $result);
    }

    /**
     * @test
     */
    public function processModifiesResponseWhenTermsFound(): void
    {
        $terms = [
            ['from' => 'test', 'to' => 'te|st']
        ];
        $originalContent = 'This is a test';
        $modifiedContent = 'This is a te&shy;st';

        $this->termRepositoryProphecy->fetchAll()->willReturn($terms);

        $this->handlerProphecy
            ->handle($this->requestProphecy->reveal())
            ->willReturn($this->responseProphecy->reveal());

        $this->responseProphecy->getBody()->willReturn($this->streamProphecy->reveal());
        $this->streamProphecy->__toString()->willReturn($originalContent);
        $this->parserProphecy->replace($terms, $originalContent)->willReturn($modifiedContent);

        $this->streamProphecy->rewind()->shouldBeCalled();
        $this->streamProphecy->write($modifiedContent)->shouldBeCalled();

        $result = $this->middleware->process(
            $this->requestProphecy->reveal(),
            $this->handlerProphecy->reveal()
        );

        self::assertSame($this->responseProphecy->reveal(), $result);
    }
}
