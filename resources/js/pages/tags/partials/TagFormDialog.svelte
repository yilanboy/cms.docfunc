<script lang="ts">
  import { useForm } from "@inertiajs/svelte";
  import TagController from "@/actions/App/Http/Controllers/TagController";

  interface Props {
    dialogWrapperId: string;
    dialogId: string;
    tag: {
      id: number;
      name: string;
    } | null;
  }

  interface TailwindDialogElement extends HTMLDialogElement {}

  let { dialogWrapperId, dialogId, tag }: Props = $props();

  // Computed property to determine if we're creating or editing
  const isEditing = $derived(tag !== null);
  const dialogTitle = $derived(
    isEditing ? "Update Tag Name" : "Create New Tag",
  );
  const submitButtonText = $derived(isEditing ? "Update" : "Create");

  const form = useForm({
    name: "",
  });

  function submit(event: SubmitEvent) {
    event.preventDefault();

    const dialog = document.getElementById(
      dialogWrapperId,
    ) as TailwindDialogElement;

    if (isEditing && tag) {
      if (tag.name !== $form.name) {
        $form.submit(TagController.update(tag.id), {
          onSuccess: () => {
            dialog.open = false;
          },
        });
      } else {
        dialog.open = false;
      }
    } else {
      $form.submit(TagController.store(), {
        onSuccess: () => {
          dialog.open = false;
        },
      });
    }
  }

  $effect(() => {
    $form.name = tag ? tag.name : "";
  });
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
        <form onsubmit={submit}>
          <div class="sm:flex sm:items-start">
            <div class="w-full text-center sm:text-left">
              <label
                for="new-tag-name"
                class="text-base font-semibold text-gray-900"
              >
                {dialogTitle}
              </label>
              <div class="mt-2">
                <input
                  id="new-tag-name"
                  type="text"
                  name="new-tag-name"
                  placeholder="Type a new tag name"
                  bind:value={$form.name}
                  class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 sm:text-sm/6"
                />
              </div>

              {#if $form.errors.name}
                <div class="mt-2 text-red-500">{$form.errors.name}</div>
              {/if}
            </div>
          </div>
          <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button
              type="submit"
              class="inline-flex w-full cursor-pointer justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 sm:ml-3 sm:w-auto"
            >
              {submitButtonText}
            </button>
            <button
              type="button"
              command="close"
              commandfor={dialogId}
              class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto"
            >
              Cancel
            </button>
          </div>
        </form>
      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
