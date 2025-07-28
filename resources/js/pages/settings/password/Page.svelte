<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Navigation from "@/pages/settings/partials/Navigation.svelte";
  import PasswordController from "@/actions/App/Http/Controllers/Settings/PasswordController";
  import { useForm } from "@inertiajs/svelte";

  interface Props {
    title: string;
  }

  let { title }: Props = $props();

  let currentPasswordInput: HTMLInputElement;
  let passwordInput: HTMLInputElement;

  const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
  });

  function submit(event: SubmitEvent) {
    event.preventDefault();

    $form.submit(PasswordController.update(), {
      preserveScroll: true,
      onSuccess: () => $form.reset(),
      onError: (errors: any) => {
        if (errors.password) {
          $form.reset("password", "password_confirmation");

          passwordInput.focus();
        }

        if (errors.current_password) {
          $form.reset("current_password");

          currentPasswordInput.focus();
        }
      },
    });
  }
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <main class="grow py-10">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-4 gap-6">
        <div class="col-span-4">
          <h2 class="text-2xl font-semibold">Update password</h2>
          <p class="text-zinc-500">
            Ensure your account is using a long, random password to stay secure
          </p>
        </div>

        <div class="col-span-1">
          <Navigation />
        </div>

        <div class="col-span-3">
          <div class="sm:w-full sm:max-w-sm">
            <form class="space-y-6" onsubmit={submit}>
              <div>
                <label
                  for="current_password"
                  class="block text-base font-medium text-gray-900"
                >
                  Current password
                </label>
                <div class="mt-2">
                  <input
                    bind:this={currentPasswordInput}
                    bind:value={$form.current_password}
                    type="password"
                    id="current_password"
                    autocomplete="current-password"
                    required
                    class="block w-full rounded-md bg-zinc-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
                  />
                </div>
              </div>

              {#if $form.errors.current_password}
                <div class="text-red-500">{$form.errors.current_password}</div>
              {/if}

              <div>
                <label
                  for="password"
                  class="block text-base font-medium text-gray-900"
                >
                  New password
                </label>
                <div class="mt-2">
                  <input
                    bind:this={passwordInput}
                    bind:value={$form.password}
                    type="password"
                    id="password"
                    autocomplete="new-password"
                    required
                    class="block w-full rounded-md bg-zinc-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
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
                  Confirm password
                </label>
                <div class="mt-2">
                  <input
                    bind:value={$form.password_confirmation}
                    type="password"
                    id="password_confirmation"
                    autocomplete="new-password"
                    required
                    class="block w-full rounded-md bg-zinc-50 px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
                  />
                </div>
              </div>

              <div>
                <button
                  type="submit"
                  class="flex cursor-pointer justify-center rounded-md bg-blue-600 px-3 py-1.5 text-base font-semibold text-zinc-50 shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                >
                  Save password
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</LayoutMain>
