<?php declare(strict_types=1);

namespace App\Model\User\Exception;

class JWTDecodeFailureException extends JWTFailureException
{
    const INVALID_TOKEN = 'invalid_token';

    const UNVERIFIED_TOKEN = 'unverified_token';

    const EXPIRED_TOKEN = 'expired_token';
}
