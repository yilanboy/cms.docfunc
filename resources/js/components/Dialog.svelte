<script lang="ts">
  import type { Snippet } from "svelte";
  import X from "@/components/icons/X.svelte";

  interface Props {
    dialogWrapperId: string;
    dialogId: string;
    children: Snippet;
  }

  let { dialogWrapperId, dialogId, children }: Props = $props();
</script>

<el-dialog id={dialogWrapperId}>
  <dialog
    id={dialogId}
    aria-labelledby="dialog-title"
    class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent"
  >
    <el-dialog-backdrop
      class="fixed inset-0 bg-gray-500/75 transition-opacity data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in"
    ></el-dialog-backdrop>

    <div
      class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none sm:items-center sm:p-0"
    >
      <el-dialog-panel
        class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all data-closed:translate-y-4 data-closed:opacity-0 data-enter:duration-300 data-enter:ease-out data-leave:duration-200 data-leave:ease-in sm:my-8 sm:w-full sm:max-w-lg sm:p-6 data-closed:sm:translate-y-0 data-closed:sm:scale-95"
      >
        <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
          <button
            tabindex="-1"
            type="button"
            command="close"
            commandfor={dialogId}
            class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600"
          >
            <span class="sr-only">Close</span>
            <X />
          </button>
        </div>

        {@render children?.()}
      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
