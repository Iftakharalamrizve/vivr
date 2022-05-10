import express from 'express';
import AuthValidator from '../validator/authValidator';
import Middleware from '../../middleware';

import AuthController from '../controller/AuthController';

const router = express.Router();


router.post('/login/auth', AuthController.loginWithAuthCode);
router.post(
    '/generate-auth-link',
    AuthValidator.validateChannelRequest(),
    Middleware.handleValidationError,
    AuthController.generateAuthLink
);



export default router;