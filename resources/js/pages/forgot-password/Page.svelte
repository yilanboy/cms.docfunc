<script lang="ts">
  import ForgotPasswordController from "@/actions/App/Http/Controllers/Auth/ForgotPasswordController";
  import { useForm, page } from "@inertiajs/svelte";
  import CircleCheck from "@/components/icons/CircleCheck.svelte";
  import { fly } from "svelte/transition";
  import CircleX from "@/components/icons/CircleX.svelte";
  import ChevronLeft from "@/components/icons/ChevronLeft.svelte";
  import { back } from "@/helpers";

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

<div
  class="font-source-sans-3 relative flex min-h-screen w-full flex-col bg-white"
>
  {#if $page.props.flash.success}
    <div
      transition:fly={{ y: -100 }}
      class="absolute top-4 right-1/2 left-1/2 -translate-x-1/2 rounded-md bg-green-50 p-4 sm:w-full sm:max-w-sm"
    >
      <div class="flex items-center">
        <div class="shrink-0">
          <CircleCheck className="size-5 text-green-400" />
        </div>
        <div class="ml-3">
          <p class="text-base font-medium text-green-700">
            {$page.props.flash.success}
          </p>
        </div>
      </div>
    </div>
  {/if}

  {#if $page.props.flash.error}
    <div
      transition:fly={{ y: -100 }}
      class="absolute top-4 right-1/2 left-1/2 -translate-x-1/2 rounded-md bg-red-50 p-4 sm:w-full sm:max-w-sm"
    >
      <div class="flex items-center">
        <div class="shrink-0">
          <CircleX className="size-5 text-red-400" />
        </div>
        <div class="ml-3">
          <p class="text-base font-medium text-red-700">
            {$page.props.flash.error}
          </p>
        </div>
      </div>
    </div>
  {/if}

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
          <button
            type="submit"
            class="flex w-full cursor-pointer justify-center rounded-md bg-blue-600 px-3 py-1.5 text-base font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
          >
            Send Reset Link
          </button>
        </div>
      </form>
    </div>
  </main>
</div>
