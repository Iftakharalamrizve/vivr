#Dockerfile for Node and express project

FROM node:16.16-alpine

ARG NODE_ENV=development

#Create App Directory
RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

#install Dependecies 
COPY ./backend/package*.json ./
COPY ./backend/tsconfig*.json ./

RUN npm install  ts-node nodemon
RUN npm install
RUN npm install typescript
RUN npm update

#Copy app source code 
COPY ./backend/ .

#Exports 
EXPOSE 8081

CMD ["npm","start"]
