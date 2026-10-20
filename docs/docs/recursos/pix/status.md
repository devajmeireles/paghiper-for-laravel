# Status

Para consultar o status de um PIX utilize o método `status`:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$pix = PagHiper::pix()->status('HF97T5SH2ZQNLF6Z');
```

## Casts

Você também pode usar os [casts](../utilidades/casts.md) disponíveis para transformar a resposta.
