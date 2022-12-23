import type { NextPage } from 'next';

export type NextPageWithLayout<P = {}> = NextPage<P> & {
  authorization?: boolean;
  getLayout?: (page: React.ReactElement) => React.ReactNode;
};

export interface Attachment {
    thumbnail: string;
    original: string;
    id?: string;
}

export interface SettingsOptions {
    siteTitle?: string;
    siteSubtitle?: string;
    signupPoints?: number;
    logo?: Attachment;
}

export interface Settings {
    id: string;
    options: SettingsOptions;
}

export interface GetParams {
    slug: string;
    language: string;
  }

  export enum SortOrder {
    Asc = 'asc',
    Desc = 'desc',
  }

  export interface QueryOptions {
    language: string;
    limit?: number;
    page?: number;
    orderBy?: string;
    sortedBy?: SortOrder;
  }

  export interface PaginatorInfo<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: any[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
  }