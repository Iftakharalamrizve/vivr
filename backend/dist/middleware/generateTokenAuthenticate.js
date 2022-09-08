"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const responseApi_1 = require("../utils/responseApi");
const jsonwebtoken_1 = __importDefault(require("jsonwebtoken"));
class generateTokenAuthenticate {
    #uid = '6993a6dc90596c7b1f74fd39fa5615cf';
    async handleValidationError(req, res, next) {
        let token = null;
        if (req.headers.authorization && req.headers.authorization.startsWith("Bearer")) {
            token = req.headers.authorization.split(" ")[1];
        }
        if (!token) {
            return res.json((0, responseApi_1.error)({ message: 'Token Not Provided', statusCode: 404 }));
        }
        try {
            let payload;
            payload = jsonwebtoken_1.default.verify(token, process.env.JWT_SECRET);
            if (payload && payload.uid == this.#uid) {
                return next();
            }
        }
        catch (e) {
            return res.json((0, responseApi_1.error)({ message: 'Token Not Provided', statusCode: 404 }));
        }
    }
}
exports.default = new generateTokenAuthenticate();
