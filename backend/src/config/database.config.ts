import { Sequelize } from 'sequelize';
import { createClient} from "redis";

//sqlite db connection 
const db = new Sequelize('app', '', '', {
	storage: './database.sqlite',
	dialect: 'sqlite',
	logging: false,
});

console.log(process.env.JWT_EXPIRES_IN)

//redis db connection 
const client = createClient({socket:{
	host:'cache',
	port:6379,
	password:'password'
}});
client.on('connect', function() { console.log(' Redis is Connected!'); });
client.on('error', (err:any) => console.log('Redis Client Error', err));
client.connect();

export { db, client };
