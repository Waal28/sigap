<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
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
            if ($request instanceof IncomingRequest && $request->isAJAX()) {
                /** @var ResponseInterface $response */
                $response = service('response');

                return $response
                    ->setStatusCode(401)
                    ->setJSON(['status' => 'error', 'message' => 'Silakan login terlebih dahulu']);
            }

            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu untuk mengakses sistem.');
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
