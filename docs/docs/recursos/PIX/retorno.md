# Retorno Automático

`Paghiper for Laravel` oferece uma forma fácil de lidar com o retorno automático. O retorno automático da PagHiper 
ocorrerá para a URL que você configurou no objeto `Basic`, no parâmetro `$notification_url` na criação do PIX, 
ou para a URL definida via [resolvedor](../../iniciando/detalhes-tecnicos.md). Essa URL deve ser uma URL pública em sua 
aplicação, e de preferência que não receba nenhum tratamento especial, por exemplo: middlewares, autenticação, etc.

Supondo que você possui uma URL nomeada como `paghiper.notification` que aceita requisições `POST`, e que essa foi 
a URL utilizada, então isso será suficiente:

```php
// arquivo: routes/web.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Facades\PagHiper;

Route::post('/payment/notification', function (Request $request) {
    $notification = $request->input('notification_id'); // 👈 enviado pelo PagHiper
    $transaction  = $request->input('transaction_id');  // 👈 enviado pelo PagHiper

    $notification = PagHiper::pix()->notification($notification, $transaction);
})->name('paghiper.notification');
```

No exemplo acima, `$notification` será um array com os dados da notificação.

## Injetando o `\Illuminate\Http\Request`

De forma auxiliar, você pode injetar uma instância de `\Illuminate\Http\Request` ao invés de ter que definir 
manualmente os parâmetros da requisição:

```php
// arquivo: routes/web.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Facades\PagHiper;

Route::post('/payment/notification', function (Request $request) {
    $notification = PagHiper::pix()->notification($request);
})->name('paghiper.notification');
```

`PagHiper for Laravel` irá buscar os parâmetros necessários para a notificação por si próprio.

### Cast Especial: `BilletNotification`

De forma especial para o retorno automático, `Paghiper for Laravel` oferece o cast `BilletNotification`, que quando
utilizado irá mapear a resposta da PagHiper para uma classe de objeto contendo muitos métodos úteis:

```php
// routes/web.php

use Illuminate\Http\Request;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use Illuminate\Support\Facades\Route;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈

Route::post('/payment/notification', function (Request $request) {
    $notification = PagHiper::billet(Cast::BilletNotification) // 👈
        ->notification($request);
})->name('paghiper.notification');
```

### Métodos Disponíveis:

```php
public function original(): Response
```

resposta original, instância de `\Illuminate\Http\Client\Response`

```php
public function type(): string
```

tipo da notificação, pode ser `billet` ou `pix`

```php
public function transactionId(): string
```

id da transação

```php
public function orderId(): string
```

`$order_id` da transação

```php
public function createDate(): Carbon
```

data de criação do pix como instância de `\Illuminate\Support\Carbon`

```php
public function status(): string
```

status da transação como string

```php
public function pending(): bool
public function reserved(): bool
public function canceled(): bool
public function completed(): bool
public function paid(): bool
public function processing(): bool
public function refunded(): bool
```

booleano para o status do pix

---

Os demais métodos seguem a <a href="https://dev.paghiper.com/reference/notificao-automatica" target="_blank">convenção de nomes da PagHiper</a>:

```php
public function dueDateTime(): \Illuminate\Support\Carbon
public function paidDate(): \Illuminate\Support\Carbon
public function valueCents(): int
public function valueFeeCents(): int
public function valueCentsPaid(): int
public function shippingPriceCents(): int
public function discountCents(): int
public function numCartItems(): int
public function dueDate(): \Illuminate\Support\Carbon
public function pixCode(): array
public function items(): array|\DevAjMeireles\PagHiper\DTO\Objects\Item
public function payer(): \DevAjMeireles\PagHiper\DTO\Objects\Payer
```

## Método Especial: `modelable`

De forma estratégica, ao passar uma instância de um modelador do Laravel como `Payer` do PIX, o `order_id` na PagHiper receberá uma referência da classe e ID do modelador para que posteriormente no retorno automático você possa utilizar o método `modelable` para obter o modelador facilmente.

Essa abordagem fará com que o `order_id` PIX fique, por exemplo, da seguinte maneira na PagHiper: `11|App\Model\User:1`, onde `11` é o número do `$order_id` que você especificou na criação da classe `Basic`. Não há preocupação enquanto a este formato, uma vez que o `order_id` do PIX é para uso interno, e não é exibido ao cliente.

Dessa forma você então poderá utilizar o método `modelable`:

```php
use App\Models\User; // 👈
use DevAjMeireles\PagHiper\DTO\Objects\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\Enums\Cast; // 👈
use DevAjMeireles\PagHiper\Facades\PagHiper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// criando o boleto para o modelador User:1 👇

$billet = PagHiper::pix()
    ->create(
        Basic::make()
            ->set('order_id', 1433)  
            ->set('days_due_date', 2)  
            ->set('discount_cents', 0),
        User::find(1), // 👈
        Item::make()
            ->set('item_id', 12) 
            ->set('description', 'Kit de Malas de Viagem') 
            ->set('quantity', 1) 
            ->set('price_cents', 25000));

// retorno automático 👇

Route::post('/payment/notification', function (Request $request) {
    $notification = PagHiper::billet(Cast::PixNotification) // 👈
        ->notification($request);
})->name('paghiper.notification');
```

No exemplo acima, `$notification` será uma instância da classe `PagHiperBilletNotification` contendo o método `modelable()`.
Ao utilizar o método `$notification->modelable()` `PagHiper for Laravel` irá recuperar o usuário automaticamente:

```php
$user = $notification->modelable(); // 👈
```

No exemplo acima, `$user` será uma instância de `\App\Models\User`.

## Tratamento de Excessão

Como é de se esperar, caso haja algum erro na tentativa de capturar o modelador, uma excessão do tipo 
`NotificationModelNotFoundException` ou `ModelNotFoundException` será lançada. Para evitar esse comportamento
você pode utilizar o método `modelable` com o parâmetro `false`:

```php
$user = $notification->modelable(false); // 👈
```

Dessa forma, se houver algum erro ou o modelador não for encontrado, o retorno será `null`.

## Casts

Você também pode usar os outros [casts](../Utilidades/casts.md) disponíveis para transformar a resposta.
