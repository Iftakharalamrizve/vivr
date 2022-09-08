"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const DataRequestService_1 = __importDefault(require("./DataRequestService"));
class DataProviderService extends DataRequestService_1.default {
    getDataProviderInformation(data, methodName) {
        this.params = JSON.stringify(data);
        this.method = methodName;
        this.getResponse(true);
        console.log(this.responseData);
        if (this.responseData) {
            return this.responseData;
        }
        return null;
    }
}
exports.default = new DataProviderService();
