import { LOG_LOCATION, VIVR_TOKEN_ISSUER } from "../config/constant";
import { LoginResponseType } from "@/types";
import bcrypt from 'bcrypt';
import jwt from "jsonwebtoken";

class JwtLibraryService {
    #SecretKey = process.env.JWT_SECRET as string;

    async createJsonWebToken(userData:LoginResponseType) {
         const iss = VIVR_TOKEN_ISSUER;
         const aud = VIVR_TOKEN_ISSUER;
         const iat = new Date().getTime();
         const nbf = new Date().getTime();
         const cli = userData.cli;
         const plan = null;
         const salt = await bcrypt.genSalt(10);
         const uid = await bcrypt.hash(cli + plan + iat, salt);
        return jwt.sign(
          {iss,aud,iat,nbf,cli,plan,uid},
          this.#SecretKey,
          {
            expiresIn: process.env.JWT_EXPIRES_IN,
          }
        );
      }

}

export default new JwtLibraryService() ;