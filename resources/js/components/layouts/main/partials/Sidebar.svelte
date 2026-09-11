<script lang="ts">
    import { inertia, page } from '@inertiajs/svelte';
    import { Home, Link, Tag, FolderTree, Settings, X } from '@lucide/svelte';
    import { fade } from 'svelte/transition';
    import { untrack } from 'svelte';
    import DashboardController from '@/actions/App/Http/Controllers/DashboardController';
    import LinkController from '@/actions/App/Http/Controllers/LinkController';
    import TagController from '@/actions/App/Http/Controllers/TagController';
    import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
    import SettingController from '@/actions/App/Http/Controllers/Blog/SettingController';
    import { isDesktop, sidebar } from '@/shared/sidebar.svelte.js';

    let activePath = $derived(page.url.split('?')[0]);
    let previousDesktop = isDesktop.current;

    // Auto-close sidebar on mobile when route path changes (closed is the mobile default)
    $effect(() => {
        if (activePath) {
            untrack(() => {
                if (!isDesktop.current) {
                    sidebar.isOpen = false;
                }
            });
        }
    });

    // Snap back to the viewport default when the breakpoint is crossed, so a
    // manual open/close on one side of the breakpoint doesn't leak to the other.
    $effect(() => {
        const currentDesktop = isDesktop.current;
        if (currentDesktop !== previousDesktop) {
            previousDesktop = currentDesktop;
            untrack(() => sidebar.reset());
        }
    });
</script>

<!-- Mobile overlay. Keyed on preference rather than isOpen so server-rendered
  HTML (where the viewport is unknown) never contains a visible overlay. -->
{#if sidebar.isOpen === true && !isDesktop.current}
    <!-- svelte-ignore a11y_click_events_have_key_events -->
    <!-- svelte-ignore a11y_no_static_element_interactions -->
    <div
        transition:fade={{ duration: 300 }}
        onclick={() => sidebar.reset()}
        class="fixed inset-0 z-40 bg-black/70"
    ></div>
{/if}

<!-- Always rendered; visibility is a CSS transform so the first paint is decided
  by the `lg:` media query alone — no JavaScript needed, hence no FOUC. The extra
  3rem clears the close button that overhangs the right edge. -->
<aside
    id="sidebar"
    inert={!(sidebar.isOpen ?? isDesktop.current)}
    class={{
        'fixed inset-y-0 top-16 z-40 flex w-72 flex-col transition-transform duration-300': true,
        '-translate-x-[calc(100%+3rem)] lg:translate-x-0':
            sidebar.isOpen === null,
        'translate-x-0': sidebar.isOpen === true,
        '-translate-x-[calc(100%+3rem)]': sidebar.isOpen === false,
    }}
>
    <button
        onclick={() => (sidebar.isOpen = false)}
        class="absolute top-3 -right-12 inline-flex size-10 cursor-pointer items-center justify-center rounded-lg text-zinc-400 transition-colors duration-200 hover:bg-zinc-800/60 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-zinc-500 lg:hidden"
        aria-label="Close sidebar"
    >
        <X class="size-5" />
    </button>

    <div
        class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-zinc-200 bg-white px-6 pb-4"
    >
        <nav class="mt-6 flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <li>
                            <a
                                use:inertia
                                href={DashboardController.index().url}
                                class={{
                                    'bg-zinc-100 text-blue-600':
                                        page.url ===
                                        DashboardController.index().url,
                                    'text-zinc-700 hover:bg-zinc-100 hover:text-blue-600':
                                        page.url !==
                                        DashboardController.index().url,
                                    'flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold': true,
                                }}
                            >
                                <Home class="size-6 shrink-0" />
                                Dashboard
                            </a>
                        </li>

                        <li>
                            <a
                                use:inertia
                                href={LinkController.index().url}
                                class={{
                                    'bg-zinc-100 text-blue-600':
                                        page.url.startsWith(
                                            LinkController.index().url,
                                        ),
                                    'text-zinc-700 hover:bg-zinc-100 hover:text-blue-600':
                                        !page.url.startsWith(
                                            LinkController.index().url,
                                        ),
                                    'flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold': true,
                                }}
                            >
                                <Link class="size-6 shrink-0" />
                                Links
                            </a>
                        </li>

                        <li>
                            <a
                                use:inertia
                                href={TagController.index().url}
                                class={{
                                    'bg-zinc-100 text-blue-600':
                                        page.url.startsWith(
                                            TagController.index().url,
                                        ),
                                    'text-zinc-700 hover:bg-zinc-100 hover:text-blue-600':
                                        !page.url.startsWith(
                                            TagController.index().url,
                                        ),
                                    'flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold': true,
                                }}
                            >
                                <Tag class="size-6 shrink-0" />
                                Tags
                            </a>
                        </li>

                        <li>
                            <a
                                use:inertia
                                href={CategoryController.index().url}
                                class={{
                                    'bg-zinc-100 text-blue-600':
                                        page.url.startsWith(
                                            CategoryController.index().url,
                                        ),
                                    'text-zinc-700 hover:bg-zinc-100 hover:text-blue-600':
                                        !page.url.startsWith(
                                            CategoryController.index().url,
                                        ),
                                    'flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold': true,
                                }}
                            >
                                <FolderTree class="size-6 shrink-0" />
                                Categories
                            </a>
                        </li>

                        <li>
                            <a
                                use:inertia
                                href={SettingController.edit().url}
                                class={{
                                    'bg-zinc-100 text-blue-600':
                                        page.url.startsWith('/blog/settings'),
                                    'text-zinc-700 hover:bg-zinc-100 hover:text-blue-600':
                                        !page.url.startsWith('/blog/settings'),
                                    'flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold': true,
                                }}
                            >
                                <Settings class="size-6 shrink-0" />
                                Blog Settings
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
