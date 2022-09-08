#Dockerfile for Node and express project

FROM node:16.16-alpine

ARG NODE_ENV=development

#Create App Directory
RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

#install Dependecies 
COPY package*.json ./
COPY tsconfig*.json ./
COPY . .

RUN npm install  ts-node nodemon
RUN npm install
RUN npm install typescript
RUN npm update

#Exports 
EXPOSE 8081

CMD ["npm","start"]
