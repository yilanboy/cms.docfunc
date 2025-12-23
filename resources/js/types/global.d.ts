import { PageProps as InertiaPageProps } from "@inertiajs/core";
import { PageProps as AppPageProps } from "./";

declare module "@inertiajs/core" {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module "svelte/elements" {
    export interface DOMAttributes {
        "onshow-toast"?: (event: CustomEvent) => void;
        "onset-toasts-layout"?: (event: CustomEvent) => void;
    }
}
