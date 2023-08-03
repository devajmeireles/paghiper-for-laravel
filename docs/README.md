<p align="center"><img src="../art/cover.png" alt="PagHiper for Laravel"></p>

# Documentação do Usuário

Estes documentos estão relacionados com a documentação disponibilizada através do link: https://devajmeireles.github.io/paghiper-for-laravel/. **Leia atentamente o guia abaixo para saber como realizar um ajuste na documentação.**

## Detalhes Técnicos

A documentação é feita com base em [MkDocs](https://www.mkdocs.org/), por essa razão você pode executar a documentação localmente para fins de correções ou melhorias.

### Local

1. Clone o repositório
2. Instale o [MkDocs](https://www.mkdocs.org/user-guide/installation/)
3. Faça o build com o seguinte comando:

```bash
mkdocs build --config-file ./docs/mkdocs.yml
```

4. Se preferir, rode o `mkdocs` como `server` para ver as mudanças em tempo real:

```bash
mkdocs serve --config-file ./docs/mkdocs.yml
```

### Contribuindo

1. Estando ciente do procedimento acima, execute o seguinte comando:
    
```bash
mkdocs gh-deploy --config-file ./docs/mkdocs.yml --remote-branch <nome_da_branch>
```

Em seguida crie um PR que aponte a sua branch criada para a branch `docs`.

Em caso de dúvida ou dificuldade, [abra um issue no repositório](https://github.com/devajmeireles/paghiper-for-laravel/issues).
