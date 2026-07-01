<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Pagination from "@/components/Pagination.svelte";
  import Dialog from "@/components/Dialog.svelte";
  import { useForm } from "@inertiajs/svelte";
  import { onMount } from "svelte";
  import CategoryController from "@/actions/App/Http/Controllers/CategoryController";
  import SubmitButton from "@/components/forms/SubmitButton.svelte";
  import { TriangleAlert, Search } from "@lucide/svelte/icons";
  import { preventDefault } from "@/helpers";
  import InputWithLabel from "@/components/forms/InputWithLabel.svelte";
  import TextareaWithLabel from "@/components/forms/TextareaWithLabel.svelte";
  import { router } from "@inertiajs/svelte";

  interface Category {
    id: number;
    name: string;
    icon: string | null;
    description: string | null;
    is_default: boolean;
  }

  interface Props {
    title: string;
    categories: {
      data: Category[];
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

  const CATEGORY_FORM_DIALOG_WRAPPER_ID = "category-form-dialog-wrapper";
  const CATEGORY_FORM_DIALOG_ID = "category-form-dialog";
  const CATEGORY_DELETE_DIALOG_WRAPPER_ID = "category-delete-dialog-wrapper";
  const CATEGORY_DELETE_DIALOG_ID = "category-delete-dialog";

  let { title, categories }: Props = $props();

  let formDialog: TailwindDialogElement;
  let categoryToEdit: {
    id: number;
    name: string;
    icon: string;
    description: string;
  } | null = $state(null);
  let deleteDialog: TailwindDialogElement;
  let categoryToDelete: { id: number; name: string } | null = $state(null);
  let search = $state("");

  const form = useForm<{ name: string; icon: string; description: string }>({
    name: "",
    icon: "",
    description: "",
  });

  const destroyForm = useForm<{}>({});

  const isEditing = $derived(categoryToEdit !== null);
  const submitButtonText = $derived(isEditing ? "Update" : "Create");

  function openEditDialog(category: Category) {
    categoryToEdit = {
      id: category.id,
      name: category.name,
      icon: category.icon ?? "",
      description: category.description ?? "",
    };
    form.name = categoryToEdit.name;
    form.icon = categoryToEdit.icon;
    form.description = categoryToEdit.description;

    formDialog.open = true;
  }

  function openCreateDialog() {
    categoryToEdit = null;
    form.name = "";
    form.icon = "";
    form.description = "";

    formDialog.open = true;
  }

  function openDeleteDialog(id: number, name: string) {
    categoryToDelete = { id, name };
    deleteDialog.open = true;
  }

  function destroySubmit() {
    if (categoryToDelete) {
      destroyForm.submit(CategoryController.destroy(categoryToDelete.id), {
        preserveScroll: true,
        onSuccess: () => {
          deleteDialog.open = false;
          categoryToDelete = null;
        },
      });
    }
  }

  function submit() {
    if (categoryToEdit) {
      const unchanged =
        categoryToEdit.name === form.name &&
        categoryToEdit.icon === form.icon &&
        categoryToEdit.description === form.description;

      if (unchanged) {
        formDialog.open = false;

        return;
      }

      form.submit(CategoryController.update(categoryToEdit.id), {
        preserveScroll: true,
        onSuccess: () => {
          formDialog.open = false;
        },
      });
    } else {
      form.submit(CategoryController.store(), {
        onSuccess: () => {
          formDialog.open = false;
        },
      });
    }
  }

  function searchCategories() {
    router.get(CategoryController.index().url, { search: search }, { preserveState: true });
  }

  onMount(() => {
    formDialog = document.getElementById(CATEGORY_FORM_DIALOG_WRAPPER_ID) as TailwindDialogElement;

    deleteDialog = document.getElementById(
      CATEGORY_DELETE_DIALOG_WRAPPER_ID,
    ) as TailwindDialogElement;
  });
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <Dialog dialogWrapperId={CATEGORY_FORM_DIALOG_WRAPPER_ID} dialogId={CATEGORY_FORM_DIALOG_ID}>
    <form onsubmit={preventDefault(submit)}>
      <div class="sm:flex sm:items-start">
        <div class="w-full space-y-4 text-center sm:text-left">
          <div>
            <InputWithLabel
              id="category-name-input"
              name="category-name-input"
              type="text"
              placeholder="New category name"
              label="Name"
              bind:value={form.name}
            />

            {#if form.errors.name}
              <div class="mt-2 text-red-500">{form.errors.name}</div>
            {/if}
          </div>

          <div>
            <TextareaWithLabel
              id="category-icon-input"
              name="category-icon-input"
              placeholder="<svg>...</svg>"
              label="Icon (SVG markup)"
              rows={4}
              bind:value={form.icon}
            />

            {#if form.errors.icon}
              <div class="mt-2 text-red-500">{form.errors.icon}</div>
            {/if}
          </div>

          <div>
            <TextareaWithLabel
              id="category-description-input"
              name="category-description-input"
              placeholder="Short description"
              label="Description"
              rows={3}
              bind:value={form.description}
            />

            {#if form.errors.description}
              <div class="mt-2 text-red-500">{form.errors.description}</div>
            {/if}
          </div>
        </div>
      </div>

      <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
        <SubmitButton
          label={submitButtonText}
          processing={form.processing}
          className="sm:ml-3 sm:w-auto w-full"
        />
        <button
          type="button"
          command="close"
          commandfor={CATEGORY_FORM_DIALOG_ID}
          class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto"
        >
          Cancel
        </button>
      </div>
    </form>
  </Dialog>

  <Dialog dialogWrapperId={CATEGORY_DELETE_DIALOG_WRAPPER_ID} dialogId={CATEGORY_DELETE_DIALOG_ID}>
    <form onsubmit={preventDefault(destroySubmit)}>
      <div class="sm:flex sm:items-start">
        <div
          class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:size-10"
        >
          <TriangleAlert class="size-6 text-red-600" />
        </div>
        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
          <h3 id="dialog-title" class="text-lg font-semibold text-gray-900">Delete Category</h3>
          <div class="mt-2">
            <p class=" text-gray-500">
              Are you sure you want to delete the category "<span class="font-medium text-gray-900"
                >{categoryToDelete ? categoryToDelete.name : ""}</span
              >"? This action cannot be undone.
            </p>
          </div>
        </div>
      </div>
      <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
        <SubmitButton
          id="delete-category-confirmation"
          label="Delete"
          processing={destroyForm.processing}
          className="bg-red-600 hover:bg-red-500 sm:ml-3 sm:w-auto w-full"
        >
          {destroyForm.processing ? "Deleting..." : "Delete"}
        </SubmitButton>
        <button
          type="button"
          command="close"
          commandfor={CATEGORY_DELETE_DIALOG_ID}
          class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
        >
          Cancel
        </button>
      </div>
    </form>
  </Dialog>

  <main class="flex min-h-[calc(100vh-4rem)] bg-white py-10">
    <div class="flex w-full grow flex-col justify-between gap-8 px-4 sm:px-6 lg:px-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-lg font-semibold text-gray-900">Categories</h1>
          <p class="mt-2 text-gray-700">Manage post categories here.</p>
        </div>

        <div class="mt-4 flex gap-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <form onsubmit={preventDefault(searchCategories)}>
            <div>
              <label for="search-category" class="hidden">Search Category</label>
              <div class="grid grid-cols-1">
                <input
                  id="search-category"
                  type="text"
                  name="search-category"
                  placeholder="Search categories..."
                  bind:value={search}
                  class="col-start-1 row-start-1 block w-full rounded-md bg-white py-1.5 pr-3 pl-10 text-base text-zinc-900 outline-1 -outline-offset-1 outline-zinc-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600 sm:pl-9"
                />
                <Search
                  class="pointer-events-none col-start-1 row-start-1 ml-3 size-5 self-center text-gray-400 sm:size-4"
                />
              </div>
            </div>
          </form>

          <button
            type="button"
            onclick={openCreateDialog}
            class="block cursor-pointer rounded-md bg-blue-600 px-3 py-1.5 text-center font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
          >
            Add Category
          </button>
        </div>
      </div>

      <div class="flow-root grow">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300">
              <thead>
                <tr>
                  <th
                    scope="col"
                    class="py-3.5 pr-3 pl-4 text-left font-semibold text-gray-900 sm:pl-0"
                  >
                    ID
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">
                    Icon
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">
                    Name
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">
                    Description
                  </th>
                  <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-0">
                    <span class="sr-only">Edit and Delete</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                {#each categories.data as category (category.id)}
                  <tr>
                    <td
                      class="max-w-16 truncate py-4 pr-3 pl-4 font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                    >
                      {category.id}
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-gray-500">
                      {#if category.icon}
                        <span
                          class="inline-flex size-6 items-center justify-center text-gray-700 [&_svg]:size-6"
                          aria-hidden="true">{@html category.icon}</span
                        >
                      {:else}
                        <span class="text-gray-300">—</span>
                      {/if}
                    </td>
                    <td class="max-w-16 truncate px-3 py-4 whitespace-nowrap text-gray-900">
                      {category.name}
                    </td>
                    <td class="max-w-xs truncate px-3 py-4 whitespace-nowrap text-gray-500">
                      {category.description ?? ""}
                    </td>
                    <td
                      class="relative flex justify-end gap-4 py-4 pr-4 pl-3 font-medium whitespace-nowrap sm:pr-0"
                    >
                      <button
                        onclick={() => openEditDialog(category)}
                        class="cursor-pointer text-blue-600 hover:text-blue-900"
                      >
                        Edit
                      </button>
                      <button
                        id={`delete-category-${category.id}`}
                        onclick={() => openDeleteDialog(category.id, category.name)}
                        disabled={category.is_default}
                        class="cursor-pointer text-red-600 hover:text-red-900 disabled:cursor-not-allowed disabled:opacity-50"
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

      <Pagination meta={categories.meta} />
    </div>
  </main>
</LayoutMain>
