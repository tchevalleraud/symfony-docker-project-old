<?php
    use OpenApi\Annotations as OA;

    /**
     * @OA\Info(
     *     title="Symfony Docker Project - API",
     *     version="0.1",
     *     description="Symfony Docker Project provides RESTful API service for external and internal applications.",
     *     @OA\Contact(email="thibault.chevalleraud@gmail.com")
     * )
     *
     * @OA\Server(
     *     url="http://symfony-docker-project.prod.pwsb.fr/api/v2/",
     *     description="Production API"
     * )
     *
     * @OA\Server(
     *     url="http://symfony-docker-project.dev.pwsb.fr/api/v2/",
     *     description="Development API"
     * )
     *
     * @OA\Server(
     *     url="http://symfony-docker-project.local.pwsb.fr/api/v2/",
     *     description="Local API (don't use)"
     * )
     *
     * @OA\SecurityScheme(
     *     securityScheme="apiKeyAuth",
     *     type="apiKey",
     *     in="header",
     *     name="X-AUTH-TOKEN"
     * )
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     * )
     *
     * @OA\Tag(name="Authentication", description="Authenticate user to access protected resources")
     * @OA\Tag(name="Autorization", description="API token and permissions")
     * @OA\Tag(name="Account", description="Symfony Docker Project Account")
     * @OA\Tag(name="User", description="Current, local and external user management")
     *
     * @OA\Response(
     *     response="BadRequest",
     *     description="400 - Bad Request",
     *     @OA\JsonContent(
     *         @OA\Property(property="code", type="integer", default="400"),
     *         @OA\Property(property="message", type="string", default="Bad Request")
     *     )
     * )
     * @OA\Response(
     *     response="Unauthorized",
     *     description="401 - Unauthorized",
     *     @OA\JsonContent(
     *         @OA\Property(property="code", type="integer", default="401"),
     *         @OA\Property(property="message", type="string", default="Unauthorized")
     *     )
     * )
     * @OA\Response(
     *     response="Forbidden",
     *     description="403 - Forbidden",
     *     @OA\JsonContent(
     *         @OA\Property(property="code", type="integer", default="403"),
     *         @OA\Property(property="message", type="string", default="Forbidden")
     *     )
     * )
     * @OA\Response(
     *     response="NotFound",
     *     description="404 - Not Found",
     *     @OA\JsonContent(
     *         @OA\Property(property="code", type="integer", default="404"),
     *         @OA\Property(property="message", type="string", default="Not Found")
     *     )
     * )
     * @OA\Response(
     *     response="InternalServerError",
     *     description="500 - Internal Server Error",
     *     @OA\JsonContent(
     *         @OA\Property(property="code", type="integer", default="500"),
     *         @OA\Property(property="message", type="string", default="Internal Server Error")
     *     )
     * )
     * @OA\Response(
     *     response="NotImplemented",
     *     description="501 - Not Implemented",
     *     @OA\JsonContent(
     *         @OA\Property(property="code", type="integer", default="501"),
     *         @OA\Property(property="message", type="string", default="Not Implemented")
     *     )
     * )
     * @OA\Response(
     *     response="ServiceUnavailable",
     *     description="503 - Service Unavailable",
     *     @OA\JsonContent(
     *         @OA\Property(property="code", type="integer", default="503"),
     *         @OA\Property(property="message", type="string", default="Service Unavailable")
     *     )
     * )
     *
     * @OA\Schema(
     *     schema="DateTime",
     *     allOf={
     *         @OA\Schema(
     *             @OA\Property(property="date", type="string", format="date-time"),
     *             @OA\Property(property="timezone_type", type="integer", default="3"),
     *             @OA\Property(property="timezone", type="string", default="UTC")
     *         )
     *     }
     * )
     * @OA\Schema(
     *     schema="RequestLogin",
     *     type="object",
     *     @OA\Property(property="username", type="string", description="The login username"),
     *     @OA\Property(property="password", type="string", description="The login password")
     * )
     * @OA\Schema(
     *     schema="Response200",
     *     allOf={
     *         @OA\Schema(
     *             @OA\Property(property="code", type="integer", default="200"),
     *             @OA\Property(property="datetime", type="array", @OA\Items(), ref="#/components/schemas/DateTime"),
     *             @OA\Property(property="message", type="string", default="OK")
     *         )
     *     }
     * )
     */