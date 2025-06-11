# Sistema de Rotas

Este documento explica como funciona o sistema de rotas do framework Lothus{PHP}.

## 🎯 Visão Geral

O sistema de rotas do Lothus{PHP} permite:
- URLs amigáveis e SEO-friendly
- Mapeamento de URLs para controllers e actions
- Suporte a parâmetros dinâmicos
- Rotas personalizadas

## 🔄 Como Funciona

1. A requisição chega ao servidor
2. O `.htaccess` redireciona para o `index.php`
3. O `System.php` processa a URL
4. O `Router.php` mapeia a URL para um controller/action
5. O controller é instanciado e a action é executada

## 📝 Convenções de Rotas

### Rota Padrão

```
http://seu-site.com/controller/action/param1/param2
```

Exemplos:
- `http://seu-site.com/produto/listar` → `ProdutoController::listar_action()`
- `http://seu-site.com/usuario/editar/123` → `UsuarioController::editar_action(123)`
- `http://seu-site.com/` → `HomeController::index_action()`

### Estrutura da URL

```
protocolo://dominio/controller/action/parametro1/parametro2/...
```

- **controller**: Nome do controller (sem o sufixo "Controller")
- **action**: Nome da action (sem o sufixo "_action")
- **parametros**: Valores passados como argumentos para a action

## 🎨 Definindo Rotas

### Controller Básico

```php
class ProdutoController extends Controller {
    // GET /produto
    public function index_action() {
        $produtos = $this->model->listar();
        $this->view('index', ['produtos' => $produtos]);
    }
    
    // GET /produto/ver/123
    public function ver_action($id) {
        $produto = $this->model->buscar($id);
        $this->view('ver', ['produto' => $produto]);
    }
    
    // GET /produto/criar
    public function criar_action() {
        $this->view('form');
    }
    
    // POST /produto/salvar
    public function salvar_action() {
        $dados = $this->getPost();
        $this->model->salvar($dados);
        $this->redirect('/produto');
    }
    
    // POST /produto/excluir/123
    public function excluir_action($id) {
        $this->model->excluir($id);
        $this->redirect('/produto');
    }
}
```

### Rotas com Parâmetros

```php
class UsuarioController extends Controller {
    // GET /usuario/perfil/123
    public function perfil_action($id) {
        $usuario = $this->model->buscar($id);
        $this->view('perfil', ['usuario' => $usuario]);
    }
    
    // GET /usuario/posts/123/pagina/2
    public function posts_action($id, $pagina = 1) {
        $posts = $this->model->getPosts($id, $pagina);
        $this->view('posts', [
            'posts' => $posts,
            'pagina' => $pagina
        ]);
    }
    
    // GET /usuario/buscar/termo/pagina/2
    public function buscar_action($termo, $pagina = 1) {
        $resultados = $this->model->buscar($termo, $pagina);
        $this->view('busca', [
            'termo' => $termo,
            'resultados' => $resultados,
            'pagina' => $pagina
        ]);
    }
}
```

## 🔒 Rotas Protegidas

### Autenticação

```php
class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->auth->requireLogin();
    }
    
    // GET /admin/dashboard
    public function dashboard_action() {
        $this->view('admin/dashboard');
    }
    
    // GET /admin/usuarios
    public function usuarios_action() {
        $this->auth->requireRole('admin');
        $usuarios = $this->model->listar();
        $this->view('admin/usuarios', ['usuarios' => $usuarios]);
    }
}
```

### Middleware

```php
class AuthMiddleware {
    public function before() {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect('/login');
        }
    }
}

class AdminMiddleware {
    public function before() {
        if (!$this->auth->hasRole('admin')) {
            $this->redirect('/acesso-negado');
        }
    }
}

// No controller
class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('admin');
    }
}
```

## 🎭 Rotas Personalizadas

### Definindo Rotas

```php
// Em app/Config/routes.php
return [
    // Rota simples
    'sobre' => 'PaginaController::sobre_action',
    
    // Rota com parâmetro
    'produto/:id' => 'ProdutoController::ver_action',
    
    // Rota com múltiplos parâmetros
    'categoria/:categoria/produto/:id' => 'ProdutoController::ver_action',
    
    // Rota com método específico
    'POST produto/salvar' => 'ProdutoController::salvar_action',
    
    // Rota com middleware
    'admin/*' => [
        'controller' => 'AdminController',
        'middleware' => ['auth', 'admin']
    ]
];
```

### Usando Rotas Personalizadas

```php
class Router {
    public function route($url) {
        $routes = $this->loadRoutes();
        
        foreach ($routes as $pattern => $handler) {
            if ($this->matchRoute($pattern, $url)) {
                return $this->handleRoute($handler);
            }
        }
        
        // Rota padrão
        return $this->defaultRoute($url);
    }
    
    private function matchRoute($pattern, $url) {
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = preg_replace('/:[a-zA-Z]+/', '([^\/]+)', $pattern);
        return preg_match('/^' . $pattern . '$/', $url);
    }
}
```

## 🔄 Redirecionamentos

### Métodos de Redirecionamento

```php
class Controller {
    // Redirecionamento básico
    public function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    // Redirecionamento com mensagem
    public function redirectWithMessage($url, $mensagem, $tipo = 'success') {
        $_SESSION['flash'] = [
            'mensagem' => $mensagem,
            'tipo' => $tipo
        ];
        $this->redirect($url);
    }
    
    // Redirecionamento de volta
    public function redirectBack() {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    // Redirecionamento externo
    public function redirectExternal($url) {
        header("Location: $url", true, 302);
        exit;
    }
}
```

### Exemplos de Uso

```php
class ProdutoController extends Controller {
    public function salvar_action() {
        try {
            $this->model->salvar($this->getPost());
            $this->redirectWithMessage(
                '/produto',
                'Produto salvo com sucesso!',
                'success'
            );
        } catch (Exception $e) {
            $this->redirectWithMessage(
                '/produto/criar',
                'Erro ao salvar produto: ' . $e->getMessage(),
                'error'
            );
        }
    }
    
    public function excluir_action($id) {
        try {
            $this->model->excluir($id);
            $this->redirectWithMessage(
                '/produto',
                'Produto excluído com sucesso!',
                'success'
            );
        } catch (Exception $e) {
            $this->redirectBack();
        }
    }
}
```

## 💡 Boas Práticas

1. **Organização**
   - Mantenha controllers focados
   - Use actions descritivas
   - Agrupe rotas relacionadas
   - Documente rotas complexas

2. **Segurança**
   - Valide parâmetros
   - Use middleware
   - Proteja rotas sensíveis
   - Escape dados de saída

3. **Performance**
   - Cache rotas quando possível
   - Otimize redirecionamentos
   - Use rotas estáticas
   - Evite rotas muito profundas

4. **Manutenção**
   - Mantenha convenções
   - Use constantes para URLs
   - Centralize configurações
   - Documente exceções

## 📚 Exemplos

### CRUD Completo

```php
class ProdutoController extends Controller {
    // GET /produto
    public function index_action() {
        $produtos = $this->model->listar();
        $this->view('produto/index', [
            'titulo' => 'Produtos',
            'produtos' => $produtos
        ]);
    }
    
    // GET /produto/criar
    public function criar_action() {
        $this->view('produto/form', [
            'titulo' => 'Novo Produto',
            'categorias' => $this->model->getCategorias()
        ]);
    }
    
    // GET /produto/editar/123
    public function editar_action($id) {
        $produto = $this->model->buscar($id);
        $this->view('produto/form', [
            'titulo' => 'Editar Produto',
            'produto' => $produto,
            'categorias' => $this->model->getCategorias()
        ]);
    }
    
    // POST /produto/salvar
    public function salvar_action() {
        $dados = $this->getPost();
        
        try {
            $this->validate($dados);
            $id = $this->model->salvar($dados);
            
            $this->redirectWithMessage(
                '/produto',
                'Produto salvo com sucesso!',
                'success'
            );
        } catch (ValidationException $e) {
            $this->view('produto/form', [
                'titulo' => isset($dados['id']) ? 'Editar Produto' : 'Novo Produto',
                'produto' => $dados,
                'categorias' => $this->model->getCategorias(),
                'erros' => $e->getErrors()
            ]);
        }
    }
    
    // POST /produto/excluir/123
    public function excluir_action($id) {
        try {
            $this->model->excluir($id);
            $this->redirectWithMessage(
                '/produto',
                'Produto excluído com sucesso!',
                'success'
            );
        } catch (Exception $e) {
            $this->redirectWithMessage(
                '/produto',
                'Erro ao excluir produto: ' . $e->getMessage(),
                'error'
            );
        }
    }
    
    // GET /produto/buscar
    public function buscar_action() {
        $termo = $this->getQuery('q');
        $pagina = $this->getQuery('pagina', 1);
        
        $resultados = $this->model->buscar($termo, $pagina);
        
        if ($this->isAjax()) {
            $this->json($resultados);
        } else {
            $this->view('produto/busca', [
                'titulo' => 'Busca de Produtos',
                'termo' => $termo,
                'resultados' => $resultados,
                'pagina' => $pagina
            ]);
        }
    }
}
```

### API REST

```php
class ApiController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->middleware('api');
    }
    
    // GET /api/produtos
    public function index_action() {
        $produtos = $this->model->listar();
        $this->json($produtos);
    }
    
    // GET /api/produtos/123
    public function ver_action($id) {
        $produto = $this->model->buscar($id);
        if (!$produto) {
            $this->json(['erro' => 'Produto não encontrado'], 404);
        }
        $this->json($produto);
    }
    
    // POST /api/produtos
    public function criar_action() {
        $dados = $this->getJson();
        
        try {
            $this->validate($dados);
            $id = $this->model->salvar($dados);
            $this->json(['id' => $id], 201);
        } catch (ValidationException $e) {
            $this->json(['erros' => $e->getErrors()], 400);
        }
    }
    
    // PUT /api/produtos/123
    public function atualizar_action($id) {
        $dados = $this->getJson();
        $dados['id'] = $id;
        
        try {
            $this->validate($dados);
            $this->model->atualizar($dados);
            $this->json(['mensagem' => 'Produto atualizado']);
        } catch (ValidationException $e) {
            $this->json(['erros' => $e->getErrors()], 400);
        }
    }
    
    // DELETE /api/produtos/123
    public function excluir_action($id) {
        try {
            $this->model->excluir($id);
            $this->json(['mensagem' => 'Produto excluído']);
        } catch (Exception $e) {
            $this->json(['erro' => $e->getMessage()], 500);
        }
    }
}
``` 