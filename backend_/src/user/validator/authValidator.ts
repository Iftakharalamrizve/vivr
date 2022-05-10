import { body} from 'express-validator';

class authValidator{

    validateChannelRequest() {
		return [
			body('cli')
                .isNumeric()
                .withMessage('Cli not a valid number')
                .bail()
                .isLength({ min: 11, max:11 })
                .withMessage('Cli lenght must 11 digit')
                .bail()
                .notEmpty()
				.withMessage('Cli is Required'),
			body('channel')
				.notEmpty()
				.withMessage('The channel value should not be empty')
                .bail()
                .isIn( ['FB', 'Web', 'IVR'])
                .withMessage('Channel Type is not valid')
                .bail()
                .isLength({min:2,max:255})
                .withMessage('Lenght is not correct')
		];
	}

}

export default new authValidator();