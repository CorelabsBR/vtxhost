<?php $title = 'Painel root'; ?>

<?php
$totalBrasil = count(array_filter($products, fn($p) => $p['location'] === 'brasil'));
$totalCanada = count(array_filter($products, fn($p) => $p['location'] === 'canada'));
?>

<div class="admin-page">
<div class="container">

    <!-- Head -->
    <div class="admin-head">
        <div>
            <div class="eyebrow">GERENCIAMENTO ROOT</div>
            <h1 style="font-size:2rem;font-weight:900">Painel Administrativo</h1>
        </div>
    </div>

    <!-- Stats -->
    <div class="admin-stats">
        <div class="admin-stat-card">
            <p class="admin-stat-lbl">Produtos</p>
            <p class="admin-stat-val"><?= count($products) ?></p>
        </div>
        <div class="admin-stat-card">
            <p class="admin-stat-lbl">Usuários</p>
            <p class="admin-stat-val"><?= count($users) ?></p>
        </div>
        <div class="admin-stat-card">
            <p class="admin-stat-lbl">Produtos Brasil</p>
            <p class="admin-stat-val"><?= $totalBrasil ?></p>
        </div>
        <div class="admin-stat-card">
            <p class="admin-stat-lbl">Produtos Canadá</p>
            <p class="admin-stat-val"><?= $totalCanada ?></p>
        </div>
    </div>

    <!-- Novo produto -->
    <div class="admin-block">
        <div class="admin-block-head">
            <h2>Novo Produto</h2>
        </div>
        <form method="POST" action="<?= e(base_url('/admin/products/create')) ?>">
            <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">
            <div class="admin-form-grid">
                <div class="form-group">
                    <label class="form-label">Nome do plano</label>
                    <input class="form-input" name="name" type="text" placeholder="VPS Core BR" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Categoria</label>
                    <select class="form-input" name="category">
                        <option value="cpanel">cPanel</option>
                        <option value="host">Host</option>
                        <option value="vps">VPS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Localização</label>
                    <select class="form-input" name="location">
                        <option value="brasil">Brasil</option>
                        <option value="canada">Canadá</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">CPU</label>
                    <input class="form-input" name="cpu" type="text" placeholder="4 vCPU" required>
                </div>
                <div class="form-group">
                    <label class="form-label">RAM</label>
                    <input class="form-input" name="ram" type="text" placeholder="8 GB DDR4" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Armazenamento</label>
                    <input class="form-input" name="storage" type="text" placeholder="80 GB NVMe" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tráfego / Banda</label>
                    <input class="form-input" name="bandwidth" type="text" placeholder="2 TB" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Proteção DDoS</label>
                    <input class="form-input" name="ddos_protection" type="text" value="Inclusa" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Preço mensal (R$)</label>
                    <input class="form-input" name="price_monthly" type="number" step="0.01" placeholder="89.90" required>
                </div>
                <div class="form-group admin-form-full">
                    <label class="form-label">Destaque comercial (exibido no card)</label>
                    <input class="form-input" name="highlight" type="text" placeholder="Ideal para jogos e aplicações de alta performance" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ordem de exibição</label>
                    <input class="form-input" name="sort_order" type="number" value="0" required>
                </div>
                <div class="form-group" style="display:flex;align-items:flex-end">
                    <label class="form-check-row">
                        <input type="checkbox" name="featured"> Exibir como destaque
                    </label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit" style="margin-top:.5rem">Cadastrar produto</button>
        </form>
    </div>

    <!-- Tabela produtos -->
    <div class="admin-block">
        <div class="admin-block-head">
            <h2>Produtos Cadastrados</h2>
            <span style="color:var(--muted);font-size:.875rem"><?= count($products) ?> no total</span>
        </div>
        <?php if (empty($products)): ?>
            <p style="color:var(--muted);text-align:center;padding:2rem">Nenhum produto cadastrado ainda.</p>
        <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Local</th>
                        <th>Preço/mês</th>
                        <th>Destaque</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td style="color:var(--dim)"><?= (int) $product['id'] ?></td>
                        <td style="font-weight:600"><?= e($product['name']) ?></td>
                        <td><?= e(strtoupper($product['category'])) ?></td>
                        <td><?= $product['location'] === 'brasil' ? '🇧🇷 BR' : '🇨🇦 CA' ?></td>
                        <td style="color:var(--accent)">R$ <?= number_format((float) $product['price_monthly'], 2, ',', '.') ?></td>
                        <td><?= (int) $product['featured'] === 1 ? '<span style="color:var(--accent)">★ Sim</span>' : '<span style="color:var(--dim)">Não</span>' ?></td>
                        <td class="td-actions">
                            <details class="row-actions">
                                <summary>Editar ▾</summary>
                                <div class="edit-dropdown">
                                    <form method="POST" action="<?= e(base_url('/admin/products/update')) ?>">
                                        <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">
                                        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                                        <div class="admin-form-grid" style="grid-template-columns:1fr 1fr;gap:.625rem">
                                            <div class="form-group"><label class="form-label">Nome</label><input class="form-input" name="name" type="text" value="<?= e($product['name']) ?>" required></div>
                                            <div class="form-group"><label class="form-label">Preço</label><input class="form-input" name="price_monthly" type="number" step="0.01" value="<?= e((string) $product['price_monthly']) ?>" required></div>
                                            <div class="form-group"><label class="form-label">CPU</label><input class="form-input" name="cpu" type="text" value="<?= e($product['cpu']) ?>" required></div>
                                            <div class="form-group"><label class="form-label">RAM</label><input class="form-input" name="ram" type="text" value="<?= e($product['ram']) ?>" required></div>
                                            <div class="form-group"><label class="form-label">Storage</label><input class="form-input" name="storage" type="text" value="<?= e($product['storage']) ?>" required></div>
                                            <div class="form-group"><label class="form-label">Tráfego</label><input class="form-input" name="bandwidth" type="text" value="<?= e($product['bandwidth']) ?>" required></div>
                                            <div class="form-group" style="grid-column:1/-1"><label class="form-label">Destaque comercial</label><input class="form-input" name="highlight" type="text" value="<?= e($product['highlight']) ?>" required></div>
                                            <div class="form-group"><label class="form-label">DDoS</label><input class="form-input" name="ddos_protection" type="text" value="<?= e($product['ddos_protection']) ?>" required></div>
                                            <div class="form-group"><label class="form-label">Ordem</label><input class="form-input" name="sort_order" type="number" value="<?= (int) $product['sort_order'] ?>" required></div>
                                            <div class="form-group">
                                                <label class="form-label">Categoria</label>
                                                <select class="form-input" name="category">
                                                    <option value="cpanel" <?= $product['category'] === 'cpanel' ? 'selected' : '' ?>>cPanel</option>
                                                    <option value="host" <?= $product['category'] === 'host' ? 'selected' : '' ?>>Host</option>
                                                    <option value="vps" <?= $product['category'] === 'vps' ? 'selected' : '' ?>>VPS</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Local</label>
                                                <select class="form-input" name="location">
                                                    <option value="brasil" <?= $product['location'] === 'brasil' ? 'selected' : '' ?>>Brasil</option>
                                                    <option value="canada" <?= $product['location'] === 'canada' ? 'selected' : '' ?>>Canadá</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="grid-column:1/-1">
                                                <label class="form-check-row">
                                                    <input type="checkbox" name="featured" <?= (int) $product['featured'] === 1 ? 'checked' : '' ?>> Exibir como destaque
                                                </label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-full" type="submit">Salvar alterações</button>
                                    </form>
                                    <form method="POST" action="<?= e(base_url('/admin/products/delete')) ?>" style="margin-top:.5rem"
                                          onsubmit="return confirm('Excluir <?= e(addslashes($product['name'])) ?>?')">
                                        <input type="hidden" name="_csrf" value="<?= e(\App\Core\Session::csrfToken()) ?>">
                                        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                                        <button class="btn btn-danger btn-full" type="submit">Excluir produto</button>
                                    </form>
                                </div>
                            </details>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <!-- Tabela usuários -->
    <div class="admin-block">
        <div class="admin-block-head">
            <h2>Usuários Cadastrados</h2>
            <span style="color:var(--muted);font-size:.875rem"><?= count($users) ?> no total</span>
        </div>
        <?php if (empty($users)): ?>
            <p style="color:var(--muted);text-align:center;padding:2rem">Nenhum usuário cadastrado ainda.</p>
        <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Papel</th>
                        <th>Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $registeredUser): ?>
                    <tr>
                        <td style="color:var(--dim)"><?= (int) $registeredUser['id'] ?></td>
                        <td style="font-weight:600"><?= e($registeredUser['name']) ?></td>
                        <td style="color:var(--muted)"><?= e($registeredUser['email']) ?></td>
                        <td>
                            <span class="role-badge <?= $registeredUser['role'] === 'root' ? 'role-root' : 'role-client' ?>">
                                <?= $registeredUser['role'] === 'root' ? '★ Root' : 'Cliente' ?>
                            </span>
                        </td>
                        <td style="color:var(--muted)"><?= e(date('d/m/Y H:i', strtotime($registeredUser['created_at']))) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

</div>
</div>