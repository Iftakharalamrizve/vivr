"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = __importDefault(require("express"));
const validator_1 = __importDefault(require("../validator"));
const middleware_1 = __importDefault(require("../../middleware"));
const controller_1 = __importDefault(require("../controller"));
const router = express_1.default.Router();
router.post('/create', validator_1.default.checkCreateTodo(), middleware_1.default.handleValidationError, controller_1.default.create);
router.get('/read', validator_1.default.checkReadTodo(), middleware_1.default.handleValidationError, controller_1.default.readPagination);
router.get('/read/:id', validator_1.default.checkIdParam(), middleware_1.default.handleValidationError, controller_1.default.readByID);
router.put('/update/:id', validator_1.default.checkIdParam(), middleware_1.default.handleValidationError, controller_1.default.update);
router.delete('/delete/:id', validator_1.default.checkIdParam(), middleware_1.default.handleValidationError, controller_1.default.delete);
exports.default = router;
