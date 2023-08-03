# Cancelando

Para cancelar um Boleto Bancário utilize o método `cancel`:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->cancel('HF97T5SH2ZQNLF6Z');
```

## Casts

Você também pode usar os [casts](../Utilidades/casts.md) disponíveis para transformar a resposta.
