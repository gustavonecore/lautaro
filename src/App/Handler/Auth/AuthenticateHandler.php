<?php namespace Leftaro\App\Handler\Auth;

use DateTimeImmutable;
use Leftaro\App\Exception\AuthenticationException;
use Leftaro\App\Exception\ResourceNotFoundException;
use Leftaro\App\Handler\HandlerInterface;
use Leftaro\App\Command\Auth\AuthenticateCommand;
use Leftaro\App\Command\Auth\AuthenticateOutCommand;
use Leftaro\App\Model\UserQuery;
use Leftaro\App\Model\Token;

/**
 * Handle the authenticate command
 */
class AuthenticateHandler implements HandlerInterface
{
	const TYPE_TOKEN = 'access';

	/**
	 * Process command
	 *
	 * @param AuthenticateCommand $command
	 * @return void
	 */
	public function handle(AuthenticateCommand $command)
	{
		$user = UserQuery::create()->findOneByEmail($command->getUsername());

		if (!$user)
		{
			throw new ResourceNotFoundException('user for ' . $command->getUsername());
		}

		if (!password_verify($command->getPassword(), $user->getPassword()))
		{
			throw new AuthenticationException;
		}

		$token = $user->getLastToken(self::TYPE_TOKEN);

		if ($token === null)
		{
			$now = new DateTimeImmutable('now UTC');

			$token = Token::create([
				'user_id' => $user->getId(),
				'type' => self::TYPE_TOKEN,
				'token' => substr(uniqid(), 0, 22),
				'created_dt' => gmdate('Y-m-d H:i:s'),
				'expire_dt' => $now->modify('+2 weeks')->format('Y-m-d H:i:s'),
			]);
		}

		return new AuthenticateOutCommand($user, $token);
	}
}