<script lang="ts">
  import type { FullAutoFill } from "svelte/elements";

  interface Props {
    label: string;
    type: string;
    name: string;
    id: string;
    autocomplete?: FullAutoFill | null;
    required?: boolean;
  }

  let {
    label,
    type,
    name,
    id,
    autocomplete = null,
    required = false,
  }: Props = $props();
</script>

<div>
  <label for={id} class="block text-base font-medium text-gray-900">
    {label}
  </label>
  <div class="mt-2">
    <input
      {type}
      {name}
      {id}
      {autocomplete}
      {required}
      class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
    />
  </div>
</div>
