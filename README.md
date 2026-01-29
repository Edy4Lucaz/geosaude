Vou criar um plano curto de tarefas e começar o rascunho do README.

Created 3 todos

Progresso: plano criado e rascunho do README iniciado. Abaixo está o Markdown em Português — quer que eu salve como `README.md` na raiz do projeto?

**Geosaude — README.md**

# Geosaude

Uma aplicação Laravel para gestão de casos de saúde pública, alertas e registros de eventos do sistema.

**Descrição**:  
Geosaude é um sistema para registrar casos, gerir configurações de alerta e manter logs do sistema. Ideal para monitoramento epidemiológico leve e visualização via mapas.

**Principais funcionalidades**
- Registro de casos clínicos.
- Configuração de alertas e parâmetros.
- Logs do sistema para auditoria.
- Endpoints web para listagem e gestão.

**Tecnologias**
- PHP 8+ e Laravel
- MySQL / MariaDB (ou outro DB suportado pelo Laravel)
- Composer, NPM/Vite
- Bibliotecas: dompdf, guzzle, etc. (ver composer.json)

**Requisitos**
- PHP 8.1+ (ver composer.json)
- Composer
- Node.js + NPM
- Banco de dados (MySQL/MariaDB)
- Servidor local (Laragon, Valet, Homestead, Docker)

**Instalação (local)**
1. Clone o repositório:
```bash
git clone <repo-url> geosaude
cd geosaude
```
2. Instale dependências PHP e JS:
```bash
composer install
npm install
```
3. Copie o arquivo de ambiente e configure:
```bash
cp .env.example .env
# editar .env com credenciais DB e APP_URL
```
4. Gere a chave da aplicação:
```bash
php artisan key:generate
```
5. Rode migrações e seeders:
```bash
php artisan migrate
php artisan db:seed
```
6. Compile assets:
```bash
npm run build
# ou para desenvolvimento:
npm run dev
```

**Executando a aplicação**
- Com Laragon/servidor local: abra o diretório `geosaude` e inicie o servidor web.
- Usando o servidor embutido:
```bash
php artisan serve --host=127.0.0.1 --port=8000
```
Acesse: `http://127.0.0.1:8000`

**Comandos úteis**
- Rodar testes:
```bash
php artisan test
```
- Rodar migrations fresh (cuidado: apaga dados):
```bash
php artisan migrate:fresh --seed
```
- Limpar cache config/route/view:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

**Arquivos e referências importantes**
- Modelos: Caso.php, Doenca.php, ConfigAlerta.php  
- Migrations: 2026_01_21_104808_create_casos_table.php, 2026_01_21_104725_create_doencas_table.php  
- Rotas Web: web.php  
- Views principais: views  
- Configurações do app: app.php

**Estrutura do banco de dados**
- `casos`: armazena registros de casos clínicos.
- `doencas`: cadastro de doenças.
- `config_alertas`: parâmetros para geração de alertas.
- `logs_sistema`: histórico de eventos/aplicação.

(Consulte as migrations para esquema detalhado.)

**Contribuindo**
- Abra uma issue descrevendo sua sugestão/bug.
- Faça um fork e branch com nome claro (`feature/x` ou `fix/x`).
- Envie PR com descrição e passos para reproduzir/testar.
- Mantenha testes para novos recursos quando aplicável.

**Licença**
- Projeto licenciado sob MIT — substitua conforme necessário.

**Contato**
- Mantainers: veja o composer.json ou `README` do repositório remoto para contatos.

Deseja que eu salve esse conteúdo em um arquivo `README.md` na raiz do projeto agora?
