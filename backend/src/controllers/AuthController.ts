import { Request, response, Response } from "express";
import date from 'date-and-time';
import { AuthGenerateRequestType, LoginRequestType, LoginResponseType } from "@/types";
import DataProviderService from "../services/DataProviderService";
import DataLoggerService from "../services/DataLoggerService";
import JwtLibraryService from '../services/JwtLibraryService'
import { responseSuccess, responseNotFound, error } from '../utils/responseApi';
import { GET_LOGIN_GENERATE_CODE, GET_USER_FROM_AUTH_CODE_FUNCTION, IVR_SOURCE } from "../config/constant";

class AuthController {
  #token : string = '';

  async loginWithAuthCode(req: Request<LoginRequestType>, res: Response) {
    let [cli, authCode] = [req.body.cli, req.body.authCode];
    let data: LoginResponseType = DataProviderService.getDataProviderInformation([cli, authCode], GET_USER_FROM_AUTH_CODE_FUNCTION);
    if (data) {
      return this.generateTokenData(req, data, IVR_SOURCE, res);
    }
    res.status(401).json(responseNotFound({ message: 'Unauthorized. User Not Found.', statusCode: res.statusCode }));
  }

  async generateTokenData(request: Request<LoginRequestType>, userData: LoginResponseType, source: string, response: Response) 
  {
    
    const cli = userData.cli.slice(-10);
    userData.session_id  = cli + new Date().getTime();
    const isLogged = DataLoggerService.createCustomerLogData(userData,'',source,true);
    if(isLogged){
      const token  = await JwtLibraryService.createJsonWebToken(userData);
      const cacheKey  = Math.random() * (999999 - 100000) + 100000;
      if(token){
        this.setInitialCacheData(userData,token);
      }
    }
  }

  setInitialCacheData(userData:LoginResponseType,token:string|number){
    
  }

  async generateAuthLink(req: Request<AuthGenerateRequestType>, res: Response) {
    let [cli, channel] = [req.body.cli, req.body.channel];
    let data = DataProviderService.getDataProviderInformation([cli, channel, "EN", "AE"], GET_LOGIN_GENERATE_CODE);
    if (data) {
      //success code generator response generate
      res.status(200).json(responseSuccess({ message: 'Auth Link Generated', statusCode: res.statusCode, data }));
    }
    // failed link generate response generate
    res.status(400).json(responseNotFound({ message: 'Auth Link Not Generated', statusCode: res.statusCode }));
  }
}

export default new AuthController();
