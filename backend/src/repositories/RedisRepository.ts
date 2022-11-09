import { IWrite,IRead } from "./interfaces/RedisCacheInterface";
import { createClient} from "redis";
import { client } from "@/config/database.config";
import { DatabaseType, noSqlDatabaseCollectionType } from "@/types";

export abstract class RedisRepository<T> implements IWrite<T>,IRead<T>{
    
    private _key: string;

    constructor(db:DatabaseType,collectionName:string){
        this._key = collectionName;
    }

    RdsCreate(key: string, item: T[]): Promise<boolean> {
        throw new Error("Method not implemented.");
    }

    RdsCreateOne(key: string, item: T): Promise<boolean> {
        throw new Error("Method not implemented.");
    }
    RdsUpdate(key: string, item: T): Promise<boolean> {
        throw new Error("Method not implemented.");
    }
    RdsDelete(key: string): Promise<boolean> {
        throw new Error("Method not implemented.");
    }

    RdsDeleteOne(key: string, name: T): Promise<boolean> {
        throw new Error("Method not implemented.");
    }

    RdsFind(key: string): Promise<T[]> {
        throw new Error("Method not implemented.");
    }

    RdsFindOne(key: string, name: T): Promise<T> {
        throw new Error("Method not implemented.");
    }
}