<?php namespace Leftaro\App\Controller;

use DI\Container;
use Gcore\Sanitizer\Template\TemplateSanitizer;
use Gcore\Sanitizer\Template\TemplateInterface;
use Leftaro\Core\Controller\AbstractController;
use Leftaro\App\Exception\MissingParameterException;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * Base controller
 */
class BaseController extends AbstractController
{
	/**
	 * @var Leftaro\App\Hex\CommandBus
	 */
	protected $bus;

	/**
	 * @var Gcore\Sanitizer\Template\TemplateSanitizer
	 */
	protected $sanitizer;

	/**
	 * {@inheritDoc}
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;

		$this->bus = $container->get('bus');
	}

	/**
	 * {@inheritDoc}
	 */
	public function before(ServerRequest $request, Response $response) : Response
	{
		$response = parent::before($request, $response);

		return $response;
	}

	/**
	 * Sanitize the request input
	 *
	 * @param array $template  Template for sanitize engine
	 * @return array
	 */
	public function sanitizeRequest(array $template, array $input) : array
	{
		$this->sanitizer = $this->getSanitizer($template)->sanitize($input);

		return $this->sanitizer;
	}

	/**
	 * Get a template sanitizer by the given template
	 *
	 * @param array $template
	 * @return TemplateInterface
	 */
	public function getSanitizer(array $template) : TemplateInterface
	{
		$this->sanitizer =  new TemplateSanitizer($template);

		return $this->sanitizer;
	}

	public function verify(array $template, array $input, array $required = [])
	{
		$sanitizer =  new TemplateSanitizer($template);

		$clean = $sanitizer->sanitize($input);

		$missingFields = [];

		foreach ($required as $field)
		{
			if ($clean[$field] === null || $clean[$field] === '')
			{
				$missingFields[] = $field;
			}
		}

		if ($missingFields !== [])
		{
			throw new MissingParameterException($missingFields);
		}

		return $clean;
	}

	/**
	 * Wraps the sanitizer require function to throw the proper API exception
	 *
	 * @param array $fields
	 * @return void
	 */
	public function requireFields(array $fields)
	{
		$missingFields = [];

		foreach ($fields as $field)
		{
			if (!isset($this->sanitizer->output[$field]) || $this->sanitizer->output[$field] === null)
			{
				$missingFields[] = $field;
			}
		}

		if ($missingFields !== [])
		{
			throw new MissingParameterException($missingFields);
		}
	}

	/**
	 * Add cors
	 *
	 * @param ServerRequest $request
	 * @param Response $response
	 * @return Response
	 */
	public function optionsCollectionAction(ServerRequest $request, Response $response) : Response
	{
		return new EmptyResponse(200);
	}

	/**
	 * Add cors
	 *
	 * @param ServerRequest $request
	 * @param Response $response
	 * @return Response
	 */
	public function optionsResourceAction(ServerRequest $request, Response $response) : Response
	{
		return new EmptyResponse(200);
	}
}