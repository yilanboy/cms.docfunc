<script lang="ts">
  import ResetPasswordController from "@/actions/App/Http/Controllers/Auth/ResetPasswordController";
  import { Form } from "@inertiajs/svelte";
  import InputWithLabel from "@/components/forms/InputWithLabel.svelte";

  interface Props {
    token: string;
    email: string;
  }

  interface FormSlotProps {
    errors: Record<string, string>;
    invalid: (field: string) => boolean;
    validate: (field: string) => void;
  }

  let title = "Reset Password";

  let { token, email }: Props = $props();
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<div class="font-source-sans-3 flex min-h-screen w-full flex-col bg-white">
  <main class="flex w-full grow flex-col justify-center p-6">
    <h2 class="text-center text-2xl/9 font-bold tracking-tight text-gray-900">
      Reset Your Password
    </h2>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <Form
        class="space-y-6"
        method="post"
        action={ResetPasswordController.update()}
      >
        {#snippet children({ errors, invalid, validate }: FormSlotProps)}
          <input type="hidden" name="token" value={token} />

          <input type="hidden" name="email" value={email} />

          <InputWithLabel
            id="password"
            type="password"
            name="password"
            label="Password"
            autocomplete="current-password"
            required
            onchange={() => validate("password")}
          />

          {#if invalid("password")}
            <div class="text-red-500">{errors.password}</div>
          {/if}

          <InputWithLabel
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            label="Confirm Password"
            autocomplete="current-password"
            required
          />

          <div>
            <button
              type="submit"
              class="flex w-full cursor-pointer justify-center rounded-md bg-blue-600 px-3 py-1.5 text-base font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
            >
              Update Password
            </button>
          </div>
        {/snippet}
      </Form>
    </div>
  </main>
</div>
