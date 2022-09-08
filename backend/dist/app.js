"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = __importDefault(require("express"));
const express_rate_limit_1 = __importDefault(require("express-rate-limit"));
const helmet_1 = __importDefault(require("helmet"));
const cors_1 = __importDefault(require("cors"));
// import todoRouter from "./todo/route/todoRouter";
const authRoute_1 = __importDefault(require("./routes/authRoute"));
const app = (0, express_1.default)();
// Allow Cross-Origin requests
app.use((0, cors_1.default)());
// Set security HTTP headers
app.use((0, helmet_1.default)());
// Limit request from the same API 
const limiter = (0, express_rate_limit_1.default)({
    max: 150,
    windowMs: 60 * 60 * 1000,
    message: 'Too Many Request from this IP, please try again in an hour'
});
app.use('/api', limiter);
// Parse the x-www-form-urlencoded request body.
app.use(express_1.default.urlencoded({ extended: true }));
// Parse the application/json request body.
app.use(express_1.default.json({
    limit: '15kb'
}));
// CORS Configuration
const corsOptions = {
    origin: true,
    credentials: true,
};
app.use((0, cors_1.default)(corsOptions));
//set redis server in application  
// app.set('redisClient', client);
// //call redis client 
// client;
// app.use("/api/v1", todoRouter);
app.use("/api/v1", authRoute_1.default);
exports.default = app;
