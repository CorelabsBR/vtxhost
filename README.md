# VortexHost

Site completo para venda de hospedagem de jogos, hospedagem web e VPS, feito em PHP com Composer.

## Requisitos

- PHP 8.2+
- Composer
- MySQL ou MariaDB

## Instalação

1. Instale as dependências:

```bash
composer install
```

2. Copie o arquivo de ambiente:

```bash
copy .env.example .env
```

3. Ajuste as credenciais do banco no `.env`.

4. Configure integrações no `.env`:

```bash
APP_KEY=uma-chave-secreta-forte
MP_ACCESS_TOKEN=seu_token_mercado_pago
PTERODACTYL_PANEL_URL=https://painel.seudominio.com
PTERODACTYL_APP_API_KEY=ptla_xxxxxxxxx
```

5. Execute o setup inicial:

```bash
composer setup
```

6. Rode localmente:

```bash
composer serve
```

## Estrutura

- Landing page inspirada em provedores premium de hosting, com identidade em verde.
- Páginas separadas para cPanel, hospedagem web e VPS.
- Produtos carregados do banco de dados.
- Cadastro, login e logout.
- Carrinho, checkout com Mercado Pago e webhook de confirmação.
- Provisionamento automático no Pterodactyl após pagamento aprovado.
- Área administrativa acessível apenas para usuários com role `root`.

## Usuário root inicial

O setup cria um usuário root com as credenciais definidas em:

- `APP_SETUP_ROOT_EMAIL`
- `APP_SETUP_ROOT_PASSWORD`

Altere esses valores antes de rodar em produção.

## comando pra rodar
- C:\xampp\php\php.exe -S localhost:8000 
-t public