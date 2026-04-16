<?php $title = 'Entrar'; ?>

<section class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="brand"><div class="brand-icon">V</div><span>VortexHost</span></div>
        </div>
        <h1 class="auth-title">Bem-vindo de volta.</h1>
        <p class="auth-sub">Acesse sua área do cliente para gerenciar seus serviços.</p>

        <form method="POST" action="<?= e(base_url('/login')) ?>" novalidate>
            <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">

            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <input class="form-input" type="email" id="email" name="email"
                       required value="<?= e(old('email')) ?>" autocomplete="email" placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Senha</label>
                <input class="form-input" type="password" id="password" name="password"
                       required autocomplete="current-password" placeholder="••••••••">
            </div>

            <button class="btn btn-primary btn-full" style="margin-top:.5rem" type="submit">ENTRAR</button>
        </form>

        <p class="auth-foot">Ainda não tem conta? <a href="<?= e(base_url('/registro')) ?>">Criar conta grátis</a></p>
    </div>
</section>