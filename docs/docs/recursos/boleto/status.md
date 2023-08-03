# Status

Para consultar o status de um Boleto Bancário utilize o método `status`:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->status('HF97T5SH2ZQNLF6Z');
```

## Casts

Você também pode usar os [casts](../utilidades/casts.md) disponíveis para transformar a resposta.
