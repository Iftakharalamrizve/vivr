import { Sequelize } from 'sequelize';
import {createClient} from 'redis';

//sqlite db connection 
const db = new Sequelize('app', '', '', {
	storage: './database.sqlite',
	dialect: 'sqlite',
	logging: false,
});

//redis db connection 
const client = createClient();
client.on('error', (err) => console.log('Redis Client Error', err));
client.connect();



export default {db,client};
