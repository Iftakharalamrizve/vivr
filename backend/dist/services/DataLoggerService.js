"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const constant_1 = require("../config/constant");
const date_and_time_1 = __importDefault(require("date-and-time"));
const DataRequestService_1 = __importDefault(require("./DataRequestService"));
class DataLoggerService extends DataRequestService_1.default {
    createCustomerLogData(userData, ip, source, isRegistered) {
        const now = new Date();
        let logTime = date_and_time_1.default.format(now, 'Y-m-d H:i:s');
        let cli = userData.cli;
        let customerJourneyData = [cli, constant_1.VIVR_MODULE_TYPE, constant_1.VIVR_ONLY_MODULE_SUBTYPE, logTime, userData.session_id];
        let customerLogData = [logTime, logTime, cli, userData.did, userData.ivr_id, 0, userData.session_id, userData.language, '', '', source, ip, isRegistered];
        const customerJourneyResponse = this.getDataProviderInformation(customerJourneyData, constant_1.STORE_CUSTOMER_JOURNEY_FUNCTION);
        const logFromWebVisitResponse = this.getDataProviderInformation(customerLogData, constant_1.VIVR_LOG_FUNCTION);
        if (customerJourneyResponse && logFromWebVisitResponse) {
            return true;
        }
        return false;
    }
    getDataProviderInformation(data, methodName) {
        this.params = JSON.stringify(data);
        this.method = methodName;
        this.getResponse(true);
        if (this.responseData) {
            return true;
        }
        return false;
    }
}
exports.default = new DataLoggerService();
