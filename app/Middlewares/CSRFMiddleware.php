<?php

namespace Middlewares;

use Src\Request;
use Src\Session;

class CSRFMiddleware
{
    public function handle(Request $request): void
    {
        if ($request->method === 'POST') {
            $token = $request->get('csrf_token') ?? ($_POST['csrf_token'] ?? null);
            $sessionToken = Session::get('csrf_token') ?? ($_SESSION['csrf_token'] ?? null);

            if (!$token || !$sessionToken || !hash_equals((string)$sessionToken, (string)$token)) {
                http_response_code(403);
                die('403 Forbidden: CSRF token invalid');
            }
        }
    }
}
