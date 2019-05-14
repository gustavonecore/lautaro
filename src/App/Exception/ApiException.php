<?php namespace Leftaro\App\Exception;

use Exception;

/**
 * Class for handling api exceptions
 */
class ApiException extends Exception
{
	const UNKNOWN_ERROR = 1;
	const INVALID_PARAMETER = 2;
	const NOT_AUTHORIZED = 3;
	const INVALID_TOKEN = 4;
	const RESOURCE_NOT_FOUND = 5;
	const SLUG_ALREADY_EXISTS = 6;
	const ENTITY_ALREADY_DELETED = 7;
	const AUTHENTICATION_ERROR = 8;
	const NOT_ALLOWED_EXTERNAL_TRANSACTIONS = 9;
	const ACCOUNT_ALREADY_EXIST = 10;
	const USERNAME_ALREADY_EXIST = 11;
	const TRANSACTION_BETWEEN_ENTITIES = 12;

    const ACL_FORBBIDEN_ACTION = 1001;
    const ACL_PERMISSION_DENIED = 1002;
    const ACL_INVALID_ROLE = 1003;
    const ACL_NOT_OWNER = 1004;

	/**
	 * Get the proper HTTP status code
	 *
	 * @return int
	 */
	public function getHttpCode() : int
	{
		return 500;
	}
}