# Estrutura do Projeto

Este documento descreve a organização de diretórios e arquivos do framework Lothus{PHP}, explicando o propósito de cada componente.

## 📁 Diretório Raiz

```
Lothus-PHP/
│
├── app/              # Código da aplicação
├── system/           # Core do framework
├── docs/             # Documentação
├── .htaccess         # Reescrita de URLs
└── manage.py         # Script CLI para geração de arquivos
```

## 📁 Diretório `app/`

O diretório `app/` contém todo o código específico da sua aplicação:

```
app/
│
├── Config/           # Configurações da aplicação
│   ├── database.php  # Configuração do banco de dados
│   └── config.php    # Configurações gerais
│
├── Controllers/      # Controllers da aplicação
│   ├── HomeController.php
│   └── ...
│
├── Models/           # Models da aplicação
│   ├── UserModel.php
│   └── ...
│
├── Views/            # Views e layouts
│   ├── Layouts/      # Templates base
│   │   ├── default.php
│   │   └── ...
│   ├── Home/         # Views do controller Home
│   │   ├── index.php
│   │   └── ...
│   └── ...
│
├── Lib/              # Bibliotecas específicas do projeto
│   ├── CustomHelper.php
│   └── ...
│
└── webroot/          # Pasta pública
    ├── index.php     # Ponto de entrada da aplicação
    ├── css/          # Arquivos CSS
    ├── js/           # Arquivos JavaScript
    └── img/          # Imagens
```

### Config/

Contém arquivos de configuração da aplicação:

- `database.php`: Configurações de conexão com o banco de dados
- `config.php`: Configurações gerais da aplicação

### Controllers/

Armazena os controllers da aplicação. Cada controller deve:
- Estender a classe base `Controller`
- Terminar com o sufixo `Controller`
- Estar em um arquivo com o mesmo nome da classe

Exemplo:
```php
class HomeController extends Controller {
    public function index_action() {
        $this->view('index');
    }
}
```

### Models/

Contém os models da aplicação. Cada model deve:
- Estender a classe base `Model`
- Terminar com o sufixo `Model`
- Definir a propriedade `$_tabela` com o nome da tabela no banco

Exemplo:
```php
class UserModel extends Model {
    protected $_tabela = 'users';
}
```

### Views/

Organizado por controller, contém:
- `Layouts/`: Templates base da aplicação
- Subdiretórios para cada controller (ex: `Home/`, `Admin/`)
- Arquivos `.php` para cada view

### Lib/

Bibliotecas e helpers específicos da aplicação.

### webroot/

Diretório público acessível pelo navegador:
- `index.php`: Ponto de entrada da aplicação
- Arquivos estáticos (CSS, JS, imagens)

## 📁 Diretório `system/`

Contém o core do framework:

```
system/
│
├── helpers/          # Helpers do framework
│   ├── AuthHelper.php
│   ├── EmailHelper.php
│   ├── ImageHelper.php
│   └── ...
│
├── Config.php        # Classe de configuração
├── Controller.php    # Classe base para controllers
├── Model.php         # Classe base para models
└── System.php        # Core do framework
```

### helpers/

Contém helpers integrados:
- `AuthHelper`: Autenticação e controle de sessão
- `EmailHelper`: Envio de emails
- `ImageHelper`: Manipulação de imagens
- `PaginationHelper`: Paginação
- `RedirectHelper`: Redirecionamento

### Arquivos Core

- `Config.php`: Gerenciamento de configurações
- `Controller.php`: Classe base para controllers
- `Model.php`: Classe base para models
- `System.php`: Core do framework, gerencia rotas e MVC

## 📄 Arquivos na Raiz

### .htaccess

Configuração do Apache para:
- Redirecionar requisições para o `webroot/`
- Habilitar URLs amigáveis
- Proteger diretórios sensíveis

### manage.py

Script CLI para:
- Gerar controllers, models e views
- Criar estrutura de diretórios
- Outras tarefas de desenvolvimento

## 🔒 Boas Práticas

1. **Controllers**
   - Nome em minúsculo
   - Sufixo `Controller`
   - Um arquivo por controller

2. **Models**
   - Nome em minúsculo
   - Sufixo `Model`
   - Definir `$_tabela`

3. **Views**
   - Organizar por controller
   - Nome do arquivo igual ao método do controller
   - Usar layouts para consistência

4. **Configuração**
   - Manter configurações sensíveis em `Config/`
   - Não versionar arquivos com credenciais
   - Usar constantes para valores globais

5. **Segurança**
   - Manter `system/` fora do webroot
   - Validar entrada de dados
   - Usar prepared statements
   - Proteger arquivos sensíveis 