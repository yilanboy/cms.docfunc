<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";

  interface Props {
    title: string;
  }

  let { title }: Props = $props();
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <main class="flex grow py-10">
    <div
      class="mx-auto grid max-w-7xl grow grid-cols-3 grid-rows-4 gap-6 px-4 sm:px-6 lg:px-8"
    >
      <div
        class="col-span-3 flex items-center justify-center rounded-lg border-2 border-dashed border-zinc-300 text-6xl text-zinc-300 lg:col-span-1"
      >
        01
      </div>
      <div
        class="col-span-3 flex items-center justify-center rounded-lg border-2 border-dashed border-zinc-300 text-6xl text-zinc-300 lg:col-span-1"
      >
        02
      </div>
      <div
        class="col-span-3 flex items-center justify-center rounded-lg border-2 border-dashed border-zinc-300 text-6xl text-zinc-300 lg:col-span-1"
      >
        03
      </div>
      <div
        class="col-span-3 row-span-1 flex items-center justify-center rounded-lg border-2 border-dashed border-zinc-300 text-6xl text-zinc-300 lg:row-span-3"
      >
        04
      </div>
      <!-- Your content -->
    </div>
  </main>
</LayoutMain>
