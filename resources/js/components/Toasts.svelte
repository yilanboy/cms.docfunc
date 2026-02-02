<script lang="ts">
  import { fade } from "svelte/transition";
  import CircleCheck from "@/components/icons/CircleCheck.svelte";
  import TriangleAlert from "@/components/icons/TriangleAlert.svelte";
  import CircleAlert from "@/components/icons/CircleAlert.svelte";
  import Info from "@/components/icons/Info.svelte";
  import X from "@/components/icons/X.svelte";
  import { toasts, type ToastProps } from "@/shared/toasts.svelte";
  import { router } from "@inertiajs/svelte";
  import { onMount } from "svelte";

  function getPosition(toast: ToastProps) {
    const toastElement = document.getElementById(`toast-${toast.id}`);
    if (!toastElement) return "translateY(0)";

    const index = toasts.items.indexOf(toast);
    const toastHeight = toastElement.getBoundingClientRect().height;

    return `translateY(${toastHeight * index + 16 * index}px)`;
  }

  const icons = {
    success: CircleCheck,
    danger: CircleAlert,
    warning: TriangleAlert,
    info: Info,
  };

  const colors = {
    success: "text-green-400",
    danger: "text-red-400",
    warning: "text-yellow-400",
    info: "text-blue-400",
  };

  onMount(() => {
    // Listen for Inertia flash messages
    const unsubscribeFlash = router.on("flash", (event) => {
      if (event.detail.flash.toast) {
        toasts.add(event.detail.flash.toast);
      }
    });

    return () => {
      unsubscribeFlash();
    };
  });
</script>

<div id="toasts-container">
  {#each toasts.topItems as toast (toast.id)}
    {@const Icon = icons[toast.type]}
    <div
      transition:fade
      aria-live="assertive"
      id={`toast-${toast.id}`}
      class="pointer-events-none fixed top-4 right-4 z-50 flex min-w-96 items-end transition-all duration-300 ease-in-out sm:items-start"
      style:transform={getPosition(toast)}
    >
      <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
        <div
          class="pointer-events-auto w-full max-w-sm translate-y-0 transform rounded-lg bg-white opacity-100 shadow-lg ring-1 ring-black/5 transition duration-300 ease-out sm:translate-x-0 starting:translate-y-2 starting:opacity-0 starting:sm:translate-x-2 starting:sm:translate-y-0"
        >
          <div class="p-4">
            <div class="flex items-start">
              <div class="shrink-0">
                <Icon className={`size-6 ${colors[toast.type]}`} />
              </div>
              <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900">
                  {toast.message}
                </p>
                {#if toast.description}
                  <p class="mt-1 text-sm text-gray-500">
                    {toast.description}
                  </p>
                {/if}
              </div>
              <div class="ml-4 flex shrink-0">
                <button
                  onclick={() => toasts.destroy(toast.id)}
                  type="button"
                  class="inline-flex rounded-md text-gray-400 hover:text-gray-500 focus:outline-2 focus:outline-offset-2 focus:outline-indigo-600"
                >
                  <span class="sr-only">Close</span>
                  <X className="size-5" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  {/each}
</div>
