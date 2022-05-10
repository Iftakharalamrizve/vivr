import { Request, Response } from "express";
import jwt from "jsonwebtoken";
import { AuthGenerateRequestType } from "@/types";
import DataProviderController from "../../vivr/controller/DataProviderController";

class AuthController {
    
    #SecretKey = process.env.JWT_SECRET as string;

    async createJsonWebToken(id:number|string){
        return jwt.sign({
            id,
        }, this.#SecretKey, {
            expiresIn: process.env.JWT_EXPIRES_IN,
        },);
    }

    
    async loginWithAuthCode(req:Request, res:Response){}



    async generateAuthLink(req:Request<AuthGenerateRequestType>, res:Response){
        let [cli,channel] = [req.body.cli , req.body.channel]
        DataProviderController.getUserAuthLinkByCli({cli:cli,channel:channel,lan:'EN',vivrType:'AE'})
    }

    customItterabole(){

    }
}

export default new AuthController();