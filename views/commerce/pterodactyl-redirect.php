<?php $title = 'Acessando painel'; ?>

<section class="auth-page">
    <div class="auth-card" style="max-width:560px;width:100%">
        <h1 class="auth-title">Abrindo seu servico</h1>
        <p class="auth-sub">Estamos enviando seu login para o painel automaticamente.</p>

        <div class="info-card" style="margin-bottom:1rem">
            <h3>Servico</h3>
            <div class="info-list">
                <div class="info-item"><span class="info-item-lbl">Plano</span><span class="info-item-val"><?= e($service['service_name'] ?? '-') ?></span></div>
                <div class="info-item"><span class="info-item-lbl">Painel</span><span class="info-item-val"><?= e($panelLoginUrl) ?></span></div>
                <div class="info-item"><span class="info-item-lbl">Usuario</span><span class="info-item-val"><?= e($service['panel_username'] ?? '-') ?></span></div>
            </div>
        </div>

        <form id="pteroAutoLogin" method="post" action="<?= e($panelLoginUrl) ?>">
            <input type="hidden" name="email" value="<?= e($service['panel_username'] ?? '') ?>">
            <input type="hidden" name="password" value="<?= e($panelPassword ?? '') ?>">
            <input type="hidden" name="remember" value="on">
            <noscript>
                <button class="btn btn-primary" type="submit">Entrar no Painel</button>
            </noscript>
        </form>

        <a class="btn btn-ghost" href="<?= e(base_url('/conta')) ?>" style="margin-top:.75rem">Voltar para conta</a>
    </div>
</section>

<script>
(function () {
    var form = document.getElementById('pteroAutoLogin');
    if (form) {
        setTimeout(function () { form.submit(); }, 450);
    }
})();
</script>
