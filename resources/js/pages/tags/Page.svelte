<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Pagination from "@/components/Pagination.svelte";
  import Dialog from "@/components/Dialog.svelte";
  import { router, useForm } from "@inertiajs/svelte";
  import { onMount } from "svelte";
  import TagController from "@/actions/App/Http/Controllers/TagController";

  interface Props {
    title: string;
    tags: {
      data: {
        id: number;
        name: string;
        created_at: string;
        updated_at: string;
      }[];
      meta: {
        current_page: number;
        last_page: number;
        links: {
          url: string | null;
          label: string;
          active: boolean;
        }[];
        per_page: number;
      };
    };
  }

  interface TailwindDialogElement extends HTMLDialogElement {}

  const TAG_FORM_DIALOG_WRAPPER_ID = "tag-form-dialog-wrapper";
  const TAG_FORM_DIALOG_ID = "tag-form-dialog";

  let { title, tags }: Props = $props();
  let dialog: TailwindDialogElement;
  let originalTag: { id: number; name: string } | null = $state(null);

  const form = useForm<{ name: string }>({
    name: "",
  });

  const isEditing = $derived(originalTag !== null);
  const submitButtonText = $derived(isEditing ? "Update" : "Create");

  function openEditDialog(id: number, name: string) {
    originalTag = { id, name };

    $form.name = name;

    dialog.open = true;
  }

  function openCreateDialog() {
    originalTag = null;
    $form.name = "";

    dialog.open = true;
  }

  function submit(event: SubmitEvent) {
    event.preventDefault();

    if (originalTag) {
      if (originalTag.name === $form.name) {
        dialog.open = false;

        return;
      }

      $form.submit(TagController.update(originalTag.id), {
        onSuccess: () => {
          dialog.open = false;
        },
      });
    } else {
      $form.submit(TagController.store(), {
        onSuccess: () => {
          dialog.open = false;

          router.get(
            TagController.index({ query: { page: tags.meta.last_page } }).url,
          );
        },
      });
    }
  }

  onMount(() => {
    dialog = document.getElementById(
      TAG_FORM_DIALOG_WRAPPER_ID,
    ) as TailwindDialogElement;
  });
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain searchIsEnabled={true}>
  <Dialog
    dialogWrapperId={TAG_FORM_DIALOG_WRAPPER_ID}
    dialogId={TAG_FORM_DIALOG_ID}
  >
    <form onsubmit={submit}>
      <div class="sm:flex sm:items-start">
        <div class="w-full text-center sm:text-left">
          <label for="name" class="block text-base font-medium text-gray-900">
            Name
          </label>
          <div class="mt-2">
            <input
              id="name"
              type="text"
              placeholder="New tag name"
              bind:value={$form.name}
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
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
          commandfor={TAG_FORM_DIALOG_ID}
          class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto"
        >
          Cancel
        </button>
      </div>
    </form>
  </Dialog>

  <main class="flex grow py-10">
    <div
      class="flex w-full grow flex-col justify-between gap-8 px-4 sm:px-6 lg:px-8"
    >
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-base font-semibold text-gray-900">Tags</h1>
          <p class="mt-2 text-sm text-gray-700">Manage post tags here.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button
            type="button"
            onclick={openCreateDialog}
            class="block cursor-pointer rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
          >
            Add Tag
          </button>
        </div>
      </div>

      <div class="flow-root grow">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div
            class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
          >
            <table class="min-w-full divide-y divide-gray-300">
              <thead>
                <tr>
                  <th
                    scope="col"
                    class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                  >
                    ID
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                  >
                    Name
                  </th>
                  <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-0">
                    <span class="sr-only">Edit</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                {#each tags.data as tag (tag.id + tag.name)}
                  <tr>
                    <td
                      class="max-w-16 truncate py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                    >
                      {tag.id}
                    </td>
                    <td
                      class="max-w-16 truncate px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                    >
                      {tag.name}
                    </td>
                    <td
                      class="relative py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-0"
                    >
                      <button
                        onclick={() => openEditDialog(tag.id, tag.name)}
                        class="cursor-pointer text-blue-600 hover:text-blue-900"
                      >
                        Edit
                      </button>
                    </td>
                  </tr>
                {/each}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <Pagination meta={tags.meta} />
    </div>
  </main>
</LayoutMain>
