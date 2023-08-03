# Tratamento de Erro

Em diversos casos você pode se deparar com excessões lançadas pelo `PagHiper for Laravel`. Nesta página você encontrará
a explicação para as mais comuns excessões disponíveis:

```php
DevAjMeireles\PagHiper\Exceptions\PagHiperException::class
```

Erro genérico para todo caso onde a resposta da PagHiper seja `reject`.

```php
DevAjMeireles\PagHiper\Exceptions\UnallowedEmptyNotificationUrl::class
```

Lançada ao tentar criar um PIX/Boleto Bancário sem informar um `notification_url`

```php
DevAjMeireles\PagHiper\Exceptions\UnsupportedCastTypeExcetion::class
```

Tentativa de uso de cast inexistente ou não aplicável

```php
DevAjMeireles\PagHiper\Exceptions\NotificationModelNotFoundException::class
```

Erro na busca pelo modelador, relacionado com o método `modelable()`

```php
DevAjMeireles\PagHiper\Exceptions\WrongModelSetUpException::class
```

Erro na montagem do modelador para uso na criação de PIX/Boleto Bancário

<div class="alert alert-warning">
    Para se precaver de situações inesperadas utilize blocos try/catch.
</div>
