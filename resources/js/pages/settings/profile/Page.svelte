<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Navigation from "@/pages/settings/partials/Navigation.svelte";
  import { useForm, page } from "@inertiajs/svelte";
  import ProfileController from "@/actions/App/Http/Controllers/Settings/ProfileController";

  interface Props {
    title: string;
  }

  let { title }: Props = $props();

  const user = $page.props.auth.user;

  const form = useForm({
    name: user.name,
    email: user.email,
  });

  function submit(event: SubmitEvent) {
    event.preventDefault();

    $form.submit(ProfileController.update(), {
      preserveScroll: true,
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
          <h2 class="text-2xl font-semibold">Profile information</h2>
          <p class="text-zinc-500">Update your name and email address</p>
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
                  Name
                </label>
                <div class="mt-2">
                  <input
                    bind:value={$form.name}
                    type="text"
                    id="name"
                    autocomplete="name"
                    required
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
                  />
                </div>
              </div>

              {#if $form.errors.name}
                <div class="text-red-500">{$form.errors.name}</div>
              {/if}

              <div>
                <label
                  for="password"
                  class="block text-base font-medium text-gray-900"
                >
                  Email address
                </label>
                <div class="mt-2">
                  <input
                    bind:value={$form.email}
                    type="text"
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
                  class="flex cursor-pointer justify-center rounded-md bg-blue-600 px-3 py-1.5 text-base font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                >
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
</LayoutMain>
