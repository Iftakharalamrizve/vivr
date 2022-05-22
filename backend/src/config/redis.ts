import redis from 'redis';
import { promisify } from 'util';

// const redis_connect = () => {

const client = redis.createClient();

// client.ahgetall = promisify(client.hgetall).bind(client);
// // client.aset = promisify(client.set).bind(client);
// client.aget = promisify(client.get).bind(client);

client.on('connect', (e) => {
    console.log('redis successfully connected.');
});

client.on('error', (err) => {
    console.log('redis is not connected.');
    console.log(err);
});
// };

// export default redis_connect;
export default client;
