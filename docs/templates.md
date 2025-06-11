# Sistema de Templates

Este documento explica como trabalhar com templates e layouts no framework Lothus{PHP}.

## 🎯 Visão Geral

O sistema de templates do Lothus{PHP} permite:
- Criar layouts reutilizáveis
- Separar conteúdo e apresentação
- Manter consistência visual
- Facilitar manutenção

## 📁 Estrutura de Templates

```
app/Views/
│
├── Layouts/           # Templates base
│   ├── default.php    # Layout padrão
│   ├── admin.php      # Layout administrativo
│   └── ...
│
├── partials/          # Partials reutilizáveis
│   ├── header.php
│   ├── footer.php
│   ├── menu.php
│   └── ...
│
└── Controller/        # Views específicas
    ├── index.php
    ├── criar.php
    └── ...
```

## 🎨 Layouts

Layouts são templates base que definem a estrutura comum das páginas.

### Layout Básico

```php
<!-- app/Views/Layouts/default.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?? 'Minha Aplicação' ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="/css/style.css">
    <?php if (isset($css)): ?>
        <?php foreach ($css as $arquivo): ?>
            <link rel="stylesheet" href="<?= $arquivo ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- JavaScript -->
    <script src="/js/main.js"></script>
    <?php if (isset($js)): ?>
        <?php foreach ($js as $arquivo): ?>
            <script src="<?= $arquivo ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header -->
    <?php include 'partials/header.php'; ?>
    
    <!-- Menu -->
    <?php include 'partials/menu.php'; ?>
    
    <!-- Conteúdo Principal -->
    <main class="container">
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?= $mensagem['tipo'] ?>">
                <?= $mensagem['texto'] ?>
            </div>
        <?php endif; ?>
        
        <?= $conteudo ?>
    </main>
    
    <!-- Footer -->
    <?php include 'partials/footer.php'; ?>
</body>
</html>
```

### Usando Layouts

```php
// No controller
class ProdutoController extends Controller {
    public function index_action() {
        // Usa o layout padrão
        $this->view('index', [
            'titulo' => 'Lista de Produtos',
            'produtos' => $this->model->listar()
        ]);
    }
    
    public function admin_action() {
        // Usa o layout administrativo
        $this->layout('admin');
        $this->view('admin', [
            'titulo' => 'Administração',
            'dados' => $this->model->getStats()
        ]);
    }
}
```

## 🧩 Partials

Partials são trechos de template reutilizáveis.

### Exemplo de Menu

```php
<!-- app/Views/partials/menu.php -->
<nav class="main-menu">
    <ul>
        <li><a href="/" class="<?= $this->isActive('home') ?>">Home</a></li>
        <li><a href="/produtos" class="<?= $this->isActive('produtos') ?>">Produtos</a></li>
        <li><a href="/sobre" class="<?= $this->isActive('sobre') ?>">Sobre</a></li>
        <li><a href="/contato" class="<?= $this->isActive('contato') ?>">Contato</a></li>
    </ul>
</nav>
```

### Exemplo de Footer

```php
<!-- app/Views/partials/footer.php -->
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Contato</h3>
                <p>Email: contato@site.com</p>
                <p>Telefone: (11) 1234-5678</p>
            </div>
            <div class="col">
                <h3>Redes Sociais</h3>
                <div class="social-links">
                    <a href="#" class="facebook">Facebook</a>
                    <a href="#" class="twitter">Twitter</a>
                    <a href="#" class="instagram">Instagram</a>
                </div>
            </div>
        </div>
        <div class="copyright">
            &copy; <?= date('Y') ?> Minha Aplicação. Todos os direitos reservados.
        </div>
    </div>
</footer>
```

## 🎭 Variáveis de Template

### Passando Dados

```php
// No controller
$this->view('produto/index', [
    'titulo' => 'Produtos',
    'produtos' => $this->model->listar(),
    'categorias' => $this->model->getCategorias(),
    'mensagem' => [
        'tipo' => 'success',
        'texto' => 'Produto salvo com sucesso!'
    ],
    'css' => [
        '/css/produtos.css',
        '/css/datatables.css'
    ],
    'js' => [
        '/js/produtos.js',
        '/js/datatables.js'
    ]
]);
```

### Acessando Dados

```php
<!-- Na view -->
<h1><?= $titulo ?></h1>

<?php if (isset($mensagem)): ?>
    <div class="alert alert-<?= $mensagem['tipo'] ?>">
        <?= $mensagem['texto'] ?>
    </div>
<?php endif; ?>

<div class="produtos">
    <?php foreach ($produtos as $produto): ?>
        <div class="produto">
            <h2><?= $produto['nome'] ?></h2>
            <p class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
            <p class="categoria"><?= $categorias[$produto['categoria_id']] ?></p>
        </div>
    <?php endforeach; ?>
</div>
```

## 🎨 Estilização

### CSS Modular

```php
<!-- app/Views/Layouts/default.php -->
<head>
    <!-- CSS Base -->
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/grid.css">
    <link rel="stylesheet" href="/css/style.css">
    
    <!-- CSS Específico -->
    <?php if (isset($css)): ?>
        <?php foreach ($css as $arquivo): ?>
            <link rel="stylesheet" href="<?= $arquivo ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
```

### JavaScript Modular

```php
<!-- app/Views/Layouts/default.php -->
<body>
    <!-- Conteúdo -->
    
    <!-- JavaScript Base -->
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.js"></script>
    <script src="/js/main.js"></script>
    
    <!-- JavaScript Específico -->
    <?php if (isset($js)): ?>
        <?php foreach ($js as $arquivo): ?>
            <script src="<?= $arquivo ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
```

## 🔄 Templates Dinâmicos

### Menu Ativo

```php
<!-- app/Views/partials/menu.php -->
<?php
function isActive($controller) {
    $current = strtolower($_GET['url'] ?? 'home');
    return $current === $controller ? 'active' : '';
}
?>

<nav>
    <ul>
        <li><a href="/" class="<?= isActive('home') ?>">Home</a></li>
        <li><a href="/produtos" class="<?= isActive('produtos') ?>">Produtos</a></li>
        <li><a href="/sobre" class="<?= isActive('sobre') ?>">Sobre</a></li>
    </ul>
</nav>
```

### Breadcrumbs

```php
<!-- app/Views/partials/breadcrumbs.php -->
<?php
$breadcrumbs = [
    'home' => 'Home',
    'produtos' => 'Produtos',
    'categoria' => 'Categoria',
    'produto' => 'Produto'
];

$current = strtolower($_GET['url'] ?? 'home');
$parts = explode('/', $current);
?>

<nav class="breadcrumbs">
    <ol>
        <?php foreach ($parts as $i => $part): ?>
            <?php if (isset($breadcrumbs[$part])): ?>
                <li>
                    <?php if ($i < count($parts) - 1): ?>
                        <a href="/<?= implode('/', array_slice($parts, 0, $i + 1)) ?>">
                            <?= $breadcrumbs[$part] ?>
                        </a>
                    <?php else: ?>
                        <?= $breadcrumbs[$part] ?>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
```

## 💡 Boas Práticas

1. **Organização**
   - Mantenha layouts em `Layouts/`
   - Use partials para componentes reutilizáveis
   - Organize views por controller
   - Separe CSS e JavaScript

2. **Performance**
   - Minifique CSS e JavaScript
   - Use cache quando apropriado
   - Carregue scripts no final do body
   - Otimize imagens

3. **Manutenção**
   - Use variáveis para textos dinâmicos
   - Mantenha templates DRY
   - Documente partials complexos
   - Use constantes para valores comuns

4. **Segurança**
   - Escape dados de saída
   - Valide dados de entrada
   - Proteja contra XSS
   - Use CSRF tokens

## 📚 Exemplos

### Layout Administrativo

```php
<!-- app/Views/Layouts/admin.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - <?= $titulo ?? 'Painel' ?></title>
    <link rel="stylesheet" href="/css/admin.css">
    <?php if (isset($css)): ?>
        <?php foreach ($css as $arquivo): ?>
            <link rel="stylesheet" href="<?= $arquivo ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="admin">
    <!-- Sidebar -->
    <?php include 'partials/admin/sidebar.php'; ?>
    
    <!-- Conteúdo -->
    <div class="admin-content">
        <!-- Header -->
        <?php include 'partials/admin/header.php'; ?>
        
        <!-- Mensagens -->
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?= $mensagem['tipo'] ?>">
                <?= $mensagem['texto'] ?>
            </div>
        <?php endif; ?>
        
        <!-- Breadcrumbs -->
        <?php include 'partials/admin/breadcrumbs.php'; ?>
        
        <!-- Conteúdo Principal -->
        <main>
            <?= $conteudo ?>
        </main>
    </div>
    
    <!-- JavaScript -->
    <script src="/js/admin.js"></script>
    <?php if (isset($js)): ?>
        <?php foreach ($js as $arquivo): ?>
            <script src="<?= $arquivo ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
```

### Formulário com Validação

```php
<!-- app/Views/Produto/form.php -->
<form method="post" action="/produto/salvar" class="produto-form">
    <?php if (isset($produto['id'])): ?>
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
    <?php endif; ?>
    
    <div class="form-group">
        <label for="nome">Nome</label>
        <input type="text" 
               id="nome" 
               name="nome" 
               value="<?= $produto['nome'] ?? '' ?>"
               class="form-control <?= isset($erros['nome']) ? 'is-invalid' : '' ?>"
               required>
        <?php if (isset($erros['nome'])): ?>
            <div class="invalid-feedback"><?= $erros['nome'] ?></div>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="preco">Preço</label>
        <input type="number" 
               id="preco" 
               name="preco" 
               step="0.01" 
               value="<?= $produto['preco'] ?? '' ?>"
               class="form-control <?= isset($erros['preco']) ? 'is-invalid' : '' ?>"
               required>
        <?php if (isset($erros['preco'])): ?>
            <div class="invalid-feedback"><?= $erros['preco'] ?></div>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="categoria">Categoria</label>
        <select id="categoria" 
                name="categoria_id" 
                class="form-control <?= isset($erros['categoria_id']) ? 'is-invalid' : '' ?>"
                required>
            <option value="">Selecione...</option>
            <?php foreach ($categorias as $id => $nome): ?>
                <option value="<?= $id ?>" 
                        <?= (isset($produto['categoria_id']) && $produto['categoria_id'] == $id) ? 'selected' : '' ?>>
                    <?= $nome ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($erros['categoria_id'])): ?>
            <div class="invalid-feedback"><?= $erros['categoria_id'] ?></div>
        <?php endif; ?>
    </div>
    
    <div class="form-group">
        <label for="foto">Foto</label>
        <input type="file" 
               id="foto" 
               name="foto" 
               class="form-control <?= isset($erros['foto']) ? 'is-invalid' : '' ?>"
               accept="image/*">
        <?php if (isset($erros['foto'])): ?>
            <div class="invalid-feedback"><?= $erros['foto'] ?></div>
        <?php endif; ?>
        
        <?php if (isset($produto['foto'])): ?>
            <div class="current-photo">
                <img src="/uploads/produtos/<?= $produto['foto'] ?>" 
                     alt="Foto atual" 
                     class="img-thumbnail">
            </div>
        <?php endif; ?>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/produto" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
``` 