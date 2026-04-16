<?php $title = 'Registro'; ?>

<section class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="brand"><div class="brand-icon">V</div><span>VortexHost</span></div>
        </div>
        <h1 class="auth-title">Comece gratuitamente.</h1>
        <p class="auth-sub">Crie sua conta e acesse a melhor infraestrutura do Brasil e Canadá.</p>

        <form method="POST" action="<?= e(base_url('/registro')) ?>" novalidate>
            <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">

            <div class="form-group">
                <label class="form-label" for="name">Nome completo</label>
                <input class="form-input" type="text" id="name" name="name"
                       required value="<?= e(old('name')) ?>" autocomplete="name" placeholder="João Silva">
            </div>
            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <input class="form-input" type="email" id="email" name="email"
                       required value="<?= e(old('email')) ?>" autocomplete="email" placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Senha</label>
                <input class="form-input" type="password" id="password" name="password"
                       required autocomplete="new-password" placeholder="Mínimo 8 caracteres">
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirmar senha</label>
                <input class="form-input" type="password" id="password_confirmation"
                       name="password_confirmation" required autocomplete="new-password"
                       placeholder="Repita a senha">
            </div>

            <button class="btn btn-primary btn-full" style="margin-top:.5rem" type="submit">CRIAR CONTA</button>
        </form>

        <p class="auth-foot">Já tem conta? <a href="<?= e(base_url('/login')) ?>">Entrar</a></p>
    </div>
</section>