import { IWrite,IRead } from "./interfaces/RedisCacheInterface";
import { createClient} from "redis";
import { client } from "@/config/database.config";

export abstract class RedisRepository<T> implements IWrite<T>,IRead<T>{
    
    public readonly _collection: Collection;
    
    constructor(){
        
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