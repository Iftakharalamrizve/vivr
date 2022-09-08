"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const jsonwebtoken_1 = __importDefault(require("jsonwebtoken"));
class AuthController {
    #SecretKey = process.env.JWT_SECRET;
    async createJsonWebToken(id) {
        return jsonwebtoken_1.default.sign({
            id,
        }, this.#SecretKey, {
            expiresIn: process.env.JWT_EXPIRES_IN,
        });
    }
    async loginWithAuthCode(req, res) { }
    async generateAuthLink(req, res) { }
}
exports.default = new AuthController();
