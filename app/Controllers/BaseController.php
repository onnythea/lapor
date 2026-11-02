<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;

    /**
     * Helper yang otomatis dimuat untuk seluruh controller.
     *
     * @var list<string>
     */
    protected $helpers = ['form', 'url', 'text', 'ticket'];

    protected $session;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);

        $this->session = service('session');
    }

    /**
     * Data user yang sedang login (nullsafe-friendly).
     *
     * @return array<string, mixed>|null
     */
    protected function currentUser(): ?array
    {
        return $this->session->get('user');
    }

    protected function currentUserId(): ?int
    {
        $id = $this->currentUser()['id'] ?? null;

        return $id !== null ? (int) $id : null;
    }
}
