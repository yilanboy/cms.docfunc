export interface Auth {
    user: User;
}

export interface User {
    id: number;
    name: string;
    email: string;
}

export interface App {
    name: string;
}

export interface Asset {
    url: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    app: App;
    auth: Auth;
    asset: Asset;
};
