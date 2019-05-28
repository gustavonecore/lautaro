Leftaro Commands
=============================

Create a controller
--------------

To add a new controller you just need to run the command

```php
> php leftaro make:controller {My\Namespace\Here\ControllerName}
```

E.g:

```php
> php leftaro make:controller "Account\PushNotificationRequest"
> Leftaro\App\Controller\Api\Account\PushNotificationRequestController was build successfuly
```

As you can see, a new controller will be placed inside the defined namespace with the related context:

```php
<?php namespace Leftaro\App\Controller\Api\Account;

use Leftaro\App\Controller\Api\ApiController;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * Controller to handle Leftaro\App\Controller\Api\Account\PushNotificationRequest requests
 */
class PushNotificationRequestController extends ApiController
{
	/**
	 * @var mixed PushNotificationRequest record
	 */
	protected $pushNotificationRequest;

	/**
	 * {@inheritDoc}
	 */
	public function before(ServerRequest $request, Response $response) : Response
	{
		$response = parent::before($request, $response);

		if ($request->getAttribute('push_notification_request_id'))
		{
			// TODO :: You need to replace the 'id' column by the required one in your PushNotificationRequest context
			$this->pushNotificationRequest = $this->bus->getEntityOrFail('push_notification_request', 'id', $request->getAttribute('push_notification_request_id'));
		}

		return $response;
	}

	/**
	 * Create a new PushNotificationRequest
	 *
	 * @param ServerRequest $request      Request instance
	 * @param Response      $response     Response instance
	 * @return Response
	 */
	public function postCollectionAction(ServerRequest $request, Response $response) : Response
	{
		return $this->json([
			'data' => [
				'push_notification_request' => [],
			],
		]);
	}

	/**
	 * Get a PushNotificationRequest
	 *
	 * @param ServerRequest $request      Request instance
	 * @param Response      $response     Response instance
	 * @return Response
	 */
	public function getResourceAction(ServerRequest $request, Response $response) : Response
	{
		return $this->json([
			'data' => [
				'push_notification_request' => $this->pushNotificationRequest->asArray(),
			],
		]);
	}

	/**
	 * Update a PushNotificationRequest
	 *
	 * @param ServerRequest $request      Request instance
	 * @param Response      $response     Response instance
	 * @return Response
	 */
	public function patchResourceAction(ServerRequest $request, Response $response) : Response
	{
		return $this->json([
			'data' => [
				'push_notification_request' => $this->pushNotificationRequest->asArray(),
			],
		]);
	}

	/**
	 * Delete a PushNotificationRequest
	 *
	 * @param ServerRequest $request      Request instance
	 * @param Response      $response     Response instance
	 * @return Response
	 */
	public function deleteResourceAction(ServerRequest $request, Response $response) : Response
	{
		return $this->json([
			'data' => [
				'push_notification_request' => [],
			],
		]);
	}

	/**
	 * List PushNotificationRequests
	 *
	 * @param ServerRequest $request      Request instance
	 * @param Response      $response     Response instance
	 * @return Response
	 */
	public function getCollectionAction(ServerRequest $request, Response $response) : Response
	{
		return $this->json([
			'data' => [
				'push_notification_requests' => [],
			],
		]);
	}
}
```