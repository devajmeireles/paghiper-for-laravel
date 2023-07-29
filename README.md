<p align="center"><img src="./art/cover.png" alt="Laravel Folio Package Logo"></p>

- [Introdução](#introduction)
- [Instalação](#installation)
- [Boleto Bancário](#billet)
    - [Criando Boleto Bancário](#creating-billet)
    - [Consultando Boleto Bancário](#consulting-billet)
    - [Cancelando Boleto Bancário](#cancelling-billet)
    - [Retorno Automático de Boleto Bancário](#billet-notification)
- [Contribuição](#contributing)
- [License](#license)

<a name="introduction"></a>
# Introdução

`PagHiper for Laravel` é um pacote que adiciona os principais recursos do PagHiper a aplicações Laravel de forma fácil e descomplicada. Com este pacote você poderá integarir com Boletos Bancários e PIX gerados pela PagHiper.

**O pacote foi criado no mais alto padrão possível do PHP moderno, com cobertura de testes e fortemente tipado, garantindo estabilidade nas funcionalidades.**

---

O pacote é mantido por mim, AJ Meireles. Você pode me encontrar em um dos canais abaixo:

- [LinkedIn](https://www.linkedin.com/in/devajmeireles/)
- [Twitter](https://twitter.com/devajmeireles)

<a name="installation"></a>
# Instalação

Para instalar o pacote, execute o comando abaixo:

```bash
composer require devajmeireles/paghiper-laravel
```

Após instalar, execute o comando `paghiper:install` para concluir a instalação:

```bash
php artisan paghiper:install
```

Este comando irá apenas publicar o arquivo `config/paghiper.php` para sua aplicação. Este arquivo armazena as informações da sua conta na PagHiper para comunicação via API.

<a name="billet"></a>
# Boleto Bancário

<a name="creating-billet"></a>
## Criando Boleto Bancário

O pacote `Paghiper for Laravel` oferece uma [Facade](https://laravel.com/docs/10.x/facades) para interação com a API do PagHiper:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->create(/* ... */)
```

Para uma melhor organização, a forma de interagir com o método `create` é enviando para ele quatro instâncias de classes de objeto que representam os dados do corpo do boleto bancário:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Core\DTO\Objects\Address; // 👈
use DevAjMeireles\PagHiper\Core\DTO\Objects\Basic;   // 👈
use DevAjMeireles\PagHiper\Core\DTO\Objects\Item;    // 👈
use DevAjMeireles\PagHiper\Core\DTO\Objects\Payer;   // 👈

$billet = (new PagHiper())->billet()
    ->create(
        new Payer(name: 'Foo Bar', email: 'foo.bar@gmail.com', document: '123.456.789-00', phone: '1199999999'),
        new Basic(orderId: fake()->randomDigit(), notificationUrl: 'https://my-app/paghiper/notification/callback', daysDueDate: 2, typeBankSlip: 'boletoA4', discountCents: 0),
        new Address(street: 'Foo Street', number: 123, complement: 'Home', district: 'Bar District', city: 'Foo City', state: 'Foo Country', zipCode: '12345-678'),
        new Item(id: fake()->randomDigit(), description: 'Foo Bar', quantity: 1, price: 1000)
    );
```

**Observe que no exemplo acima todos os parâmetros das classes: `Payer`, `Basic`, `Address` e `Item` foram nomeados apenas para fins de instrução. Você pode optar por utilizar dessa forma ou não.**

---

Uma alternativa disponível é enviar uma classe de modelador do Laravel para o método `create`:

```php
use App\Models\User; // 👈
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Core\DTO\Objects\Address;
use DevAjMeireles\PagHiper\Core\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\Core\DTO\Objects\Item;

$billet = (new PagHiper())->billet()
    ->create(
        User::first(), // 👈
        new Basic(orderId: fake()->randomDigit(), notificationUrl: 'https://my-app/paghiper/notification/callback', daysDueDate: 2, typeBankSlip: 'boletoA4', discountCents: 0),
        new Address(street: 'Foo Street', number: 123, complement: 'Home', district: 'Bar District', city: 'Foo City', state: 'Foo Country', zipCode: '12345-678'),
        new Item(id: fake()->randomDigit(), description: 'Foo Bar', quantity: 1, price: 1000)
    );
```

**Para utilizar a abordagem acima**, seu modelador deve implementar a interface `PagHiperModelAbstraction`, a qual exigirá os seguintes métodos:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DevAjMeireles\PagHiper\Core\Contracts\PagHiperModelAbstraction; // 👈

class User extends Model implements PagHiperModelAbstraction // 👈
{
    // ...

    public function pagHiperName(): string
    {
        return 'Foo bar';
    }

    public function pagHiperEmail(): string
    {
        return 'foo.bar@gmail.com';
    }

    public function pagHiperDocument(): string
    {
        return '123.456.789-00';
    }

    public function pagHiperPhone(): string
    {
        return '1199999999';
    }

    public function pagHiperAddress(): array
    {
        return [
            'street'     => 'Foo Street',
            'number'     => 123,
            'complement' => 'Home',
            'district'   => 'Bar District',
            'city'       => 'Foo City',
            'zip_code'   => '12345-678',
        ];
    }
};
```

**Isso facilita processos de formatações antes de enviar os dados à PagHiper, por exemplo.**

Obs.: Se você tiver mais de um modelador que interaja com o pacote, abstraia os métodos para uma trait. 😉

---

Para facilitar sua interação com a resposta da PagHiper, o pacote oferece "casts" diferentes, sendo eles:

- `Response`: o objeto original da resposta
- `Json` ou `Array`: a resposta convertida para um array
- `Collect` ou `Collection`: a resposta convertida para uma instância de `Illuminate\Support\Collection`

```php
use App\Models\User;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Core\DTO\Objects\Address;
use DevAjMeireles\PagHiper\Core\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\Core\DTO\Objects\Item;
use DevAjMeireles\PagHiper\Core\Enums\Cast; // 👈

$billet = (new PagHiper())->billet(Cast::Collection) // 👈
    ->create(
        User::first(),
        new Basic(orderId: fake()->randomDigit(), notificationUrl: 'https://my-app/paghiper/notification/callback', daysDueDate: 2, typeBankSlip: 'boletoA4', discountCents: 0),
        new Address(street: 'Foo Street', number: 123, complement: 'Home', district: 'Bar District', city: 'Foo City', state: 'Foo Country', zipCode: '12345-678'),
        new Item(id: fake()->randomDigit(), description: 'Foo Bar', quantity: 1, price: 1000)
    );
```

Tendo feito isso, `$billet` será uma instância de `Illuminate\Support\Collection` contendo a resposta da PagHiper. **Por padrão, as respostas de todos os métodos de interação com o pacote utilizam o cast `Cast::Array`, que transforma a resposta em `array`**

<a name="consulting-billet"></a>
### Consultando Boleto Bancário

Para consultar o status de um Boleto Bancário utilize o método `status`:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->status(transaction: 'HF97T5SH2ZQNLF6Z');
```

---

Você pode utilizar os casts para consultar um boleto bancário e transformar a resposta:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Core\Enums\Cast; // 👈

$billet = PagHiper::billet(Cast::Collection) // 👈
    ->status(transaction: 'HF97T5SH2ZQNLF6Z');
```

<a name="cancelling-billet"></a>
### Cancelando Boleto Bancário

Para consultar o status de um Boleto Bancário utilize o método `cancel`:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet(Cast::Collection) // 👈
    ->cancel(transaction: 'HF97T5SH2ZQNLF6Z');
```

---

Você pode utilizar os casts para cancelar um boleto bancário e transformar a resposta:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Core\Enums\Cast; // 👈

$billet = PagHiper::billet(Cast::Collection) // 👈
    ->cancel(transaction: 'HF97T5SH2ZQNLF6Z');
```

<a name="billet-notification"></a>
### Retorno Automático de Boleto Bancário

O pacote oferece uma forma fácil de lidar com o retorno automático de boletos bancários. **O retorno automático do PagHiper ocorrerá para a URL que você configurou no objeto `Basic`, no parâmetro `$notificationUrl`.** Essa URL deve ser uma URL pública em sua aplicação, e de preferência que não receba nenhum tratamento especial (middlewares, por exemplo):

Supondo que você possui uma URL nomeada como `paghiper.notification`, e que essa foi a URL enviada como `$notificationUrl` na classe de objeto `Basic` no momento da criação do boleto bancário:

```php
// routes/web.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Facades\PagHiper;

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id'); // 👈 enviado pelo PagHiper
    $transaction  = $request->input('transaction_id');  // 👈 enviado pelo PagHiper

    $status = PagHiper::notification(notification: $notification, transaction: $transaction)->consult();
    
    // $status será um array da resposta...
})->name('payment.notification');
```

---

Você pode utilizar os casts para lidar com a resposta da consulta:

```php
// routes/web.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Core\Enums\Cast; // 👈

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id'); // 👈 enviado pelo PagHiper
    $transaction  = $request->input('transaction_id');  // 👈 enviado pelo PagHiper

    $status = PagHiper::cast(Cast::Collection)
        ->notification(notification: $notification, transaction: $transaction)
        ->consult();
    
    // $status será uma instância de \Illuminate\Support\Collection...
})->name('payment.notification');
```

---

**De forma especial para o retorno automático, o pacote oferece um cast diferente: `Dto`:**

```php
// routes/web.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Core\Enums\Cast; // 👈

Route::get('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id'); // 👈 enviado pelo PagHiper
    $transaction  = $request->input('transaction_id');  // 👈 enviado pelo PagHiper

    $status = PagHiper::cast(Cast::Dto)
        ->notification(notification: $notification, transaction: $transaction)
        ->consult();
})->name('payment.notification');
```

O cast `Dto` irá interceptar a resposta, transformar em array e em seguida instanciar a classe `DevAjMeireles\PagHiper\Core\DTO\PagHiperNotification`, que possui diversos métodos úteis como atalhos para lidar com a consulta da notificação:

- `transaction()`: retorna o ID da transação
- `order()`: retorna o ID do pedido
- `createdAt()`: retorna a data de criação do boleto como instância de `Illuminate\Support\Carbon`
- `pending()`: retorna `true` se o status do boleto for `pending`
- `reserved()`: retorna `true` se o status do boleto for `reserved`
- `canceled()`: retorna `true` se o status do boleto for `canceled`
- `completed()`: retorna `true` se o status do boleto for `completed`
- `paid()`: retorna `true` se o status do boleto for `paid`
- `processing()`: retorna `true` se o status do boleto for `processing`
- `refunded()`: retorna `true` se o status do boleto for `refunded`
- `paidAt()`: retorna a data de pagamento do boleto como instância de `Illuminate\Support\Carbon`
- `payer(bool $toCollection = false)`: retorna um array com os dados do pagador 
  - defina o parâmetro como `true` para transformar o array para uma instância de `Illuminate\Support\Collection`
- `address(bool $toCollection = false)`: retorna um array com os dados do endereço 
  - defina o parâmetro como `true` para transformar o array para uma instância de `Illuminate\Support\Collection`
- `finalPrice()`: retorna o valor final do boleto, `value_cents`
- `discount()`: retorna o valor do desconto do boleto, `discount_cents`
- `bankSlipUrl()`: retorna um array com dados do boleto (URL, linha digitável...)
- `dueDateAt()`: retorna a data de vencimento do boleto como instância de `Illuminate\Support\Carbon`
- `numItems`(): retorna o número de itens do boleto
- `items(bool $toCollection = false)`: retorna um array com os itens do array
  - defina o parâmetro como `true` para transformar o array para uma instância de `Illuminate\Support\Collection`
