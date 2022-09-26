"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = __importDefault(require("express"));
const authValidator_1 = __importDefault(require("../validators/authValidator"));
const middleware_1 = __importDefault(require("../middleware"));
const AuthController_1 = __importDefault(require("../controllers/AuthController"));
const router = express_1.default.Router();
router.get('/test', function (req, res) {
    res.status(200).json("test Bangladesh");
});
router.post('/login/auth', authValidator_1.default.validateLoginRequest(), middleware_1.default.handleValidationError, AuthController_1.default.loginWithAuthCode);
router.post('/generate-auth-link', authValidator_1.default.validateChannelRequest(), middleware_1.default.handleValidationError, AuthController_1.default.generateAuthLink);
exports.default = router;
