<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Link from "@/components/icons/Link.svelte";
  import Tag from "@/components/icons/Tag.svelte";

  interface Props {
    title: string;
    linkCount: number;
    tagCount: number;
  }

  let { title, linkCount, tagCount }: Props = $props();
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <main class="py-10">
    <div
      class="grid w-full grid-cols-1 gap-6 px-4 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8"
    >
      <!-- Links Card -->
      <div
        class="group overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-blue-100 p-6 shadow-sm transition-all duration-300 hover:scale-105 hover:shadow-lg"
      >
        <div class="flex items-center justify-between">
          <div>
            <div class="mb-1 text-sm font-medium text-blue-600">
              Total Links
            </div>
            <div
              class="text-3xl font-bold text-blue-900 transition-colors duration-300"
            >
              {linkCount}
            </div>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-500 text-white shadow-lg transition-transform duration-300 group-hover:scale-110"
          >
            <Link className="size-6" />
          </div>
        </div>
      </div>

      <!-- Tags Card -->
      <div
        class="group overflow-hidden rounded-2xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 shadow-sm transition-all duration-300 hover:scale-105 hover:shadow-lg"
      >
        <div class="flex items-center justify-between">
          <div>
            <div class="mb-1 text-sm font-medium text-emerald-600">
              Total Tags
            </div>
            <div
              class="text-3xl font-bold text-emerald-900 transition-colors duration-300"
            >
              {tagCount}
            </div>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-lg transition-transform duration-300 group-hover:scale-110"
          >
            <Tag className="size-6" />
          </div>
        </div>
      </div>
    </div>
  </main>
</LayoutMain>
