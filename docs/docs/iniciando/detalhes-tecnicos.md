# Detalhes Técnicos

`Paghiper for Laravel` foi criado para suportar aplicações Laravel a partir da versão 9, com PHP 8.1, no mais 
alto padrão possível do PHP moderno, com cobertura de testes e fortemente tipado, garantindo estabilidade nas 
funcionalidades.

## PHP & Laravel

- Versão do PHP:
    - 8.0.x
    - 8.1.x
- Versão do Laravel:
    - 9.x
    - 10.x

## Facade

`Paghiper for Laravel` oferece uma <a href="https://laravel.com/docs/facades" target="_blank">Facade</a> para 
interação com a classe principal do pacote:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->create(/* ... */)
```

### Cliente HTTP

Por trás dos panos, `Paghiper for Laravel` utiliza o poder do <a href="https://laravel.com/docs/http-client" 
target="_blank">cliente HTTP do Laravel</a>. Com isso, caso você precise escrever testes automatizados, 
você deve seguir o esquema de testes do Laravel.

## Resolvedores

`Paghiper for Laravel` oferece recursos de resolvedores para viabilizar a definição de configurações em tempo de execução, 
ideal para casos onde você precise **sobescrever as configurações** de `api` ou `token` do arquivo `.env`, 
ou para prefixar URL de retorno automático:

```php
// arquivo: app/Providers/AppServicesProvider.php

use DevAjMeireles\PagHiper\Facades\PagHiper; // 👈

public function boot(): void
{
    // ...
    
    PagHiper::resolveApiUsing(fn () => 'api-que-vai-sobescrever-a-api-do-env');
    PagHiper::resolveTokenUsing(fn () => 'token-que-vai-sobescrever-o-token-do-env');
    PagHiper::resolveBilletNotificationUrlUsing(fn () => route('rota-padrão-de-retorno-automático-de-boletos'));
    PagHiper::resolvePixNotificationUrlUsing(fn () => route('rota-padrão-de-retorno-automático-de-pix'));
}
```

Se preferir você pode utilizar métodos que combinam as ações:

```php
// arquivo: app/Providers/AppServicesProvider.php

use DevAjMeireles\PagHiper\Facades\PagHiper; // 👈

public function boot(): void
{
    // ...
    
    PagHiper::resolveCredentials(
        fn () => 'api-que-vai-sobescrever-a-api-do-env',
        fn () => 'token-que-vai-sobescrever-o-token-do-env'
    );
}
```

Você também pode utilizar uma única função para resolver duas rotas:

```php
// arquivo: app/Providers/AppServicesProvider.php

use DevAjMeireles\PagHiper\Facades\PagHiper; // 👈

public function boot(): void
{
    // ...
    
    PagHiper::resolveNotificationUrlUsing(
        fn () => route('rota-padrão-de-retorno-automático-de-boletos'),
        fn () => route('rota-padrão-de-retorno-automático-de-pix')
    );
}
```
    
## Outros Detalhes

- Cobertura de Testes, usando <a href="https://pestphp.com" target="_blank">PestPHP</a>
- Cobertura de Tipagem de Código (100%), <a href="https://pestphp.com" target="_blank">PestPHP</a>
- Cobertura de Analise de Código (Level 5, 100%), <a href="https://phpstan.org/" target="_blank">PhpStan</a>

<div class="alert alert-warning">
    Os testes são projetados exclusivamete sob o Laravel 10.
</div>
