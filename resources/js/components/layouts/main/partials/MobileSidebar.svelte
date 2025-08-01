<script lang="ts">
  import { inertia, page } from "@inertiajs/svelte";
  import Home from "@/components/icons/Home.svelte";
  import X from "@/components/icons/X.svelte";
  import { fly, fade } from "svelte/transition";
  import DashboardController from "@/actions/App/Http/Controllers/DashboardController";

  interface Props {
    sidebarIsOpen: boolean;
  }

  let { sidebarIsOpen = $bindable(false) }: Props = $props();

  function closeSidebar() {
    sidebarIsOpen = false;
  }
</script>

<!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
{#if sidebarIsOpen}
  <div class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
    <!-- Off-canvas menu backdrop, show/hide based on off-canvas menu state. -->
    <div
      transition:fade
      class="fixed inset-0 bg-gray-900/80"
      aria-hidden="true"
    ></div>

    <div transition:fly={{ x: -200 }} class="fixed inset-0 flex">
      <!-- Off-canvas menu, show/hide based on off-canvas menu state. -->
      <div class="relative mr-16 flex w-full max-w-xs flex-1">
        <!-- Close button, show/hide based on off-canvas menu state. -->
        <div
          class="absolute top-0 left-full flex w-16 cursor-pointer justify-center pt-5"
        >
          <button onclick={closeSidebar} type="button" class="-m-2.5 p-2.5">
            <span class="sr-only">Close sidebar</span>
            <X className="size-6 text-zinc-50" />
          </button>
        </div>

        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div
          class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4"
        >
          <div class="flex h-16 shrink-0 items-center text-xl font-semibold">
            <div
              class="flex size-8 items-center justify-center rounded-md bg-zinc-900 text-center text-zinc-50"
            >
              S
            </div>
            <span>Svelte Starter Kit</span>
          </div>

          <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
              <li>
                <ul role="list" class="-mx-2 space-y-1">
                  <li>
                    <!--
                      Current: "bg-zinc-100 text-blue-600",
                      Default: "text-zinc-700 hover:text-blue-600 hover:bg-zinc-100"
                      -->
                    <a
                      use:inertia
                      href={DashboardController.index().url}
                      class={{
                        "bg-zinc-100 text-blue-600":
                          $page.url === DashboardController.index().url,
                        "text-zinc-700 hover:bg-zinc-100 hover:text-blue-600":
                          $page.url !== DashboardController.index().url,
                        "-mx-2 flex gap-x-3 rounded-md p-2 text-base font-semibold": true,
                      }}
                    >
                      <Home className="size-6 shrink-0" />
                      Dashboard
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
{/if}
