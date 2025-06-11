# Guia de Instalação

Este guia fornece instruções detalhadas para instalar e configurar o framework Lothus{PHP}.

## 🛠️ Requisitos

- PHP 7.4 ou superior
- Servidor web (Apache/Nginx)
- Extensão PDO habilitada
- Mod_rewrite habilitado (Apache)
- Composer (opcional, para gerenciamento de dependências)

## 📥 Instalação

### 1. Download

Clone o repositório:
```bash
git clone https://github.com/guilouro/Lothus-PHP.git
cd Lothus-PHP
```

### 2. Configuração do Servidor Web

#### Apache

1. Configure o DocumentRoot para apontar para `app/webroot/`:
```apache
<VirtualHost *:80>
    ServerName seu-dominio.com
    DocumentRoot /caminho/para/Lothus-PHP/app/webroot
    
    <Directory /caminho/para/Lothus-PHP/app/webroot>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

2. Verifique se o mod_rewrite está habilitado:
```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

#### Nginx

1. Configure o servidor:
```nginx
server {
    listen 80;
    server_name seu-dominio.com;
    root /caminho/para/Lothus-PHP/app/webroot;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 3. Configuração do Banco de Dados

1. Copie o arquivo de configuração do banco:
```bash
cp app/Config/database.example.php app/Config/database.php
```

2. Edite `app/Config/database.php`:
```php
return [
    'host'     => 'localhost',
    'dbname'   => 'seu_banco',
    'username' => 'seu_usuario',
    'password' => 'sua_senha',
    'charset'  => 'utf8mb4'
];
```

### 4. Configuração da Aplicação

1. Copie o arquivo de configuração:
```bash
cp app/Config/config.example.php app/Config/config.php
```

2. Edite `app/Config/config.php`:
```php
return [
    'base_url'    => 'http://seu-dominio.com',
    'app_name'    => 'Minha Aplicação',
    'debug'       => true,
    'timezone'    => 'America/Sao_Paulo',
    'charset'     => 'UTF-8',
    'default_controller' => 'home'
];
```

### 5. Permissões

Configure as permissões corretas:
```bash
chmod -R 755 .
chmod -R 777 app/webroot/img
chmod -R 777 app/webroot/uploads
```

## 🔧 Verificação da Instalação

1. Acesse a aplicação no navegador:
```
http://seu-dominio.com
```

2. Você deve ver a página inicial do framework.

3. Verifique os logs de erro do PHP e do servidor web se encontrar problemas.

## 🚀 Primeiros Passos

1. Crie um novo controller:
```bash
php manage.py create:controller Usuario
```

2. Crie um novo model:
```bash
php manage.py create:model Usuario
```

3. Crie uma nova view:
```bash
php manage.py create:view Usuario index
```

## ⚠️ Solução de Problemas

### Erro 404

- Verifique se o mod_rewrite está habilitado
- Confirme se o .htaccess está presente
- Verifique as permissões dos arquivos

### Erro de Conexão com Banco

- Verifique as credenciais em `database.php`
- Confirme se o banco existe
- Teste a conexão PDO

### Erro de Permissão

- Verifique as permissões dos diretórios
- Confirme o usuário do servidor web
- Ajuste as permissões conforme necessário

## 🔒 Segurança

1. Mantenha o PHP atualizado
2. Use HTTPS em produção
3. Proteja arquivos sensíveis
4. Não exponha o diretório `system/`
5. Configure corretamente o php.ini

## 📚 Próximos Passos

- Leia a [documentação MVC](mvc.md)
- Explore os [helpers disponíveis](helpers.md)
- Aprenda sobre [roteamento](routing.md)
- Veja [casos de uso](use-cases.md) 