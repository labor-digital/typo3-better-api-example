<?php
/*
 * Copyright 2021 LABOR.digital
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Last modified: 2021.04.29 at 22:21
 */

declare(strict_types=1);


namespace LaborDigital\Typo3BetterApiExample\Middleware;


use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class DemoMiddleware
 *
 * This is a super simple middleware that acts as an example on how to configure a middleware
 * and how to register constructor arguments to middlewares
 *
 * @package LaborDigital\Typo3BetterApiExample\Middleware
 * @see     \LaborDigital\Typo3BetterApiExample\Configuration\ExtConfig\DiContainer
 * @see     \LaborDigital\Typo3BetterApiExample\Configuration\ExtConfig\Http
 */
class DemoMiddleware implements MiddlewareInterface
{
    /**
     * @var string|null
     */
    protected $message;
    
    /**
     * @var string|null
     */
    protected $route;
    
    /**
     * @var \Psr\Http\Message\ResponseFactoryInterface
     */
    protected $responseFactory;
    
    /**
     * DemoMiddleware constructor.
     *
     * @param   string|null                                 $message
     * @param   string|null                                 $route
     * @param   \Psr\Http\Message\ResponseFactoryInterface  $responseFactory
     */
    public function __construct(?string $message, ?string $route, ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
        $this->message = $message;
        $this->route = $route;
    }
    
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (str_starts_with($request->getUri()->getPath(), $this->route)) {
            return $this->responseFactory->createResponse()->withBody(Utils::streamFor($this->message));
        }
        
        return $handler->handle($request);
    }
    
}
