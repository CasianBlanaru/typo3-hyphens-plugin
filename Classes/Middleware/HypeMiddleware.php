<?php

declare(strict_types = 1);
namespace CA\HypePlugin\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use CA\HypePlugin\Parser\HypeParser;
use CA\HypePlugin\Repository\TermRepository;

final class HypeMiddleware implements MiddlewareInterface
{
    /**
     * @var TermRepository
     */
    private $termRepository;

    /**
     * @var HypeParser
     */
    private $parser;

    public function __construct(HypeParser $parser, TermRepository $termRepository)
    {
        $this->termRepository = $termRepository;
        $this->parser = $parser;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        if ($terms = $this->termRepository->fetchAll()) {
            $output = $response->getBody()->__toString();
            $parsedOutput = $this->parser->replace($terms, $output);
            $response->getBody()->rewind();
            $response->getBody()->write($parsedOutput);
        }
        return $response;
    }
}
