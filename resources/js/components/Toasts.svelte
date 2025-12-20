<script lang="ts">
  import { onMount } from "svelte";
  import { fly } from "svelte/transition";
  import X from "@/components/icons/X.svelte";
  import CircleCheck from "@/components/icons/CircleCheck.svelte";
  import Info from "@/components/icons/Info.svelte";
  import TriangleAlert from "@/components/icons/TriangleAlert.svelte";
  import CircleAlert from "@/components/icons/CircleAlert.svelte";
  import { router } from "@inertiajs/svelte";

  type ToastsPosition =
    | "top-right"
    | "top-left"
    | "top-center"
    | "bottom-right"
    | "bottom-left"
    | "bottom-center";

  type ToastsLayout = "default" | "expanded";

  type ToastsType = "success" | "info" | "warning" | "danger" | "default";

  interface messageToastProps {
    id: string;
    type: ToastsType;
    message: string;
    description: string;
    position?: ToastsPosition;
  }

  interface htmlToastProps {
    id: string;
    type: ToastsType;
    html: string;
    position?: ToastsPosition;
  }

  type ToastProps = messageToastProps | htmlToastProps;

  let position: ToastsPosition = $state("top-center");
  let layout: ToastsLayout = $state("default");
  let paddingBetweenToasts = $state(16);
  let toasts: ToastProps[] = $state([]);
  let toastsHovered = $state(false);
  let expanded = $state(false);
  let toastsContainer: HTMLUListElement;

  // Hover-to-pause: timer management
  const AUTO_DISMISS_MS = 6000;

  interface ToastTimer {
    timeoutId: number; // -1 when paused/not scheduled
    start: number; // timestamp when the current timeout was (re)started
    remaining: number; // ms remaining until auto-dismiss
  }

  const toastTimers = new Map<string, ToastTimer>();

  function deleteToastWithId(id: string) {
    // Clear any outstanding timer for this toast
    const timer = toastTimers.get(id);

    if (timer) {
      if (timer.timeoutId >= 0) {
        clearTimeout(timer.timeoutId);
      }
      toastTimers.delete(id);
    }

    for (let i = 0; i < toasts.length; i++) {
      if (toasts[i].id === id) {
        toasts.splice(i, 1);

        break;
      }
    }
  }

  function burnToast(id: string) {
    let burnToastElement = document.getElementById(id);

    if (!burnToastElement) return;

    if (toasts.length == 1) {
      if (layout == "default") {
        expanded = false;
      }
    }

    burnToastElement.style.opacity = "0";

    setTimeout(function () {
      // Ensure the timer is cleared when manually dismissed
      const timer = toastTimers.get(id);
      if (timer) {
        if (timer.timeoutId >= 0) {
          clearTimeout(timer.timeoutId);
        }
        toastTimers.delete(id);
      }
      deleteToastWithId(id);

      requestAnimationFrame(stackToasts);
    }, 300);
  }

  // Schedule auto-dismiss for a toast
  function scheduleAutoDismiss(id: string, duration: number) {
    // Clear any existing timer first
    const existing = toastTimers.get(id);
    if (existing && existing.timeoutId >= 0) {
      clearTimeout(existing.timeoutId);
    }

    const start = Date.now();
    const timeoutId = window.setTimeout(
      () => {
        toastTimers.delete(id);
        deleteToastWithId(id);
      },
      Math.max(0, duration),
    );

    toastTimers.set(id, { timeoutId, start, remaining: Math.max(0, duration) });
  }

  // Pause all toasts (clear timeouts and capture remaining time)
  function pauseAllToasts() {
    const now = Date.now();
    for (let i = 0; i < toasts.length; i++) {
      const id = toasts[i].id;
      const t = toastTimers.get(id);
      if (!t) {
        // If no timer (e.g., just added while already hovered), initializes paused state
        toastTimers.set(id, {
          timeoutId: -1,
          start: now,
          remaining: AUTO_DISMISS_MS,
        });
        continue;
      }
      const elapsed = Math.max(0, now - t.start);
      const remaining = Math.max(0, t.remaining - elapsed);
      if (t.timeoutId >= 0) {
        clearTimeout(t.timeoutId);
      }
      toastTimers.set(id, { timeoutId: -1, start: now, remaining });
    }
  }

  // Resume all toasts with their remaining time
  function resumeAllToasts() {
    const now = Date.now();
    for (let i = 0; i < toasts.length; i++) {
      const id = toasts[i].id;
      const t = toastTimers.get(id);
      if (!t) {
        // If somehow missing, start a fresh countdown
        scheduleAutoDismiss(id, AUTO_DISMISS_MS);
        continue;
      }
      if (t.remaining <= 0) {
        // Time already elapsed while paused; remove immediately
        toastTimers.delete(id);
        deleteToastWithId(id);
        continue;
      }
      // Schedule with remaining time
      const timeoutId = window.setTimeout(() => {
        toastTimers.delete(id);
        deleteToastWithId(id);
      }, t.remaining);
      toastTimers.set(id, { timeoutId, start: now, remaining: t.remaining });
    }
  }

  function stackToasts() {
    positionToasts();
    calculateHeightOfToastsContainer();

    setTimeout(function () {
      calculateHeightOfToastsContainer();
    }, 300);
  }

  function positionToasts() {
    // If there is no toast, don't do anything'
    if (toasts.length == 0) return;

    let topToast = document.getElementById(toasts[0].id);
    if (!topToast) return;

    topToast.style.zIndex = (100).toString();

    // if toasts are in expanded mode, set top to 0
    if (expanded) {
      if (position.startsWith("bottom")) {
        topToast.style.top = "auto";
        topToast.style.bottom = "0";
      } else {
        topToast.style.top = "0";
      }
    }

    if (toasts.length == 1) return;

    let middleToast = document.getElementById(toasts[1].id);
    if (!middleToast) return;

    middleToast.style.zIndex = (90).toString();

    if (expanded) {
      let middleToastPosition =
        topToast.getBoundingClientRect().height + paddingBetweenToasts + "px";

      if (position.startsWith("bottom")) {
        middleToast.style.top = "auto";
        middleToast.style.bottom = middleToastPosition;
      } else {
        middleToast.style.top = middleToastPosition;
      }

      middleToast.style.scale = "100%";
      middleToast.style.transform = "translateY(0)";
    } else {
      middleToast.style.scale = "94%";

      if (position.startsWith("bottom")) {
        middleToast.style.transform = "translateY(-16px)";
      } else {
        middleToast.style.transform = "translateY(16px)";
      }
    }

    if (toasts.length == 2) return;

    let bottomToast = document.getElementById(toasts[2].id);
    if (!bottomToast) return;

    bottomToast.style.zIndex = (80).toString();

    if (expanded) {
      let bottomToastPosition =
        topToast.getBoundingClientRect().height +
        paddingBetweenToasts +
        middleToast.getBoundingClientRect().height +
        paddingBetweenToasts +
        "px";

      if (position.startsWith("bottom")) {
        bottomToast.style.top = "auto";
        bottomToast.style.bottom = bottomToastPosition;
      } else {
        bottomToast.style.top = bottomToastPosition;
      }

      bottomToast.style.scale = "100%";
      bottomToast.style.transform = "translateY(0)";
    } else {
      bottomToast.style.scale = "88%";

      if (position.startsWith("bottom")) {
        bottomToast.style.transform = "translateY(-32px)";
      } else {
        bottomToast.style.transform = "translateY(32px)";
      }
    }

    if (toasts.length === 3) return;

    let burnToast = document.getElementById(toasts[3].id);

    if (!burnToast) return;

    burnToast.style.zIndex = (70).toString();

    if (expanded) {
      let burnToastPosition =
        topToast.getBoundingClientRect().height +
        paddingBetweenToasts +
        middleToast.getBoundingClientRect().height +
        paddingBetweenToasts +
        bottomToast.getBoundingClientRect().height +
        paddingBetweenToasts +
        "px";

      if (position.startsWith("bottom")) {
        burnToast.style.top = "auto";
        burnToast.style.bottom = burnToastPosition;
      } else {
        burnToast.style.top = burnToastPosition;
      }

      burnToast.style.scale = "100%";
      burnToast.style.transform = "translateY(0)";
    } else {
      burnToast.style.scale = "82%";
      burnToast.style.transform = "translateY(48px)";
    }

    toasts.pop();

    return;
  }

  // Reset bottom when mouse hovers toasts container
  function resetBottom() {
    for (let i = 0; i < toasts.length; i++) {
      if (document.getElementById(toasts[i].id)) {
        let toastElement = document.getElementById(toasts[i].id);
        if (!toastElement) continue;
        toastElement.style.bottom = "0";
      }
    }
  }

  // Reset top when mouse hovers toasts container
  function resetTop() {
    for (let i = 0; i < toasts.length; i++) {
      if (document.getElementById(toasts[i].id)) {
        let toastElement = document.getElementById(toasts[i].id);
        if (!toastElement) continue;
        toastElement.style.top = "0";
      }
    }
  }

  function calculateHeightOfToastsContainer() {
    if (toasts.length == 0) {
      toastsContainer.style.height = "0";

      return;
    }

    let lastToast = toasts[toasts.length - 1];
    let lastToastElement = document.getElementById(lastToast.id);

    if (!lastToastElement) return;

    let lastToastRectangle = lastToastElement.getBoundingClientRect();

    let firstToast = toasts[0];

    let firstToastElement = document.getElementById(firstToast.id);

    if (!firstToastElement) return;

    let firstToastRectangle = firstToastElement.getBoundingClientRect();

    if (toastsHovered) {
      if (position.startsWith("bottom")) {
        toastsContainer.style.height =
          firstToastRectangle.top +
          firstToastRectangle.height -
          lastToastRectangle.top +
          "px";
      } else {
        toastsContainer.style.height =
          lastToastRectangle.top +
          lastToastRectangle.height -
          firstToastRectangle.top +
          "px";
      }
    } else {
      toastsContainer.style.height = firstToastRectangle.height + "px";
    }
  }

  // Change toasts layout to expanded or default
  function onSetToastsLayout(event: CustomEvent) {
    layout = event.detail.layout;
    expanded = layout === "expanded";
    stackToasts();
  }

  // Start to show toasts, add a toast to toasts
  function onShowToast(event: CustomEvent<ToastProps>) {
    event.stopPropagation();

    const detail = event.detail;

    if (detail.position) {
      position = detail.position;
    }

    // Narrow the union and construct the correct toast shape
    let toast: ToastProps;

    if ("html" in detail) {
      toast = {
        id: "toast-" + crypto.randomUUID(),
        type: detail.type,
        html: detail.html,
        position: detail.position,
      } satisfies htmlToastProps;
    } else {
      toast = {
        id: "toast-" + crypto.randomUUID(),
        type: detail.type,
        message: detail.message,
        description: detail.description,
        position: detail.position,
      } satisfies messageToastProps;
    }

    toasts.unshift(toast);

    requestAnimationFrame(stackToasts);

    // Auto-dismiss handling with hover-to-pause support
    if (toastsHovered) {
      // Initialize the paused timer state; will start on mouse leave
      toastTimers.set(toast.id, {
        timeoutId: -1,
        start: Date.now(),
        remaining: AUTO_DISMISS_MS,
      });
    } else {
      scheduleAutoDismiss(toast.id, AUTO_DISMISS_MS);
    }
  }

  function onMouseEnterToastsContainer() {
    toastsHovered = true;

    // Pause countdowns while hovered
    pauseAllToasts();

    if (layout !== "default") return;

    if (position.startsWith("bottom")) {
      resetBottom();
    } else {
      resetTop();
    }

    expanded = true;
    stackToasts();
  }

  function onMouseLeaveToastsContainer() {
    toastsHovered = false;

    // Resume countdowns after hover ends
    resumeAllToasts();

    if (layout !== "default") return;

    if (position.startsWith("bottom")) {
      resetBottom();
    } else {
      resetTop();
    }

    expanded = false;
    stackToasts();
  }

  function toastFlyInY(): number {
    if (position.startsWith("bottom")) {
      return 50;
    }

    return -50;
  }

  function toastFlyOutY(): number {
    // y 如果是正整數，代表由下往上，負數代表由上往下
    if (position.startsWith("bottom")) {
      if (expanded) {
        return -50;
      }

      return 50;
    }

    if (expanded) {
      return 50;
    }

    return -50;
  }

  onMount(() => {
    if (layout == "expanded") {
      expanded = true;
    }

    stackToasts();

    const listenFlash = router.on("flash", (event) => {
      if (event.detail.flash.toast) {
        window.dispatchEvent(
          new CustomEvent("show-toast", {
            detail: {
              ...event.detail.flash.toast,
              position: "top-right",
            },
          }),
        );
      }
    });

    return () => {
      listenFlash();
    };
  });
</script>

<svelte:window
  onset-toasts-layout={onSetToastsLayout}
  onshow-toast={onShowToast}
/>

<ul
  class={{
    "top-0 right-0 sm:mt-6 sm:mr-6": position === "top-right",
    "top-0 left-0 sm:mt-6 sm:ml-6": position === "top-left",
    "top-0 left-1/2 -translate-x-1/2 sm:mt-6": position === "top-center",
    "right-0 bottom-0 sm:mr-6 sm:mb-6": position === "bottom-right",
    "bottom-0 left-0 sm:mb-6 sm:ml-6": position === "bottom-left",
    "bottom-0 left-1/2 -translate-x-1/2 sm:mb-6": position === "bottom-center",
    "fixed z-99 block w-full sm:max-w-xs": true,
  }}
  onmouseenter={onMouseEnterToastsContainer}
  onmouseleave={onMouseLeaveToastsContainer}
  bind:this={toastsContainer}
>
  {#each toasts as toast (toast.id)}
    <li
      in:fly={{
        y: toastFlyInY(),
        duration: 300,
        opacity: 1,
      }}
      out:fly={{
        y: toastFlyOutY(),
        duration: 300,
        opacity: 0,
      }}
      id={toast.id}
      class={{
        "toast-no-description": !toast.description,
        "absolute w-full duration-300 ease-out select-none sm:max-w-xs": true,
      }}
    >
      <span
        class={{
          "p-4": !toast.html,
          "p-0": toast.html,
          "group relative flex w-full flex-col items-start border border-gray-100 bg-white shadow-[0_5px_15px_-3px_rgb(0_0_0_/_0.08)] transition-all duration-300 ease-out sm:max-w-xs sm:rounded-md": true,
        }}
      >
        {#if toast.html}
          <div>{@html toast.html}</div>
        {:else}
          <div class="relative">
            <div
              class={{
                "text-green-500": toast.type === "success",
                "text-blue-500": toast.type === "info",
                "text-orange-400": toast.type === "warning",
                "text-red-500": toast.type === "danger",
                "text-gray-800": toast.type === "default",
                "flex items-center": true,
              }}
            >
              {#if toast.type === "success"}
                <CircleCheck className="mr-2 -ml-1 size-4" />
              {:else if toast.type === "info"}
                <Info className="mr-2 -ml-1 size-4" />
              {:else if toast.type === "warning"}
                <TriangleAlert className="mr-2 -ml-1 size-4" />
              {:else if toast.type === "danger"}
                <CircleAlert className="mr-2 -ml-1 size-4" />
              {/if}
              <p class="text-base leading-none font-medium text-gray-800">
                {toast.message}
              </p>
            </div>

            {#if toast.description}
              <p
                class={{
                  "pl-5.5": toast.type !== "default",
                  "mt-1.5 text-sm leading-none opacity-70": true,
                }}
              >
                {toast.description}
              </p>
            {/if}
          </div>
        {/if}

        <button
          aria-label="Dismiss notification"
          type="button"
          onclick={() => {
            burnToast(toast.id);
          }}
          class={{
            "top-1/2 -translate-y-1/2": !toast.description && !toast.html,
            "top-0 mt-2.5": toast.description || toast.html,
            "absolute right-0 mr-2.5 cursor-pointer rounded-full p-1.5 text-gray-400 opacity-0 transition duration-100 ease-in-out group-hover:opacity-100 hover:bg-gray-50 hover:text-gray-500": true,
          }}
        >
          <X className="size-4" />
        </button>
      </span>
    </li>
  {/each}
</ul>
