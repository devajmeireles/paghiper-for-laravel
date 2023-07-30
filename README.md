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

`PagHiper for Laravel` é um pacote que adiciona os principais recursos do PagHiper a aplicações Laravel de forma fácil e descomplicada. Com este pacote você poderá interagir com Boletos Bancários e PIX gerados pela PagHiper.

**`Paghiper for Laravel` foi criado para Laravel 10 e PHP 8.1, no mais alto padrão possível do PHP moderno, com cobertura de testes e fortemente tipado, garantindo estabilidade nas funcionalidades.**

---

`Paghiper for Laravel` foi criado e é mantido por mim, [AJ Meireles](https://www.linkedin.com/in/devajmeireles/). Sou desenvolvedor de software há 12 anos, dos quais há 9 trabalho exclusivamente com PHP, inclusive como fundador da comunidade [EuSeiPhp](https://www.youtube.com/@euseiphp), um canal para compartilhamento de conteúdos sobre PHP e Laravel.

<a name="technical-details"></a>
### Detalhes Técnicos

- Versão do PHP: **8.1**
- Versão do Laravel: **10.x**

---

#### Facade

`Paghiper for Laravel` oferece uma [Facade](https://laravel.com/docs/10.x/facades) para interação com a classe principal do pacote:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->create(/* ... */)
```

---

#### Cliente HTTP

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

<a name="billet"></a>
# Boleto Bancário

<a name="creating-billet"></a>
### Criando Boleto Bancário

Para uma melhor organização, a forma de interagir com o método `create` é enviar para ele quatro (4) instâncias de classes de objeto que representam os dados do corpo do boleto bancário:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Address; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Basic; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Item; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Payer; // 👈

$billet = PagHiper::billet()
    ->create(
        new Basic( // 👈
            order_id: 1433, 
            notification_url: route('paghiper.notification'), 
            days_due_date: 2, 
            type_bank_slip: 'boletoA4', 
            discount_cents: 0,
        ),
        new Payer( // 👈
            name: 'Joao Inácio da Silva', 
            email: 'joao.inacio@gmail.com', 
            cpf_cnpj: '123.456.789-00', 
            phone: '11985850505'
            new Address( // 👈
                street: 'Rua Alameda Barão de Limeira',
                number: 102,
                complement: 'Casa',
                district: 'São Vicente',
                city: 'São Paulo',
                state: 'São Paulo',
                zip_code: '13332251'
            )
        ),
        new Item( // 👈
            item_id: 12, 
            description: 'Kit de Malas de Viagem', 
            quantity: 1, 
            price_cents: 25000
        ),
    );
```

**Algumas observações:**

1. Por mais que pareça confuso, dessa forma você tem uma declaração exata do que está sendo enviado para o boleto bancário.
2. No exemplo acima os parâmetros foram nomeados para fins de instrução. Você pode optar por utilizar dessa forma ou não.

### Url Padrão de Retorno Automático

Se a sua aplicação possuir uma URL fixa para o [retorno automático do PagHiper](#billet-notification), você pode definir uma nova chave no arquivo `config/paghiper.php` com essa URL:

```php
// config/paghiper.php

return [
    // ...
    
    'notification_url' => 'https://retorno-automático.com/paghiper/notification',
    
    // ...
];
```

**Caso você não defina esta configuração**, `Paghiper for Laravel` espera que você informe a URL através do parametro `$notification_url` da classe `Basic`:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Basic;

// ...

$billet = PagHiper::billet()
    ->create(
        // ...
        new Basic(
            order_id: 1433, 
            notification_url: 'https://minha-aplicacão.com.br/paghiper/notification', // 👈 
            days_due_date: 2, 
            type_bank_slip: 'boletoA4', 
            discount_cents: 0,
        ),
        // ...
    );
```

---

Uma alternativa disponível e eficaz é enviar uma classe de um modelador do Laravel para o método `create`:

```php
use App\Models\User; // 👈
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Item;

$billet = PagHiper::billet()
    ->create(
        new Basic(
            order_id: 1433, 
            notification_url: route('paghiper.notification'), 
            days_due_date: 2, 
            type_bank_slip: 'boletoA4', 
            discount_cents: 0,
        ),
        User::first(), // 👈
        new Item(
            item_id: 12, 
            description: 'Kit de Malas de Viagem', 
            quantity: 1, 
            price_cents: 25000
        ),
    );
```

**Para utilizar a abordagem acima**, seu modelador deve implementar a interface `PagHiperModelAbstraction`, a qual exigirá que os seguintes métodos sejam criados na classe do modelador:

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

**Essa abordagem facilita processos de formatações antes de enviar os dados a PagHiper, por exemplo.**

---

Você também pode enviar um array de itens, para casos quais você crie o boleto bancário para mais de um item:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\DTO\Objects\Payer;

$billet = PagHiper::billet()
    ->create(
        new Basic(
            order_id: 1433, 
            notification_url: route('paghiper.notification'), 
            days_due_date: 2, 
            type_bank_slip: 'boletoA4', 
            discount_cents: 0,
        ),
        new Payer(
            name: 'Joao Inácio da Silva', 
            email: 'joao.inacio@gmail.com', 
            cpf_cnpj: '123.456.789-00', 
            phone: '11985850505'
            new Address(
                street: 'Rua Alameda Barão de Limeira',
                number: 102,
                complement: 'Casa',
                district: 'São Vicente',
                city: 'São Paulo',
                state: 'São Paulo',
                zip_code: '13332251'
            )
        ),
        [
            new Item(item_id: 12, description: 'Kit de Malas de Viagem', quantity: 1, price_cents: 25000),        
            new Item(item_id: 13, description: 'Capa de Mala (100x100)', quantity: 1, price_cents: 5000),        
            new Item(item_id: 14, description: 'Kit de Rodas (100x100)', quantity: 1, price_cents: 3500),        
        ]       
    );
```

---

Para facilitar sua interação com as respostas, `Paghiper for Laravel` oferece casts diferentes, sendo eles:

- `Response`: o objeto original da resposta
- `Array`: resposta convertida para um `array`
- `Json`: resposta convertida para um `json`
- `Collect` ou `Collection`: resposta convertida para uma instância de `Illuminate\Support\Collection`

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

$billet = PagHiper::billet(Cast::Collection) // 👈
    ->create(
        new Basic(
            order_id: 1433, 
            notification_url: route('paghiper.notification'), 
            days_due_date: 2, 
            type_bank_slip: 'boletoA4', 
            discount_cents: 0,
        ),
        new Payer(
            name: 'Joao Inácio da Silva', 
            email: 'joao.inacio@gmail.com', 
            cpf_cnpj: '123.456.789-00', 
            phone: '11985850505'
            new Address(
                street: 'Rua Alameda Barão de Limeira',
                number: 102,
                complement: 'Casa',
                district: 'São Vicente',
                city: 'São Paulo',
                state: 'São Paulo',
                zip_code: '13332251'
            )
        ),
        new Item(
            item_id: 12, 
            description: 'Kit de Malas de Viagem', 
            quantity: 1, 
            price_cents: 25000
        ),
    );

// $billet será a resposta convertida para uma instância de Illuminate\Support\Collection
```

**Por padrão, as respostas de todos os métodos de interação com `Paghiper for Laravel` utilizam o cast `Cast::Array`, que transforma a resposta em `array`**

### Alternativa de Construção das Classes de Objeto

As classes `Basic`, `Payer`, `Address` e `Item`, acima mencionadas, oferecem duas formas de serem instanciadas:

1. Via método comum de PHP, `new`:

```php
use DevAjMeireles\PagHiper\DTO\Objects\Basic;

$basic = new Basic(/* ... */);

// ...
```

2. Via padrão estático, `make`:

```php
use DevAjMeireles\PagHiper\DTO\Objects\Basic;

$basic = Basic::make([
    'order_id'         => 1222,
    'notification_url' => route('paghiper.notification'),
    'days_due_date'    => 2,
    'type_bank_slip'   => 'boletoA4',
    'discount_cents'   => 0,
]);

// ou ...

$basic = Basic::make(12, route('paghiper.notification'), 2, 'boletoA4', 0);
```

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

// $billet será a resposta convertida para uma instância de Illuminate\Support\Collection
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

// $billet será a resposta convertida para uma instância de Illuminate\Support\Collection
```

<a name="billet-notification"></a>
## Retorno Automático de Boleto Bancário

`Paghiper for Laravel` oferece uma forma fácil de lidar com o retorno automático de boletos bancários. 

**O retorno automático do PagHiper ocorrerá para a URL que você configurou no objeto `Basic`, no parâmetro `$notification_url` na criação do boleto bancário, ou para a URL definida via `config/paghiper.php`.** Essa URL deve ser uma URL pública em sua aplicação, e de preferência que não receba nenhum tratamento especial (middlewares, por exemplo):

Supondo que você possui uma URL nomeada como `paghiper.notification`, e que essa foi a URL enviada como `$notification_url` na classe de objeto `Basic` no momento da criação do boleto bancário, então isso será suficiente:

```php
// routes/web.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Facades\PagHiper;

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id'); // 👈 enviado pelo PagHiper
    $transaction  = $request->input('transaction_id');  // 👈 enviado pelo PagHiper

    $status = PagHiper::notification(notification: $notification, transaction: $transaction)->consult();
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
    
// $status será a resposta convertida para uma instância de Illuminate\Support\Collection
})->name('paghiper.notification');
```

---

### Cast Especial: `PagHiperNotification`

**De forma especial para o retorno automático, `Paghiper for Laravel` oferece um cast diferente, `Dto`:**

```php
// routes/web.php

use Illuminate\Http\Request;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id'); // 👈 enviado pelo PagHiper
    $transaction  = $request->input('transaction_id');  // 👈 enviado pelo PagHiper

    $status = PagHiper::cast(Cast::Dto) // 👈
        ->notification(notification: $notification, transaction: $transaction)
        ->consult();
})->name('paghiper.notification');
```

O cast `Dto` irá interceptar a resposta da PagHiper e transformá-la em uma instância da classe `PagHiperNotification` que **possui diversos métodos úteis como atalhos para lidar com a consulta da notificação:**

- `transaction()`: ID da transação
- `order()`: ID do pedido
- `createdAt()`: data de criação do boleto como instância de `Illuminate\Support\Carbon`
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
- `items()`: instância da clase `Payer` mapeada
  - **se um item**, será uma instância de `Payer`
  - **se mais de um item**, será um array de instâncias de `Payer`

### Método Especial: `modelable`

De forma estratégica, ao passar uma instância de um modelador do Laravel como `Payer` do boleto bancário, o `order_id` na PagHiper receberá uma referência da classe e ID do modelador para que posteriormente no retorno automático você possa utilizar o método `modelable` para obter o modelador facilmente.

Essa abordagem fará com que o `order_id` do boleto bancário fique, por exemplo, da seguinte maneira na PagHiper: `11|App\Model\User:1`, onde `11` é o número do `$order_id` que você especificou na criação da classe `Basic`. Não há preocupação enquanto a este formato, uma vez que o `order_id` do boleto bancário é para uso interno, e não é exibido ao cliente.

Dessa forma você então poderá utilizar o método `modelable`:

```php
use App\Models\User;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Item;

// criando o boleto usando o modelador User:1 👇

$billet = PagHiper::billet()
    ->create(
        new Basic(
            order_id: 1433, 
            notification_url: route('paghiper.notification'), 
            days_due_date: 2, 
            type_bank_slip: 'boletoA4', 
            discount_cents: 0,
        ),
        User::find(1), // 👈
        new Item(
            item_id: 12, 
            description: 'Kit de Malas de Viagem', 
            quantity: 1, 
            price_cents: 25000
        ),
    );

// retorno automático 👇

// routes/web.php

use Illuminate\Http\Request;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Enums\Cast;

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id');
    $transaction  = $request->input('transaction_id');

    $status = PagHiper::cast(Cast::Dto)
        ->notification(notification: $notification, transaction: $transaction)
        ->consult();
        
    $status->modelable(); // 👈 retornará uma instância de App\Models\User:1
})->name('paghiper.notification');
```

De forma opcional, você pode definir o único parâmetro de `modelable()` como `false` para evitar que uma exception do tipo `NotificationModelNotFoundException` ou `ModelNotFoundException` seja lançada caso haja falha na busca pelo modelador.

<a name="billet-errors"></a>
## Tratamento de Erros

- `DevAjMeireles\PagHiper\Exceptions\PagHiperException` 
  - erro genérico do PagHiper
- `DevAjMeireles\PagHiper\Exceptions\UnallowedCastType` 
  - tentativa de uso indetivo do cast `DevAjMeireles\PagHiper\Enums\Cast::Dto`
- `DevAjMeireles\PagHiper\Exceptions\UnsupportedCastTypeExcetion` 
  - tentativa de uso de um cast inexistente
- `DevAjMeireles\PagHiper\Exceptions\WrongModelSetUpException` 
  - tentativa de criação de boleto usando um modelador sem que ele tenha sido preparado
  - tentativa de uso de um cast inexistente
- `DevAjMeireles\PagHiper\Exceptions\NotificationModelNotFoundException` 
  - não foi possível recuperar o model ao usar o método `modelable` no retorno automático

<a name="todo"></a>
## Pendências

- Integração com [PIX do PagHiper](https://dev.paghiper.com/reference/emissao-de-pix-paghiper)
- Integração com [Contas Bancárias](https://dev.paghiper.com/reference/solicitacao-saque)
- Integração com [Listas de Transações](https://dev.paghiper.com/reference/listar-transacoes-via-api-exemplo)

<a name="contributing"></a>
## Contribuição

Todo e qualquer PR será bem-vindo em favor de ajustes de bugs, melhorias ou aprimoramentos desde que:
- O PR ser criado de forma explicativa, mencionando inclusive o problema
- O PR ser criado em favor de algo que faça sentido ou relevância
- O código do PR ser escrito em inglês, seguindo a [PSR12](https://www.php-fig.org/psr/psr-12/)
- O código do PR ser formatado usando [Laravel Pint](https://laravel.com/docs/10.x/pint)
- O código do PR ser analisando usando [LaraStan](https://github.com/nunomaduro/larastan)
- O código do PR ser testado usando [PestPHP](https://pestphp.com/), inclusive adições ou modificações

### Ambiente de Desenvolvimento

1. Crie um fork do repositório
2. Clone o repositório:

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

<a name="licensing"></a>
## Licença de Uso

`PagHiper for Laravel` é um projeto open-source sobre a licença [MIT](LICENSE.md).
