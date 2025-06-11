# MVC no Lothus{PHP}

Este documento explica como trabalhar com o padrão MVC (Model-View-Controller) no framework Lothus{PHP}.

## 🎯 Visão Geral

O Lothus{PHP} implementa o padrão MVC para separar a lógica de negócios, apresentação e dados:

- **Model**: Acesso e manipulação de dados
- **View**: Apresentação e interface do usuário
- **Controller**: Lógica de negócios e fluxo da aplicação

## 🎮 Controllers

Controllers são a camada de controle da aplicação, responsáveis por:
- Processar requisições
- Interagir com models
- Renderizar views
- Gerenciar o fluxo da aplicação

### Estrutura Básica

```php
class HomeController extends Controller {
    public function init() {
        // Inicialização do controller
    }

    public function index_action() {
        // Lógica da action
        $this->view('index');
    }
}
```

### Convenções

1. Nome do arquivo: `NomeController.php`
2. Nome da classe: `NomeController`
3. Métodos de ação: terminam com `_action`
4. Views: mesmo nome do método (sem `_action`)

### Métodos Comuns

```php
class UsuarioController extends Controller {
    // Renderiza uma view
    public function index_action() {
        $this->view('index');
    }

    // Passa dados para a view
    public function perfil_action() {
        $dados = ['nome' => 'João', 'email' => 'joao@email.com'];
        $this->view('perfil', $dados);
    }

    // Redireciona
    public function salvar_action() {
        // ... lógica de salvamento ...
        $this->redirect('usuario/perfil');
    }

    // Usa um layout diferente
    public function admin_action() {
        $this->layout('admin');
        $this->view('admin');
    }
}
```

## 📊 Models

Models representam a camada de dados, responsáveis por:
- Acesso ao banco de dados
- Validação de dados
- Regras de negócio relacionadas aos dados

### Estrutura Básica

```php
class UsuarioModel extends Model {
    protected $_tabela = 'usuarios';

    public function buscarPorEmail($email) {
        return $this->consulta("SELECT * FROM {$this->_tabela} WHERE email = ?", [$email]);
    }
}
```

### Métodos do Model Base

```php
class ProdutoModel extends Model {
    protected $_tabela = 'produtos';

    // Inserir
    public function criar($dados) {
        return $this->insert($dados);
    }

    // Buscar
    public function listar() {
        return $this->read();
    }

    // Buscar por ID
    public function buscar($id) {
        return $this->read($id);
    }

    // Atualizar
    public function atualizar($id, $dados) {
        return $this->update($id, $dados);
    }

    // Excluir
    public function excluir($id) {
        return $this->delete($id);
    }

    // Consulta personalizada
    public function buscarPorCategoria($categoria) {
        return $this->consulta(
            "SELECT * FROM {$this->_tabela} WHERE categoria = ?",
            [$categoria]
        );
    }
}
```

### Relacionamentos

```php
class PedidoModel extends Model {
    protected $_tabela = 'pedidos';

    public function buscarComItens($pedido_id) {
        $sql = "SELECT p.*, i.* 
                FROM {$this->_tabela} p 
                JOIN itens_pedido i ON p.id = i.pedido_id 
                WHERE p.id = ?";
        return $this->consulta($sql, [$pedido_id]);
    }
}
```

## 👁️ Views

Views são responsáveis pela apresentação, contendo:
- HTML
- CSS
- JavaScript
- Dados dinâmicos

### Estrutura Básica

```php
<!-- app/Views/Usuario/index.php -->
<div class="container">
    <h1>Lista de Usuários</h1>
    
    <?php foreach ($usuarios as $usuario): ?>
        <div class="usuario">
            <h2><?= $usuario['nome'] ?></h2>
            <p><?= $usuario['email'] ?></p>
        </div>
    <?php endforeach; ?>
</div>
```

### Layouts

Layouts são templates base que definem a estrutura comum:

```php
<!-- app/Views/Layouts/default.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?= $titulo ?? 'Minha Aplicação' ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <!-- Cabeçalho comum -->
    </header>

    <main>
        <?= $conteudo ?>
    </main>

    <footer>
        <!-- Rodapé comum -->
    </footer>
</body>
</html>
```

### Partials

Partials são trechos de view reutilizáveis:

```php
<!-- app/Views/partials/menu.php -->
<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/sobre">Sobre</a></li>
        <li><a href="/contato">Contato</a></li>
    </ul>
</nav>

<!-- Incluindo em uma view -->
<?php include 'partials/menu.php'; ?>
```

## 🔄 Fluxo MVC

1. Requisição chega ao `index.php`
2. Router identifica controller e action
3. Controller é instanciado
4. Action é executada
5. Controller interage com Model se necessário
6. Controller renderiza View
7. View é processada com dados
8. Resposta é enviada ao cliente

## 💡 Boas Práticas

### Controllers

- Mantenha controllers enxutos
- Use models para lógica de dados
- Valide dados de entrada
- Trate erros adequadamente
- Use redirecionamentos apropriados

### Models

- Um model por tabela
- Validação de dados
- Queries preparadas
- Métodos específicos para consultas comuns
- Documente métodos complexos

### Views

- Separe HTML, CSS e JavaScript
- Use layouts para consistência
- Evite lógica complexa
- Reutilize partials
- Mantenha views organizadas por controller

## 📚 Exemplos

### CRUD Completo

```php
// Controller
class ProdutoController extends Controller {
    private $model;

    public function init() {
        $this->model = new ProdutoModel();
    }

    public function index_action() {
        $produtos = $this->model->listar();
        $this->view('index', ['produtos' => $produtos]);
    }

    public function criar_action() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = $this->validarDados($_POST);
            if ($this->model->criar($dados)) {
                $this->redirect('produto/index');
            }
        }
        $this->view('criar');
    }

    public function editar_action($id) {
        $produto = $this->model->buscar($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = $this->validarDados($_POST);
            if ($this->model->atualizar($id, $dados)) {
                $this->redirect('produto/index');
            }
        }
        $this->view('editar', ['produto' => $produto]);
    }

    public function excluir_action($id) {
        if ($this->model->excluir($id)) {
            $this->redirect('produto/index');
        }
    }

    private function validarDados($dados) {
        // Validação dos dados
        return $dados;
    }
}
```

### View com Layout

```php
<!-- app/Views/Produto/index.php -->
<div class="produtos">
    <h1>Produtos</h1>
    
    <a href="/produto/criar" class="btn">Novo Produto</a>
    
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= $produto['nome'] ?></td>
                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td>
                        <a href="/produto/editar/<?= $produto['id'] ?>">Editar</a>
                        <a href="/produto/excluir/<?= $produto['id'] ?>" 
                           onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
``` 