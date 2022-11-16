import axios from 'axios';
import { API_URL } from "../config/constant";
import { requestDataType  } from "@/types";

class DataRequestService {
    protected responseData: any;
    protected params: any;
    protected method: string = "";

    protected async getResponse(isArray: boolean = false):Promise<any> {
        const requestData: requestDataType = {
            method: this.method,
            params: this.params,
        };
        //console.log(requestData)
        await this.dataRequest(requestData);
    }

    async dataRequest(postData: requestDataType): Promise<void> {
        let options = {
            method: "POST",
            url: API_URL,
            headers: {
                "Content-Type": "application/json",
            },
            data: postData,
        };

        try {
            const response = await axios(options);
            this.responseData = response.data;
            //console.log(this.responseData)
        } catch (error) {
            console.log(error.message)
            this.responseData = null ;
        }
    }
}
export default  DataRequestService;