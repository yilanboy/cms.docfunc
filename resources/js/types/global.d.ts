import type { PublicKeyCredentialCreationOptionsJSON } from "@simplewebauthn/browser";

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

export type ToastsPosition =
    | "top-right"
    | "top-left"
    | "top-center"
    | "bottom-right"
    | "bottom-left"
    | "bottom-center";

export type ToastsType = "success" | "info" | "warning" | "danger" | "default";

export interface messageToastProps {
    id: string;
    type: ToastsType;
    message: string;
    description: string;
    position?: ToastsPosition;
}

export interface htmlToastProps {
    id: string;
    type: ToastsType;
    html: string;
    position?: ToastsPosition;
}

declare module "@inertiajs/core" {
    export interface InertiaConfig {
        sharedPageProps: {
            app: App;
            auth: Auth;
            asset: Asset;
        };
        flashDataType: {
            toast?: messageToastProps | htmlToastProps;
        };
    }
}

declare module "svelte/elements" {
    export interface DOMAttributes {
        "onshow-toast"?: (event: CustomEvent) => void;
        "onset-toasts-layout"?: (event: CustomEvent) => void;
    }
}
