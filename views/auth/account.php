<?php $title = 'Minha conta'; ?>

<div class="account-page">
    <div class="container">

        <!-- Header -->
        <div class="account-head">
            <div>
                <div class="eyebrow">Área do cliente</div>
                <h1 class="account-name">Olá, <?= e($user['name']) ?> 👋</h1>
                <div class="account-meta">
                    <span><?= e($user['email']) ?></span>
                    <span class="role-badge <?= $user['role'] === 'root' ? 'role-root' : 'role-client' ?>">
                        <?= $user['role'] === 'root' ? '★ Root' : 'Cliente' ?>
                    </span>
                    <span>Desde <?= e(date('d/m/Y', strtotime($user['created_at']))) ?></span>
                </div>
            </div>
            <form method="POST" action="<?= e(base_url('/logout')) ?>">
                <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">
                <button class="btn btn-ghost" type="submit">Sair da conta</button>
            </form>
        </div>

        <!-- Grid -->
        <div class="account-grid">
            <!-- Sidebar info -->
            <aside>
                <div class="info-card">
                    <h3>Dados da Conta</h3>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="info-item-lbl">Nome</span>
                            <span class="info-item-val"><?= e($user['name']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-lbl">E-mail</span>
                            <span class="info-item-val"><?= e($user['email']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-lbl">Tipo de conta</span>
                            <span class="info-item-val">
                                <span class="role-badge <?= $user['role'] === 'root' ? 'role-root' : 'role-client' ?>">
                                    <?= $user['role'] === 'root' ? '★ Administrador' : 'Cliente' ?>
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-item-lbl">Membro desde</span>
                            <span class="info-item-val"><?= e(date('d/m/Y', strtotime($user['created_at']))) ?></span>
                        </div>
                    </div>
                </div>
                <?php if ($user['role'] === 'root'): ?>
                <div class="info-card" style="margin-top:1rem">
                    <h3>Acesso Admin</h3>
                    <p style="color:var(--muted);font-size:.875rem;margin-bottom:1rem">
                        Você tem acesso ao painel de administração.
                    </p>
                    <a class="btn btn-outline btn-full" href="<?= e(base_url('/admin')) ?>">Painel Admin →</a>
                </div>
                <?php endif; ?>
            </aside>

            <!-- Main content -->
            <section>
                <div class="section-head" style="margin-bottom:1rem">
                    <div class="eyebrow">SEUS SERVICOS</div>
                    <h2 class="section-title">Servicos provisionados</h2>
                    <p class="section-sub">Acesse rapidamente os servidores criados apos a confirmacao do pagamento.</p>
                </div>

                <?php if (empty($services)): ?>
                    <div class="info-card" style="margin-bottom:1.5rem">
                        <p style="color:var(--muted);margin-bottom:1rem">Voce ainda nao possui servicos ativos.</p>
                        <a class="btn btn-primary" href="<?= e(base_url('/host')) ?>">Comprar agora</a>
                    </div>
                <?php else: ?>
                    <div class="admin-block" style="margin-bottom:1.5rem">
                        <div class="table-wrap">
                            <table>
                                <thead>
                                <tr>
                                    <th>Servico</th>
                                    <th>Status</th>
                                    <th>Painel</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td style="font-weight:700"><?= e($service['service_name']) ?></td>
                                        <td>
                                            <?php if (($service['status'] ?? '') === 'active'): ?>
                                                <span class="role-badge role-client">Ativo</span>
                                            <?php elseif (($service['status'] ?? '') === 'failed'): ?>
                                                <span class="role-badge role-root">Falhou</span>
                                            <?php else: ?>
                                                <span class="role-badge role-client">Pendente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="color:var(--muted)"><?= e((string) ($service['panel_url'] ?? '-')) ?></td>
                                        <td>
                                            <?php if (($service['status'] ?? '') === 'active'): ?>
                                                <a class="btn btn-primary btn-sm" href="<?= e(base_url('/servicos/acessar?service=' . (int) $service['id'])) ?>">Acessar servico</a>
                                            <?php else: ?>
                                                <span style="color:var(--muted);font-size:.82rem">Aguardando</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="section-head">
                    <div class="eyebrow">PEDIDOS</div>
                    <h2 class="section-title">Historico recente</h2>
                </div>
                <?php if (empty($orders)): ?>
                    <div class="info-card" style="margin-bottom:1.5rem">
                        <p style="color:var(--muted)">Sem pedidos no historico.</p>
                    </div>
                <?php else: ?>
                    <div class="admin-block" style="margin-bottom:1.5rem">
                        <div class="table-wrap">
                            <table>
                                <thead>
                                <tr>
                                    <th># Pedido</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Criado em</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= (int) $order['id'] ?></td>
                                        <td><?= e(strtoupper((string) $order['status'])) ?></td>
                                        <td>R$ <?= number_format((float) $order['total_amount'], 2, ',', '.') ?></td>
                                        <td><?= e(date('d/m/Y H:i', strtotime((string) $order['created_at']))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="section-head">
                    <div class="eyebrow">RECOMENDADOS</div>
                    <h2 class="section-title">Planos em destaque</h2>
                    <p class="section-sub">Escolha o servico ideal para o seu projeto.</p>
                </div>
                <?php if (empty($featuredProducts)): ?>
                    <div class="info-card" style="text-align:center;padding:2rem">
                        <p style="color:var(--muted)">Nenhum plano em destaque no momento.</p>
                        <a class="btn btn-primary" href="<?= e(base_url('/host')) ?>" style="margin-top:1rem">Ver todos os planos</a>
                    </div>
                <?php else: ?>
                <div class="plans-grid" style="grid-template-columns:repeat(auto-fill,minmax(220px,1fr))">
                    <?php foreach ($featuredProducts as $product): ?>
                    <div class="plan-card">
                        <div>
                            <p class="svc-tag"><?= e(strtoupper($product['category'])) ?> · <?= $product['location'] === 'brasil' ? 'BR' : 'CA' ?></p>
                            <p class="plan-name"><?= e($product['name']) ?></p>
                            <p class="plan-desc"><?= e($product['highlight']) ?></p>
                        </div>
                        <div class="price-wrap">
                            <span class="price-amount">R$ <?= number_format((float) $product['price_monthly'], 2, ',', '.') ?></span>
                            <span class="price-period">/mes</span>
                        </div>
                        <ul class="plan-feats">
                            <li><?= e($product['cpu']) ?></li>
                            <li><?= e($product['ram']) ?></li>
                            <li><?= e($product['storage']) ?></li>
                        </ul>
                        <?php $href = base_url('/' . ($product['category'] === 'host' ? 'host' : $product['category'])); ?>
                        <a class="btn btn-ghost btn-full" href="<?= e($href) ?>">Ver planos</a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>
        </div>

    </div>
</div>