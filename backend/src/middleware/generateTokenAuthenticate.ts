import { Request, Response, NextFunction } from 'express';
import {error} from '../utils/responseApi'
import jwt from "jsonwebtoken";

class generateTokenAuthenticate {
    #uid = '6993a6dc90596c7b1f74fd39fa5615cf'
	async handleValidationError(req: Request, res: Response, next: NextFunction) {
        let token:string|null = null;

        if (req.headers.authorization && req.headers.authorization.startsWith("Bearer")) {
            token = req.headers.authorization.split(" ")[1];
        }

        if (!token) {
            return res.json(error({message: 'Token Not Provided',statusCode: 404}));
        }

        
        try {
            let payload:any;
            payload = jwt.verify(token, process.env.JWT_SECRET as string);
            if(payload && payload.uid == this.#uid){
                return next();
            }

        } catch (e) {
            return res.json(error({message: 'Token Not Provided',statusCode: 404}));
        }
	}

}
export default new generateTokenAuthenticate();