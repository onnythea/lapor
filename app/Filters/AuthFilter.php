<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter autentikasi + Role Based Access Control.
 *
 * Contoh penggunaan pada Routes.php:
 *   ['filter' => 'auth']                 -> hanya butuh login
 *   ['filter' => 'auth:admin']           -> hanya role admin
 *   ['filter' => 'auth:petugas,admin']   -> role petugas ATAU admin
 */
class AuthFilter implements FilterInterface
{
    /**
     * @param list<string>|null $arguments Daftar role yang diizinkan.
     */
    public function before(RequestInterface $request, $arguments = null): ResponseInterface|null
    {
        $session = session();

        // Belum login -> arahkan ke halaman login.
        if (! $session->get('is_logged_in')) {
            $session->setFlashdata('error', 'Silakan login terlebih dahulu.');

            return redirect()->to('/auth/login');
        }

        // Tidak ada batasan role -> cukup login.
        if ($arguments === null || $arguments === []) {
            return null;
        }

        $role = (string) $session->get('role');

        // Gunakan match expression PHP 8 untuk keputusan akses.
        $allowed = in_array($role, $arguments, true);

        return match ($allowed) {
            true  => null,
            false => $this->deny(),
        };
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): ResponseInterface|null
    {
        return null;
    }

    private function deny(): ResponseInterface
    {
        session()->setFlashdata('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');

        return redirect()->to('/')->withStatus(403);
    }
}
