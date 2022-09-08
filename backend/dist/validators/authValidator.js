"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const express_validator_1 = require("express-validator");
class authValidator {
    validateLoginRequest() {
        return [
            (0, express_validator_1.body)('cli')
                .isNumeric()
                .withMessage('Cli not a valid number')
                .bail()
                .isLength({ min: 10, max: 10 })
                .withMessage('Cli lenght must 10 digit')
                .bail()
                .notEmpty()
                .withMessage('Cli is Required'),
            (0, express_validator_1.body)('authCode')
                .isAlphanumeric()
                .withMessage('Auth Code not a valid number')
                .bail()
                .isLength({ min: 8, max: 12 })
                .withMessage('Auth Code lenght Minimum 8 maximum 12')
                .bail()
                .notEmpty()
                .withMessage('Auth Code is Required'),
        ];
    }
    validateChannelRequest() {
        return [
            (0, express_validator_1.body)('cli')
                .isNumeric()
                .withMessage('Cli not a valid number')
                .bail()
                .isLength({ min: 11, max: 11 })
                .withMessage('Cli lenght must 11 digit')
                .bail()
                .notEmpty()
                .withMessage('Cli is Required'),
            (0, express_validator_1.body)('channel')
                .notEmpty()
                .withMessage('The channel value should not be empty')
                .bail()
                .isIn(['FB', 'Web', 'IVR'])
                .withMessage('Channel Type is not valid')
                .bail()
                .isLength({ min: 2, max: 255 })
                .withMessage('Lenght is not correct')
        ];
    }
}
exports.default = new authValidator();
