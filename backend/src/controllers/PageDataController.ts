import { Request, Response } from "express";
import jwt from "jsonwebtoken";

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

    async generateAuthLink(req:Request, res:Response){
        
    }
}

export default new AuthController();