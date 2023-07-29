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

Após instalar o pacote execute o comando `paghiper:install` para concluir a instalação do pacote em sua aplicação:

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

Para consultar o status de um Boleto Bancário utilize o seguinte método:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;

$billet = PagHiper::billet()->status(transaction: 'HF97T5SH2ZQNLF6Z');
```

---

Assim como na criação de Boleto Bancário, você também pode utilizar os casts para consultar um Boleto Bancário e transformar a resposta:

```php
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Core\Enums\Cast; // 👈

$billet = PagHiper::billet(Cast::Collection)
    ->status(transaction: 'HF97T5SH2ZQNLF6Z');
```

<a name="cancelling-billet"></a>
### Cancelando Boleto Bancário

<a name="index-routes"></a>
### Index Routes

Sometimes, you may wish to make a given page the "index" of a directory. By placing an `index.blade.php` template within a Folio directory, any requests to the root of that directory will be routed to that page:

```bash
php artisan make:folio index
# pages/index.blade.php → /

php artisan make:folio users/index
# pages/users/index.blade.php → /users
```

<a name="route-parameters"></a>
## Route Parameters

Often, you will need to have segments of the incoming request's URL injected into your page so that you can interact with them. For example, you may need to access the "ID" of the user whose profile is being displayed. To accomplish this, you may encapsulate a segment of the page's filename in square brackets:

```bash
php artisan make:folio "users/[id]"

# pages/users/[id].blade.php → /users/1
```

Captured segments can be accessed as variables within your Blade template:

```html
<div>
    User {{ $id }}
</div>
```

To capture multiple segments, you can prefix the encapsulated segment with three dots `...`:

```bash
php artisan make:folio "users/[...ids]"

# pages/users/[...ids].blade.php → /users/1/2/3
```

When capturing multiple segments, the captured segments will be injected into the page as an array:

```html
<ul>
    @foreach ($ids as $id)
        <li>User {{ $id }}</li>
    @endforeach
</ul>
```

<a name="route-model-binding"></a>
## Route Model Binding

If a wildcard segment of your page template's filename corresponds one of your application's Eloquent models, Folio will automatically take advantage of Laravel's route model binding capabilities and attempt to inject the resolved model instance into your page:

```bash
php artisan make:folio "users/[User]"

# pages/users/[User].blade.php → /users/1
```

Captured models can be accessed as variables within your Blade template. The model's variable name will be converted to "camel case":

```html
<div>
    User {{ $user->id }}
</div>
```

#### Customizing The Key

Sometimes you may wish to resolve bound Eloquent models using a column other than `id`. To do so, you may specify the column in the page's filename. For example, a page with the filename `[Post:slug].blade.php` will attempt to resolve the bound model via the `slug` column instead of the `id` column.

#### Model Location

By default, Folio will search for your model within your application's `app/Models` directory. However, if needed, you may specify the fully-qualified model class name in your template's filename:

```bash
php artisan make:folio "users/[.App.Models.User]"

# pages/users/[.App.Models.User].blade.php → /users/1
```

<a name="soft-deleted-models"></a>
### Soft Deleted Models

By default, models that have been soft deleted are not retrieved when resolving implicit model bindings. However, if you wish, you can instruct Folio to retrieve soft deleted models by invoking the `withTrashed` function within the page's template:

```php
<?php

use function Laravel\Folio\{withTrashed};

withTrashed();

?>

<div>
    User {{ $user->id }}
</div>
```

<a name="middleware"></a>
## Middleware

You can apply middleware to a specific page by invoking the `middleware` function within the page's template:

```php
<?php

use function Laravel\Folio\{middleware};

middleware(['auth']);

?>

<div>
    Dashboard
</div>
```

Or, to assign middleware to a group of pages, you may provide the `middleware` argument when invoking the `Folio::route` method.

To specify which pages the middleware should be applied to, the array of middleware may be keyed using the corresponding URL patterns of the pages they should be applied to. The `*` character may be utilized as a wildcard character:

```php
use Laravel\Folio\Folio;

Folio::route(resource_path('views/pages'), middleware: [
    'chirps/*' => [
        'auth',
        // ...
    ],
]);
```

You may include closures in the array of middleware to define inline, anonymous middleware:

```php
use Closure;
use Illuminate\Http\Request;
use Laravel\Folio\Folio;

Folio::route(resource_path('views/pages'), middleware: [
    'chirps/*' => [
        'auth',

        function (Request $request, Closure $next) {
            // ...

            return $next($request);
        },
    ],
]);
```

<a name="php-blocks"></a>
## PHP Blocks

When using Folio, the `<?php` and `?>` tags are reserved for the Folio page definition functions such as `middleware` and `withTrashed`.

Therefore, if you need to write PHP code that should be executed within your Blade template, you should use the `@php` Blade directive:

```php
@php
    if (! Auth::user()->can('view-posts', $user)) {
        abort(403);
    }

    $posts = $user->posts;
@endphp

@foreach ($posts as $post)
    <div>
        {{ $post->title }}
    </div>
@endforeach
```

## Contributing
<a name="contributing"></a>

Thank you for considering contributing to Folio! You can read the contribution guide [here](.github/CONTRIBUTING.md).

## Code of Conduct
<a name="code-of-conduct"></a>

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities
<a name="security-vulnerabilities"></a>

Please review [our security policy](https://github.com/laravel/folio/security/policy) on how to report security vulnerabilities.

## License
<a name="license"></a>

Laravel Folio is open-sourced software licensed under the [MIT license](LICENSE.md).
