import { createInertiaApp, type ResolvedComponent } from "@inertiajs/svelte";
import { hydrate, mount } from "svelte";
import "@tailwindplus/elements";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob<ResolvedComponent>(
            "./pages/**/*.svelte",
            { eager: true },
        );

        return pages[`./pages/${name}.svelte`];
    },
    setup({ el, App, props }) {
        if (el instanceof HTMLElement) {
            if (el.dataset.serverRendered === "true") {
                hydrate(App, { target: el, props });
            } else {
                mount(App, { target: el, props });
            }
        }
    },
});
