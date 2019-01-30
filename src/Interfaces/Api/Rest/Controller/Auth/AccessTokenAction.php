<?php

declare(strict_types=1); // strict mode

namespace App\Interfaces\Api\Rest\Controller\Auth;

use App\Domain\User\Auth\AuthProviderInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Psr\Http\Message\ServerRequestInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Zend\Diactoros\Response as Psr7Response;

final class AccessTokenAction
{

    /** @var AuthProviderInterface */
    private $authProvider;

    /**
     * AccessTokenAction constructor.
     *
     * @param AuthProviderInterface $authProvider
     */
    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    /**
     * @Route(
     *     "oauth/token",
     *     name="api_get_access_token",
     *     methods={"POST"},
     *     requirements={
     *         "grant_type": "\w+",
     *         "client_id": "\w+",
     *         "client_secret": "\w+",
     *         "scope": "\w+",
     *         "username": "\w+",
     *         "password": "\w+",
     *     }
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns token with scopes",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials"
     * )
     * @SWG\Parameter(
     *     name="grant_type",
     *     in="formData",
     *     type="string",
     *     description="The type of grant",
     *     default="password",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="client_id",
     *     in="formData",
     *     type="string",
     *     description="The ID of an App client",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="client_secret",
     *     in="formData",
     *     type="string",
     *     description="The App client's secret",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="scope",
     *     in="formData",
     *     type="string",
     *     description="User's scope, example: *",
     *     default="*",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="username",
     *     in="formData",
     *     type="string",
     *     description="User's email or phone number",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     description="User's password",
     *     required=true
     * )
     * @SWG\Tag(name="Authentication")
     * @Security(name="Bearer")
     *
     * @param ServerRequestInterface $request
     *
     * @return null|Psr7Response
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request): ?Psr7Response
    {
        return $this->authProvider->getAccessToken($request);
    }
}
