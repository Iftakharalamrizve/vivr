
import { VIVR_MODULE_TYPE, VIVR_ONLY_MODULE_SUBTYPE } from '@/config/constant';
import date from 'date-and-time';
import DataRequestService from './DataRequestService';

class DataLoggerService extends DataRequestService {
    
    public createCustomerLogData(userData:object,ip:string,source:string,isRegistered:boolean):boolean
    {
        const now:Date = new Date();
        let logTime = date.format(now, 'Y-m-d H:i:s');
        let cli:string = userData.cli;
        let customerJourneyData = [cli,VIVR_MODULE_TYPE,VIVR_ONLY_MODULE_SUBTYPE,logTime,userData.session_id];
        let customerLogData = [logTime,logTime,cli,userData.did, userData.ivr_id,0,userData.session_id,userData.language,'','',source,ip,isRegistered];
        // const $customerJourneyResponse
        return false;
    }

    

    
}