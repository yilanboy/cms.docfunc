<script lang="ts">
  import ForgotPasswordController from "@/actions/App/Http/Controllers/Auth/ForgotPasswordController";
  import { useForm } from "@inertiajs/svelte";
  import ChevronLeft from "@/components/icons/ChevronLeft.svelte";
  import { back } from "@/helpers";
  import LayoutGuest from "@/components/layouts/guest/LayoutGuest.svelte";
  import SubmitButton from "@/components/forms/SubmitButton.svelte";

  let title = "Forgot Password";

  const form = useForm({
    email: "",
  });

  function submit(event: SubmitEvent) {
    event.preventDefault();

    $form.submit(ForgotPasswordController.store());
  }
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutGuest>
  <header class="flex w-full items-center justify-start p-4">
    <button
      type="button"
      onclick={back}
      class="flex cursor-pointer items-center gap-2 rounded-full px-4 py-2 transition duration-150 hover:bg-zinc-100"
    >
      <ChevronLeft className="size-4" />
      <span class="text-lg">Go back</span>
    </button>
  </header>

  <main class="flex w-full grow flex-col justify-center p-6">
    <h2 class="text-center text-2xl/9 font-bold tracking-tight text-gray-900">
      Forgot Your Password?
    </h2>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" onsubmit={submit}>
        <div>
          <label for="email" class="block text-base font-medium text-gray-900">
            Email address
          </label>
          <div class="mt-2">
            <input
              bind:value={$form.email}
              type="email"
              name="email"
              id="email"
              autocomplete="email"
              required
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
            />
          </div>
        </div>

        {#if $form.errors.email}
          <div class="text-red-500">{$form.errors.email}</div>
        {/if}

        <div>
          <SubmitButton
            label="Send Reset Link"
            className="w-full"
            processing={$form.processing}
          />
        </div>
      </form>
    </div>
  </main>
</LayoutGuest>
