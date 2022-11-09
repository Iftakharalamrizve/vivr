export interface IWrite<T> {
    RdsCreate(key: string, item: T[]): Promise<boolean>;
    RdsCreateOne(key: string, item: T): Promise<boolean>;
    RdsUpdate(key: string, item: T): Promise<boolean>;
    RdsDelete(key: string): Promise<boolean>;
    RdsDeleteOne(key: string, name: T): Promise<boolean>;
}

export interface IRead<T> {
    RdsFind(key: string, item: T): Promise<T[]>;
    RdsFindOne(key: string, name: T): Promise<T>;
}
