<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Navigation from "@/pages/settings/partials/Navigation.svelte";
  import Dialog from "@/components/Dialog.svelte";
  import { onMount } from "svelte";
  import { useForm } from "@inertiajs/svelte";
  import SubmitButton from "@/components/forms/SubmitButton.svelte";
  import InputWithLabel from "@/components/forms/InputWithLabel.svelte";
  import GeneratePasskeyRegistrationOptionController from "@/actions/App/Http/Controllers/Api/GeneratePasskeyRegistrationOptionController";
  import PasskeyController from "@/actions/App/Http/Controllers/Settings/PasskeyController";
  import {
    browserSupportsWebAuthn,
    startRegistration,
  } from "@simplewebauthn/browser";
  import TriangleAlert from "@/components/icons/TriangleAlert.svelte";
  import { preventDefault } from "@/helpers";
  import { toasts } from "@/shared/toasts.svelte";

  interface TailwindDialogElement extends HTMLDialogElement {}

  interface Passkey {
    id: number;
    name: string;
    last_used_at: string | null;
  }

  interface Props {
    title: string;
    passkeys: Passkey[];
  }

  const PASSKEY_FORM_DIALOG_WRAPPER_ID = "passkey-form-dialog-wrapper";
  const PASSKEY_FORM_DIALOG_ID = "passkey-form-dialog";
  const PASSKEY_DELETE_DIALOG_WRAPPER_ID = "passkey-delete-dialog-wrapper";
  const PASSKEY_DELETE_DIALOG_ID = "passkey-delete-dialog";

  let formDialog: TailwindDialogElement;
  let deleteDialog: TailwindDialogElement;

  let { title, passkeys }: Props = $props();

  let passkeyToDelete: Passkey | null = $state(null);

  const form = useForm({
    name: "",
    passkey: "",
  });

  const destroyForm = useForm({});

  function addPasskey() {
    if (!browserSupportsWebAuthn()) {
      toasts.add({
        type: "warning",
        message: "WebAuthn is not supported in this browser.",
        description: "Please try again with a different browser.",
      });

      return;
    }

    formDialog.open = true;
  }

  async function register() {
    if (form.name === "") {
      form.setError("name", "Name is required.");

      return;
    }

    if (form.name.length < 3) {
      form.setError("name", "Name must be at least 3 characters long.");

      return;
    }

    const response = await fetch(
      GeneratePasskeyRegistrationOptionController().url,
    );
    const optionsJSON = await response.json();

    try {
      const registrationResponse = await startRegistration({ optionsJSON });

      form.passkey = JSON.stringify(registrationResponse);

      form.submit(PasskeyController.store(), {
        onSuccess: () => {
          formDialog.open = false;
          form.reset();

          toasts.add({
            type: "success",
            message: "Passkey registered successfully.",
          });
        },
      });
    } catch (error) {
      formDialog.open = false;

      toasts.add({
        type: "danger",
        message: "Registration failed. Please try again.",
      });
    }
  }

  function openDeleteDialog(passkey: Passkey) {
    passkeyToDelete = passkey;
    deleteDialog.open = true;
  }

  function destroySubmit() {
    if (passkeyToDelete) {
      destroyForm.submit(PasskeyController.destroy(passkeyToDelete.id), {
        preserveScroll: true,
        onSuccess: () => {
          deleteDialog.open = false;
          passkeyToDelete = null;

          toasts.add({
            type: "success",
            message: "Passkey deleted successfully.",
          });
        },
      });
    }
  }

  onMount(() => {
    formDialog = document.getElementById(
      PASSKEY_FORM_DIALOG_WRAPPER_ID,
    ) as TailwindDialogElement;

    deleteDialog = document.getElementById(
      PASSKEY_DELETE_DIALOG_WRAPPER_ID,
    ) as TailwindDialogElement;
  });
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <Dialog
    dialogWrapperId={PASSKEY_FORM_DIALOG_WRAPPER_ID}
    dialogId={PASSKEY_FORM_DIALOG_ID}
  >
    <form onsubmit={preventDefault(register)}>
      <div class="sm:flex sm:items-start">
        <div class="w-full text-center sm:text-left">
          <InputWithLabel
            id="passkey-name-input"
            name="name"
            type="text"
            placeholder="New passkey name"
            label="Name"
            bind:value={form.name}
          />

          {#if form.hasErrors}
            <div class="mt-2 text-red-500">
              {Object.values(form.errors)[0]}
            </div>
          {/if}
        </div>
      </div>

      <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
        <SubmitButton
          label="Create"
          processing={form.processing}
          className="sm:ml-3 sm:w-auto w-full"
        />

        <button
          type="button"
          command="close"
          commandfor={PASSKEY_FORM_DIALOG_ID}
          class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto"
        >
          Cancel
        </button>
      </div>
    </form>
  </Dialog>

  <Dialog
    dialogWrapperId={PASSKEY_DELETE_DIALOG_WRAPPER_ID}
    dialogId={PASSKEY_DELETE_DIALOG_ID}
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
            Delete Passkey
          </h3>
          <div class="mt-2">
            <p class=" text-gray-500">
              Are you sure you want to delete the passkey "<span
                class="font-medium text-gray-900"
                >{passkeyToDelete ? passkeyToDelete.name : ""}</span
              >"? This action cannot be undone.
            </p>
          </div>
        </div>
      </div>
      <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
        <SubmitButton
          id="delete-passkey-confirmation"
          label="Delete"
          processing={destroyForm.processing}
          className="bg-red-600 hover:bg-red-500 sm:ml-3 sm:w-auto w-full"
        >
          {destroyForm.processing ? "Deleting..." : "Delete"}
        </SubmitButton>
        <button
          type="button"
          command="close"
          commandfor={PASSKEY_DELETE_DIALOG_ID}
          class="mt-3 inline-flex w-full cursor-pointer justify-center rounded-md bg-white px-3 py-2 font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50 sm:mt-0 sm:w-auto"
        >
          Cancel
        </button>
      </div>
    </form>
  </Dialog>

  <main class="grow py-10">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-4 gap-6">
        <div class="col-span-4">
          <h2 class="text-2xl font-semibold">Manage passkeys</h2>
          <p class="text-zinc-500">Manage your passkeys.</p>
        </div>

        <div class="col-span-4 lg:col-span-1">
          <Navigation />
        </div>

        <div class="col-span-4 lg:col-span-3">
          <div class="sm:w-full sm:max-w-sm">
            <div class="flex w-full items-center justify-end space-x-4">
              <button
                title="Add passkey"
                type="button"
                class="focus:ring-opacity-75 rounded-md bg-blue-500 px-4 py-2 text-white hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                onclick={addPasskey}
              >
                Add passkey
              </button>
            </div>

            <!-- list passkeys -->
            <div class="mt-6 space-y-4">
              {#each passkeys as passkey (passkey.id)}
                <div
                  class="flex items-center justify-between border-b border-zinc-200 py-4"
                >
                  <div>
                    <p class="font-medium">{passkey.name}</p>
                    {#if passkey.last_used_at}
                      <p class="text-sm text-zinc-500">
                        Last used {passkey.last_used_at}
                      </p>
                    {:else}
                      <p class="text-sm text-zinc-500">Never used</p>
                    {/if}
                  </div>

                  <div class="flex items-center space-x-4">
                    <button
                      onclick={() => openDeleteDialog(passkey)}
                      class="text-red-500 hover:text-red-600 focus:outline-none"
                    >
                      Delete
                    </button>
                  </div>
                </div>
              {:else}
                <p class="text-zinc-500">No passkeys added yet.</p>
              {/each}
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</LayoutMain>
