import { LoginRequestType } from "@/types";
import DataRequestService from "./DataRequestService";

class DataProviderService extends DataRequestService {
    async  getDataProviderInformation<T>(data:T, methodName:string):Promise<any>{
        this.params = JSON.stringify(data) ;
        this.method = methodName;
        await this.getResponse(true);
        console.log(this.params,this.method)
        return this.responseData;
    }
}

export default new DataProviderService();