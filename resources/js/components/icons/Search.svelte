<script lang="ts">
  interface Props {
    className?: string;
  }

  let { className = "size-6" }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class={className}>
  <g
    stroke="currentColor"
    stroke-linecap="round"
    stroke-linejoin="round"
    stroke-width="2"
  >
    <path d="m21 21l-4.34-4.34" fill="none" />
    <circle cx="11" cy="11" r="8" fill="none" />
  </g>
</svg>
