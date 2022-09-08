"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const constant_1 = require("../config/constant");
const bcrypt_1 = __importDefault(require("bcrypt"));
const jsonwebtoken_1 = __importDefault(require("jsonwebtoken"));
class JwtLibraryService {
    #SecretKey = process.env.JWT_SECRET;
    async createJsonWebToken(userData) {
        const iss = constant_1.VIVR_TOKEN_ISSUER;
        const aud = constant_1.VIVR_TOKEN_ISSUER;
        const iat = new Date().getTime();
        const nbf = new Date().getTime();
        const cli = userData.cli;
        const plan = null;
        const salt = await bcrypt_1.default.genSalt(10);
        const uid = await bcrypt_1.default.hash(cli + plan + iat, salt);
        return jsonwebtoken_1.default.sign({ iss, aud, iat, nbf, cli, plan, uid }, this.#SecretKey, {
            expiresIn: process.env.JWT_EXPIRES_IN,
        });
    }
}
exports.default = new JwtLibraryService();
