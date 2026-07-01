<script lang="ts">
  import type { Snippet } from "svelte";
  import Toasts from "@/components/Toasts.svelte";

  interface Props {
    children: Snippet;
  }

  let { children }: Props = $props();
</script>

<div class="flex min-h-screen w-full flex-col font-source-sans-3">
  {@render children?.()}

  <Toasts />
</div>
