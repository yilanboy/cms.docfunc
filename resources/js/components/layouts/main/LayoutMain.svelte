<script lang="ts">
  import type { Snippet } from "svelte";
  import MobileSidebar from "@/components/layouts/main/partials/MobileSidebar.svelte";
  import DesktopSidebar from "@/components/layouts/main/partials/DesktopSidebar.svelte";
  import Header from "@/components/layouts/main/partials/Header.svelte";

  let sidebarIsOpen = $state(false);

  interface Props {
    children: Snippet;
  }

  let { children }: Props = $props();
</script>

<div
  class="font-source-sans-3 relative flex min-h-screen w-full flex-col bg-zinc-50"
>
  <MobileSidebar bind:sidebarIsOpen />

  <DesktopSidebar />

  <div class="flex min-h-screen flex-col lg:pl-72">
    <Header bind:sidebarIsOpen />

    {@render children?.()}
  </div>
</div>
