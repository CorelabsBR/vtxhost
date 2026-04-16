</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="brand">
                    <div class="brand-icon">V</div>
                    <strong>VortexHost</strong>
                </div>
                <p>Criamos a infraestrutura que sempre faltou: desempenho bruto, estabilidade real e suporte que resolve de verdade.</p>
                <a href="mailto:contato@vortexhost.com.br">contato@vortexhost.com.br</a>
                <span class="footer-status" style="margin-top:.875rem;display:inline-flex">SISTEMAS ONLINE</span>
            </div>

            <div class="footer-col">
                <h4>Serviços</h4>
                <ul class="footer-links">
                    <li><a href="<?= e(base_url('/host')) ?>">Hospedagem Web</a></li>
                    <li><a href="<?= e(base_url('/vps')) ?>">VPS Premium</a></li>
                    <li><a href="<?= e(base_url('/cpanel')) ?>">cPanel Brasil</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Suporte</h4>
                <ul class="footer-links">
                    <li><a href="<?= e(base_url('/conta')) ?>">Área do Cliente</a></li>
                    <li><a href="<?= e(base_url('/login')) ?>">Login</a></li>
                    <li><a href="<?= e(base_url('/registro')) ?>">Criar Conta</a></li>
                    <li><a href="mailto:contato@vortexhost.com.br">Abrir Ticket</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Legal</h4>
                <ul class="footer-links">
                    <li><a href="#">Termos de Serviço</a></li>
                    <li><a href="#">Política de Privacidade</a></li>
                    <li><a href="#">Política de Reembolso</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span class="footer-copy">© <?= date('Y') ?> VortexHost. Todos os direitos reservados.</span>

            <div class="footer-payments">
                <span class="payment-badge">PIX</span>
                <span class="payment-badge">Cartão</span>
                <span class="payment-badge">Boleto</span>
                <span class="payment-badge">PayPal</span>
                <span class="payment-badge">Stripe</span>
            </div>

            <div class="footer-social">
                <a class="social-link" href="#" title="Discord">💬</a>
                <a class="social-link" href="#" title="Instagram">📷</a>
                <a class="social-link" href="#" title="WhatsApp">📱</a>
            </div>
        </div>
    </div>
</footer>

<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>