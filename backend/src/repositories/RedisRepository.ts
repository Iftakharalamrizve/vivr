import { IWrite,IRead } from "./interfaces/RedisCacheInterface";

export abstract class RedisRepository<T> implements IWrite<T>,IRead<T>{
    
}