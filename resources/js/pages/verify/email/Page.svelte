<script lang="ts">
  import { page, router } from "@inertiajs/svelte";
  import { fly } from "svelte/transition";
  import CircleCheck from "@/components/icons/CircleCheck.svelte";

  let title = "Verify Email";

  const COUNTDOWN_SECONDS = 60;

  let countdown = $state(COUNTDOWN_SECONDS);
  let showCountdown = $state(false);
  let countdownMessage = $derived(`Wait ${countdown.toString()} seconds`);

  function startCountdown() {
    showCountdown = true;

    let timer = setInterval(() => {
      countdown = countdown - 1;

      if (countdown <= 0) {
        clearInterval(timer);

        showCountdown = false;
        countdown = COUNTDOWN_SECONDS;
      }
    }, 1000);
  }

  function resend() {
    router.visit("/email/verify", {
      method: "post",
      preserveState: true,
      onSuccess: () => startCountdown(),
    });
  }
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<div
  class="font-source-sans-3 relative flex min-h-screen w-full flex-col bg-white"
>
  {#if $page.props.flash.success && showCountdown}
    <div
      transition:fly={{ y: -100 }}
      class="absolute top-4 right-1/2 left-1/2 -translate-x-1/2 rounded-md bg-green-50 p-4 sm:w-full sm:max-w-sm"
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

  <main class="flex w-full grow flex-col items-center justify-center gap-6 p-6">
    <div class="px-4 py-5 sm:p-6">
      <h2 class="text-2xl font-semibold text-zinc-900">
        Welcome to join us! We are almost there. 😊
      </h2>

      <div class="mt-2 max-w-xl text-base text-zinc-500">
        <p>
          Please check your email to verify your account. If you didn't receive
          the verification email, click the button below to resend.
        </p>
      </div>

      <div class="mt-5">
        <button
          onclick={resend}
          type="button"
          class="inline-flex cursor-pointer items-center rounded-md bg-blue-600 px-3 py-2 text-base font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 disabled:cursor-default disabled:bg-zinc-400"
          disabled={showCountdown}
        >
          {showCountdown ? countdownMessage : "Resend"}
        </button>
      </div>
    </div>
  </main>
</div>
