<?php namespace Leftaro\App;

use Exception;
use Gcore\Sanitizer\Template\RequiredFieldsException;
use Leftaro\App\Exception\ApiException;
use Leftaro\Core\Exception\ExceptionHandler as LeftaroExceptionHandler;
use Leftaro\Core\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Propel\Runtime\Exception\EntityNotFoundException;
use Zend\Diactoros\Response\{
	JsonResponse,
	HtmlResponse
};

/**
 * Custom exception handler
 */
class ExceptionHandler extends LeftaroExceptionHandler
{
	/**
	 * {@inheritDoc}
	 */
	public function getResponse(Exception $e, RequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		if ($e instanceof ApiException)
		{
			return $this->apiExceptionToResponse($e);
		}

		if ($e instanceof NotFoundException)
		{
			return $this->notFoundResponse($e->getRequest());
		}

		if ($e instanceof EntityNotFoundException)
		{
			return $this->notFoundResponse($request, $e->getMessage());
		}

		if ($e instanceof RequiredFieldsException)
		{
			return new JsonResponse([
				'error' => $e->getMessage(),
				'code' => ApiException::INVALID_PARAMETER,
				'params' => $e->getErrors(),
			], 400);
		}

		return parent::getResponse($e, $request, $response);
	}

	/**
	 * Transform the given api exception to an http response
	 *
	 * @param ApiException $e
	 * @return ResponseInterface
	 */
	private function apiExceptionToResponse(ApiException $e) : ResponseInterface
	{
		// TODO: move this code into a new custom Response like ApiResponse
		$details = [
			'error' => $e->getMessage(),
			'code' => $e->getCode(),
		];

		if ($e instanceof \Leftaro\App\Exception\MissingParameterException)
		{
			$details['params'] = $e->getParams();
		}

		return new JsonResponse($details, $e->getHttpCode());
	}

	/**
	 * Builds an not found response
	 *
	 * @param RequestInterface $request
	 * @return ResponseInterface
	 */
	private function notFoundResponse(RequestInterface $request, string $message = null) : ResponseInterface
	{
		if ($request->getAttribute('is_ajax') === true)
		{
			return new JsonResponse([
				'error' => 'Resource not found',
				'description' => is_null($message) ? 'The requested resource ' . $request->getUri()->getPAth() . ' was not found' : $message,
			], 404);
		}

		$html = $this->container->get('twig')->render('error/404.twig', [
			'title' => 'Page not found',
			'description' => 'The requested page "' . $request->getUri()->getPAth() . '" was not found',
		]);

		return new HtmlResponse($html, 404);
	}
}