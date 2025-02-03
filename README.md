# Projeto de Gestão de Ordens de Viagem

Este projeto é uma aplicação completa para a gestão de ordens de viagem, composta por um backend em Laravel e um frontend em Vue.js, ambos rodando em containers Docker.

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

### 1️⃣ Configurar variáveis de ambiente

Crie o arquivo `.env` na pasta `backend/` com base no `.env.example`:
```sh
cp pedido-viagem/backend/.env.example pedido-viagem/backend/.env
```
Atualize os valores conforme necessário, especialmente a configuração do banco de dados:
```env
DB_CONNECTION=mysql
DB_HOST=travel_bd
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```
Caso esteja utilizando o banco de logs, configure também:
```env
DB_LOGS_CONNECTION=mysql
DB_LOGS_HOST=travel_logs_bd
DB_LOGS_PORT=3306
DB_LOGS_DATABASE=logs_activity
DB_LOGS_USERNAME=sail
DB_LOGS_PASSWORD=password
```

### 2️⃣ Subir os containers Docker

Na raiz do projeto, execute:
```sh
docker-compose up -d --build
```
Isso iniciará os serviços do frontend, backend, banco de dados e fila de processamento.

### 3️⃣ Instalar dependências do Laravel

Acesse o container do backend e instale as dependências:
```sh
docker exec -it travel_laravel bash
composer install
```

### 4️⃣ Rodar as migrações do banco de dados

Ainda dentro do container do backend:
```sh
php artisan migrate --seed
```

### 5️⃣ Gerar chave da aplicação Laravel
```sh
php artisan key:generate
```

### 6️⃣ Executar os testes do backend
```sh
php artisan test
```

### 7️⃣ Iniciar o processamento de filas
Se estiver utilizando filas no Laravel, inicie o worker:
```sh
docker exec -it travel_queue bash
php artisan queue:work --daemon
```

### 8️⃣ Acessar a aplicação

Após subir os containers, acesse:
- **Frontend**: [http://localhost:8080](http://localhost:8080)
- **Backend (API)**: [http://localhost:8000](http://localhost:8000)
- **Banco de Dados (MySQL)**: `localhost:3311`
- **Banco de Logs**: `localhost:3310`

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

O projeto utiliza o [Scribe](https://scribe.knapsackpro.com/) para gerar a documentação da API. Para gerar a documentação, execute:
```sh
docker exec -it travel_laravel php artisan scribe:generate
```
Acesse a documentação gerada em:
[http://localhost:8000/docs](http://localhost:8000/docs)

---

Qualquer dúvida, entre em contato!

