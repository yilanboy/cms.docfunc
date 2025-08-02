<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import { inertia } from "@inertiajs/svelte";
  import ArrowRight from "@/components/icons/ArrowRight.svelte";
  import ArrowLeft from "@/components/icons/ArrowLeft.svelte";

  interface Props {
    title: string;
    tags: {
      data: {
        id: number;
        name: string;
        created_at: string;
        updated_at: string;
      }[];
      links: {
        url: string;
        label: string;
        active: boolean;
      }[];
      prev_page_url: string | null;
      next_page_url: string | null;
      per_page: number;
    };
  }

  let { title, tags }: Props = $props();
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <main class="flex grow py-10">
    <div class="w-full px-4 sm:px-6 lg:px-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-base font-semibold text-gray-900">Tags</h1>
          <p class="mt-2 text-sm text-gray-700">Manage your tags here.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button
            type="button"
            class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
          >
            Add Tag
          </button>
        </div>
      </div>
      <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div
            class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
          >
            <table class="min-w-full divide-y divide-gray-300">
              <thead>
                <tr>
                  <th
                    scope="col"
                    class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                  >
                    ID
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                  >
                    Name
                  </th>
                  <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-0">
                    <span class="sr-only">Edit</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                {#each tags.data as tag (tag.id)}
                  <tr>
                    <td
                      class="max-w-16 truncate py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                    >
                      {tag.id}
                    </td>
                    <td
                      class="max-w-16 truncate px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                    >
                      {tag.name}
                    </td>
                    <td
                      class="relative py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-0"
                    >
                      <a href="#" class="text-blue-600 hover:text-blue-900">
                        Edit
                      </a>
                    </td>
                  </tr>
                {/each}
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="mt-8">
        <nav
          class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0"
        >
          <div class="-mt-px flex w-0 flex-1">
            {#if tags.prev_page_url}
              <a
                use:inertia
                href={tags.prev_page_url}
                class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
              >
                <ArrowLeft className="mr-3 size-5 text-gray-400" />
                Previous
              </a>
            {:else}
              <span
                class="inline-flex cursor-not-allowed items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500"
              >
                <ArrowLeft className="mr-3 size-5 text-gray-400" />
                Previous
              </span>
            {/if}
          </div>
          <div class="hidden md:-mt-px md:flex">
            {#each tags.links.slice(1, tags.per_page + 1) as link}
              <a
                use:inertia
                href={link.url}
                class={{
                  "border-indigo-500 text-indigo-600": link.active,
                  "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700":
                    !link.active,
                  "inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium": true,
                }}
              >
                {link.label}
              </a>
            {/each}
            <!--            <span-->
            <!--              class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500"-->
            <!--              >...</span-->
            <!--            >-->
          </div>
          <div class="-mt-px flex w-0 flex-1 justify-end">
            {#if tags.next_page_url}
              <a
                use:inertia
                href={tags.next_page_url}
                class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
              >
                Next
                <ArrowRight className="ml-3 size-5 text-gray-400" />
              </a>
            {:else}
              <span
                class="inline-flex cursor-not-allowed items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500"
              >
                Next
                <ArrowRight className="ml-3 size-5 text-gray-400" />
              </span>
            {/if}
          </div>
        </nav>
      </div>
    </div>
  </main>
</LayoutMain>
