<?php

use Firebase\JWT\JWT;

function makeTokenForUser(int $id = 1): string
{
    return JWT::encode(
        ['sub' => $id],
        env('JWT_SECRET') ?? config('jwt.secret'),
        'HS256'
    );
}

function authHeaders(?int $id = null): array
{
    if ($id === null)
        return [];

    return [
        'Authorization' => 'Bearer ' . makeTokenForUser($id)
    ];
}
