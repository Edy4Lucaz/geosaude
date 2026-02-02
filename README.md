


# 🏥 GeoSaúde Angola

> **Sistema Inteligente de Vigilância Epidemiológica e Georreferenciamento.**

O **GeoSaúde** é uma solução baseada em Laravel para o registo de casos de saúde pública, monitorização de surtos e auditoria de eventos. Foi desenhado para facilitar a visualização de dados epidemiológicos e a gestão de alertas em tempo real.

---

### ✨ Principais Funcionalidades

* 📍 **Geolocalização**: Registo de casos clínicos com coordenadas geográficas.
* 🚨 **Gestão de Alertas**: Configuração de parâmetros para disparar alertas de surtos.
* 📂 **Auditoria**: Logs detalhados de todas as ações críticas no sistema.
* 📊 **Dashboard**: Interface administrativa para gestão e listagem de dados.

### 🛠️ Tecnologias Utilizadas

* **Backend:** PHP 8.2+ & Laravel 12
* **Frontend:** Blade Engine, Tailwind CSS, Vite
* **Base de Dados:** MySQL / MariaDB
* **Bibliotecas:** `dompdf` (Relatórios), `guzzle` (Requisições HTTP), `Leaflet.js` (Mapas)

---

### 🚀 Guia de Instalação (Ambiente Local)

1. **Clonar e Aceder:**
```bash
git clone <repo-url> geosaude
cd geosaude

```


2. **Instalar Dependências:**
```bash
composer install
npm install

```


3. **Configuração do Ambiente:**
```bash
cp .env.example .env
php artisan key:generate

```


> *Nota: Configure as credenciais do seu banco de dados no ficheiro `.env`.*


4. **Base de Dados e Assets:**
```bash
php artisan migrate --seed
npm run build

```


5. **Executar:**
Se estiver a usar o servidor embutido:
```bash
php artisan serve

```


Aceda a: `http://localhost:8000`

---

### 🗄️ Estrutura da Base de Dados

| Tabela | Função |
| --- | --- |
| `casos` | Armazena registos clínicos e coordenadas GPS. |
| `doencas` | Catálogo de patologias monitorizadas. |
| `config_alertas` | Define os limiares para estados de surto/epidemia. |
| `logs_sistema` | Histórico completo de auditoria e segurança. |

---

### ⌨️ Comandos Úteis do Projeto

| Ação | Comando |
| --- | --- |
| **Testar** | `php artisan test` |
| **Resetar BD** | `php artisan migrate:fresh --seed` |
| **Limpar Cache** | `php artisan optimize:clear` |
| **Dev Mode** | `npm run dev` |

---

### 📂 Referências de Código

* **Models:** `Caso.php`, `Doenca.php`, `ConfigAlerta.php`
* **Routes:** `routes/web.php`
* **Views:** `resources/views/`

---

### ⚖️ Licença


---
