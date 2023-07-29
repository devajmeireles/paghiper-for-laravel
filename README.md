<p align="center"><img src="./art/cover.png" alt="Laravel Folio Package Logo"></p>

- [Introdução](#introduction)
- [Instalação](#installation)
- [Boleto Bancário](#billet)
    - [Criando Boleto Bancário](#creating-billet)
    - [Consultando Boleto Bancário](#consulting-billet)
    - [Cancelando Boleto Bancário](#cancelling-billet)
    - [Retorno Automático](#billet-notification)
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

use DevAjMeireles\PagHiper\Core\Contracts\PagHiperModelAbstraction;

class User extends Model implements PagHiperModelAbstraction
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

Isso facilitará formatações antes de enviar os dados à PagHiper, por exemplo.

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
