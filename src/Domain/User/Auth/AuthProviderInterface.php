<?php

declare(strict_types=1); // strict mode

namespace App\Domain\User\Auth;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;

interface AuthProviderInterface
{

    /**
     * @param ServerRequestInterface $request
     *
     * @return null|Psr7Response
     * @throws \Exception
     */
    public function getAccessToken(ServerRequestInterface $request): ?Psr7Response;

}
