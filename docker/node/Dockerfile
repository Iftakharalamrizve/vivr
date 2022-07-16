#Dockerfile for Node and express project

FROM node:16.16-alpine

#Create App Directory
RUN mkdir -p /var/www/html
WORKDIR /var/www/html/backend

#install Dependecies 
COPY ./backend/package*.json ./

RUN npm install -g npm@8.14.0
RUN npm update

#Copy app source code 
COPY ./backend/ .

#Exports 
EXPOSE 9000 

CMD ["npm","dev"]
