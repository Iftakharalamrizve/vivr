"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.TodoInstance = void 0;
const sequelize_1 = require("sequelize");
const database_config_1 = require("../../config/database.config");
class TodoInstance extends sequelize_1.Model {
}
exports.TodoInstance = TodoInstance;
TodoInstance.init({
    id: {
        type: sequelize_1.DataTypes.UUIDV4,
        primaryKey: true,
        allowNull: false,
    },
    title: {
        type: sequelize_1.DataTypes.STRING,
        allowNull: false,
    },
    completed: {
        type: sequelize_1.DataTypes.BOOLEAN,
        allowNull: false,
        defaultValue: false,
    },
}, {
    sequelize: database_config_1.db,
    tableName: 'todos',
});