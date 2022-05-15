import { Request, response, Response } from "express";
import jwt from "jsonwebtoken";
import { AuthGenerateRequestType , LoginRequestType} from "@/types";
import DataProviderService from "@/services/DataProviderService";
import {responseSuccess,responseNotFound,error} from '../utils/responseApi';
import { GET_LOGIN_GENERATE_CODE, GET_USER_FROM_AUTH_CODE_FUNCTION, IVR_SOURCE } from "@/config/constant";

class AuthController {
  #SecretKey = process.env.JWT_SECRET as string;

  async createJsonWebToken(id: number | string) {
    return jwt.sign(
      {
        id,
      },
      this.#SecretKey,
      {
        expiresIn: process.env.JWT_EXPIRES_IN,
      }
    );
  }

  /**
 * @todo Create Token System.
 * @todo Implement this function.
 */

  async loginWithAuthCode(req: Request<LoginRequestType>, res: Response) {
    let [cli, authCode] = [req.body.cli, req.body.authCode];
    let data = DataProviderService.getDataProviderInformation([cli,authCode],GET_USER_FROM_AUTH_CODE_FUNCTION);
    if (data) {
      return this.generateTokenData(req,data,IVR_SOURCE,res);
    }
    res.status(401).json(responseNotFound({message:'Unauthorized. User Not Found.',statusCode:res.statusCode}));
  }

  generateTokenData (request : Request<LoginRequestType> , data:any , source : string , response:Response){

    

  }

  async generateAuthLink(req: Request<AuthGenerateRequestType>, res: Response) {
    let [cli, channel] = [req.body.cli, req.body.channel];
    let data = DataProviderService.getDataProviderInformation([cli,channel,"EN","AE"],GET_LOGIN_GENERATE_CODE);
    if (data) {
      //success code generator response generate
      res.status(200).json(responseSuccess({message:'Auth Link Generated',statusCode:res.statusCode,data}));
    }
    // failed link generate response generate
    res.status(400).json(responseNotFound({message:'Auth Link Not Generated',statusCode:res.statusCode}));
  }
}

export default new AuthController();
