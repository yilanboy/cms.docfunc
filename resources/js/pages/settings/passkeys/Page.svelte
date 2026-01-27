<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Navigation from "@/pages/settings/partials/Navigation.svelte";

  interface Passkey {
    id: number;
    name: string;
    last_used_at: string | null;
  }

  interface Props {
    title: string;
    passkeys: Passkey[];
  }

  let { title, passkeys }: Props = $props();
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
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
