<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Dialog from "@/components/Dialog.svelte";
  import { onMount } from "svelte";
  import { router, useForm } from "@inertiajs/svelte";
  import LinkController from "@/actions/App/Http/Controllers/LinkController";
  import TriangleAlert from "@/components/icons/TriangleAlert.svelte";

  interface Props {
    title: string;
    links: {
      id: number;
      title: string;
      url: string;
      created_at: string;
      updated_at: string;
    }[];
  }

  interface TailwindDialogElement extends HTMLDialogElement {}

  const LINK_FORM_DIALOG_WRAPPER_ID = "link-form-dialog-wrapper";
  const LINK_FORM_DIALOG_ID = "link-form-dialog";
  const LINK_DELETE_DIALOG_WRAPPER_ID = "link-delete-dialog-wrapper";
  const LINK_DELETE_DIALOG_ID = "link-delete-dialog";

  let { title, links }: Props = $props();
  let formDialog: TailwindDialogElement;
  let linkToEdit: { id: number; title: string; url: string } | null =
    $state(null);
  let deleteDialog: TailwindDialogElement;
  let linkToDelete: { id: number; title: string } | null = $state(null);

  const isEditing = $derived(linkToEdit !== null);
  const submitButtonText = $derived(isEditing ? "Update" : "Create");

  const form = useForm<{ title: string; url: string }>({
    title: "",
    url: "",
  });

  function openCreateDialog() {
    linkToEdit = null;

    $form.title = "";
    $form.url = "";

    formDialog.open = true;
  }

  function openEditDialog(id: number, title: string, url: string) {
    linkToEdit = { id, title, url };

    $form.title = title;
    $form.url = url;

    formDialog.open = true;
  }

  function openDeleteDialog(id: number, title: string) {
    linkToDelete = { id, title };
    deleteDialog.open = true;
  }

  function destroyLink() {
    if (linkToDelete) {
      router.delete(LinkController.destroy(linkToDelete.id), {
        preserveScroll: true,
        onSuccess: () => {
          deleteDialog.open = false;
          linkToDelete = null;
        },
      });
    }
  }

  function submit(event: SubmitEvent) {
    event.preventDefault();

    if (linkToEdit) {
      if (linkToEdit.title === $form.title && linkToEdit.url === $form.url) {
        formDialog.open = false;
      }

      $form.submit(LinkController.update(linkToEdit.id), {
        preserveScroll: true,
        onSuccess: () => {
          formDialog.open = false;
        },
      });

      return;
    } else {
      $form.submit(LinkController.store(), {
        onSuccess: () => {
          formDialog.open = false;
        },
      });
    }
  }

  onMount(() => {
    formDialog = document.getElementById(
      LINK_FORM_DIALOG_WRAPPER_ID,
    ) as TailwindDialogElement;

    deleteDialog = document.getElementById(
      LINK_DELETE_DIALOG_WRAPPER_ID,
    ) as TailwindDialogElement;
  });
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <Dialog
    dialogWrapperId={LINK_FORM_DIALOG_WRAPPER_ID}
    dialogId={LINK_FORM_DIALOG_ID}
  >
    <form onsubmit={submit}>
      <div class="flex flex-col items-start gap-2">
        <div class="w-full text-center sm:text-left">
          <label for="title" class="block text-base font-medium text-gray-900">
            Title
          </label>
          <div class="mt-2">
            <input
              id="title"
              type="text"
              placeholder="Link title"
              bind:value={$form.title}
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
            />
          </div>

          {#if $form.errors.title}
            <div class="mt-2 text-red-500">{$form.errors.title}</div>
          {/if}
        </div>

        <div class="w-full text-center sm:text-left">
          <label for="url" class="block text-base font-medium text-gray-900">
            URL
          </label>
          <div class="mt-2">
            <input
              id="url"
              type="text"
              placeholder="Link URL"
              bind:value={$form.url}
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
            />
          </div>

          {#if $form.errors.url}
            <div class="mt-2 text-red-500">{$form.errors.url}</div>
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
          commandfor={LINK_FORM_DIALOG_ID}
          class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto"
        >
          Cancel
        </button>
      </div>
    </form>
  </Dialog>

  <Dialog
    dialogWrapperId={LINK_DELETE_DIALOG_WRAPPER_ID}
    dialogId={LINK_DELETE_DIALOG_ID}
  >
    <div class="sm:flex sm:items-start">
      <div
        class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:size-10"
      >
        <TriangleAlert className="size-6 text-red-600" />
      </div>
      <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
        <h3 id="dialog-title" class="text-lg font-semibold text-gray-900">
          Delete Link
        </h3>
        <div class="mt-2">
          <p class=" text-gray-500">
            Are you sure you want to delete the link "<span
              class="font-medium text-gray-900"
              >{linkToDelete ? linkToDelete.title : ""}</span
            >"? This action cannot be undone.
          </p>
        </div>
      </div>
    </div>
    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
      <button
        type="button"
        onclick={destroyLink}
        class="inline-flex w-full cursor-pointer justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto"
      >
        Delete
      </button>
      <button
        type="button"
        command="close"
        commandfor={LINK_DELETE_DIALOG_ID}
        class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
      >
        Cancel
      </button>
    </div>
  </Dialog>

  <main class="flex grow py-10">
    <div class="w-full px-4 sm:px-6 lg:px-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-base font-semibold text-gray-900">Links</h1>
          <p class="mt-2 text-sm text-gray-700">
            Links to recommended resources
          </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button
            type="button"
            onclick={openCreateDialog}
            disabled={links.length >= 5}
            class="block cursor-pointer rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:pointer-events-none disabled:opacity-50"
          >
            Add link
          </button>
        </div>
      </div>
      <div class="mt-8 flow-root">
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
                    Title
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                  >
                    URL
                  </th>
                  <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-0">
                    <span class="sr-only">Edit and Delete</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                {#each links as link (link.id + link.title + link.url)}
                  <tr>
                    <td
                      class="max-w-16 truncate py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                    >
                      {link.id}
                    </td>
                    <td
                      class="max-w-16 truncate px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                    >
                      {link.title}
                    </td>
                    <td
                      class="max-w-16 truncate px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                    >
                      {link.url}
                    </td>
                    <td
                      class="relative flex justify-end gap-2 py-4 pr-4 pl-3 text-sm font-medium whitespace-nowrap sm:pr-0"
                    >
                      <button
                        onclick={() =>
                          openEditDialog(link.id, link.title, link.url)}
                        class="cursor-pointer text-blue-600 hover:text-blue-900"
                      >
                        Edit
                      </button>

                      <button
                        onclick={() => openDeleteDialog(link.id, link.title)}
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
    </div>
  </main>
</LayoutMain>
