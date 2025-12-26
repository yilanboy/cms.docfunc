<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Pagination from "@/components/Pagination.svelte";
  import Dialog from "@/components/Dialog.svelte";
  import { useForm } from "@inertiajs/svelte";
  import { onMount } from "svelte";
  import TagController from "@/actions/App/Http/Controllers/TagController";
  import TriangleAlert from "@/components/icons/TriangleAlert.svelte";
  import { preventDefault } from "@/helpers";
  import InputWithLabel from "@/components/forms/InputWithLabel.svelte";
  import Search from "@/components/icons/Search.svelte";
  import { router } from "@inertiajs/svelte";

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
  const TAG_DELETE_DIALOG_WRAPPER_ID = "tag-delete-dialog-wrapper";
  const TAG_DELETE_DIALOG_ID = "tag-delete-dialog";

  let { title, tags }: Props = $props();
  let formDialog: TailwindDialogElement;
  let tagToEdit: { id: number; name: string } | null = $state(null);
  let deleteDialog: TailwindDialogElement;
  let tagToDelete: { id: number; name: string } | null = $state(null);
  let search = $state("");

  const form = useForm<{ name: string }>({
    name: "",
  });

  const destroyForm = useForm<{}>({});

  const isEditing = $derived(tagToEdit !== null);
  const submitButtonText = $derived(isEditing ? "Update" : "Create");

  function openEditDialog(id: number, name: string) {
    tagToEdit = { id, name };
    $form.name = name;

    formDialog.open = true;
  }

  function openCreateDialog() {
    tagToEdit = null;
    $form.name = "";

    formDialog.open = true;
  }

  function openDeleteDialog(id: number, name: string) {
    tagToDelete = { id, name };
    deleteDialog.open = true;
  }

  function destroySubmit() {
    if (tagToDelete) {
      $destroyForm.submit(TagController.destroy(tagToDelete.id), {
        preserveScroll: true,
        onSuccess: () => {
          deleteDialog.open = false;
          tagToDelete = null;
        },
      });
    }
  }

  function submit() {
    if (tagToEdit) {
      if (tagToEdit.name === $form.name) {
        formDialog.open = false;

        return;
      }

      $form.submit(TagController.update(tagToEdit.id), {
        preserveScroll: true,
        onSuccess: () => {
          formDialog.open = false;
        },
      });
    } else {
      $form.submit(TagController.store(), {
        onSuccess: () => {
          formDialog.open = false;
        },
      });
    }
  }

  function searchTags() {
    router.get(
      TagController.index().url,
      { search: search },
      { preserveState: true },
    );
  }

  onMount(() => {
    formDialog = document.getElementById(
      TAG_FORM_DIALOG_WRAPPER_ID,
    ) as TailwindDialogElement;

    deleteDialog = document.getElementById(
      TAG_DELETE_DIALOG_WRAPPER_ID,
    ) as TailwindDialogElement;
  });
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <Dialog
    dialogWrapperId={TAG_FORM_DIALOG_WRAPPER_ID}
    dialogId={TAG_FORM_DIALOG_ID}
  >
    <form onsubmit={preventDefault(submit)}>
      <div class="sm:flex sm:items-start">
        <div class="w-full text-center sm:text-left">
          <InputWithLabel
            id="tag-name-input"
            type="text"
            placeholder="New tag name"
            label="Name"
            bind:value={$form.name}
          />

          {#if $form.errors.name}
            <div class="mt-2 text-red-500">{$form.errors.name}</div>
          {/if}
        </div>
      </div>

      <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
        <button
          type="submit"
          disabled={$form.processing}
          class="inline-flex w-full cursor-pointer justify-center rounded-md bg-blue-600 px-3 py-2 font-semibold text-white shadow-xs hover:bg-blue-500 disabled:pointer-events-none disabled:opacity-50 sm:ml-3 sm:w-auto"
        >
          {submitButtonText}
        </button>
        <button
          type="button"
          command="close"
          commandfor={TAG_FORM_DIALOG_ID}
          class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto"
        >
          Cancel
        </button>
      </div>
    </form>
  </Dialog>

  <Dialog
    dialogWrapperId={TAG_DELETE_DIALOG_WRAPPER_ID}
    dialogId={TAG_DELETE_DIALOG_ID}
  >
    <form onsubmit={preventDefault(destroySubmit)}>
      <div class="sm:flex sm:items-start">
        <div
          class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:size-10"
        >
          <TriangleAlert className="size-6 text-red-600" />
        </div>
        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
          <h3 id="dialog-title" class="text-lg font-semibold text-gray-900">
            Delete Tag
          </h3>
          <div class="mt-2">
            <p class=" text-gray-500">
              Are you sure you want to delete the tag "<span
                class="font-medium text-gray-900"
                >{tagToDelete ? tagToDelete.name : ""}</span
              >"? This action cannot be undone.
            </p>
          </div>
        </div>
      </div>
      <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
        <button
          id="delete-tag-confirmation"
          type="submit"
          disabled={$destroyForm.processing}
          class="inline-flex w-full cursor-pointer justify-center rounded-md bg-red-600 px-3 py-2 font-semibold text-white shadow-xs hover:bg-red-500 disabled:pointer-events-none disabled:opacity-50 sm:ml-3 sm:w-auto"
        >
          {$destroyForm.processing ? "Deleting..." : "Delete"}
        </button>
        <button
          type="button"
          command="close"
          commandfor={TAG_DELETE_DIALOG_ID}
          class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
        >
          Cancel
        </button>
      </div>
    </form>
  </Dialog>

  <main class="flex min-h-[calc(100vh-4rem)] bg-white py-10">
    <div
      class="flex w-full grow flex-col justify-between gap-8 px-4 sm:px-6 lg:px-8"
    >
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-lg font-semibold text-gray-900">Tags</h1>
          <p class="mt-2 text-gray-700">Manage post tags here.</p>
        </div>

        <div class="mt-4 flex gap-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <form onsubmit={preventDefault(searchTags)}>
            <div>
              <label for="search-tag" class="hidden">Search Tag</label>
              <div class="grid grid-cols-1">
                <input
                  id="search-tag"
                  type="text"
                  name="search-tag"
                  placeholder="Search tags..."
                  bind:value={search}
                  class="col-start-1 row-start-1 block w-full rounded-md bg-white py-1.5 pr-3 pl-10 text-base text-zinc-900 outline-1 -outline-offset-1 outline-zinc-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 sm:pl-9"
                />
                <Search
                  className="pointer-events-none col-start-1 row-start-1 ml-3 size-5 self-center text-gray-400 sm:size-4"
                />
              </div>
            </div>
          </form>

          <button
            type="button"
            onclick={openCreateDialog}
            class="block cursor-pointer rounded-md bg-blue-600 px-3 py-1.5 text-center font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
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
                    class="py-3.5 pr-3 pl-4 text-left font-semibold text-gray-900 sm:pl-0"
                  >
                    ID
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3.5 text-left font-semibold text-gray-900"
                  >
                    Name
                  </th>
                  <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-0">
                    <span class="sr-only">Edit and Delete</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                {#each tags.data as tag (tag.id + tag.name)}
                  <tr>
                    <td
                      class="max-w-16 truncate py-4 pr-3 pl-4 font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                    >
                      {tag.id}
                    </td>
                    <td
                      class="max-w-16 truncate px-3 py-4 whitespace-nowrap text-gray-500"
                    >
                      {tag.name}
                    </td>
                    <td
                      class="relative flex justify-end gap-4 py-4 pr-4 pl-3 font-medium whitespace-nowrap sm:pr-0"
                    >
                      <button
                        onclick={() => openEditDialog(tag.id, tag.name)}
                        class="cursor-pointer text-blue-600 hover:text-blue-900"
                      >
                        Edit
                      </button>
                      <button
                        id={`delete-tag-${tag.id}`}
                        onclick={() => openDeleteDialog(tag.id, tag.name)}
                        class="cursor-pointer text-red-600 hover:text-red-900"
                      >
                        Delete
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
