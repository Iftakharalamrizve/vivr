import express from 'express';
import AuthValidator from '../validators/authValidator';
import Middleware from '../middleware';

import AuthController from '../controllers/AuthController';

const router = express.Router();
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