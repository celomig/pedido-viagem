# Projeto de Pedidos de Viagem

Este projeto é uma aplicação pedidos de viagem, composta por um backend em Laravel e um frontend em Vue.js, ambos rodando em containers Docker.

## Estrutura do Projeto

A estrutura do repositório é a seguinte:
```
/
├── pedido-viagem/
│   ├── backend/   # API Laravel
│   ├── frontend/  # Aplicação Vue.js
├── docker-compose.yml  # Configuração dos containers Docker
```

## Pré-requisitos

Certifique-se de ter os seguintes requisitos instalados antes de iniciar o projeto:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Como Iniciar o Projeto

### Configurar variáveis de ambiente

Crie o arquivo `.env` na pasta `backend/` com base no `.env.example`:
```sh
cp pedido-viagem/backend/.env.example pedido-viagem/backend/.env
```
Atualize os valores conforme necessário, especialmente a configuração de envio de email:
```env
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=ssl
MAIL_FROM_NAME="${APP_NAME}"
MAIL_FROM_ADDRESS=
```

### Subir os containers Docker

Na raiz do projeto, execute:
```sh
docker-compose up --build
```
Isso iniciará os serviços do frontend, backend, banco de dados e fila de processamento.

### Instalar dependências do Laravel

Acesse o container do backend:
```sh
docker exec -it travel_laravel bash
```

### Rodar seed para o banco e dados (caso ainda não possua dados)

Ainda dentro do container do backend:
```sh
php artisan db:seed
```

### Executar os testes do backend
```sh
php artisan test
```

### Acessar a aplicação

Após subir os containers, acesse:
- **Frontend**: [http://localhost:8080](http://localhost:8080)
- **Backend (API)**: [http://localhost:8000](http://localhost:8000)
- **Banco de Dados (MySQL)**: `localhost:3311`

## Comandos Úteis

### Parar os containers
```sh
docker-compose down
```

### Verificar logs dos containers
```sh
docker-compose logs -f
```

### Executar um comando no backend
```sh
docker exec -it travel_laravel bash
```

## Documentação da API

O projeto utiliza o [Scribe](https://scribe.knapsackpro.com/) para gerar a documentação da API. Caso alatere algo, para gerar a documentação novamente, execute:
```sh
docker exec -it travel_laravel php artisan scribe:generate
```
Acesse a documentação gerada em:
[http://localhost:8000/docs](http://localhost:8000/docs)

---

Qualquer dúvida, entre em contato!

