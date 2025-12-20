<script lang="ts">
  import type { Snippet } from "svelte";
  import Toasts from "@/components/Toasts.svelte";

  interface Props {
    children: Snippet;
  }

  let { children }: Props = $props();
</script>

<div class="font-source-sans-3 flex min-h-screen w-full flex-col bg-zinc-50">
  {@render children?.()}

  <Toasts />
</div>
