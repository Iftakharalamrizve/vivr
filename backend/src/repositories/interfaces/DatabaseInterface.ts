export interface IWrite<T> {
    create(item: T[]): Promise<boolean>;
    createOne(item: T): Promise<boolean>;
    update(item: T): Promise<boolean>;
    delete(): Promise<boolean>;
    deleteOne(name: T): Promise<boolean>;
}

export interface IRead<T> {
    find(item: T[]): Promise<T[]| boolean>;
    findOne(name: T): Promise<T | boolean>;
}
