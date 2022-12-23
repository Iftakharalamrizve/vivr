import { Key } from 'react';

export interface iNodeUpdate {
    success: boolean;
    ErrorText?: string;
}

export interface IfosTreeModel {
    _id: string;
    parent_id?: Key;
    label: Key;
    children?: Array<IfosTreeModel>;
    selected_id?: Key;
    handleSelect?: (ret: Key) => void;
    create: (parent_id?:Key) => Promise<iNodeUpdate>;
    save: (data:IfosTreeModel) => Promise<iNodeUpdate>;
    edit:(data:IfosTreeModel) => Promise<iNodeUpdate>;
    update:(data:IfosTreeModel) => Promise<iNodeUpdate>
    onRemove?: (id: Key) => Promise<iNodeUpdate>;
}

  