<p align="center"><img src="./art/cover.png" alt="PagHiper for Laravel"></p>

- [Introdução](#introduction)
- [Detalhes Técnicos](#technical-details)
- [Instalação](#installation)
- [Boleto Bancário](#billet)
    - [Criando Boleto Bancário](#creating-billet)
    - [Consultando Boleto Bancário](#consulting-billet)
    - [Cancelando Boleto Bancário](#cancelling-billet)
    - [Retorno Automático de Boleto Bancário](#billet-notification)
    - [Tratamento de Erros](#billet-errors)
- [Atualizações](CHANGELOG.md)
- [Pendências](#todo)
- [Contribuição](#contributing)
- [Licença de Uso](#license)

<a name="introduction"></a>
# Introdução

`PagHiper for Laravel` é um pacote que adiciona os principais recursos do PagHiper a aplicações Laravel de forma fácil e descomplicada. Com este pacote você poderá interagir com Boletos Bancários e PIX *(pendência)* gerados pela PagHiper.

**`Paghiper for Laravel` foi criado para Laravel 10 e PHP 8.1, no mais alto padrão possível do PHP moderno, com cobertura de testes e fortemente tipado, garantindo estabilidade nas funcionalidades.**

---

`Paghiper for Laravel` foi criado e é mantido por mim, [AJ Meireles](https://www.linkedin.com/in/devajmeireles/). Sou desenvolvedor de software há 12 anos, dos quais há 9 trabalho exclusivamente com PHP, inclusive como fundador da comunidade [EuSeiPhp](https://www.youtube.com/@euseiphp), um canal para compartilhamento de conteúdos sobre PHP e Laravel.

<a name="technical-details"></a>
# Detalhes Técnicos

- Versão do PHP: ^8.1 | ^8.2
- Versão do Laravel: 10.x

---

### Facade

`Paghiper for Laravel` oferece uma [Facade](https://laravel.com/docs/10.x/facades) para interação com a classe principal do pacote:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->create(/* ... */)
```

---

### Cliente HTTP

Por trás dos panos, `Paghiper for Laravel` utiliza o poder do [cliente de HTTP do Laravel](https://laravel.com/docs/10.x/http-client). Com isso, caso você precise escrever testes automatizados, você deve seguir o esquema de testes do Laravel.

<a name="installation"></a>
# Instalação

Para instalar `Paghiper for Laravel`, execute o comando abaixo:

```bash
composer require devajmeireles/paghiper-for-laravel
```

Após instalar, execute o comando `paghiper:install` para concluir a instalação:

```bash
php artisan paghiper:install
```

Este comando irá publicar o arquivo `config/paghiper.php` para sua aplicação, junto a criação de variáveis de ambiente para os seus arquivos: `.env`. **Recomendo que abra o arquivo `config/paghiper.php` e leia com atenção (traduza se necessário!)**

Opcionalmente, você pode utilizar o parâmetro `--force` para forçar que o arquivo `config/paghiper.php` seja sobescrito se já existir.

---


## Resolvedores

`Paghiper for Laravel` oferece recursos de resolvedores para viabilizar a definição de configurações em tempo de execução, ideal para casos onde você precise **sobescrever as configurações de `api` ou `token` do arquivo `.env`**, ou para prefixar uma URL de retorno automático de boletos usando a função `route()` do Laravel:

```php
// app/Providers/AppServicesProvider.php

use DevAjMeireles\PagHiper\PagHiper; // 👈

public function boot(): void
{
    // ...
    
    PagHiper::resolveApiUsing(fn () => 'api-que-vai-sobescrever-a-api-do-env');
    PagHiper::resolveTokenUsing(fn () => 'token-que-vai-sobescrever-o-token-do-env');
    PagHiper::resolveBilletNotificationlUrlUsing(fn () => 'rota-padrão-de-retorno-automático-de-boletos');
}
```

Assim, para toda interação com a PagHiper estas configuraçõe serão usadas, ao invés das configurações definidas em seu arquivo `.env`.

<a name="billet"></a>
# Boleto Bancário

<a name="creating-billet"></a>
### Criando Boleto Bancário

Para uma melhor organização, a forma de interagir com o método `create` é enviar para ele quatro (4) instâncias de classes de objeto que representam os dados do corpo do boleto bancário:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Address; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Basic; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Item; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Payer; // 👈

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
            ->set('cpf_cnpj', '123.456.789-00') 
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

**Algumas observações:**

1. O método `set` irá procurar pela propriedade e só definirá o seu valor caso encontre a propriedade na classe que está sendo construída pelo método `make`.
2. O nome das propriedades deve seguir exatamente a [convenção de nome das propriedades de boleto bancário da PagHiper](https://dev.paghiper.com/reference/especificacoes-dos-campos-que-devem-ser-enviados-na-requisicao-boleto)

---

Opcionalmente, você pode usar um modelador do Laravel como `Payer` do boleto no método `create`:

```php
use App\Models\User; // 👈
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Item;

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

Para utilizar a abordagem acima seu modelador deve implementar a interface `PagHiperModelAbstraction`, a qual exigirá que os seguintes métodos sejam criados na classe do modelador:

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
        return '123.456.789-00';
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

---

Você também pode enviar um array de itens:

```php
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
            ->set('cpf_cnpj', '123.456.789-00') 
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
            [
                Item::make()->set('item_id', 12)->set('description', 'Kit de Malas de Viagem')->set('quantity', 1)->set('price_cents', 25000), 
                Item::make()->set('item_id', 12)->set('description', 'Protetor de Malas (100x100)')->set('quantity', 3)->set('price_cents', 3550), 
            ]   
        );
```

---

Para facilitar a sua interação com as respostas, `Paghiper for Laravel` oferece casts diferentes, sendo eles:

- `Array`: resposta convertida para `array`
- `Json`: resposta convertida para `json`
- `Response`: objeto original da resposta, `Illuminate\Http\Client\Response`
- `Collect` ou `Collection`: resposta convertida para `Illuminate\Support\Collection`

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Item;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

$billet = PagHiper::billet(Cast::Collection)
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
            ->set('cpf_cnpj', '123.456.789-00') 
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

// $billet será a resposta convertida para instância de Illuminate\Support\Collection
```

**Por padrão, as respostas de todos os métodos de interação com `Paghiper for Laravel` utilizam o cast `Cast::Array`, que transforma a resposta em `array`**

### Alternativa de Construção das Classes de Objeto

As classes `Basic`, `Payer`, `Address` e `Item`, acima mencionadas, oferecem duas formas de serem instanciadas:

1. Via método comum de PHP, `new`:

```php
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Basic;

$basic = new Basic(/* ... */);

// ...
```

2. Via padrão estático, `make`:

```php
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Basic;

$basic = Basic::make([
    'order_id'         => 1222,
    'notification_url' => route('paghiper.notification'),
    'days_due_date'    => 2,
    'type_bank_slip'   => 'boletoA4',
    'discount_cents'   => 0,
]);

// ou ...

$basic = Basic::make(12, route('paghiper.notification'), 2, 'boletoA4', 0);

// ou ...

$basic = Basic::make()->set(/* propriedade */, /* valor */);
```

* Recomendo que utilize o método `Basic::make()->set()`

<a name="consulting-billet"></a>
## Consultando Boleto Bancário

Para consultar o status de um Boleto Bancário utilize o método `status`:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->status(transaction: 'HF97T5SH2ZQNLF6Z');
```

---

Você também pode utilizar os casts na consulta de um boleto bancário:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

$billet = PagHiper::billet(Cast::Collection) // 👈
    ->status(transaction: 'HF97T5SH2ZQNLF6Z');

// $billet será a resposta convertida para instância de Illuminate\Support\Collection
```

<a name="cancelling-billet"></a>
## Cancelando Boleto Bancário

Para cancelar um boleto bancário utilize o método `cancel`:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet(Cast::Collection) // 👈
    ->cancel(transaction: 'HF97T5SH2ZQNLF6Z');
```

---

Você também pode utilizar os casts no cancelamento de um boleto bancário:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

$billet = PagHiper::billet(Cast::Collection) // 👈
    ->cancel(transaction: 'HF97T5SH2ZQNLF6Z');

// $billet será a resposta convertida para instância de Illuminate\Support\Collection
```

<a name="billet-notification"></a>
## Retorno Automático de Boleto Bancário

`Paghiper for Laravel` oferece uma forma fácil de lidar com o retorno automático de boletos bancários. 

**O retorno automático do PagHiper ocorrerá para a URL que você configurou no objeto `Basic`, no parâmetro `$notification_url` na criação do boleto bancário, ou para a URL definida via [resolvedor](https://github.com/devajmeireles/paghiper-for-laravel#resolvedores).** Essa URL deve ser uma URL pública em sua aplicação, e de preferência que não receba nenhum tratamento especial (middlewares, por exemplo):

Supondo que você possui uma URL nomeada como `paghiper.notification`, e que essa foi a URL utilizada, então isso será suficiente:

```php
// routes/web.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Facades\PagHiper;

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id'); // 👈 enviado pelo PagHiper
    $transaction  = $request->input('transaction_id');  // 👈 enviado pelo PagHiper

    $status = PagHiper::notification(notification: $notification, transaction: $transaction)->consult();
    
    // $status será a resposta convertida para array
})->name('paghiper.notification');
```

---

Você também pode utilizar os casts na consulta da notificação de um boleto bancário:

```php
// routes/web.php

use Illuminate\Http\Request;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id'); // 👈 enviado pelo PagHiper
    $transaction  = $request->input('transaction_id');  // 👈 enviado pelo PagHiper

    $status = PagHiper::cast(Cast::Collection) // 👈
        ->notification(notification: $notification, transaction: $transaction)
        ->consult();
    
    // $status será a resposta convertida para instância de Illuminate\Support\Collection
})->name('paghiper.notification');
```

---

### Cast Especial: `PagHiperNotification`

**De forma especial para o retorno automático, `Paghiper for Laravel` oferece o cast `BilletNotification`:**

```php
// routes/web.php

use Illuminate\Http\Request;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id'); // 👈 enviado pelo PagHiper
    $transaction  = $request->input('transaction_id');  // 👈 enviado pelo PagHiper

    $status = PagHiper::cast(Cast::BilletNotification) // 👈
        ->notification(notification: $notification, transaction: $transaction)
        ->consult();
})->name('paghiper.notification');
```

O cast `BilletNotification` irá interceptar a resposta da PagHiper e transformá-la numa instância da classe `PagHiperNotification` que **possui diversos métodos úteis como atalhos para lidar com a consulta da notificação:**

- `transaction()`: ID da transação
- `order()`: ID do pedido
- `createdAt()`: data de criação do boleto como instância de `Illuminate\Support\Carbon`
- `status()`: status do boleto como string
- `pending()`: `true` se o status do boleto for `pending`
- `reserved()`: `true` se o status do boleto for `reserved`
- `canceled()`: `true` se o status do boleto for `canceled`
- `completed()`: `true` se o status do boleto for `completed`
- `paid()`: `true` se o status do boleto for `paid`
- `processing()`: `true` se o status do boleto for `processing`
- `refunded()`: `true` se o status do boleto for `refunded`
- `paidAt()`: data de pagamento do boleto como instância de `Illuminate\Support\Carbon`
- `payer()`: instância da clase `Payer` mapeada
- `finalPrice()`: valor final do boleto, `value_cents`
- `discount()`: valor do desconto do boleto, `discount_cents`
- `bankSlip()`: array com dados do boleto (URL, linha digitável...)
- `dueDateAt()`: data de vencimento do boleto como instância de `Illuminate\Support\Carbon`
- `numItems`(): número de itens do boleto
- `original()`: resposta original da PagHiper como instância de `Illuminate\Http\Client\Response`
- `items()`: instância da clase `Payer` mapeada
  - **se um item**, será uma instância de `Payer`
  - **se mais de um item**, será um array de instâncias de `Payer`

### Método Especial: `modelable`

De forma estratégica, ao passar uma [instância de um modelador do Laravel](#creating-billet) como `Payer` do boleto bancário, o `order_id` na PagHiper receberá uma referência da classe e ID do modelador para que posteriormente no retorno automático você possa utilizar o método `modelable` para obter o modelador facilmente.

Essa abordagem fará com que o `order_id` do boleto bancário fique, por exemplo, da seguinte maneira na PagHiper: `11|App\Model\User:1`, onde `11` é o número do `$order_id` que você especificou na criação da classe `Basic`. Não há preocupação enquanto a este formato, uma vez que o `order_id` do boleto bancário é para uso interno, e não é exibido ao cliente.

Dessa forma você então poderá utilizar o método `modelable`:

```php
use App\Models\User; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Item;
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// criando o boleto (User:1) 👇

$billet = PagHiper::billet()
    ->create(
        Basic::make()
            ->set('order_id', 1433) 
            ->set('notification_url', route('paghiper.notification')) 
            ->set('days_due_date', 2) 
            ->set('type_bank_slip', 'boletoA4') 
            ->set('discount_cents', 0),
        User::find(1), // 👈
        Item::make()
            ->set('item_id', 12) 
            ->set('description', 'Kit de Malas de Viagem') 
            ->set('quantity', 1) 
            ->set('price_cents', 25000));

// retorno automático 👇

// routes/web.php

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id');
    $transaction  = $request->input('transaction_id');

    $status = PagHiper::cast(Cast::BilletNotification)
        ->notification(notification: $notification, transaction: $transaction)
        ->consult();
        
    $status->modelable(); // 👈 retornará uma instância de App\Models\User:1
})->name('paghiper.notification');
```

Opcionalmente, você pode definir o parâmetro de `modelable()` como `false` para evitar que uma exception do tipo `NotificationModelNotFoundException` ou `ModelNotFoundException` seja lançada caso haja falha na busca pelo modelador. Nesse caso, o método retornará `null` caso não encontre o modelador ou se depare a algum erro.

<a name="billet-errors"></a>
## Tratamento de Erros

- `DevAjMeireles\PagHiper\Exceptions\PagHiperException` 
  - erro genérico do PagHiper, para todo caso onde `result` é `reject`
- `DevAjMeireles\PagHiper\Exceptions\UnallowedCastType` 
  - tentativa de uso indetivo do cast `BilletNotification`
- `DevAjMeireles\PagHiper\Exceptions\UnsupportedCastTypeExcetion` 
  - tentativa de uso de um cast inexistente
- `DevAjMeireles\PagHiper\Exceptions\WrongModelSetUpException` 
  - tentativa de criação de boleto usando um modelador sem que ele tenha sido preparado

- `DevAjMeireles\PagHiper\Exceptions\NotificationModelNotFoundException` 
  - não foi possível recuperar o model ao usar o método `modelable` no retorno automático

<a name="todo"></a>
## Pendências

- Integração com [PIX do PagHiper](https://dev.paghiper.com/reference/emissao-de-pix-paghiper)
- Integração com [Contas Bancárias](https://dev.paghiper.com/reference/solicitacao-saque)
- Integração com [Listas de Transações](https://dev.paghiper.com/reference/listar-transacoes-via-api-exemplo)

<a name="contributing"></a>
## Contribuição

Todo e qualquer PR será bem-vindo em favor de ajustes de bugs, melhorias ou aprimoramentos desde que atenda as seguintes exigências:
- O código do PR ser escrito em inglês, seguindo a [PSR12](https://www.php-fig.org/psr/psr-12/)
- O código do PR ser formatado usando [Laravel Pint](https://laravel.com/docs/10.x/pint)
- O código do PR ser analisando usando [LaraStan](https://github.com/nunomaduro/larastan)
- O código do PR ser testado usando [PestPHP](https://pestphp.com/), inclusive adições ou modificações

## Ambiente de Desenvolvimento

1. Crie um fork do repositório
2. Clone o repositório a partir do seu fork:

```bash
git clone <url_do_repositório>
```

3. Instale as dependências:

```bash
cd pahiper-for-laravel && composer install
```

4. Execute testes:

```bash
composer test
```

5. Analise a integridade do código: 

```bash
composer analyse
```

6. Analise a integridade de tipagem do código: 

```bash
composer type:coverage
```

<a name="licensing"></a>
## Licença de Uso

`PagHiper for Laravel` é um projeto "open-source" sobre a licença [MIT](LICENSE.md).
