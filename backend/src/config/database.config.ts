import { Sequelize } from 'sequelize';
import { createClient} from "redis";

//sqlite db connection 
const db = new Sequelize('app', '', '', {
	storage: './database.sqlite',
	dialect: 'sqlite',
	logging: false,
});


//redis db connection 
const client: createClient = createClient({socket:{
	host:'cache',
	port:6379
}});
client.on('connect', function() { console.log('Redis is Connected!'); });
client.on('error', (err:any) => console.log('Redis Client Error', err));
client.connect();



export { db, client };
