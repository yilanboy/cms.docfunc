<script lang="ts">
  import type { Snippet } from "svelte";
  import MobileSidebar from "@/components/layouts/main/partials/MobileSidebar.svelte";
  import DesktopSidebar from "@/components/layouts/main/partials/DesktopSidebar.svelte";
  import Header from "@/components/layouts/main/partials/Header.svelte";

  let sidebarIsOpen = $state(false);

  interface Props {
    searchIsEnabled?: boolean;
    children: Snippet;
  }

  let { searchIsEnabled = false, children }: Props = $props();
</script>

<div class="font-source-sans-3">
  <MobileSidebar bind:sidebarIsOpen />

  <DesktopSidebar />

  <div class="min-h-screen bg-white lg:pl-72">
    <Header bind:sidebarIsOpen {searchIsEnabled} />

    {@render children?.()}
  </div>
</div>
