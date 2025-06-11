# Lothus{PHP}

**Lothus{PHP}** é um framework PHP baseado no padrão MVC, criado para ser simples, leve e eficiente no desenvolvimento de aplicações web. Ideal para desenvolvedores que buscam controle total sobre o código, com foco em produtividade e organização.

## 🚀 Características Principais

- Framework MVC leve e eficiente
- URLs amigáveis e roteamento intuitivo
- Sistema de templates flexível
- Helpers integrados para tarefas comuns
- Suporte a banco de dados via PDO
- Estrutura modular e reutilizável

## 📚 Documentação

A documentação completa do framework está disponível na pasta `docs/`:

- [Estrutura do Projeto](docs/structure.md) - Organização de diretórios e arquivos
- [Instalação](docs/installation.md) - Guia de instalação e configuração
- [MVC](docs/mvc.md) - Trabalhando com Models, Views e Controllers
- [Helpers](docs/helpers.md) - Utilização dos helpers integrados
- [Templates](docs/templates.md) - Sistema de templates e layouts
- [Roteamento](docs/routing.md) - URLs amigáveis e sistema de rotas
- [Casos de Uso](docs/use-cases.md) - Exemplos práticos de aplicações

## 🛠️ Requisitos

- PHP 7.4 ou superior
- Servidor web (Apache/Nginx)
- Extensão PDO habilitada
- Mod_rewrite habilitado (Apache)

## ⚡ Início Rápido

1. Clone o repositório:
   ```bash
   git clone https://github.com/guilouro/Lothus-PHP.git
   ```

2. Configure seu servidor web para apontar para a pasta `app/webroot/`

3. Acesse a aplicação através do navegador

Para mais detalhes, consulte o [guia de instalação](docs/installation.md).

## 📦 Estrutura do Projeto

```
Lothus-PHP/
│
├── app/              # Código da aplicação
│   ├── Config/       # Configuração do banco de dados
│   ├── Controllers/  # Controllers da aplicação
│   ├── Models/       # Models (acesso ao banco)
│   ├── Views/        # Views e layouts
│   ├── Lib/          # Bibliotecas específicas do projeto
│   └── webroot/      # Pasta pública (CSS, JS, imagens, index.php)
│
├── system/           # Core do framework
│   ├── helpers/      # Helpers (Auth, Email, Image, etc.)
│   ├── Config.php    # Configuração global
│   ├── Controller.php
│   ├── Model.php
│   └── System.php
│
├── docs/             # Documentação
├── .htaccess         # Reescrita de URLs
└── manage.py         # Script CLI para geração de arquivos
```

## 🤝 Contribuindo

Contribuições são bem-vindas! Por favor, leia as diretrizes de contribuição antes de enviar um pull request.

## 📄 Licença

Este projeto está disponível para fins acadêmicos e comerciais, desde que mantida a referência ao autor original.

## 👨‍💻 Autor

Guilherme Peixoto da Costa Louro  
[LinkedIn](https://www.linkedin.com/in/guilhermelouro/)  
[GitHub](https://github.com/guilouro) 