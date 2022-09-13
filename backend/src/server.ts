import {db} from "./config/database.config";
import app from "./app";

import dotenv from 'dotenv';
dotenv.config({
    path: './config.env'
});

db.sync().then(() => {
	console.log("connect to the db");
});

console.log("test server");

const port = 8081;

app.listen(port, () => {
	console.log("server is running on port " + port);
});
