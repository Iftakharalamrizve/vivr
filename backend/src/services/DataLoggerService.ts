
import { STORE_CUSTOMER_JOURNEY_FUNCTION, VIVR_LOG_FUNCTION, VIVR_MODULE_TYPE, VIVR_ONLY_MODULE_SUBTYPE } from '../config/constant';
import { LoginResponseType } from '@/types';
import date from 'date-and-time';
import DataRequestService from './DataRequestService';

class DataLoggerService extends DataRequestService {
    
    async createCustomerLogData(userData:LoginResponseType,ip:string,source:string,isRegistered:boolean):Promise<boolean> 
    {
        const now : Date = new Date();
        let logTime = date.format(now, 'YYYY-MM-DD HH:mm:ss');
        let cli:string = userData.cli.slice(1);
        let customerJourneyData = [cli,VIVR_MODULE_TYPE,VIVR_ONLY_MODULE_SUBTYPE,logTime,userData.session_id];
        let customerLogData = [logTime,logTime,cli,userData.did, userData.ivr_id,0,userData.session_id,userData.language,'','','',source,ip,isRegistered];
        const customerJourneyResponse = await this.getDataProviderInformation(customerJourneyData,STORE_CUSTOMER_JOURNEY_FUNCTION);
        const logFromWebVisitResponse = await this.getDataProviderInformation(customerLogData,VIVR_LOG_FUNCTION);
        console.trace([customerJourneyResponse],[logFromWebVisitResponse])
        if(customerJourneyResponse && logFromWebVisitResponse){
            return true ;
        }
        return false;
    }

    async getDataProviderInformation<T>(data:T, methodName:string): Promise<boolean> 
    {
        this.params = JSON.stringify(data) ;
        this.method = methodName;
        await this.getResponse(true);
        if(this.responseData){
            return true;
        }
        return false;

    }

    

    
}

export default new DataLoggerService();