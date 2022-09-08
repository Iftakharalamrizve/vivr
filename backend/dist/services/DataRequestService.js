"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const axios_1 = __importDefault(require("axios"));
const constant_1 = require("../config/constant");
class DataRequestService {
    responseData;
    params;
    method = "";
    getResponse(isArray = false) {
        const requestData = {
            method: this.method,
            params: this.params,
        };
        this.dataRequest(requestData);
    }
    async dataRequest(postData) {
        let options = {
            method: "POST",
            url: constant_1.API_URL,
            headers: {
                "Content-Type": "application/json",
            },
            data: postData,
        };
        try {
            const response = await (0, axios_1.default)(options);
            this.responseData = response.data;
        }
        catch (error) {
            this.responseData = null;
        }
    }
}
exports.default = DataRequestService;
