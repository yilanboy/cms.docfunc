<script lang="ts">
  import Check from "@/components/icons/Check.svelte";
  import type { HTMLInputAttributes } from "svelte/elements";

  interface Props extends HTMLInputAttributes {
    label: string;
    id: string;
  }

  let { label, id, ...rest }: Props = $props();
</script>

<div class="flex gap-3">
  <div class="flex h-6 shrink-0 items-center">
    <div class="group grid size-5 grid-cols-1">
      <input
        {id}
        {...rest}
        type="checkbox"
        class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-blue-600 checked:bg-blue-600 indeterminate:border-blue-600 indeterminate:bg-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"
      />

      <Check
        className="col-start-1 row-start-1 justify-self-center self-center pointer-events-none size-4 text-white group-has-disabled:stroke-gray-950/25"
      />
    </div>
  </div>
  <label for={id} class="block text-base text-gray-900">
    {label}
  </label>
</div>
