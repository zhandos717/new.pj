<?php

declare(strict_types=1);

namespace App;

class Authorization
{
    /**
     * @var Database
     */

    private Database $database;

    /**
     * Authorization constructor
     * @param Database $database
     */

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param array $data
     * @return bool
     * @throws AuthorizationException
     */

    public function register(array $data): bool
    {
        if (empty($data['username'])) {
            throw new AuthorizationException('The Username should not be empty');
        }
        if (empty($data['email'])) {
            throw new AuthorizationException('The Email should not be empty');
        }
        if (empty($data['password'])) {
            throw new AuthorizationException('The Password should not be empty');
        }
        if ($data['confirm_password'] !== $data['password']) {
            throw new AuthorizationException('The Password and Confrm Password should match');
        }
        return \true;
    }
}