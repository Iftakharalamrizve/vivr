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
import  { responseSuccess, responseNotFound, error, responseWithToken } from "../utils/responseApi";
import { Controller } from "./Controller";

import {
  DEFAULT_ACTION,
  DEFAULT_PAGE,
  GET_LOGIN_GENERATE_CODE,
  GET_USER_FROM_AUTH_CODE_FUNCTION,
  IVR_SOURCE,
} from "../config/constant";
import { RedisRepository } from "@/repositories/RedisRepository";

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
      let tokenOperationStatus = await this.generateTokenData(req, data[0],IVR_SOURCE);
      if(tokenOperationStatus){
        return responseWithToken(res,this.token,this.cacheKey,201);
      }else{
        responseNotFound(res,{
          message: "Unauthorized. User Not Found.",
          statusCode: 401,
        })
      }
      
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
      this.cacheKey = new Date().getTime() + Math.floor(1000 + Math.random() * 900000) + (999999 - 100000) + 100000 + userData.session_id;
      if (this.token) {
        let initialCacheStatus = await this.setInitialCacheData(userData);
        if(initialCacheStatus){
          return true;
        }else{
          return false;
        }
      }
    }
  }


  async setInitialCacheData(userData: LoginResponseType) {
    const key = this.cacheKey;
    let redisRpositoryInstance =  new RedisRepository(key);
    let initialStoreData = {
      ivrId: userData.ivr_id,
      cli: userData.cli.slice(-10),
      sessionId: userData.session_id,
      sound: 'ON',
      did: userData.did,
      startTime:new Date().getTime(),
      action:DEFAULT_ACTION,
      firstGreeting: true,
      tokenData:this.token,
      lastRequestedPage : DEFAULT_PAGE,
      REQUEST_COUNT: 1,
      requestAmount: 1
    }
    let chaceWriteStatus = await redisRpositoryInstance.create([initialStoreData]);
    if(chaceWriteStatus){
      return true;
    }else{
      return false;
    }

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
