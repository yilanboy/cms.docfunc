<script lang="ts">
  import type { Snippet } from "svelte";
  import Sidebar from "@/components/layouts/main/partials/Sidebar.svelte";
  import Header from "@/components/layouts/main/partials/Header.svelte";
  import Toasts from "@/components/Toasts.svelte";
  import { sidebar } from "@/shared/sidebar.svelte.js";

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
      "lg:pl-72": sidebar.isOpen,
      "text-base transition-all duration-300 ease-in-out": true,
    }}
  >
    {@render children?.()}
  </div>

  <Toasts />
</div>
