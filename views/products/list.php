<?php
/**
 * @var string $title
 * @var string $description
 * @var string $category
 * @var array  $products
 */

$hasLocations = in_array($category, ['host', 'vps'], true);
$byLocation   = [];
foreach ($products as $p) {
    $byLocation[$p['location']][] = $p;
}
$locations = array_keys($byLocation);
?>

<!-- ══ PAGE HERO ═══════════════════════════════════════════ -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero-inner">
            <div>
                <div class="eyebrow"><?= e(strtoupper($category)) ?></div>
                <h1 class="page-hero-title"><?= e($title) ?></h1>
                <p class="page-hero-desc"><?= e($description) ?></p>
                <div class="feat-pills" style="margin-top:1rem">
                    <?php if ($category === 'vps'): ?>
                        <span class="feat-pill">Acesso Root Total</span>
                        <span class="feat-pill">NVMe Enterprise</span>
                        <span class="feat-pill">IP Dedicado</span>
                        <span class="feat-pill">Anti-DDoS</span>
                    <?php elseif ($category === 'cpanel'): ?>
                        <span class="feat-pill">cPanel Oficial</span>
                        <span class="feat-pill">SSL Gratuito</span>
                        <span class="feat-pill">E-mails Ilimitados</span>
                        <span class="feat-pill">Backup Diário</span>
                    <?php else: ?>
                        <span class="feat-pill">SSL Gratuito</span>
                        <span class="feat-pill">Backup Diário</span>
                        <span class="feat-pill">Anti-DDoS</span>
                        <span class="feat-pill">BR + Canadá</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hero-info-card">
                <p class="info-lbl">COBERTURA</p>
                <p class="info-val">
                    <?= $category === 'cpanel' ? '🇧🇷 Brasil' : '🇧🇷 Brasil + 🇨🇦 Canadá' ?>
                </p>
                <div class="info-rows">
                    <div class="info-row"><span>Anti-DDoS</span><span>Incluso</span></div>
                    <div class="info-row"><span>Uptime alvo</span><span>99.9%</span></div>
                    <div class="info-row"><span>Ativação</span><span>Rápida</span></div>
                    <div class="info-row"><span>Suporte</span><span>Humano</span></div>
                    <?php if ($category === 'vps'): ?>
                        <div class="info-row"><span>Acesso root</span><span>Total</span></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ PLANOS ══════════════════════════════════════════════ -->
<section class="plan-section">
    <div class="container">

        <!-- Billing switcher -->
        <div class="billing-switcher">
            <button class="billing-btn active" data-billing="monthly">Mensal</button>
            <button class="billing-btn" data-billing="quarterly">Trimestral <span class="billing-disc">-5%</span></button>
            <button class="billing-btn" data-billing="semiannual">Semestral <span class="billing-disc">-10%</span></button>
            <button class="billing-btn" data-billing="annual">Anual <span class="billing-disc">-15%</span></button>
        </div>

        <?php if ($hasLocations && count($locations) > 1): ?>
        <!-- Location switcher -->
        <div class="location-switcher">
            <?php foreach ($locations as $loc): ?>
                <button class="location-btn" data-location="<?= e($loc) ?>">
                    <?= $loc === 'brasil' ? '🇧🇷 Brasil' : '🇨🇦 Canadá' ?>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Cards -->
        <?php if (empty($products)): ?>
            <div style="text-align:center;padding:4rem 0;color:var(--muted)">
                <p style="font-size:1.1rem">Nenhum plano cadastrado ainda.</p>
                <p style="font-size:.875rem;margin-top:.5rem">O administrador pode adicionar planos no <a href="<?= e(base_url('/admin')) ?>" style="color:var(--accent)">painel admin</a>.</p>
            </div>
        <?php else: ?>
        <div class="plans-grid">
            <?php foreach ($products as $i => $product): ?>
            <div class="plan-card <?= $i === 1 ? 'popular' : '' ?>"
                 data-location="<?= e($product['location']) ?>"
                 data-base-price="<?= number_format((float) $product['price_monthly'], 2, '.', '') ?>">

                <?php if ($i === 1): ?>
                    <div class="popular-label">MAIS ESCOLHIDO</div>
                <?php endif; ?>

                <div>
                    <p class="plan-location-tag">
                        <?= $product['location'] === 'brasil' ? '🇧🇷' : '🇨🇦' ?>
                        <?= e(ucfirst($product['location'])) ?>
                    </p>
                    <p class="plan-name"><?= e($product['name']) ?></p>
                    <p class="plan-desc"><?= e($product['highlight']) ?></p>
                </div>

                <div class="price-wrap">
                    <span class="price-amount"
                          data-price="<?= number_format((float) $product['price_monthly'], 2, '.', '') ?>">
                        R$ <?= number_format((float) $product['price_monthly'], 2, ',', '.') ?>
                    </span>
                    <span class="price-period">/mês</span>
                </div>

                <ul class="plan-feats">
                    <li><?= e($product['cpu']) ?></li>
                    <li><?= e($product['ram']) ?></li>
                    <li><?= e($product['storage']) ?></li>
                    <li>Tráfego: <?= e($product['bandwidth']) ?></li>
                    <li>DDoS: <?= e($product['ddos_protection']) ?></li>
                    <?php if ($category === 'cpanel'): ?>
                        <li>SSL Gratuito incluso</li>
                        <li>Backup diário</li>
                    <?php elseif ($category === 'vps'): ?>
                        <li>Acesso root total</li>
                        <li>IP dedicado</li>
                    <?php else: ?>
                        <li>SSL Gratuito incluso</li>
                        <li>E-mails profissionais</li>
                    <?php endif; ?>
                </ul>

                <?php if (\App\Core\Auth::check()): ?>
                    <form method="post" action="<?= e(base_url('/carrinho/adicionar')) ?>">
                        <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">
                        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                        <button class="btn btn-primary btn-full" type="submit">ADICIONAR AO CARRINHO</button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-primary btn-full" href="<?= e(base_url('/registro')) ?>">CRIAR CONTA PARA COMPRAR</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($category === 'vps'): ?>
        <!-- OS Section -->
        <div class="os-row" style="margin-top:3rem">
            <p class="os-section-lbl" style="width:100%">Sistemas Operacionais Suportados</p>
            <div class="os-item"><div class="os-icon">🐧</div>Ubuntu</div>
            <div class="os-item"><div class="os-icon">🐧</div>Debian</div>
            <div class="os-item"><div class="os-icon">🎩</div>AlmaLinux</div>
            <div class="os-item"><div class="os-icon">🪟</div>Windows</div>
            <div class="os-item"><div class="os-icon">☁️</div>CloudLinux</div>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- ══ FEATURES (compacto) ═════════════════════════════════ -->
<section class="features-section" style="padding-block:3rem">
    <div class="container">
        <div class="eyebrow">ENGENHARIA DE PONTA</div>
        <h2 class="section-title" style="margin-bottom:3rem">Engenharia de Ponta.</h2>

        <div class="feature-block">
            <div class="feature-content">
                <div class="feat-badge"><span class="feat-badge-lbl">HARDWARE DEDICADO</span><span class="feat-badge-sub">PERFORMANCE</span></div>
                <h3 class="feat-title">Sua máquina, suas regras.</h3>
                <p class="feat-desc">Potência bruta em processadores de última geração. Recursos isolados, sem vizinhos barulhentos.</p>
                <div class="feat-pills">
                    <span class="feat-pill">Recursos isolados</span>
                    <span class="feat-pill">Alta frequência</span>
                    <span class="feat-pill">Sem vizinho barulhento</span>
                </div>
            </div>
            <div>
                <div class="feat-visual-card">
                    <span class="feat-glyph">⚡</span>
                    <div class="feat-rows">
                        <div class="feat-row"><span>CPU</span><span class="val">Ryzen 9 / Xeon</span></div>
                        <div class="feat-row"><span>Storage</span><span class="val">NVMe Enterprise</span></div>
                        <div class="feat-row"><span>Proteção</span><span class="val">L3/L4/L7</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="feature-block reverse" style="border-bottom:none">
            <div class="feature-content">
                <div class="feat-badge"><span class="feat-badge-lbl">BACKUPS AUTOMÁTICOS</span><span class="feat-badge-sub">SEGURANÇA</span></div>
                <h3 class="feat-title">Segurança de Dados.</h3>
                <p class="feat-desc">Rotinas de backup isoladas para garantir que você nunca perca o progresso do seu servidor ou site.</p>
                <div class="feat-pills">
                    <span class="feat-pill">Rotina recorrente</span>
                    <span class="feat-pill">Camada isolada</span>
                    <span class="feat-pill">Restauração rápida</span>
                </div>
            </div>
            <div>
                <div class="feat-visual-card">
                    <span class="feat-glyph">💾</span>
                    <div class="feat-rows">
                        <div class="feat-row"><span>Frequência</span><span class="val">Diária</span></div>
                        <div class="feat-row"><span>Retenção</span><span class="val">7 dias</span></div>
                        <div class="feat-row"><span>Restauração</span><span class="val">Rápida</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ FAQ ════════════════════════════════════════════════= -->
<section class="faq-section">
    <div class="container">
        <div class="faq-grid">
            <div>
                <div class="eyebrow">RESPOSTAS RÁPIDAS E DIRETAS</div>
                <h2 class="section-title" style="margin-bottom:2rem">Dúvidas Frequentes</h2>
                <div class="faq-list">
                    <details class="faq-item">
                        <summary>A ativação é imediata?</summary>
                        <p>Após confirmação do pagamento e validação operacional, o provisionamento é iniciado rapidamente.</p>
                    </details>
                    <details class="faq-item">
                        <summary>Posso migrar meu site atual?</summary>
                        <p>Sim. Nossa equipe auxilia na migração de conteúdo e configurações para minimizar o tempo de inatividade.</p>
                    </details>
                    <details class="faq-item">
                        <summary>A hospedagem tem proteção Anti-DDoS?</summary>
                        <p>Sim. Mitigação empresarial inclusa em todos os planos, capaz de filtrar ataques nas camadas L3, L4 e L7.</p>
                    </details>
                    <details class="faq-item">
                        <summary>Qual o método de pagamento?</summary>
                        <p>Aceitamos PIX com aprovação instantânea, Cartão de Crédito, PayPal, Stripe e Boleto Bancário.</p>
                    </details>
                </div>
            </div>
            <div>
                <div class="support-card">
                    <p class="support-tag">PRECISA DE AJUDA?</p>
                    <h3 class="support-title">Suporte humano, sem robôs.</h3>
                    <p class="support-desc">Nossa equipe técnica atua diretamente nos datacenters. Sem robôs chatos, suporte de humano para humano.</p>
                    <div class="support-btns">
                        <a class="btn btn-primary" href="mailto:contato@vortexhost.com.br">Suporte via Ticket <span style="font-size:.75em;opacity:.8">SLA 15 MIN</span></a>
                        <a class="btn btn-ghost" href="#">Base de Conhecimento</a>
                    </div>
                    <div class="support-stats">
                        <div class="support-stat"><span class="support-stat-lbl">ATENDIMENTO</span><span class="support-stat-val">Especializado</span></div>
                        <div class="support-stat"><span class="support-stat-lbl">RESPOSTA</span><span class="support-stat-val">Ágil</span></div>
                        <div class="support-stat"><span class="support-stat-lbl">GUIAS</span><span class="support-stat-val">Prontos</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ OUTROS SERVIÇOS ═════════════════════════════════════ -->
<section class="services-section">
    <div class="container">
        <div class="section-head center">
            <div class="eyebrow">O QUE VOCÊ QUER TIRAR DO PAPEL HOJE?</div>
            <h2 class="section-title">Escolha o serviço ideal<br>para o seu projeto.</h2>
            <p class="section-sub">Infraestrutura robusta de verdade, sem letras miúdas.</p>
        </div>
        <div class="services-grid">
            <?php if ($category !== 'host'): ?>
            <div class="svc-card">
                <div>
                    <p class="svc-tag">HOSPEDAGEM WEB</p>
                    <h3 class="svc-title">Seu Projeto Sempre Online.</h3>
                    <p class="svc-desc">Hospedagem com SSL, backup e presença no Brasil e Canadá.</p>
                    <ul class="svc-feats" style="margin-top:1rem">
                        <li>SSL Gratuito</li><li>Backup Diário</li><li>BR + Canadá</li>
                    </ul>
                </div>
                <a class="btn btn-ghost" href="<?= e(base_url('/host')) ?>">EXPLORAR PLANOS →</a>
            </div>
            <?php endif; ?>
            <?php if ($category !== 'vps'): ?>
            <div class="svc-card">
                <div>
                    <p class="svc-tag">VPS PREMIUM</p>
                    <h3 class="svc-title">Você no Comando de Tudo.</h3>
                    <p class="svc-desc">Máquinas virtuais com NVMe, root total e IP dedicado.</p>
                    <ul class="svc-feats" style="margin-top:1rem">
                        <li>Acesso Root Total</li><li>NVMe Enterprise</li><li>IP Dedicado</li>
                    </ul>
                </div>
                <a class="btn btn-primary" href="<?= e(base_url('/vps')) ?>">EXPLORAR PLANOS →</a>
            </div>
            <?php endif; ?>
            <?php if ($category !== 'cpanel'): ?>
            <div class="svc-card">
                <div>
                    <p class="svc-tag">SITE (CPANEL)</p>
                    <h3 class="svc-title">Simplicidade que Funciona.</h3>
                    <p class="svc-desc">cPanel oficial com SSL gratuito e e-mails ilimitados no Brasil.</p>
                    <ul class="svc-feats" style="margin-top:1rem">
                        <li>cPanel Oficial</li><li>SSL Gratuito</li><li>E-mails Ilimitados</li>
                    </ul>
                </div>
                <a class="btn btn-ghost" href="<?= e(base_url('/cpanel')) ?>">EXPLORAR PLANOS →</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>