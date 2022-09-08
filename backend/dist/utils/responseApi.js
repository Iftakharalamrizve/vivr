"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.responseWithToken = exports.validation = exports.error = exports.responseNotFound = exports.responseSuccess = void 0;
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
const responseSuccess = (param) => {
    return {
        status: 'success',
        status_code: param.statusCode,
        message: param.message,
        data: param.data ?? null,
        error: false
    };
};
exports.responseSuccess = responseSuccess;
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
const responseNotFound = (params) => {
    return {
        status: 'error',
        status_code: params.statusCode,
        message: params.message,
        error: true
    };
};
exports.responseNotFound = responseNotFound;
/**
 * @desc    Send any error response
 *
 * @param   {RequestParamOne}
 *
 */
const error = (params) => {
    // List of common HTTP request code
    const codes = [200, 201, 400, 401, 404, 403, 422, 500];
    // Get matched code
    const findCode = codes.find((code) => code == params.statusCode);
    if (!findCode)
        params.statusCode = 500;
    else
        params.statusCode = findCode;
    return {
        status: 'error',
        status_code: params.statusCode,
        message: params.message,
        errors: params.errors,
        error: true
    };
};
exports.error = error;
/**
 * @desc    Send any validation response
 *
 * @param   {object | array} errors
 */
const validation = (errors) => {
    return {
        status: 'error',
        message: "Validation errors",
        error: true,
        status_code: 422,
        errors
    };
};
exports.validation = validation;
/**
 * @desc    Send any  Token with response
 *
 * @param   {object | array} user
 * @param   {String} token
 * @param   {String} expiration
 * @param   {number} statusCode
 */
const responseWithToken = (token, user, expiration = process.env.JWT_EXPIRES_IN, statusCode) => {
    return {
        access_token: token,
        user,
        token_type: 'bearer',
        'expires_in': expiration,
        error: false,
        status_code: statusCode
    };
};
exports.responseWithToken = responseWithToken;
