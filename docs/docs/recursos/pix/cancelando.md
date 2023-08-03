# Cancelando

Para cancelar um PIX utilize o método `cancel`:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::pix()->cancel('HF97T5SH2ZQNLF6Z');
```

## Casts

Você também pode usar os [casts](../utilidades/casts.md) disponíveis para transformar a resposta.
