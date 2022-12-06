# Teste Prático Back-end PHP - Drugovich(V1)

## Descrição

API RESTful utilizando Laravel e Postgres.

Aplicação apresenta uma organização bem simples, na arquitetura monolítica que inteliga todos os componentes em um único programa dentro de uma única plataforma.
Como também, foi utilizado o conceito do 'Repository Pattern' que permite um encapsulamento da lógica de acesso a dados, impulsionando o uso da injeção de dependencia (DI) e proporcionando uma visão mais orientada a objetos das interações com a DAL. Na minha opinião, esse pattern é bem intessante, pois conseguimos abstrair o acesso direto com banco. Além disso, é um bom Pattern para entender e estudar definições importantes.

## Diagramas

Abaixo, irei apresentar dois diagramas que utilizo para entender, ilustrar e definir pontos essenciais para o desenvolvimento do programa.

### Caso de uso:

![Diagrama de uso](https://github.com/marcosggoncalves/api-drugovich/blob/master/Diagramas/Diagrama%20de%20caso%20de%20uso(UseCase).png?raw=true)
 
Em particularidade, crie os *CASOS* com base nos requisitos. Mas, caso seja levado em consideração as demais funcionalidades, teramos um diagrama mais detalhado e com mais casos.

### Modelagem do Banco(Lógico):

![Diagrama de modelagem](https://github.com/marcosggoncalves/api-drugovich/blob/master/Diagramas/Modelagem%20logica%20do%20banco.png?raw=true)

Em questão da modelagem do banco de dados, foi definido o relacionamento entre as entidades, de acordo com o requisito: "Um cliente deve pertencer a apenas um grupo". Nesse caso, Um cliente pode pertencer a um único grupo, e um grupo pode conter varios clientes. Ou seja, temos um relacionamento *(Clientes) 1 -> (GRUPOS) N*.

## Documentação OpenAPI/Swagger

Nesse projeto, configuerei o swagger para documentar os endpoints. Sem necessidade de um software terceiro para testá los.

Acessar documentação [Swagger](http://localhost:8000/api/documentation)

Página que irá abrir:

![Swagger](https://github.com/marcosggoncalves/api-drugovich/blob/master/Imagens/API%20Documentation.png?raw=true)

## Observação:

Não se esqueça de fazer o login para utilizar os endpoints:

1º Passo(Autenticar com seu usuário e senha):

![Swagger](https://github.com/marcosggoncalves/api-drugovich/blob/master/Imagens/API%20Documentation%20Login.png?raw=true)

Copie o token gerado:

*Exemplo de retorno:*

```bash
{
  "status": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3YxL2dlcmVudGVzL2xvZ2luIiwiaWF0IjoxNjY5NTg4NDQ1LCJleHAiOjE2Njk1OTIwNDUsIm5iZiI6MTY2OTU4ODQ0NSwianRpIjoiTWgzUG5INlpuOTVwZDc2VSIsInN1YiI6IjEiLCJwcnYiOiIxNWJlNDhiNjdjNmE4YmM4ZjI1MjFlYzdlNzQ0MGM2MzliNjhlNjE5In0.BdB28RgwEAllB1NO6xd_s-86x3TAMtOTSd8x5AeBpl0",
  "usuario": {
    "id": 1,
    "nome": "Marcos",
    "email": "marcoslopesg7@gmail.com",
    "nivel": 1,
    "created_at": "2022-11-27T21:12:21.000000Z",
    "updated_at": "2022-11-27T21:12:21.000000Z"
  }
}

```

2º Passo(Cole o token para autorizar acesso):

![Swagger](https://github.com/marcosggoncalves/api-drugovich/blob/master/Imagens/API%20Documentation%20AUTH.png?raw=true)


## Iniciando Aplicação

### Baixar projeto:

GitHub

```bash
$ git clone https://github.com/marcosggoncalves/api-drugovich.git
```

### Instalar dependência:

Dentro do diretório 'Backend', execute o comando:

```bash
$ compose install 
```

### Criando o banco de dados:

 - Configure a conexão com o banco "env". 

Exemplo:

```bash
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=8965
DB_DATABASE=system_clientes
DB_USERNAME=postgres
DB_PASSWORD=root
```

 - Crie um database "system_clientes" no SGBD postgres e execute os seguintes comandos:

```bash

- Comando para criar estrutura(banco):

$  php artisan migrate

- Comando para criar registros primários:

$  php artisan db:seed  

```

Por fim, só subir o serviço da aplicação:

```bash
$  php artisan serve
```

## Testando Aplicação

Nessa aplicação, contém dois tipos de testes(Unitário, Integração).

Comando para executar os testes:

```bash
$  php artisan test
```

Exemplo:

![Swagger](https://github.com/marcosggoncalves/api-drugovich/blob/master/Imagens/API%20Test.png?raw=true)

### Observação: 

Caso você queira criar um novo database para não interferir no banco de produção, fique à vontade.

 - Configure a conexão com o banco ".env.testing". 

Exemplo:

```bash
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=8965
DB_DATABASE=system_clientes_test
DB_USERNAME=postgres
DB_PASSWORD=root
```

Execute os comando:

```bash

- Comando para criar estrutura(banco):

$  php artisan migrate

- Comando para criar registros primários:

$  php artisan db:seed  

```
