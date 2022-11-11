import { IWrite, IRead } from "./interfaces/DatabaseInterface";
import { createClient } from "redis";
import { client } from "@/config/database.config";
import { REDIS_EXPIRE_TIME } from "@/config/constant";

export class RedisRepository<T> implements IWrite<T>, IRead<T>{

    private _key: string;
    private _db: createClient;

    constructor(collectionName: string) {
        this._key = collectionName.toString();
        this._db = client;
        this.initialCacheManagement();
    }

    async initialCacheManagement() {

        let existStatus: boolean = await client.exists(this._key);
        if (!existStatus) {
            await client.hSet(this._key, 'Session Start', 'Expire Session');
            await client.expire(this._key, REDIS_EXPIRE_TIME);
        }

    }

    async create(items: T[]): Promise<boolean> {
        try {

            items.forEach(item => {
                for (let key in item) {
                    this._db.hSet(this._key, `${key}`, `${item[key]}`)
                }
            })
            return true;
        } catch (error) {
            console.trace(error)
            return false;
        }
    }

    async createOne(item: T): Promise<boolean> {
        throw new Error("Method not supported.");
    }
    update(item: T): Promise<boolean> {
        throw new Error("Method not supported.");
    }

    async delete(): Promise<boolean> {
        try {
            await this._db.del(this._key)
            return true;
        } catch (error) {
            console.trace(error)
            return false;
        }
    }

    async deleteOne(name: T): Promise<boolean> {
        try {
            await this._db.hdel(this._key, `${name}`)
            return true;
        } catch (error) {
            console.trace(error)
            return false;
        }
    }

    async find(items: T[]): Promise<T[] | boolean> {
        try {
            let response = await this._db.hget(this._key, ...items);
            return response;
        } catch (error) {
            return false;
        }
    }

    async findOne(item: T): Promise<T | boolean> {
        try {
            let response = await this._db.hget(this._key, item);
            return response;
        } catch (error) {
            return false;
        }
    }
}