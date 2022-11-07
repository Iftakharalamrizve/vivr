export type  RequestParam = {
   message:string,
   statusCode:number,
   data?:object,
   errors?:object|null
}

export type ResponseParam = {
   status:string,
   status_code: number,
   message:string,
   data?:object|null,
   error: boolean,
   errors?:object|null
}


export type AuthGenerateRequestType = {
    cli : string ;
    channel : string;
 }

 export type LoginRequestType = {
    cli:string,
    authCode:string
 }

 export type requestDataType = {
    method:string,
    params:any
}

 export interface AuthGenerateType{
    cli : string ;
    channel : string;
    vivrType : string;
    lan : string;
 }

 export interface LoginResponseType {
   log_time: string;
   token: string;
   cli: string;
   did?: string | number;
   plan?: string | null;
   ivr_id?: string;
   language?:string;
   exp_time:string;
   status:string;
   short_code:string;
   short_code_exp:string;
   ip?:string;
   req_time?:string;
   short_lnk_status?:string;
   session_id?:string|number;
   // iss?:string;
   // aud?:string;
   // iat?:string|number;
   // nbf?:string|number;
 }

