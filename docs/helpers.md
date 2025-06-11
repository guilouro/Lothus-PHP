# Helpers do Lothus{PHP}

Este documento descreve os helpers integrados ao framework Lothus{PHP} e como utilizá-los.

## 🎯 O que são Helpers?

Helpers são classes utilitárias que fornecem funcionalidades comuns para sua aplicação. No Lothus{PHP}, os helpers estão localizados em `system/helpers/`.

## 📦 Helpers Disponíveis

### AuthHelper

Gerencia autenticação e sessões de usuário.

```php
// Inicialização
$auth = new AuthHelper();

// Login
$auth->login($email, $senha);

// Verificar se está logado
if ($auth->isLogged()) {
    // Usuário está logado
}

// Obter usuário atual
$usuario = $auth->getUser();

// Logout
$auth->logout();

// Verificar permissão
if ($auth->hasPermission('admin')) {
    // Usuário tem permissão
}
```

### EmailHelper

Wrapper para envio de emails usando PHPMailer.

```php
// Inicialização
$email = new EmailHelper();

// Configuração básica
$email->setFrom('remetente@email.com', 'Nome do Remetente');
$email->addTo('destinatario@email.com', 'Nome do Destinatário');
$email->setSubject('Assunto do Email');
$email->setBody('Corpo do email em texto plano');
$email->setHtmlBody('<h1>Corpo do email em HTML</h1>');

// Anexos
$email->addAttachment('/caminho/do/arquivo.pdf', 'documento.pdf');

// Envio
if ($email->send()) {
    echo 'Email enviado com sucesso!';
} else {
    echo 'Erro ao enviar email: ' . $email->getError();
}
```

### ImageHelper

Manipulação de imagens e upload.

```php
// Inicialização
$image = new ImageHelper();

// Upload de imagem
if ($image->upload($_FILES['foto'], 'uploads/')) {
    $nomeArquivo = $image->getFileName();
}

// Redimensionar
$image->resize(800, 600);

// Recortar
$image->crop(100, 100, 300, 300);

// Adicionar marca d'água
$image->watermark('logo.png', 'bottom-right');

// Salvar
$image->save('nova-imagem.jpg', 80); // 80 é a qualidade JPEG

// Obter dimensões
$largura = $image->getWidth();
$altura = $image->getHeight();
```

### PaginationHelper

Paginação de resultados com suporte a Bootstrap.

```php
// No controller
$total = $this->model->count();
$pagina = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$porPagina = 10;

$pagination = new PaginationHelper($total, $pagina, $porPagina);
$offset = $pagination->getOffset();

$resultados = $this->model->listar($offset, $porPagina);

// Na view
echo $pagination->render();
```

### RedirectHelper

Redirecionamento entre páginas.

```php
// Inicialização
$redirect = new RedirectHelper();

// Redirecionamento básico
$redirect->to('usuario/perfil');

// Com parâmetros
$redirect->to('produto/editar', ['id' => 123]);

// Com mensagem flash
$redirect->with('success', 'Produto salvo com sucesso!')
         ->to('produto/index');

// Redirecionamento externo
$redirect->toUrl('https://exemplo.com');

// Voltar
$redirect->back();
```

## 🛠️ Criando Helpers Personalizados

Você pode criar seus próprios helpers em `app/Lib/`:

```php
// app/Lib/CustomHelper.php
class CustomHelper {
    public function formatarData($data) {
        return date('d/m/Y', strtotime($data));
    }

    public function formatarMoeda($valor) {
        return 'R$ ' . number_format($valor, 2, ',', '.');
    }
}

// Uso
$helper = new CustomHelper();
echo $helper->formatarData('2024-03-20'); // 20/03/2024
echo $helper->formatarMoeda(1234.56);     // R$ 1.234,56
```

## 💡 Boas Práticas

1. **Organização**
   - Mantenha helpers específicos em `app/Lib/`
   - Use namespaces para evitar conflitos
   - Documente métodos e parâmetros

2. **Segurança**
   - Valide dados de entrada
   - Use prepared statements
   - Sanitize saída HTML
   - Proteja contra XSS e CSRF

3. **Performance**
   - Cache quando apropriado
   - Otimize consultas
   - Limite tamanho de uploads
   - Comprima imagens

4. **Manutenção**
   - Mantenha helpers focados
   - Evite duplicação de código
   - Use constantes para configuração
   - Faça testes unitários

## 📚 Exemplos de Uso

### Autenticação com Permissões

```php
class AdminController extends Controller {
    private $auth;

    public function init() {
        $this->auth = new AuthHelper();
        
        if (!$this->auth->isLogged()) {
            $this->redirect('login');
        }
        
        if (!$this->auth->hasPermission('admin')) {
            $this->redirect('home');
        }
    }

    public function index_action() {
        $usuario = $this->auth->getUser();
        $this->view('admin/index', ['usuario' => $usuario]);
    }
}
```

### Upload e Processamento de Imagem

```php
class ProdutoController extends Controller {
    public function salvar_action() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image = new ImageHelper();
            
            if ($image->upload($_FILES['foto'], 'uploads/produtos/')) {
                // Redimensionar para thumbnail
                $image->resize(150, 150);
                $image->save('uploads/produtos/thumb_' . $image->getFileName());
                
                // Redimensionar para visualização
                $image->resize(800, 600);
                $image->watermark('logo.png', 'bottom-right');
                $image->save('uploads/produtos/' . $image->getFileName());
                
                // Salvar no banco
                $this->model->salvar([
                    'nome' => $_POST['nome'],
                    'foto' => $image->getFileName()
                ]);
                
                $this->redirect('produto/index');
            }
        }
    }
}
```

### Paginação com Filtros

```php
class ProdutoController extends Controller {
    public function index_action() {
        $filtro = $_GET['filtro'] ?? '';
        $categoria = $_GET['categoria'] ?? '';
        
        $total = $this->model->count($filtro, $categoria);
        $pagina = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $porPagina = 12;
        
        $pagination = new PaginationHelper($total, $pagina, $porPagina);
        $offset = $pagination->getOffset();
        
        $produtos = $this->model->listar($filtro, $categoria, $offset, $porPagina);
        
        $this->view('produto/index', [
            'produtos' => $produtos,
            'pagination' => $pagination,
            'filtro' => $filtro,
            'categoria' => $categoria
        ]);
    }
}

// Na view
<div class="produtos">
    <?php foreach ($produtos as $produto): ?>
        <!-- Exibir produto -->
    <?php endforeach; ?>
    
    <?php if ($pagination->getTotalPages() > 1): ?>
        <div class="paginacao">
            <?= $pagination->render([
                'filtro' => $filtro,
                'categoria' => $categoria
            ]) ?>
        </div>
    <?php endif; ?>
</div>
```

### Envio de Email com Template

```php
class ContatoController extends Controller {
    public function enviar_action() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = new EmailHelper();
            
            // Configurar email
            $email->setFrom('contato@site.com', 'Site');
            $email->addTo('admin@site.com', 'Administrador');
            $email->setSubject('Novo contato via site');
            
            // Template HTML
            $html = $this->view('email/contato', [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'mensagem' => $_POST['mensagem']
            ], true);
            
            $email->setHtmlBody($html);
            
            // Enviar
            if ($email->send()) {
                $this->redirect('contato/sucesso');
            } else {
                $this->view('contato/erro', [
                    'erro' => $email->getError()
                ]);
            }
        }
    }
}
``` 