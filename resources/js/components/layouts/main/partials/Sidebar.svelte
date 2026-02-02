<script lang="ts">
  import { inertia, page } from "@inertiajs/svelte";
  import Home from "@/components/icons/Home.svelte";
  import { fly } from "svelte/transition";
  import DashboardController from "@/actions/App/Http/Controllers/DashboardController";
  import { onMount } from "svelte";
  import LinkController from "@/actions/App/Http/Controllers/LinkController";
  import TagController from "@/actions/App/Http/Controllers/TagController";
  import Link from "@/components/icons/Link.svelte";
  import Tag from "@/components/icons/Tag.svelte";
  import { sidebar } from "@/shared/sidebar.svelte.js";

  let slideOffset = $state(0);

  onMount(() => {
    slideOffset = -200;
  });
</script>

<!-- Desktop Sidebar -->
{#if sidebar.isOpen}
  <aside
    transition:fly={{ x: slideOffset, duration: 300 }}
    class="fixed inset-y-0 top-16 z-40 flex w-72 flex-col"
  >
    <div
      class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-zinc-200 bg-white px-6 pb-4"
    >
      <nav class="mt-6 flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
          <li>
            <ul role="list" class="-mx-2 space-y-1">
              <li>
                <a
                  use:inertia
                  href={DashboardController.index().url}
                  class={{
                    "bg-zinc-100 text-blue-600":
                      $page.url === DashboardController.index().url,
                    "text-zinc-700 hover:bg-zinc-100 hover:text-blue-600":
                      $page.url !== DashboardController.index().url,
                    "flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold": true,
                  }}
                >
                  <Home className="size-6 shrink-0" />
                  Dashboard
                </a>
              </li>

              <li>
                <a
                  use:inertia
                  href={LinkController.index().url}
                  class={{
                    "bg-zinc-100 text-blue-600": $page.url.startsWith(
                      LinkController.index().url,
                    ),
                    "text-zinc-700 hover:bg-zinc-100 hover:text-blue-600":
                      !$page.url.startsWith(LinkController.index().url),
                    "flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold": true,
                  }}
                >
                  <Link className="size-6 shrink-0" />
                  Links
                </a>
              </li>

              <li>
                <a
                  use:inertia
                  href={TagController.index().url}
                  class={{
                    "bg-zinc-100 text-blue-600": $page.url.startsWith(
                      TagController.index().url,
                    ),
                    "text-zinc-700 hover:bg-zinc-100 hover:text-blue-600":
                      !$page.url.startsWith(TagController.index().url),
                    "flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold": true,
                  }}
                >
                  <Tag className="size-6 shrink-0" />
                  Tags
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
{/if}
