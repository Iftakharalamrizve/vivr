import { Request, response, Response } from "express";
import date from "date-and-time";
import {
  AuthGenerateRequestType,
  LoginRequestType,
  LoginResponseType,
} from "@/types";
import DataProviderService from "../services/DataProviderService";
import DataLoggerService from "../services/DataLoggerService";
import JwtLibraryService from "../services/JwtLibraryService";
import { responseSuccess, responseNotFound, error } from "../utils/responseApi";
import {
  GET_LOGIN_GENERATE_CODE,
  GET_USER_FROM_AUTH_CODE_FUNCTION,
  IVR_SOURCE,
} from "../config/constant";

class AuthController {

  private token: any;
  private cacheKey: any;

  async loginWithAuthCode(req: Request<LoginRequestType>, res: Response) {
    let [authCode,cli] = [req.body.authCode,req.body.cli];
    cli = '0'+cli;
    let data = await DataProviderService.getDataProviderInformation(
        [authCode,cli],
        GET_USER_FROM_AUTH_CODE_FUNCTION
      );
    if (data) {
      let data1 = await this.generateTokenData(req, data[0], IVR_SOURCE, res);
      res.status(401).json(
        responseNotFound({
          message: "Unauthorized. User Not Found.",
          statusCode: res.statusCode,
        })
      );
    } else {
      res.status(401).json(
        responseNotFound({
          message: "Unauthorized. User Not Found.",
          statusCode: res.statusCode,
        })
      );
    }
  }
 

  async generateTokenData(request: Request<LoginRequestType>,userData: any,source: string,response: Response) {
    const cli = userData.cli.slice(-10);
    userData.session_id = cli + new Date().getTime();
    userData.session_id = userData.session_id.slice(0,20)
    const isLogged = await DataLoggerService.createCustomerLogData(
      userData,
      "",
      source,
      true
    );
    console.trace(isLogged)
    if (isLogged) {
      this.token = await JwtLibraryService.createJsonWebToken(userData);
      this.cacheKey = Math.random() * (999999 - 100000) + 100000;
      if (this.token) {
        console.log(this.token)
        this.setInitialCacheData(userData);
      }
    }
  }

  setInitialCacheData(userData: LoginResponseType) {
    const key = this.cacheKey;
  }

  async generateAuthLink(req: Request<AuthGenerateRequestType>, res: Response) {
    let [cli, channel] = [req.body.cli, req.body.channel];
    let data = await DataProviderService.getDataProviderInformation(
      [cli, channel, "EN", "AE"],
      GET_LOGIN_GENERATE_CODE
    );
    if (data) {
      //success code generator response generate
      res.status(200).json(
        responseSuccess({
          message: "Auth Link Generated",
          statusCode: res.statusCode,
          data,
        })
      );
    } else {
      res.status(400).json(
        responseNotFound({
          message: "Auth Link Not Generated",
          statusCode: res.statusCode,
        })
      );
    }
  }
}

export default  new AuthController();
