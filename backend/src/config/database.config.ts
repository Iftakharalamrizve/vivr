import { Sequelize } from 'sequelize';
import { createClient} from "redis";

//sqlite db connection 
const db = new Sequelize('app', '', '', {
	storage: './database.sqlite',
	dialect: 'sqlite',
	logging: false,
});


//redis db connection 
const client = createClient({socket:{
	host:process.env.REDIS_HOST,
	port:6379,
	tls:true
}});

// client.on('error', (err) => console.log('Redis Client Error', err));
// client.connect();

export { db, client };
