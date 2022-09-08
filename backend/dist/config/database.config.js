"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.client = exports.db = void 0;
const sequelize_1 = require("sequelize");
const redis_1 = require("redis");
//sqlite db connection 
const db = new sequelize_1.Sequelize('app', '', '', {
    storage: './database.sqlite',
    dialect: 'sqlite',
    logging: false,
});
exports.db = db;
//redis db connection 
const client = (0, redis_1.createClient)({ socket: {
        host: process.env.REDIS_HOST,
        port: 6379,
        tls: true
    } });
exports.client = client;
