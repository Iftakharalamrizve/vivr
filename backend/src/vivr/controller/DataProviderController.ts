import DataRequestController from "./DataRequestController";

class DataProviderController extends DataRequestController {

    getUserAuthLinkByCli<T>(data:T): any {
        this.params = JSON.stringify(data) ;
        this.method = 'getIVRGeneratedLink';
        this.getResponse(true);
        if(this.responseData){
            return this.responseData;
        }
        return null;

    }
    
}

export default new DataProviderController();