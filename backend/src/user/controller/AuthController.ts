import { Request, Response } from "express";
import jwt from "jsonwebtoken";
import { AuthGenerateRequestType } from "@/types";
import DataProviderController from "../../vivr/controller/DataProviderController";

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

  async loginWithAuthCode(req: Request, res: Response) {}

  async generateAuthLink(req: Request<AuthGenerateRequestType>, res: Response) {
    let [cli, channel] = [req.body.cli, req.body.channel];
    let data = DataProviderController.getUserAuthLinkByCli([cli,channel,"EN","AE"]);
    if (data) {
      //success code generator response generate
      
    }
    // failed link generate response generate
  }
}

export default new AuthController();
