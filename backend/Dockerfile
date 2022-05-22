#Dockerfile for Node and express project

FROM node:18.2-alpine

#Create App Directory
RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app 

#install Dependecies 
COPY package*.json ./

RUN npm install --silent 

#Copy app source code 
COPY . .

#Exports 
EXPOSE 5000 

CMD ["npm","start"]
