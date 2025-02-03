# Etapa 1: Construção da imagem com Node.js para o frontend
FROM node:16 AS build

# Diretório de trabalho onde o código será copiado dentro do contêiner
WORKDIR /app

# Copia os arquivos de configuração do package.json e package-lock.json para dentro do contêiner
COPY package*.json ./

# Instala as dependências do projeto
RUN npm install

# Copia todo o código do frontend para dentro do contêiner
COPY . /app/

# Etapa 2: Servir a aplicação Vue no modo de desenvolvimento
CMD ["npm", "run", "serve"]  # Rodando Vue no modo de desenvolvimento
