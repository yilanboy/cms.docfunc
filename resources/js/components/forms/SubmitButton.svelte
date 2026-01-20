<script lang="ts">
  import type { HTMLButtonAttributes } from "svelte/elements";

  interface Props extends HTMLButtonAttributes {
    label?: string;
    processing?: boolean;
    className?: string;
  }

  let {
    label = "Save",
    processing = false,
    className = "",
    children,
    ...rest
  }: Props = $props();

  const defaultClasses =
    "flex cursor-pointer justify-center items-center rounded-md bg-blue-600 px-3 py-1.5 text-base font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed";
</script>

<button
  type="submit"
  disabled={processing}
  class="{defaultClasses} {className}"
  {...rest}
>
  {#if children}
    {@render children()}
  {:else}
    {processing ? "Processing..." : label}
  {/if}
</button>
