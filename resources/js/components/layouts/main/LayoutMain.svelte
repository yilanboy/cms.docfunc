<script lang="ts">
    import type { Snippet } from 'svelte';
    import Sidebar from '@/components/layouts/main/partials/Sidebar.svelte';
    import Header from '@/components/layouts/main/partials/Header.svelte';
    import Toasts from '@/components/Toasts.svelte';
    import { sidebar } from '@/shared/sidebar.svelte.js';

    interface Props {
        children: Snippet;
    }

    let { children }: Props = $props();
</script>

<div class="font-source-sans-3 relative min-h-screen w-full">
    <Header />

    <Sidebar />

    <div
        class={{
            'text-base transition-all duration-300 ease-in-out': true,
            'pl-0 lg:pl-72': sidebar.isOpen === null,
            'pl-0': sidebar.isOpen === false,
            'lg:pl-72': sidebar.isOpen === true,
        }}
    >
        {@render children?.()}
    </div>

    <Toasts />
</div>
