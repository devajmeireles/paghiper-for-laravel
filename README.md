<p align="center"><img src="./art/cover.png" alt="PagHiper for Laravel"></p>

- [Introdução](#introduction)
- [Instalação](#installation)
- [Detalhes Técnicos](#technical-details)
- [Boleto Bancário](#billet)
    - [Criando Boleto Bancário](#creating-billet)
    - [Consultando Boleto Bancário](#consulting-billet)
    - [Cancelando Boleto Bancário](#cancelling-billet)
    - [Retorno Automático de Boleto Bancário](#billet-notification)
    - [Tratamento de Erros](#billet-errors)
- [Atualizações](CHANGELOG.md)
- [A Fazeres](#todo)
- [Contribuição](#contributing)
- [Licença de Uso](#license)

<a name="introduction"></a>
# Introdução

`PagHiper for Laravel` é um pacote que adiciona os principais recursos do PagHiper a aplicações Laravel de forma fácil e descomplicada. Com este pacote você poderá integarir com Boletos Bancários e PIX gerados pela PagHiper.

**O pacote foi criado para Laravel 10 e PHP 8.1, no mais alto padrão possível do PHP moderno, com cobertura de testes e fortemente tipado, garantindo estabilidade nas funcionalidades.**

---

O pacote foi criado e é mantido por mim, [AJ Meireles](https://www.linkedin.com/in/devajmeireles/). Sou desenvolvedor de software há 12 anos, dos quais há 9 trabalho exclusivamente com PHP, inclusive como fundador da comunidade [EuSeiPhp](https://www.youtube.com/@euseiphp), um canal para compartilhamento de conteúdos sobre PHP e Laravel.

<a name="installation"></a>
# Instalação

Para instalar o pacote, execute o comando abaixo:

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

<a name="technical-details"></a>
### Detalhes Técnicos

- Versão do Laravel Exigida: **10.x**
- Versão do PHP Exigida: **8.1**

---

#### Facade

O pacote `Paghiper for Laravel` oferece uma [Facade](https://laravel.com/docs/10.x/facades) para interação com a API do PagHiper:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->create(/* ... */)
```

---

#### Cliente HTTP

Por trás dos panos, o pacote utiliza o poder do [cliente de HTTP do Laravel](https://laravel.com/docs/10.x/http-client). Com isso, caso você precise escrever testes automatizados, você deve seguir o esquema de testes do Laravel.

<a name="creating-billet"></a>
### Criando Boleto Bancário

Para uma melhor organização, a forma de interagir com o método `create` é enviar para ele quatro instâncias de classes de objeto que representam os dados do corpo do boleto bancário:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Address; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Basic; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Item; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Payer; // 👈

$billet = (new PagHiper())->billet()
    ->create(
        new Payer(name: 'Foo Bar', email: 'foo.bar@gmail.com', document: '123.456.789-00', phone: '1199999999'),
        new Basic(orderId: 12, notificationUrl: route('paghiper.notification'), daysDueDate: 2, typeBankSlip: 'boletoA4', discountCents: 0),
        new Address(street: 'Foo Street', number: 123, complement: 'Home', district: 'Bar District', city: 'Foo City', state: 'Foo Country', zipCode: '12345-678'),
        new Item(id: 12, description: 'Foo Bar', quantity: 1, price: 1000)
    );
```

**Observe que no exemplo acima todos os parâmetros das classes: `Payer`, `Basic`, `Address` e `Item` foram nomeados apenas para fins de instrução. Você pode optar por utilizar dessa forma ou não.**

### Url Padrão de Retorno Automático

Se a sua aplicação possuir uma URL específica e fixa de retorno automático, você pode definir uma nova chave no arquivo `config/paghiper.php` com essa URL:

```php
// config/paghiper.php

return [
    // ...
    
    'notification_url' => 'https://retorno-automático.com/paghiper/notification',
    
    // ...
];
```

**Caso você não defina esta configuração**, o pacote espera que você informe a URL através do parametro `notificationUrl` da classe `Basic`:

```php
// ...

$billet = (new PagHiper())->billet()
    ->create(
        // ...
        new Basic(orderId: 12, notificationUrl: 'https://retorno-automático.com/paghiper/notification', ...), // 👈
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

$billet = (new PagHiper())->billet()
    ->create(
        User::first(), // 👈
        new Basic(orderId: 12, notificationUrl: route('paghiper.notification'), daysDueDate: 2, typeBankSlip: 'boletoA4', discountCents: 0),
        new Address(street: 'Foo Street', number: 123, complement: 'Home', district: 'Bar District', city: 'Foo City', state: 'Foo Country', zipCode: '12345-678'),
        new Item(id: 12, description: 'Foo Bar', quantity: 1, price: 1000)
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

**Essa abordagem facilita processos de formatações antes de enviar os dados à PagHiper, por exemplo.** Se você tiver mais de um modelador que interaja com o pacote, abstraia os métodos acima para uma trait e aplique-os aos modeladores que implementam a interface.

---

Você também pode enviar um array de itens, para casos quais você crie o boleto bancário para mais de um item:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\DTO\Objects\Payer;

$billet = (new PagHiper())->billet()
    ->create(
        new Payer(name: 'Foo Bar', email: 'foo.bar@gmail.com', document: '123.456.789-00', phone: '1199999999'),
        new Basic(orderId: 12, notificationUrl: route('paghiper.notification'), daysDueDate: 2, typeBankSlip: 'boletoA4', discountCents: 0),
        new Address(street: 'Foo Street', number: 123, complement: 'Home', district: 'Bar District', city: 'Foo City', state: 'Foo Country', zipCode: '12345-678'),
        [
            new Item(id: 12, description: 'Foo Bar 12', quantity: 1, price: 1200),
            new Item(id: 13, description: 'Foo Bar 13', quantity: 1, price: 1300),
            new Item(id: 14, description: 'Foo Bar 14', quantity: 1, price: 1400),
        ]
    );
```

---

Para facilitar sua interação com a Facade, o pacote oferece casts diferentes, sendo eles:

- `Response`: o objeto original da resposta
- `Json` ou `Array`: a resposta convertida para um array
- `Collect` ou `Collection`: a resposta convertida para uma instância de `Illuminate\Support\Collection`

```php
use App\Models\User;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\DTO\Objects\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

$billet = (new PagHiper())->billet(Cast::Collection) // 👈
    ->create(
        User::first(),
        new Basic(orderId: 12, notificationUrl: route('paghiper.notification'), daysDueDate: 2, typeBankSlip: 'boletoA4', discountCents: 0),
        new Address(street: 'Foo Street', number: 123, complement: 'Home', district: 'Bar District', city: 'Foo City', state: 'Foo Country', zipCode: '12345-678'),
        new Item(id: 12, description: 'Foo Bar', quantity: 1, price: 1000)
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

Você também pode utilizar os casts na consulta de um boleto bancário:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

$billet = PagHiper::billet(Cast::Collection) // 👈
    ->status(transaction: 'HF97T5SH2ZQNLF6Z');

// $billet passa a ser uma instância de Illuminate\Support\Collection
```

<a name="cancelling-billet"></a>
### Cancelando Boleto Bancário

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

// $billet passa a ser uma instância de Illuminate\Support\Collection
```

<a name="billet-notification"></a>
### Retorno Automático de Boleto Bancário

O pacote oferece uma forma fácil de lidar com o retorno automático de boletos bancários. **O retorno automático do PagHiper ocorrerá para a URL que você configurou no objeto `Basic`, no parâmetro `$notificationUrl` na criação do boleto bancário.** Essa URL deve ser uma URL pública em sua aplicação, e de preferência que não receba nenhum tratamento especial (middlewares, por exemplo):

Supondo que você possui uma URL nomeada como `paghiper.notification`, e que essa foi a URL enviada como `$notificationUrl` na classe de objeto `Basic` no momento da criação do boleto bancário, então isso será suficiente:

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

    $status = PagHiper::cast(Cast::Collection)
        ->notification(notification: $notification, transaction: $transaction)
        ->consult();
    
// $status passa a ser uma instância de Illuminate\Support\Collection
})->name('paghiper.notification');
```

---

**De forma especial para o retorno automático, o pacote oferece um cast diferente: `Dto`:**

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

O cast `Dto` irá interceptar a resposta, transformar em array e em seguida instanciar a classe `DevAjMeireles\PagHiper\DTO\PagHiperNotification`, que **possui diversos métodos úteis como atalhos para lidar com a consulta da notificação:**

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
- `payer()`: retorna um array com os dados do pagador 
  - defina o parâmetro como `true` para transformar o array para uma instância de `Illuminate\Support\Collection`
- `address()`: retorna um array com os dados do endereço 
  - defina o parâmetro como `true` para transformar o array para uma instância de `Illuminate\Support\Collection`
- `finalPrice()`: retorna o valor final do boleto, `value_cents`
- `discount()`: retorna o valor do desconto do boleto, `discount_cents`
- `bankSlipUrl()`: retorna um array com dados do boleto (URL, linha digitável...)
- `dueDateAt()`: retorna a data de vencimento do boleto como instância de `Illuminate\Support\Carbon`
- `numItems`(): retorna o número de itens do boleto
- `items()`: retorna um array com os itens do array
  - defina o parâmetro como `true` para transformar o array para uma instância de `Illuminate\Support\Collection`

<a name="billet-errors"></a>
## Tratamento de Erros

- `DevAjMeireles\PagHiper\Exceptions\PagHiperException` 
  - erro genérico do PagHiper
- `DevAjMeireles\PagHiper\Exceptions\UnallowedCastType` 
  - tentativa de uso indetivo do cast `DevAjMeireles\PagHiper\Enums\Cast\Dto`
- `DevAjMeireles\PagHiper\Exceptions\UnsupportedCastTypeExcetion` 
  - tentativa de uso de um cast inexistente
- `DevAjMeireles\PagHiper\Exceptions\WrongModelSetUpException` 
  - tentativa de criação de boleto usando um modelador sem que ele tenha sido preparado

<a name="todo"></a>
## A Fazeres

- Integração com [PIX do PagHiper](https://dev.paghiper.com/reference/emissao-de-pix-paghiper)
- Integração com [Contas Bancárias](https://dev.paghiper.com/reference/solicitacao-saque)

<a name="contributing"></a>
## Contribuição

Todo e qualquer PR será bem-vindo em favor de ajustes de bugs, melhorias ou aprimoramentos desde que:
- O PR ser criado de forma explicativa, mencionando inclusive o problema
- O PR ser criado em favor de algo que faça sentido ou relevância
- O código do PR ser escrito em inglês, seguindo a [PSR12](https://www.php-fig.org/psr/psr-12/)
- O código do PR ser formatado usando [Laravel Pint](https://laravel.com/docs/10.x/pint)
- O código do PR ser analisando usando [LaraStan](https://github.com/nunomaduro/larastan)
- O código do PR ser testado usando [PestPHP](https://pestphp.com/), inclusive adições ou modificações

<a name="licensing"></a>
## Licença de Uso

`PagHiper for Laravel` é um projeto open-source sobre a licença [MIT](LICENSE.md).
