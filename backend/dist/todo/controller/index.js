"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const uuid_1 = require("uuid");
const model_1 = require("../model");
class TodoController {
    async create(req, res) {
        const id = (0, uuid_1.v4)();
        try {
            const record = await model_1.TodoInstance.create({ ...req.body, id });
            return res.json({ record, msg: "Successfully create todo" });
        }
        catch (e) {
            return res.json({ msg: "fail to create", status: 500, route: "/create" });
        }
    }
    async readPagination(req, res) {
        try {
            const limit = req.query.limit || 10;
            const offset = req.query.offset;
            const records = await model_1.TodoInstance.findAll({ where: {}, limit, offset });
            return res.json(records);
        }
        catch (e) {
            return res.json({ msg: "fail to read", status: 500, route: "/read" });
        }
    }
    async readByID(req, res) {
        try {
            const { id } = req.params;
            const record = await model_1.TodoInstance.findOne({ where: { id } });
            return res.json(record);
        }
        catch (e) {
            return res.json({ msg: "fail to read", status: 500, route: "/read/:id" });
        }
    }
    async update(req, res) {
        try {
            const { id } = req.params;
            const record = await model_1.TodoInstance.findOne({ where: { id } });
            if (!record) {
                return res.json({ msg: "Can not find existing record" });
            }
            const updatedRecord = await record.update({
                completed: !record.getDataValue("completed"),
            });
            return res.json({ record: updatedRecord });
        }
        catch (e) {
            return res.json({
                msg: "fail to read",
                status: 500,
                route: "/update/:id",
            });
        }
    }
    async delete(req, res) {
        try {
            const { id } = req.params;
            const record = await model_1.TodoInstance.findOne({ where: { id } });
            if (!record) {
                return res.json({ msg: "Can not find existing record" });
            }
            const deletedRecord = await record.destroy();
            return res.json({ record: deletedRecord });
        }
        catch (e) {
            return res.json({
                msg: "fail to read",
                status: 500,
                route: "/delete/:id",
            });
        }
    }
}
exports.default = new TodoController();
