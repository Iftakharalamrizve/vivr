import axios from 'axios';
import { API_URL } from "../../config/constant";
import { requestDataType ,AuthGenerateType } from "@/types";


class DataRequestController {
    
    protected responseData : any ;
    protected params : any;
    protected method : string = '';
    
    protected  getResponse(isArray:boolean = false)
    {
        const requestData : requestDataType = {'method':this.method,'params':this.params};
        this.responseData = this.dataRequest(requestData);
    }

    async dataRequest (postData:requestDataType) {
        let options = {
            'method': 'POST',
            'url': API_URL,
            'headers': {
              'Content-Type': 'application/json'
            },
            'data' : postData
        };
        const response =  await axios(options) ; 
        console.log(response);
    }

    
}

export default  DataRequestController;