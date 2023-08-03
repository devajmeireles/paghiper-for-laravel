# Criando

## Sintaxe

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->create(/* ... */)
```

## Criando Boleto

Para uma melhor organização, a forma de interagir com a criação de boletos é enviando para o método `create` quatro (4) instâncias de classes de objeto:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Basic; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Address; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Item; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Payer; // 👈

$billet = PagHiper::billet()
    ->create(
        Basic::make() // 👈
            ->set('order_id', 1433) 
            ->set('notification_url', route('paghiper.notification')) 
            ->set('days_due_date', 2) 
            ->set('type_bank_slip', 'boletoA4') 
            ->set('discount_cents', 0),
        Payer::make() // 👈
            ->set('name', 'Joao Inácio da Silva') 
            ->set('email', 'joao.inacio@gmail.com') 
            ->set('cpf_cnpj', '99279725041') 
            ->set('phone', '11985850505')
            ->set(
                'address', Address::make() // 👈
                    ->set('street', 'Rua Alameda Barão de Limeira')
                    ->set('number', 102)
                    ->set('complement', 'Casa')
                    ->set('district', 'São Vicente')
                    ->set('city', 'São Paulo')
                    ->set('state', 'São Paulo')
                    ->set('zip_code', '13332251')
            ),
        Item::make() // 👈
            ->set('item_id', 12) 
            ->set('description', 'Kit de Malas de Viagem') 
            ->set('quantity', 1) 
            ->set('price_cents', 25000));
```

### Observações

1. O método `set` irá procurar pela propriedade e só definirá o seu valor caso encontre a propriedade na classe que está sendo construída pelo método `make`.
2. O nome das propriedades deve seguir exatamente a <a href="https://dev.paghiper.com/reference/especificacoes-dos-campos-que-devem-ser-enviados-na-requisicao-boleto" target="_blank">convenção de nome das propriedades de boleto bancário da PagHiper</a>

## Usando Modelador do Laravel

Opcionalmente, você pode usar um modelador do Laravel como `Payer` do boleto:

```php
use App\Models\User; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()
    ->create(
        Basic::make()
            ->set('order_id', 1433) 
            ->set('notification_url', route('paghiper.notification')) 
            ->set('days_due_date', 2) 
            ->set('type_bank_slip', 'boletoA4') 
            ->set('discount_cents', 0),
        User::first(), // 👈
        Item::make()
            ->set('item_id', 12) 
            ->set('description', 'Kit de Malas de Viagem') 
            ->set('quantity', 1) 
            ->set('price_cents', 25000));
```

Para utilizar a abordagem, acima seu modelador deve implementar a interface `PagHiperModelAbstraction`, a qual exigirá que os seguintes métodos sejam criados na classe do modelador:

```php
namespace App\Models;

use DevAjMeireles\PagHiper\Contracts\PagHiperModelAbstraction; // 👈
use Illuminate\Database\Eloquent\Model;

class User extends Model implements PagHiperModelAbstraction // 👈
{
    // ...

    public function pagHiperName(): string
    {
        return 'Joao Inácio da Silva';
    }

    public function pagHiperEmail(): string
    {
        return 'joao.inacio@gmail.com';
    }

    public function pagHiperCpfCnpj(): string
    {
        return '99279725041';
    }

    public function pagHiperPhone(): string
    {
        return '11985850505';
    }

    public function pagHiperAddress(): array
    {
        return [
            'street'     => 'Rua Alameda Barão de Limeira',
            'number'     => 102,
            'complement' => 'Casa',
            'district'   => 'São Vicente',
            'city'       => 'São Paulo',
            'zip_code'   => '13332251',
        ];
    }
};
```

## Multiplos Itens

Você também pode enviar um array de itens:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\DTO\Objects\Payer;

$billet = PagHiper::billet()
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
            ->set('cpf_cnpj', '99279725041') 
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
            [
                Item::make()->set('item_id', 12)->set('description', 'Kit de Malas de Viagem')->set('quantity', 1)->set('price_cents', 25000), 
                Item::make()->set('item_id', 12)->set('description', 'Protetor de Malas (100x100)')->set('quantity', 3)->set('price_cents', 3550), 
            ]   
        );
```

## Casts

Você pode usar [casts](../utilidades/casts.md) disponíveis para transformar a resposta.
