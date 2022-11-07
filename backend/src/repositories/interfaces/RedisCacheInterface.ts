export interface IWrite<T> {
    create(key: string, item: T): Promise<boolean>;
    update(key: string, item: T): Promise<boolean>;
    delete(key: string, item: T): Promise<boolean>;
}

export interface IRead<T> {
    find(key: string, item: T): Promise<T[]>;
    findOne(key: string): Promise<T>;
}
