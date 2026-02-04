<script lang="ts">
  import AuthenticatedSessionController from "@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController";
  import PasskeyLoginController from "@/actions/App/Http/Controllers/Auth/PasskeyLoginController";
  import ForgotPasswordController from "@/actions/App/Http/Controllers/Auth/ForgotPasswordController";
  import { inertia, page, Form } from "@inertiajs/svelte";
  import ChevronLeft from "@/components/icons/ChevronLeft.svelte";
  import { back } from "@/helpers";
  import InputWithLabel from "@/components/forms/InputWithLabel.svelte";
  import CheckboxWithLabel from "@/components/forms/CheckboxWithLabel.svelte";
  import SubmitButton from "@/components/forms/SubmitButton.svelte";
  import FingerprintPattern from "@/components/icons/FingerprintPattern.svelte";
  import { useForm } from "@inertiajs/svelte";
  import {
    browserSupportsWebAuthn,
    startAuthentication,
  } from "@simplewebauthn/browser";
  import { toasts } from "@/shared/toasts.svelte";
  import GeneratePasskeyAuthenticationOptionsController from "@/actions/App/Http/Controllers/Api/GeneratePasskeyAuthenticationOptionsController";
  import Toasts from "@/components/Toasts.svelte";

  interface SlotFormProps {
    errors: Record<string, string>;
  }

  const passkeyLoginForm = useForm({
    answer: "",
  });

  async function loginWithPasskey() {
    if (!browserSupportsWebAuthn()) {
      toasts.add({
        type: "warning",
        message: "WebAuthn is not supported in this browser.",
        description: "Please try again with a different browser.",
      });

      return;
    }

    const response = await fetch(
      GeneratePasskeyAuthenticationOptionsController().url,
    );
    const optionsJSON = await response.json();

    try {
      $passkeyLoginForm.answer = JSON.stringify(
        await startAuthentication({
          optionsJSON,
        }),
      );
    } catch (error) {
      toasts.add({
        type: "warning",
        message: "Unable to start authentication.",
        description: "Please try again later.",
      });
    }

    $passkeyLoginForm.post(PasskeyLoginController().url);
  }

  let title = "Login";
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<div class="font-source-sans-3 flex min-h-screen w-full flex-col bg-white">
  <Toasts />

  <header class="flex w-full items-center justify-start p-4">
    <button
      type="button"
      onclick={back}
      class="flex cursor-pointer items-center gap-2 rounded-full px-4 py-2 transition duration-150 hover:bg-zinc-100"
    >
      <ChevronLeft className="size-4" />
      <span class="text-lg">Go back</span>
    </button>
  </header>
  <main class="flex w-full grow flex-col justify-center p-6">
    <h2
      class="-mt-12 text-center text-2xl font-bold tracking-tight text-gray-900"
    >
      {$page.props.app.name}
    </h2>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-sm">
      <Form class="space-y-6" action={AuthenticatedSessionController.store()}>
        {#snippet children({ errors }: SlotFormProps)}
          <InputWithLabel
            label="Email address"
            name="email"
            type="email"
            id="email"
            required={true}
            autocomplete="email"
          />

          {#if errors.email}
            <div class="text-red-500">{errors.email}</div>
          {/if}

          <InputWithLabel
            label="Password"
            name="password"
            type="password"
            id="password"
            required={true}
            autocomplete="current-password"
          />

          {#if errors.password}
            <div class="text-red-500">{errors.password}</div>
          {/if}

          <div class="flex items-center justify-between">
            <CheckboxWithLabel
              label="Remember me"
              name="remember"
              id="remember"
            />

            <div class="text-base">
              <a
                use:inertia
                href={ForgotPasswordController.create().url}
                class="font-semibold text-blue-600 hover:text-blue-500"
              >
                Forgot password?
              </a>
            </div>
          </div>

          <div>
            <SubmitButton label="Sign in" className="w-full" />
          </div>
        {/snippet}
      </Form>

      <!-- Passkey login -->
      <div class="relative mt-6">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
          <div class="w-full border-t border-zinc-200"></div>
        </div>
        <div class="relative flex justify-center font-medium">
          <span class="bg-white px-6 text-lg text-zinc-900">or</span>
        </div>
      </div>

      <div class="mt-6">
        <button
          type="button"
          class="flex w-full cursor-pointer items-center justify-center gap-3 rounded-md bg-white px-4 py-2 text-zinc-900 shadow-xs ring-1 ring-zinc-200 ring-inset hover:bg-zinc-50 focus-visible:ring-transparent active:bg-white"
          onclick={loginWithPasskey}
        >
          <FingerprintPattern className="size-5" />
          <span>Use passkey</span>
        </button>
      </div>
    </div>
  </main>
</div>
