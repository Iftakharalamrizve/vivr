
type  RequestParam = {
    message:string,
    statusCode:number,
    data?:object,
    errors?:object|null
}

type ResponseParam = {
    status:string,
    status_code: number,
    message:string,
    data?:object|null,
    error: boolean,
    errors?:object|null
}

/**
 * @desc    Common Response Style for full project
 * @author  Iftakhar Alam Rizve
 *
 */



/**
 * @desc    Send any success response
 *
 * @param   {string} message
 * @param   {object | array} data
 */
const responseSuccess = (param : RequestParam) : ResponseParam => {
    return {
        status:'success',
        status_code: param.statusCode,
        message:param.message,
        data:param.data??null,
        error: false
    };
};



/**
 * @desc    Send any paginator response
 *
 * @param   {string} message
 * @param   {object | array} data
 * @param   {number} statusCode
 */




/**
 * @desc   Response Not Found
 *
 * @param   {string} message
 * @param   {number} statusCode
 */
const responseNotFound = (params:RequestParam) : ResponseParam =>{
    return {
        status:'error',
        status_code: params.statusCode,
        message:params.message,
        error: true
    };
}



/**
 * @desc    Send any error response
 *
 * @param   {RequestParamOne}
 * 
 */
const error = (params:RequestParam) : ResponseParam  => {
    // List of common HTTP request code
    const codes = [200, 201, 400, 401, 404, 403, 422, 500];

    // Get matched code
    const findCode = codes.find((code) => code == params.statusCode);

    if (!findCode) params.statusCode = 500;
    else params.statusCode = findCode;
    return {
        status:'error',
        status_code: params.statusCode,
        message:params.message,
        errors:params.errors,
        error: true
    };
};



/**
 * @desc    Send any validation response
 *
 * @param   {object | array} errors
 */
const validation = (errors:object|null) : ResponseParam => {
    return {
        status:'error',
        message: "Validation errors",
        error: true,
        status_code: 422,
        errors
    };
};



/**
 * @desc    Send any  Token with response
 *
 * @param   {object | array} user
 * @param   {String} token
 * @param   {String} expiration
 * @param   {number} statusCode
 */

const responseWithToken = (token:string,user:object,expiration=process.env.JWT_EXPIRES_IN,statusCode:number) => {
    return {
        access_token:token,
        user,
        token_type:'bearer',
        'expires_in':expiration,
        error: false,
        status_code: statusCode
    };
}

export {responseSuccess,responseNotFound,error,validation,responseWithToken};