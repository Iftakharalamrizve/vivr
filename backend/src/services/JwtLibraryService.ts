import { LOG_LOCATION, VIVR_TOKEN_ISSUER } from "@/config/constant";
import jwt from "jsonwebtoken";

class JwtLibraryService {
    #SecretKey = process.env.JWT_SECRET as string;

    async createJsonWebToken(tokenData:any) {
        tokenData.iss = VIVR_TOKEN_ISSUER;
        tokenData.aud = VIVR_TOKEN_ISSUER;
        tokenData.iat = 1;
        tokenData.nbf = 1;
        return jwt.sign(
          {...tokenData},
          this.#SecretKey,
          {
            expiresIn: process.env.JWT_EXPIRES_IN,
          }
        );
      }

}