import express from 'express';
import AuthValidator from '../validators/authValidator';
import Middleware from '../middleware';
import AuthController from '../controllers/AuthController';
const router = express.Router();


router.get('/test',(req:any, res:any)=>{
    res.statusCode = 200;
    res.setHeader('Content-Type', 'text/plain');
    res.end('Hello World');
});


router.post(
    '/login/auth',
    AuthValidator.validateLoginRequest(),
    Middleware.handleValidationError,
    AuthController.loginWithAuthCode.bind(AuthController)
);
router.post(
    '/generate-auth-link',
    AuthValidator.validateChannelRequest(),
    Middleware.handleValidationError,
    AuthController.generateAuthLink
);

export default router;