<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\App\Exception\AuthorizedException;
use Leftaro\App\Exception\Acl\ForbbidenActionException;
use Leftaro\App\Exception\Acl\InvalidRoleException;
use Leftaro\App\Exception\Acl\PermissionDeniedException;
use Leftaro\Core\ContainerAwareInterface;
use Leftaro\Core\ContainerAwareTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AclMiddleware implements MiddlewareInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null) : ResponseInterface
	{
		$allowedMethods = $this->container->get('config')->get('app.acl_whitelist_methods');
		$allowedResources = $this->container->get('config')->get('app.acl_whitelist_resources');

		$method = $request->method();
        $action = self::getPermissionFlag($method);
        $resource = self::getCanonicResource($request->getPathInfo());
        $authUser = $request->getAttribute('authenticated_user');

        if (in_array($method, $allowedMethods) || in_array($resource, $allowedResources))
        {
            return $next($request, $response);
        }

        if (!$authUser)
        {
            throw new AuthorizedException;
        }

        if (!$action)
        {
            throw new ForbbidenActionException($method);
        }

        if (!$authUser->role)
        {
            throw new InvalidRoleException;
        }

        if (!$authUser->getRole()->hasPermission($action, $resource))
        {
            throw new PermissionDeniedException;
        }

		return $next($request, $response);
	}


    /**
     * Get the mapped action from the HTTP method name
     *
     * @param string $method
     * @return midex string|null
     */
    public static function getPermissionFlag(string $method)
    {
        if ($method === 'POST')
        {
            return 'create';
        }

        if ($method === 'GET')
        {
            return 'read';
        }

        if ($method === 'PATCH' || $method === 'PUT')
        {
            return 'update';
        }

        if ($method === 'DELETE')
        {
            return 'delete';
        }

        return null;
    }

    /**
     * Test if the given method allow the required action
     *
     * @param string $method     HTTP Method name
     * @param string $action     Action related to test
     * @return integer
     */
    public static function isActionEnabledByMethod(string $method, string $action) : int
    {
        return (int)($action === self::getPermissionFlag($method));
    }

    /**
     * Get a canonic representation of the HTTP url path.
     * e.g:
     *        /company/10/user/20 -> /company/{company_id}/user
     *        /company/10/user/   -> /company/{company_id}/user
     *        /company/10         -> /company
     *        /company            -> /company
     *
     * @param string $path
     * @return string
     */
    public static function getCanonicResource(string $path) : string
    {
        $i = 1;
        $parts = array_values(array_filter(explode('/', str_replace('/api/', '/', $path))));
        $canonic = [];
        $resource = null;

        foreach ($parts as $part)
        {
            if ($resource === null)
            {
                $resource = $part;
            }

            if ($i % 2 === 0)
            {
                $part = '{' . strtolower($resource) . '_id}';
                $resource = null;
            }

            $canonic[] = $part;

            $i++;
        }

        if (count($canonic) % 2 === 0)
        {
            unset($canonic[count($canonic) -1]);
        }

        return '/' . implode('/', $canonic);
    }
}