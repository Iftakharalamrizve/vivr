"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const DataProviderService_1 = __importDefault(require("../services/DataProviderService"));
const DataLoggerService_1 = __importDefault(require("../services/DataLoggerService"));
const JwtLibraryService_1 = __importDefault(require("../services/JwtLibraryService"));
const responseApi_1 = require("../utils/responseApi");
const constant_1 = require("../config/constant");
class AuthController {
    token;
    cacheKey;
    async loginWithAuthCode(req, res) {
        let [cli, authCode] = [req.body.cli, req.body.authCode];
        let data = DataProviderService_1.default.getDataProviderInformation([cli, authCode], constant_1.GET_USER_FROM_AUTH_CODE_FUNCTION);
        if (data) {
            return this.generateTokenData(req, data, constant_1.IVR_SOURCE, res);
        }
        res
            .status(401)
            .json((0, responseApi_1.responseNotFound)({
            message: "Unauthorized. User Not Found.",
            statusCode: res.statusCode,
        }));
    }
    async generateTokenData(request, userData, source, response) {
        const cli = userData.cli.slice(-10);
        userData.session_id = cli + new Date().getTime();
        const isLogged = DataLoggerService_1.default.createCustomerLogData(userData, "", source, true);
        if (isLogged) {
            this.token = await JwtLibraryService_1.default.createJsonWebToken(userData);
            this.cacheKey = Math.random() * (999999 - 100000) + 100000;
            if (this.token) {
                this.setInitialCacheData(userData);
            }
        }
    }
    setInitialCacheData(userData) {
        const key = this.cacheKey;
    }
    async generateAuthLink(req, res) {
        let [cli, channel] = [req.body.cli, req.body.channel];
        let data = DataProviderService_1.default.getDataProviderInformation([cli, channel, "EN", "AE"], constant_1.GET_LOGIN_GENERATE_CODE);
        if (data) {
            //success code generator response generate
            res
                .status(200)
                .json((0, responseApi_1.responseSuccess)({
                message: "Auth Link Generated",
                statusCode: res.statusCode,
                data,
            }));
        }
        // failed link generate response generate
        res
            .status(400)
            .json((0, responseApi_1.responseNotFound)({
            message: "Auth Link Not Generated",
            statusCode: res.statusCode,
        }));
    }
}
exports.default = new AuthController();
