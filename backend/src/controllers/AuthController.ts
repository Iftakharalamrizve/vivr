import { Request, response, Response } from "express";
import date from "date-and-time";
import {
  AuthGenerateRequestType,
  LoginRequestType,
  LoginResponseType,
  ResponseParam
} from "@/types";
import DataProviderService from "../services/DataProviderService";
import DataLoggerService from "../services/DataLoggerService";
import JwtLibraryService from "../services/JwtLibraryService";
import  { responseSuccess, responseNotFound, error } from "../utils/responseApi";
import { Controller } from "./Controller";

import {
  GET_LOGIN_GENERATE_CODE,
  GET_USER_FROM_AUTH_CODE_FUNCTION,
  IVR_SOURCE,
} from "../config/constant";

class AuthController extends Controller {

  private token: any;
  private cacheKey: any;
  
  
  
  /**
   * Returns the user loged in information .
   *
   * @remarks
   * This method get {@link LoginRequestType} data and concat cli with 0 for 11 digit number
   * Recive Generated Link information from server.
   * Then generate JWT token for user authentication .
   *
   * @param req  - {Request} data
   * @param rest - {Response}
   * @returns {ResponseParam} type data 
   *
   * @beta
   */

  async loginWithAuthCode(req: Request<LoginRequestType>, res: Response): Promise<void> {
    let [authCode,cli] = [req.body.authCode,req.body.cli];
    cli = '0'+cli;
    let data = await DataProviderService.getDataProviderInformation([authCode,cli],GET_USER_FROM_AUTH_CODE_FUNCTION);
    if (data) {
      await this.generateTokenData(req, data[0],IVR_SOURCE);
      responseNotFound(res,{
        message: "Unauthorized. User Not Found.",
        statusCode: 401,
      })
    } else {
      responseNotFound(res,{
        message: "Unauthorized. User Not Found.",
        statusCode: 401,
      })
    }
  }
 

  async generateTokenData(request: Request<LoginRequestType>,userData: any,source: string) {
    const cli = userData.cli.slice(-10);
    userData.session_id = cli + new Date().getTime();
    userData.session_id = userData.session_id.slice(0,20)
    const isLogged = await DataLoggerService.createCustomerLogData(userData,"127.0.0.1",source,true);
    if (isLogged) {
      this.token = await JwtLibraryService.createJsonWebToken(userData);
      this.cacheKey = Math.random() * (999999 - 100000) + 100000;
      if (this.token) {
        this.setInitialCacheData(userData);
      }
    }
  }


  setInitialCacheData(userData: LoginResponseType) {
    const key = this.cacheKey;
    //Redis work here 
  }

  async generateAuthLink(req: Request<AuthGenerateRequestType>, res: Response) {
    let [cli, channel] = [req.body.cli, req.body.channel];
    let data = await DataProviderService.getDataProviderInformation([cli, channel, "EN", "AE"],GET_LOGIN_GENERATE_CODE);
    if (data) {
      responseSuccess(res, {
        message: "Auth Link Generated",
        statusCode: 200,
        data
      })
    } else {
      responseNotFound(res,{
        message: "Auth Link Not Generated",
        statusCode: 401,
      })
    }
  }

}

export default  new AuthController();
