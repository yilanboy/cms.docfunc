export interface Auth {
    user: User;
}

export interface User {
    id: number;
    name: string;
    email: string;
}

export interface Flash {
    success: string;
    error: string;
}

export interface App {
    name: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    app: App;
    auth: Auth;
    flash: Flash;
};
