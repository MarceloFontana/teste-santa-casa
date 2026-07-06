# Módulo de Acessos - Santa Casa de Misericórdia de Porto Alegre

Módulo administrativo para centralizar o gerenciamento de usuários e o controle de acesso aos sistemas internos da instituição, desenvolvido como teste técnico para a vaga de Desenvolvedor de Sistemas Júnior (Laravel).

## Requisitos

- PHP >= 8.2
- Composer
- MySQL ou MariaDB
- Node.js e npm (para compilar os assets do front-end)
- Git

### Ambiente utilizado no desenvolvimento

Versões exatas instaladas e usadas para desenvolver e testar este projeto:

| Ferramenta | Versão |
| --- | --- |
| PHP | 8.2.12 |
| Composer | 2.10 |
| Laravel Framework | 12.62.0 |
| MariaDB | 10.4.32 |
| Node.js | v22.19.0 |
| npm | 10.9.3 |
| Git | 2.49.0 |

## Instalação

```bash
# Clonar o repositório
git clone https://github.com/MarceloFontana/teste-santa-casa.git
cd teste-santa-casa

# Instalar dependências PHP
composer install

# Instalar dependências JS e compilar os assets
npm install
npm run build
```

## Configuração

```bash
# Copiar o arquivo de ambiente
cp .env.example .env

# Gerar a chave da aplicação
php artisan key:generate
```

Edite o `.env` com as credenciais do seu banco de dados MySQL/MariaDB:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=santa_casa_acessos
DB_USERNAME=root
DB_PASSWORD=
```

Crie o banco de dados (o Laravel não cria o schema automaticamente):

```sql
CREATE DATABASE santa_casa_acessos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Migrations e Seeders

```bash
# Roda as migrations e os seeders (roles, permissões e usuário admin)
php artisan migrate --seed
```

Os seeders criam:

- As roles `admin` e `colaborador`;
- As permissões dos 4 módulos operacionais (`setores-hospitalares`, `especialidades-medicas`, `equipamentos`, `unidades-assistenciais`);
- Um usuário administrador (veja credenciais abaixo).

## Execução

```bash
php artisan serve
```

Acesse `http://localhost:8000`.

## Testes automatizados

```bash
php artisan test
```

Cobrem autenticação, perfil, e especificamente o controle de acesso e o bloqueio de usuário: gerenciamento de permissões (`PermissionManagementTest`), acesso aos módulos por role/permission (`ModuleAccessControlTest`) e o bloqueio de login (`UserBlockingTest`).

## Credenciais

| Perfil | E-mail | Senha |
| --- | --- | --- |
| Administrador | `admin@santacasa.org.br` | `password` |

## Tecnologias utilizadas

- **Laravel 12.62.0** (PHP 8.2.12)
- **Laravel Breeze v2.4.2** (stack Blade) para autenticação (login)
- **Spatie Laravel Permission 6.25.0** para roles e permissões
- **Tailwind CSS** para estilização
- **Laravel Pint v1.29.3** para padronização de código
- **MySQL/MariaDB 10.4.32** como banco de dados

## Decisões técnicas

- **Roles vs. Permissions**: o perfil **Administrador** é modelado como uma *role* (`admin`) e controla o acesso às telas de Usuários e Permissões via middleware `role:admin`. Já o acesso aos módulos operacionais pelo perfil **Colaborador** é controlado por *permissions* individuais (uma por módulo), verificadas via middleware `permission:<modulo>` em cada rota. Isso reflete a regra de negócio: o administrador não usa os módulos operacionais, e cada colaborador pode ter combinações diferentes de módulos liberados.
- **Bloqueio por rota, não só por menu**: os links do menu são condicionados por `@role`/`@can`, mas a proteção real acontece nas rotas via middleware. Uma tentativa de acessar uma URL diretamente sem permissão retorna `403`.
- **Sem autorregistro de usuários**: o desafio define que o cadastro de usuários é responsabilidade do administrador (CRUD de Usuários). Por isso, as telas de registro público, recuperação de senha e verificação de e-mail do Laravel Breeze foram removidas — o sistema tem apenas login.
- **CRUD de Permissões desacoplado dos módulos**: o CRUD de permissões gerencia livremente as chaves de permissão no banco. As 4 chaves usadas pelos módulos operacionais vêm do seeder, mas o administrador pode criar novas permissões (para usos futuros) ou remover as existentes — o que naturalmente revoga o acesso ao módulo correspondente para quem a possuía.
- **Sem exclusão da própria conta de admin**: por segurança, um usuário não pode excluir a si mesmo pela tela de Usuários (evita que o único administrador se auto-bloqueie).
- **Factories**: mantidas apenas as factories padrão do Laravel; o povoamento de dados de teste é feito via seeders, conforme exigido.
- **Bloqueio de usuário (`usr_blq`)**: coluna adicionada à tabela `users` (`1` = desbloqueado, `2` = bloqueado) como base para um controle de usuários mais completo no futuro. Já está funcional: um usuário bloqueado não consegue autenticar (validado em `LoginRequest::authenticate()`), o status aparece na listagem de Usuários, e o próprio administrador não pode se autobloquear (mesma lógica de proteção usada na exclusão).
- **Páginas de erro customizadas**: `resources/views/errors/403.blade.php` e `404.blade.php` seguem a identidade visual do sistema em vez da página padrão do Laravel — o framework já usa essas views automaticamente quando existem, sem configuração adicional.
