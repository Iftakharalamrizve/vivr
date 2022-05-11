import axios from 'axios';
import { API_URL } from "../../config/constant";
import { requestDataType  } from "@/types";


class DataRequestController {

  protected responseData: any;
  protected params: any;
  protected method: string = "";

  protected getResponse(isArray: boolean = false):void {
    const requestData: requestDataType = {
      method: this.method,
      params: this.params,
    };
    this.dataRequest(requestData);
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
    } catch (error) {
      this.responseData = null ;
    }
  }
}

export default  DataRequestController;