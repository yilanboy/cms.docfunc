<script lang="ts">
  interface Props {
    className?: string;
  }

  let { className = "size-6" }: Props = $props();
</script>

<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class={className}>
  <path
    fill="none"
    stroke="currentColor"
    stroke-linecap="round"
    stroke-linejoin="round"
    stroke-width="2"
    d="M5 12h14m-7-7l7 7l-7 7"
  />
</svg>
