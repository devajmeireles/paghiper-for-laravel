# Contribuição

Sinta-se a vontade para contribuir com um PR que resolva algum problema ou introduza melhorias. Será muito útil se o seu PR seguir os seguintes padrões:

- O código do PR ser escrito em inglês, seguindo a <a href="https://www.php-fig.org/psr/psr-12/" target="_blank">PSR12</a>
- O código do PR ser formatado usando <a href="https://laravel.com/docs/pint" target="_blank">PestPHP</a>
- O código do PR ser analisando usando <a href="https://phpstan.org/" target="_blank">PhpStan</a>
- O código do PR ser testado usando <a href="https://pestphp.com" target="_blank">PestPHP</a>, inclusive adições ou modificações

Sinta-se à vontade para enviar o seu PR mesmo que ele não atenda as exigências acima. 😉

## Ambiente de Desenvolvimento

1. Crie um fork do repositório
2. Clone o repositório a partir do seu fork:

```bash
git clone <url_do_repositório>
```

3. Instale as dependências:

```bash
cd <pasta> && composer install
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
