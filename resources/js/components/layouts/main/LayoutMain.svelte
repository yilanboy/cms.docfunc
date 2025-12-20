<script lang="ts">
  import type { Snippet } from "svelte";
  import Sidebar from "@/components/layouts/main/partials/Sidebar.svelte";
  import Header from "@/components/layouts/main/partials/Header.svelte";
  import Toasts from "@/components/Toasts.svelte";

  interface Props {
    children: Snippet;
  }

  let { children }: Props = $props();

  let sidebarIsOpen = $state(true);
</script>

<div class="font-source-sans-3 relative min-h-screen w-full bg-zinc-50">
  <Header bind:sidebarIsOpen />

  <Sidebar bind:isOpen={sidebarIsOpen} />

  <div
    class={{
      "lg:pl-72": sidebarIsOpen,
      "transition-all duration-300 ease-in-out": true,
    }}
  >
    {@render children?.()}
  </div>

  <Toasts />
</div>
