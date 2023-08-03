# Instalação

Para instalar o pacote `PagHiper for Laravel`, execute os seguintes comandos:

```shell
composer require devajmeireles/paghiper-for-laravel
```

E logo em seguida execute o comando:

```shell
php artisan paghiper:install
```

O comando `paghiper:install` irá publicar o arquivo de configuração do pacote em `config/paghiper.php`,
e também criará variáveis de ambiente no arquivo `.env` do projeto. Opcionalmente, você pode utilizar o comando com o parametro `--force` para sobrescrever o arquivo de configuração, caso ele já exista.

Por último, configure o `.env` do projeto preenchendo as credenciais da sua conta PagHiper:

```dotenv
PAGHIPER_API=sua-api-aqui
PAGHIPER_TOKEN=seu-token-aqui
```
