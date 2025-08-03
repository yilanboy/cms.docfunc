<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Pagination from "@/components/Pagination.svelte";

  interface Props {
    title: string;
    tags: {
      data: {
        id: number;
        name: string;
        created_at: string;
        updated_at: string;
      }[];
      meta: {
        current_page: number;
        last_page: number;
        links: {
          url: string | null;
          label: string;
          active: boolean;
        }[];
        per_page: number;
      };
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
        <Pagination meta={tags.meta} />
      </div>
    </div>
  </main>
</LayoutMain>
