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

-------------------------

Create a command/handler
--------------

To add a new command and the related handler, you just need to run the command

```php
> php leftaro make:command --name="{My\Namespace\Here\CommandNameHere}" --description="{some description}" --args="[type:name, ....]" --services=="[service, ...]"
```

The **args** option it's related to the command arguments, and you can use any type of data like `string, int, bool` and so on.

The **services** option it's related to the required services used by the handler to process the handler, e.g: database, guzzle, twig, etc. All this services are fetched from the services file in `config/local/services.php`.

E.g: Let's add a command to **create a post** record . For this we'll need:
- title (string)
- content (string)
- author (ORM User)
- puslished (DateTimeImmutable)

```php
> php leftaro make:command \
	--name="Post\CreatePost" \
	--description="Create a new Post" \
	--args="string:title,string:content,User:author,DateTimeImmutable:publishedDt" \
	--services="guzzle,logger"
> Leftaro\App\Command\Post\CreatePostCommand was build successfuly
> Leftaro\App\Handler\Post\CreatePostHandler was build successfuly
```

**Generated command**

Here is the  generated command **Leftaro\App\Command\Post\CreatePostCommand**

```php
<?php namespace Leftaro\App\Command\Post;

use Leftaro\App\Command\CommandInterface;

/**
 * Command to Create a new Post
 */
class CreatePostCommand implements CommandInterface
{
	/**
	* @var string Title
	*/
	protected $title;
	/**
	* @var string Content
	*/
	protected $content;
	/**
	* @var User Author
	*/
	protected $author;
	/**
	* @var DateTimeImmutable Publisheddt
	*/
	protected $publishedDt;

	/**
	 * Create the command
	 *
	 * @param string $title
	 * @param string $content
	 * @param User $author
	 * @param DateTimeImmutable $publishedDt
	 */
	public function __construct(string $title, string $content, User $author, DateTimeImmutable $publishedDt)
	{
		$this->title = $title;
		$this->content = $content;
		$this->author = $author;
		$this->publishedDt = $publishedDt;
	}

	/**
	 * @return string
	 */
	public function getTitle() : string
	{
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getContent() : string
	{
		return $this->content;
	}

	/**
	 * @return User
	 */
	public function getAuthor() : User
	{
		return $this->author;
	}

	/**
	 * @return DateTimeImmutable
	 */
	public function getPublishedDt() : DateTimeImmutable
	{
		return $this->publishedDt;
	}
}
```

**Generated handler**

And finally, here is the  generated handler **Leftaro\App\Handler\Post\CreatePostHandler**

```php
<?php namespace Leftaro\App\Handler\Post;

use GuzzleHttp\Client;
use Monolog\Logger;
use Leftaro\App\Handler\HandlerInterface;
use Leftaro\App\Command\Post\CreatePostCommand;

/**
 * Handle the CreatePost command
 */
class CreatePostHandler implements HandlerInterface
{
	/**
	* @var \GuzzleHttp\Client
	*/
	protected $guzzle;
	/**
	* @var \Monolog\Logger
	*/
	protected $logger;

	/**
	 * Create the handler
	 *
	 * @param \GuzzleHttp\Client $guzzle
	 * @param \Monolog\Logger $logger
	 */
	public function __construct(Client $guzzle, Logger $logger)
	{
		$this->guzzle = $guzzle;
		$this->logger = $logger;
	}

	/**
	 * Handle command
	 */
	public function handle(CreatePostCommand $command)
	{
		return [];
	}
}
```