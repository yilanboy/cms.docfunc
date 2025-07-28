<script lang="ts">
  import { inertia } from "@inertiajs/svelte";
  import AuthenticatedSessionController from "@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController";
  import RegisterController from "@/actions/App/Http/Controllers/Auth/RegisterController";

  interface Props {
    title: string;
  }

  let { title }: Props = $props();
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<div class="font-source-sans-3 flex min-h-screen w-full flex-col bg-zinc-50">
  <header class="flex w-full items-center justify-end gap-6 p-6">
    <a
      use:inertia
      href={AuthenticatedSessionController.create().url}
      class="text-lg transition duration-150 hover:text-gray-600"
    >
      Login
    </a>

    <a
      use:inertia
      href={RegisterController.create().url}
      class="text-lg transition duration-150 hover:text-gray-600"
    >
      Register
    </a>
  </header>

  <main class="flex w-full grow flex-col items-center justify-center p-6">
    <h1 class="text-4xl font-semibold">
      Welcome! This is a simple example to use Inertia.js with Svelte and
      Laravel
    </h1>
  </main>
</div>
