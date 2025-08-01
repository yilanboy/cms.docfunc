<script lang="ts">
  import ResetPasswordController from "@/actions/App/Http/Controllers/Auth/ResetPasswordController";
  import { useForm } from "@inertiajs/svelte";

  interface Props {
    token: string;
    email: string;
  }

  let title = "Reset Password";

  let { token, email }: Props = $props();

  const form = useForm({
    token: token,
    email: email,
    password: "Password102",
    password_confirmation: "Password102",
  });

  function submit(event: SubmitEvent) {
    event.preventDefault();

    $form.submit(ResetPasswordController.update());
  }
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<div class="font-source-sans-3 flex min-h-screen w-full flex-col bg-white">
  <main class="flex w-full grow flex-col justify-center p-6">
    <h2 class="text-center text-2xl/9 font-bold tracking-tight text-gray-900">
      Reset Your Password
    </h2>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" onsubmit={submit}>
        <div>
          <label
            for="password"
            class="block text-base font-medium text-gray-900"
          >
            Password
          </label>
          <div class="mt-2">
            <input
              bind:value={$form.password}
              type="password"
              name="password"
              id="password"
              autocomplete="current-password"
              required
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
            />
          </div>
        </div>

        {#if $form.errors.password}
          <div class="text-red-500">{$form.errors.password}</div>
        {/if}

        <div>
          <label
            for="password_confirmation"
            class="block text-base font-medium text-gray-900"
          >
            Confirm Password
          </label>
          <div class="mt-2">
            <input
              bind:value={$form.password_confirmation}
              type="password"
              name="password_confirmation"
              id="password_confirmation"
              required
              class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="flex w-full cursor-pointer justify-center rounded-md bg-blue-600 px-3 py-1.5 text-base font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
          >
            Update Password
          </button>
        </div>
      </form>
    </div>
  </main>
</div>
