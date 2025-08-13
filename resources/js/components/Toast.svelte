<script lang="ts">
  import { page } from "@inertiajs/svelte";
  import CircleCheck from "@/components/icons/CircleCheck.svelte";
  import { fly } from "svelte/transition";
  import CircleX from "@/components/icons/CircleX.svelte";

  let showToast = $state(false);
  let timeoutId: ReturnType<typeof setTimeout>;

  $effect(() => {
    if ($page.props.flash.success || $page.props.flash.error) {
      showToast = true;

      clearTimeout(timeoutId);

      timeoutId = setTimeout(() => {
        showToast = false;
      }, 3000);
    }
  });
</script>

{#if showToast && $page.props.flash.success}
  <div
    transition:fly={{ y: -100 }}
    class="fixed top-4 right-1/2 left-1/2 z-50 -translate-x-1/2 rounded-md bg-green-50 p-4 sm:w-full sm:max-w-sm"
  >
    <div class="flex items-center">
      <div class="shrink-0">
        <CircleCheck className="size-5 text-green-400" />
      </div>
      <div class="ml-3">
        <p class="text-base font-medium text-green-700">
          {$page.props.flash.success}
        </p>
      </div>
    </div>
  </div>
{/if}

{#if showToast && $page.props.flash.error}
  <div
    transition:fly={{ y: -100 }}
    class="fixed top-4 right-1/2 left-1/2 z-50 -translate-x-1/2 rounded-md bg-red-50 p-4 sm:w-full sm:max-w-sm"
  >
    <div class="flex items-center">
      <div class="shrink-0">
        <CircleX className="size-5 text-red-400" />
      </div>
      <div class="ml-3">
        <p class="text-base font-medium text-red-700">
          {$page.props.flash.error}
        </p>
      </div>
    </div>
  </div>
{/if}
