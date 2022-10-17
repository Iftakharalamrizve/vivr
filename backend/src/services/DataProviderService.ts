import { LoginRequestType } from "@/types";
import DataRequestService from "./DataRequestService";

class DataProviderService extends DataRequestService {
    async  getDataProviderInformation<T>(data:T, methodName:string): Promise<LoginRequestType> {
        this.params = JSON.stringify(data) ;
        this.method = methodName;
        await this.getResponse(true);
        if(this.responseData){
            return this.responseData;
        }
        return null;

    }
}

export default new DataProviderService();