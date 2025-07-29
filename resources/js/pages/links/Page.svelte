<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";

  interface Props {
    title: string;
    links: {
      id: number;
      title: string;
      link: string;
      description: string;
      created_at: string;
      updated_at: string;
    }[];
  }

  let { title, links }: Props = $props();
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <main class="flex grow py-10">
    <div class="w-full px-4 sm:px-6 lg:px-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-base font-semibold text-gray-900">Links</h1>
          <p class="mt-2 text-sm text-gray-700">
            Links to recommended resources
          </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
          <button
            type="button"
            class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
          >
            Add link
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
                    Title
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                  >
                    Link
                  </th>
                  <th
                    scope="col"
                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                  >
                    Description
                  </th>
                  <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-0">
                    <span class="sr-only">Edit</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                {#each links as link (link.id)}
                  <tr>
                    <td
                      class="max-w-16 truncate py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                    >
                      {link.id}
                    </td>
                    <td
                      class="max-w-16 truncate px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                    >
                      {link.title}
                    </td>
                    <td
                      class="max-w-16 truncate px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                    >
                      {link.link}
                    </td>
                    <td
                      class="max-w-16 truncate px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                    >
                      {link.description}
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
    </div>
  </main>
</LayoutMain>
