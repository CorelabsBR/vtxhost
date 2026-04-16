<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Session;
use App\Models\HostedService;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        Auth::ensureGuest();
        $this->view('auth/login');
    }

    public function login(): void
    {
        Auth::ensureGuest();

        if (! Session::validateCsrf($_POST['_csrf'] ?? null)) {
            Session::flash('error', 'Token inválido. Atualize a página e tente novamente.');
            $this->redirect('/login');
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        Session::putOld(['email' => $email]);

        if ($email === '' || $password === '') {
            Session::flash('error', 'Informe e-mail e senha.');
            $this->redirect('/login');
        }

        if (! Auth::attempt($email, $password)) {
            Session::flash('error', 'Credenciais inválidas.');
            $this->redirect('/login');
        }

        Session::clearOld();
        Session::flash('success', 'Login realizado com sucesso.');
        $this->redirect('/conta');
    }

    public function showRegister(): void
    {
        Auth::ensureGuest();
        $this->view('auth/register');
    }

    public function register(): void
    {
        Auth::ensureGuest();

        if (! Session::validateCsrf($_POST['_csrf'] ?? null)) {
            Session::flash('error', 'Token inválido. Atualize a página e tente novamente.');
            $this->redirect('/registro');
        }

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

        Session::putOld(['name' => $name, 'email' => $email]);

        if ($name === '' || $email === '' || $password === '') {
            Session::flash('error', 'Preencha todos os campos obrigatórios.');
            $this->redirect('/registro');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Digite um e-mail válido.');
            $this->redirect('/registro');
        }

        if (strlen($password) < 8) {
            Session::flash('error', 'A senha precisa ter ao menos 8 caracteres.');
            $this->redirect('/registro');
        }

        if ($password !== $passwordConfirmation) {
            Session::flash('error', 'As senhas não coincidem.');
            $this->redirect('/registro');
        }

        $users = new User();

        if ($users->findByEmail($email)) {
            Session::flash('error', 'Já existe uma conta com este e-mail.');
            $this->redirect('/registro');
        }

        $users->create($name, $email, $password);
        Auth::attempt($email, $password);

        Session::clearOld();
        Session::flash('success', 'Conta criada com sucesso.');
        $this->redirect('/conta');
    }

    public function logout(): void
    {
        Auth::ensureAuthenticated();

        if (! Session::validateCsrf($_POST['_csrf'] ?? null)) {
            Session::flash('error', 'Token inválido.');
            $this->redirect('/conta');
        }

        Auth::logout();
        Session::flash('success', 'Sessão encerrada.');
        $this->redirect('/');
    }

    public function account(): void
    {
        Auth::ensureAuthenticated();

        $user = Auth::user();

        $this->view('auth/account', [
            'user' => $user,
            'featuredProducts' => (new Product())->featured(3),
            'services' => (new HostedService())->byUser((int) ($user['id'] ?? 0)),
            'orders' => (new Order())->byUser((int) ($user['id'] ?? 0), 10),
        ]);
    }
}