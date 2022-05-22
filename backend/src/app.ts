import express from "express";
import rateLimit from 'express-rate-limit';
import helmet from 'helmet';
import xss from "xss";
import hpp from "hpp";
import cors from "cors";
// import todoRouter from "./todo/route/todoRouter";
import authRouter from "./routes/authRoute";

const app = express();

// Allow Cross-Origin requests
app.use(cors());

// Set security HTTP headers
app.use(helmet());

// Limit request from the same API 
const limiter = rateLimit({
    max: 150,
    windowMs: 60 * 60 * 1000,
    message: 'Too Many Request from this IP, please try again in an hour'
});
app.use('/api', limiter);

import client from './config/redis.js';
app.set('redisClient', client);
// Parse the x-www-form-urlencoded request body.
app.use(express.urlencoded({ extended: true }));

// Parse the application/json request body.
app.use(express.json({
    limit: '15kb'
}));

// CORS Configuration
const corsOptions = {
    origin: true,
    credentials: true,
};
app.use(cors(corsOptions));


// app.use("/api/v1", todoRouter);
app.use("/api/v1", authRouter);

export default app;
