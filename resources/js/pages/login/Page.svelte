<script lang="ts">
  import AuthenticatedSessionController from "@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController";
  import ForgotPasswordController from "@/actions/App/Http/Controllers/Auth/ForgotPasswordController";
  import { inertia, page, Form } from "@inertiajs/svelte";
  import Check from "@/components/icons/Check.svelte";
  import ChevronLeft from "@/components/icons/ChevronLeft.svelte";
  import { back } from "@/helpers";

  let title = "Login";
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<div class="font-source-sans-3 flex min-h-screen w-full flex-col bg-white">
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
    <h2 class="text-center text-2xl font-bold tracking-tight text-gray-900">
      {$page.props.app.name}
    </h2>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <Form class="space-y-6" action={AuthenticatedSessionController.store()}>
        {#snippet children({
          errors,
        }: {
          errors: { email: String; password: String };
        })}
          <div>
            <label
              for="email"
              class="block text-base font-medium text-gray-900"
            >
              Email address
            </label>
            <div class="mt-2">
              <input
                type="email"
                name="email"
                id="email"
                autocomplete="email"
                required
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
              />
            </div>
          </div>

          {#if errors.email}
            <div class="text-red-500">{errors.email}</div>
          {/if}

          <div>
            <label
              for="password"
              class="block text-base font-medium text-gray-900"
            >
              Password
            </label>
            <div class="mt-2">
              <input
                type="password"
                name="password"
                id="password"
                autocomplete="current-password"
                required
                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600"
              />
            </div>
          </div>

          {#if errors.password}
            <div class="text-red-500">{errors.password}</div>
          {/if}

          <div class="flex items-center justify-between">
            <div class="flex gap-3">
              <div class="flex h-6 shrink-0 items-center">
                <div class="group grid size-5 grid-cols-1">
                  <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                    class="col-start-1 row-start-1 appearance-none rounded-sm border border-gray-300 bg-white checked:border-blue-600 checked:bg-blue-600 indeterminate:border-blue-600 indeterminate:bg-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"
                  />

                  <Check
                    className="pointer-events-none col-start-1 row-start-1 size-4 self-center justify-self-center text-white group-has-disabled:stroke-gray-950/25"
                  />
                </div>
              </div>
              <label for="remember" class="block text-base text-gray-900">
                Remember me
              </label>
            </div>

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
            <button
              type="submit"
              class="flex w-full cursor-pointer justify-center rounded-md bg-blue-600 px-3 py-1.5 text-base font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
            >
              Sign in
            </button>
          </div>
        {/snippet}
      </Form>
    </div>
  </main>
</div>
