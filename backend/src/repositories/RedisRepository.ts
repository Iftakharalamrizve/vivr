import { IWrite,IRead } from "./interfaces/RedisCacheInterface";

export abstract class RedisRepository<T> implements IWrite<T>,IRead<T>{

    create(item: T): Promise<boolean> {
        
    }
    update(id: string, item: T): Promise<boolean> {
        throw new Error("Method not implemented.");
    }
    delete(id: string): Promise<boolean> {
        throw new Error("Method not implemented.");
    }
    find(item: T): Promise<T[]> {
        throw new Error("Method not implemented.");
    }
    findOne(id: string): Promise<T> {
        throw new Error("Method not implemented.");
    }
}