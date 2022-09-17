import express from 'express';
import AuthValidator from '../validators/authValidator';
import Middleware from '../middleware';

import AuthController from '../controllers/AuthController';

const router = express.Router();
router.get('/test',function(req,res){
    res.status(200).json("test Bangladesh");

})
router.post(
    '/login/auth',
    AuthValidator.validateLoginRequest(),
    Middleware.handleValidationError,
    AuthController.loginWithAuthCode
);
router.post(
    '/generate-auth-link',
    AuthValidator.validateChannelRequest(),
    Middleware.handleValidationError,
    AuthController.generateAuthLink
);



export default router;