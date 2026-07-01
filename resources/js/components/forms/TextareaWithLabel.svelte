<script lang="ts">
  import type { HTMLTextareaAttributes } from "svelte/elements";

  interface Props extends HTMLTextareaAttributes {
    label: string;
    id: string;
    value?: string;
  }

  let { label, id, value = $bindable(""), rows = 3, ...rest }: Props = $props();
</script>

<div>
  <label for={id} class="block text-base font-medium text-gray-900">
    {label}
  </label>
  <div class="mt-2">
    <textarea
      {id}
      {rows}
      bind:value
      {...rest}
      class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
    ></textarea>
  </div>
</div>
