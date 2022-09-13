"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const database_config_1 = require("./config/database.config");
const app_1 = __importDefault(require("./app"));
const dotenv_1 = __importDefault(require("dotenv"));
dotenv_1.default.config({
    path: './config.env'
});
database_config_1.db.sync().then(() => {
    console.log("connect to the db");
});
console.log("test server");
const port = 8081;
app_1.default.listen(port, () => {
    console.log("server is running on port " + port);
});
