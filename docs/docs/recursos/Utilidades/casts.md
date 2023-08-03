# Cast das Respostas

Para facilitar a sua interação com as respostas dadas pela PagHiper, o `Paghiper for Laravel` oferece diversos casts 
diferentes que visam interceptar a resposta e transforma-lá em algo, sendo eles:

- **Array**: resposta convertida para `array`
- **Json**: resposta convertida para `json`
- **Collect** ou **Collection**: resposta convertida para `Illuminate\Support\Collection`
- **Response**: objeto original da resposta, `Illuminate\Http\Client\Response`

<div class="alert alert-success">
    Os casts estão disponíveis tanto para interação com Boleto Bancário como PIX.
</div>

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

$billet = PagHiper::billet(Cast::Collection) // 👈
    ->create(
        Basic::make()
            ->set('order_id', 1433) 
            ->set('notification_url', route('paghiper.notification')) 
            ->set('days_due_date', 2) 
            ->set('type_bank_slip', 'boletoA4') 
            ->set('discount_cents', 0),
        Payer::make()
            ->set('name', 'Joao Inácio da Silva') 
            ->set('email', 'joao.inacio@gmail.com') 
            ->set('cpf_cnpj', '123.456.789-00') 
            ->set('phone', '11985850505')
            ->set(
                'address', Address::make()
                    ->set('street', 'Rua Alameda Barão de Limeira')
                    ->set('number', 102)
                    ->set('complement', 'Casa')
                    ->set('district', 'São Vicente')
                    ->set('city', 'São Paulo')
                    ->set('state', 'São Paulo')
                    ->set('zip_code', '13332251')
            ),
        Item::make()
            ->set('item_id', 12) 
            ->set('description', 'Kit de Malas de Viagem') 
            ->set('quantity', 1) 
            ->set('price_cents', 25000));
```

No exemplo acima, `$billet` passa a ser uma instância de `Illuminate\Support\Collection`
