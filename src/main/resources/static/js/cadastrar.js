const form = document.getElementById('cadastroForm');
const apiError = document.getElementById('jsError');

if (form) {
    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        if (!apiError) return;

        apiError.style.display = 'none';
        apiError.textContent = '';

        const data = {
            nome: document.getElementById('nome').value,
            email: document.getElementById('email').value,
            senha: document.getElementById('senha').value,
            cpfCnpj: document.getElementById('cpfCnpj').value,
            celular: document.getElementById('celular').value,
            dataNasc: document.getElementById('dataNasc').value || null,
            cep: document.getElementById('cep').value,
            rua: document.getElementById('rua').value,
            numero: document.getElementById('numero').value,
            complemento: document.getElementById('complemento').value,
            bairro: document.getElementById('bairro').value,
            cidade: document.getElementById('cidade').value,
            estado: document.getElementById('estado').value,
            pais: document.getElementById('pais').value
        };

        const confirmacao = document.getElementById('senhaConfirmacao').value;
        if (confirmacao !== data.senha) {
            apiError.textContent = 'As senhas não coincidem.';
            apiError.style.display = 'block';
            return;
        }

        try {
            const response = await fetch('/api/cadastro', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                apiError.textContent = result.message || 'Não foi possível criar a conta.';
                apiError.style.display = 'block';
                return;
            }

            window.location.href = '/login';
        } catch (error) {
            apiError.textContent = 'Erro ao conectar com o servidor. Tente novamente.';
            apiError.style.display = 'block';
        }
    });
}
