<?php
/**
 * Página de produtos Minecraft
 * Dados hardcoded por enquanto
 */

$tiers = [
    [
        'id'   => 1,
        'name' => 'Tier Carvão',
        'ram'  => '2 GB',
        'storage' => '40 GB',
        'processor' => '-',
        'price' => 19.99,
        'description' => 'Perfeito para começar'
    ],
    [
        'id'   => 2,
        'name' => 'Tier Ferro',
        'ram'  => '4 GB',
        'storage' => '60 GB',
        'processor' => '-',
        'price' => 36.49,
        'description' => 'Para servidores pequenos'
    ],
    [
        'id'   => 3,
        'name' => 'Tier Ouro',
        'ram'  => '8 GB',
        'storage' => '80 GB',
        'processor' => '-',
        'price' => 49.99,
        'description' => 'Desempenho otimizado'
    ],
    [
        'id'   => 4,
        'name' => 'Tier Diamante',
        'ram'  => 'Ilimitado',
        'storage' => '100 GB',
        'processor' => '-',
        'price' => 59.49,
        'description' => 'Para comunidades'
    ],
    [
        'id'   => 5,
        'name' => 'Tier Netherite',
        'ram'  => 'Ilimitado',
        'storage' => 'Ilimitado',
        'processor' => '-',
        'price' => 70.49,
        'description' => 'Poder máximo'
    ]
];
?>

<!-- ══ PAGE HERO ═══════════════════════════════════════════ -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero-inner">
            <div>
                <div class="eyebrow">MINECRAFT</div>
                <h1 class="page-hero-title">Servidores Minecraft</h1>
                <p class="page-hero-desc">Hospedagem de alta performance para seus servidores Minecraft</p>
                <div class="feat-pills" style="margin-top:1rem">
                    <span class="feat-pill">Acesso Total</span>
                    <span class="feat-pill">FTP Ilimitado</span>
                    <span class="feat-pill">Console de Gerenciamento</span>
                    <span class="feat-pill">Backup Automático</span>
                </div>
            </div>
            <div class="hero-info-card">
                <p class="info-lbl">COBERTURA</p>
                <p class="info-val">🇧🇷 Brasil</p>
                <div class="info-rows">
                    <div class="info-row"><span>Anti-DDoS</span><span>Incluso</span></div>
                    <div class="info-row"><span>Uptime alvo</span><span>99.9%</span></div>
                    <div class="info-row"><span>Ativação</span><span>Rápida</span></div>
                    <div class="info-row"><span>Suporte</span><span>Humano</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ PLANOS ══════════════════════════════════════════════ -->
<section class="plan-section">
    <div class="container">
        <div class="plans-grid">
            <?php foreach ($tiers as $i => $tier): ?>
            <div class="plan-card" data-product-id="<?= (int) $tier['id'] ?>">
                <div>
                    <p class="plan-location-tag">🎮 Minecraft</p>
                    <p class="plan-name"><?= e($tier['name']) ?></p>
                    <p class="plan-desc"><?= e($tier['description']) ?></p>
                </div>

                <div class="price-wrap">
                    <span class="price-amount">R$ <?= number_format($tier['price'], 2, ',', '.') ?></span>
                    <span class="price-period">/mês</span>
                </div>

                <ul class="plan-feats">
                    <li>RAM: <?= e($tier['ram']) ?></li>
                    <li>Armazenamento: <?= e($tier['storage']) ?></li>
                    <li>Processador: <?= e($tier['processor']) ?></li>
                    <li>Anti-DDoS incluso</li>
                    <li>Suporte 24/7</li>
                    <li>Backup automático</li>
                </ul>

                <?php if (\App\Core\Auth::check()): ?>
                    <form method="post" action="<?= e(base_url('/carrinho/adicionar')) ?>">
                        <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">
                        <input type="hidden" name="product_id" value="<?= (int) $tier['id'] ?>">
                        <button class="btn btn-primary btn-full" type="submit">ADICIONAR AO CARRINHO</button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-primary btn-full" href="<?= e(base_url('/registro')) ?>">CRIAR CONTA PARA COMPRAR</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
