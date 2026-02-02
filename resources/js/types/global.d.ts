import { type ToastProps } from "@/shared/toasts.svelte";

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

declare module "@inertiajs/core" {
    export interface InertiaConfig {
        sharedPageProps: {
            app: App;
            auth: Auth;
            asset: Asset;
        };
        flashDataType: {
            toast?: Omit<ToastProps, "id">;
        };
    }
}

declare module "svelte/elements" {
    export interface DOMAttributes {
        "onset-toasts-layout"?: (event: CustomEvent) => void;
    }
}
