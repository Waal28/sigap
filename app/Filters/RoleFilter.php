<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * @param array<int, string>|null $arguments
     *
     * @return ResponseInterface|null
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (empty($arguments)) {
            return null;
        }

        // Expand any comma-separated values in arguments
        $allowedRoles = [];
        foreach ($arguments as $arg) {
            $parts = array_map('trim', explode(',', $arg));
            foreach ($parts as $part) {
                if ($part !== '') {
                    $allowedRoles[] = strtoupper($part);
                }
            }
        }

        $userRole = strtoupper((string) $session->get('role'));

        if (! in_array($userRole, $allowedRoles, true)) {
            if ($request instanceof IncomingRequest && $request->isAJAX()) {
                /** @var ResponseInterface $response */
                $response = service('response');

                return $response
                    ->setStatusCode(403)
                    ->setJSON(['status' => 'error', 'message' => 'Akses ditolak']);
            }

            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk halaman tersebut.');
        }

        return null;
    }

    /**
     * @param array<int, string>|null $arguments
     *
     * @return ResponseInterface|null
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
