<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";

  interface Props {
    title: string;
    totalLinks: number;
    totalTags: number;
  }

  let { title, totalLinks, totalTags }: Props = $props();
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <main class="py-10">
    <div
      class="grid w-full grid-cols-1 gap-4 px-4 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8"
    >
      <div
        class="flex flex-col items-center justify-center rounded-xl bg-zinc-100 p-12"
      >
        <div>Links count:</div>
        <div class="text-2xl">{totalLinks}</div>
      </div>
      <div
        class="flex flex-col items-center justify-center rounded-xl bg-zinc-100 p-12"
      >
        <div>Tags count:</div>
        <div class="text-2xl">{totalTags}</div>
      </div>
    </div>
  </main>
</LayoutMain>
