<script lang="ts">
  import ArrowLeft from "@/components/icons/ArrowLeft.svelte";
  import ArrowRight from "@/components/icons/ArrowRight.svelte";
  import { inertia } from "@inertiajs/svelte";

  interface Props {
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
  }

  let { meta }: Props = $props();
</script>

{#if meta.last_page > 1}
  <nav
    class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0"
  >
    <div class="-mt-px flex w-0 flex-1">
      {#if meta.links[0].url}
        <a
          use:inertia
          href={meta.links[0].url}
          class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
        >
          <ArrowLeft className="mr-3 size-5 text-gray-400" />
          Previous
        </a>
      {:else}
        <span
          class="inline-flex cursor-default items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500"
        >
          <ArrowLeft className="mr-3 size-5 text-gray-400" />
          Previous
        </span>
      {/if}
    </div>
    <div class="hidden md:-mt-px md:flex">
      <!-- Remove the first and last links,  -->
      <!-- which are previous page and next page links -->
      {#each meta.links.slice(1, meta.links.length - 1) as link, index (index + link.label + String(link.url))}
        {#if link.url !== null}
          <a
            use:inertia
            href={link.url}
            class={{
              "border-blue-500 text-blue-600": link.active,
              "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700":
                !link.active,
              "inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium": true,
            }}
          >
            {link.label}
          </a>
        {:else}
          <span
            class="inline-flex cursor-default items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500"
          >
            {link.label}
          </span>
        {/if}
      {/each}
    </div>
    <div class="-mt-px flex w-0 flex-1 justify-end">
      {#if meta.links[meta.links.length - 1].url}
        <a
          use:inertia
          href={meta.links[meta.links.length - 1].url}
          class="inline-flex items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
        >
          Next
          <ArrowRight className="ml-3 size-5 text-gray-400" />
        </a>
      {:else}
        <span
          class="inline-flex cursor-default items-center border-t-2 border-transparent pt-4 pr-1 text-sm font-medium text-gray-500"
        >
          Next
          <ArrowRight className="ml-3 size-5 text-gray-400" />
        </span>
      {/if}
    </div>
  </nav>
{/if}
