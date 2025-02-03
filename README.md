# Projeto de Gestão de Pedidos de Viagem

Este projeto é uma aplicação completa para a gestão de Pedidos de viagem, composta por um backend em Laravel e um frontend em Vue.js, ambos rodando em containers Docker.

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

### 1️ Configurar variáveis de ambiente

Crie o arquivo `.env` na pasta `backend/` com base no `.env.example`:
```sh
cp pedido-viagem/backend/.env.example pedido-viagem/backend/.env
```
Atualize os valores conforme necessário, especialmente a configuração do banco de dados:
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=seu_banco
DB_USERNAME=root
DB_PASSWORD=root
```

### 2 Subir os containers Docker

Na raiz do projeto, execute:
```sh
docker-compose up -d --build
```
Isso iniciará os serviços do frontend, backend e banco de dados.

### 3️ Instalar dependências do Laravel

Acesse o container do backend e instale as dependências:
```sh
docker exec -it backend-app bash
composer install
```

### 4️ Rodar as migrações do banco de dados

Ainda dentro do container do backend:
```sh
php artisan migrate --seed
```

### 5️ Gerar chave da aplicação Laravel
```sh
php artisan key:generate
```

### 6️ Acessar a aplicação

Após subir os containers, acesse:
- **Frontend**: [http://localhost:8080](http://localhost:8080)
- **Backend (API)**: [http://localhost:8000](http://localhost:8000)
- **Banco de Dados (MySQL)**: `localhost:3306`

## 🔄 Comandos Úteis

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
docker exec -it backend-app bash
```

## Documentação da API

O projeto utiliza o [Scribe](https://scribe.knapsackpro.com/) para gerar a documentação da API. Para gerar a documentação, execute:
```sh
docker exec -it backend-app php artisan scribe:generate
```
Acesse a documentação gerada em:
[http://localhost:8000/docs](http://localhost:8000/docs)

---

Qualquer dúvida, entre em contato! 😊

# pedido-viagem
