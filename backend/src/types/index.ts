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

