<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Link from "@/components/icons/Link.svelte";
  import Tag from "@/components/icons/Tag.svelte";
  import { toasts } from "@/shared/toasts.svelte";

  interface Props {
    title: string;
    linkCount: number;
    tagCount: number;
  }

  let { title, linkCount, tagCount }: Props = $props();

  function triggerSuccessToast() {
    toasts.add({
      type: "success",
      message: "Success!",
      description:
        "That's one small step for a man, one giant leap for mankind.",
    });
  }

  function triggerInfoToast() {
    toasts.add({
      type: "info",
      message: "Info!",
      description: "This is some useful information.",
    });
  }

  function triggerWarningToast() {
    toasts.add({
      type: "warning",
      message: "Warning!",
      description: "Better safe than sorry.",
    });
  }

  function triggerDangerToast() {
    toasts.add({
      type: "danger",
      message: "Error!",
      description: "Something went wrong.",
    });
  }
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
        class="group overflow-hidden rounded-2xl border border-blue-100 bg-linear-to-br from-blue-50 to-blue-100 p-6 shadow-sm transition-all duration-300 hover:scale-105 hover:shadow-lg"
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
        class="group overflow-hidden rounded-2xl border border-emerald-100 bg-linear-to-br from-emerald-50 to-emerald-100 p-6 shadow-sm transition-all duration-300 hover:scale-105 hover:shadow-lg"
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

      <!-- Trigger Toast Card -->
      <div
        class="col-span-1 flex w-full flex-col gap-4 rounded-xl border border-zinc-200 p-4 md:col-span-2 lg:col-span-4"
      >
        <span class="font-semibold">Trigger Toast</span>
        <div class="flex gap-4">
          <button
            type="button"
            class="cursor-pointer rounded-lg bg-green-500 px-4 py-2 text-white hover:bg-green-600"
            onclick={triggerSuccessToast}
          >
            Success
          </button>

          <button
            type="button"
            class="cursor-pointer rounded-lg bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
            onclick={triggerInfoToast}
          >
            Info
          </button>

          <button
            type="button"
            class="cursor-pointer rounded-lg bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600"
            onclick={triggerWarningToast}
          >
            Warning
          </button>

          <button
            type="button"
            class="cursor-pointer rounded-lg bg-red-500 px-4 py-2 text-white hover:bg-red-600"
            onclick={triggerDangerToast}
          >
            Danger
          </button>
        </div>
      </div>
    </div>
  </main>
</LayoutMain>
