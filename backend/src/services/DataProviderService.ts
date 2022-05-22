import DataRequestService from "./DataRequestService";

class DataProviderService extends DataRequestService {
    
    getDataProviderInformation<T>(data:T, methodName:string): any {
        this.params = JSON.stringify(data) ;
        this.method = methodName;
        this.getResponse(true);
        console.log(this.responseData);
        if(this.responseData){
            return this.responseData;
        }
        return null;

    }
}

export default new DataProviderService();