<?php $title = 'Carrinho'; ?>

<section class="plan-section">
    <div class="container">
        <div class="section-head">
            <div class="eyebrow">CHECKOUT</div>
            <h1 class="section-title">Seu Carrinho</h1>
            <p class="section-sub">Confirme os planos, finalize no Mercado Pago e o provisionamento sera automatico no Pterodactyl.</p>
        </div>

        <?php if ($items === []): ?>
            <div class="info-card" style="text-align:center;padding:2rem">
                <p style="color:var(--muted)">Seu carrinho esta vazio.</p>
                <a class="btn btn-primary" href="<?= e(base_url('/host')) ?>" style="margin-top:1rem">Ver planos</a>
            </div>
        <?php else: ?>
            <div class="admin-block" style="margin-bottom:1rem">
                <div class="table-wrap">
                    <table>
                        <thead>
                        <tr>
                            <th>Plano</th>
                            <th>Categoria</th>
                            <th>Local</th>
                            <th>Qtd</th>
                            <th>Valor</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td style="font-weight:700"><?= e($item['name']) ?></td>
                                <td><?= e(strtoupper($item['category'])) ?></td>
                                <td><?= $item['location'] === 'brasil' ? 'BR' : 'CA' ?></td>
                                <td><?= (int) $item['quantity'] ?></td>
                                <td>R$ <?= number_format((float) $item['unit_price'], 2, ',', '.') ?></td>
                                <td>
                                    <form method="post" action="<?= e(base_url('/carrinho/remover')) ?>">
                                        <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">
                                        <input type="hidden" name="item_id" value="<?= (int) $item['id'] ?>">
                                        <button class="btn btn-danger btn-sm" type="submit">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="account-grid">
                <div class="info-card">
                    <h3>Resumo</h3>
                    <div class="info-list">
                        <div class="info-item"><span class="info-item-lbl">Subtotal</span><span class="info-item-val">R$ <?= number_format((float) $total, 2, ',', '.') ?></span></div>
                        <div class="info-item"><span class="info-item-lbl">Pagamento</span><span class="info-item-val">Mercado Pago</span></div>
                        <div class="info-item"><span class="info-item-lbl">Provisionamento</span><span class="info-item-val">Automatico</span></div>
                    </div>
                </div>

                <div class="info-card">
                    <h3>Finalizar Compra</h3>
                    <form method="post" action="<?= e(base_url('/checkout')) ?>">
                        <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">

                        <div class="form-group">
                            <label class="form-label" for="account_password">Confirme sua senha do site</label>
                            <input class="form-input" id="account_password" name="account_password" type="password" autocomplete="current-password" required>
                            <small style="color:var(--muted)">Usaremos essa senha para criar/sincronizar seu login no Pterodactyl e liberar acesso rapido na area do cliente.</small>
                        </div>

                        <button class="btn btn-primary" type="submit">Pagar com Mercado Pago</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
