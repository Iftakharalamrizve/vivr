import { AuthGenerateType } from "@/types";
import DataRequestController from "./DataRequestController";

class DataProviderController extends DataRequestController {

    getUserAuthLinkByCli(data:AuthGenerateType): void {
        this.params = JSON.stringify(data) ;
        this.method = 'getIVRGeneratedLink';
        this.getResponse(true);
    }
    
}

export default new DataProviderController();