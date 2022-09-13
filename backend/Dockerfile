
#Dockerfile for Node and express project

FROM node:16.16-alpine

ARG NODE_ENV=development

#Create App Directory
RUN apk update && apk add bash
RUN apk --no-cache add --virtual .builds-deps build-base python3
RUN apk --update-cache add sqlite


RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

#install Dependecies 
COPY package*.json ./
COPY tsconfig*.json ./
COPY . .

RUN npm install && npm rebuild bcrypt --build-from-source && npm cache clean --force 

RUN npm install typescript
RUN npm update

#Exports 
EXPOSE 8081

CMD ["npm","start"]
