<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import Navigation from "@/pages/settings/partials/Navigation.svelte";
  import { page, Form } from "@inertiajs/svelte";
  import ProfileController from "@/actions/App/Http/Controllers/Settings/ProfileController";
  import InputWithLabel from "@/components/forms/InputWithLabel.svelte";
  import SubmitButton from "@/components/forms/SubmitButton.svelte";

  interface Props {
    title: string;
  }

  interface SlotFormProps {
    errors: Record<string, string>;
  }

  let { title }: Props = $props();

  const user = $page.props.auth.user;
</script>

<svelte:head>
  <title>{title}</title>
</svelte:head>

<LayoutMain>
  <main class="grow py-10">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-4 gap-6">
        <div class="col-span-4">
          <h2 class="text-2xl font-semibold">Profile information</h2>
          <p class="text-zinc-500">Update your name and email address</p>
        </div>

        <div class="col-span-4 lg:col-span-1">
          <Navigation />
        </div>

        <div class="col-span-4 lg:col-span-3">
          <div class="sm:w-full sm:max-w-sm">
            <Form
              class="space-y-6"
              method="post"
              action={ProfileController.update()}
            >
              {#snippet children({ errors }: SlotFormProps)}
                <InputWithLabel
                  label="Name"
                  name="name"
                  type="text"
                  id="name"
                  value={user.name}
                  required={true}
                  autocomplete="name"
                />

                {#if errors.name}
                  <div class="text-red-500">{errors.name}</div>
                {/if}

                <InputWithLabel
                  label="Email address"
                  name="email"
                  type="email"
                  id="email"
                  value={user.email}
                  required={true}
                  autocomplete="email"
                />

                {#if errors.email}
                  <div class="text-red-500">{errors.email}</div>
                {/if}

                <div>
                  <SubmitButton label="Save" />
                </div>
              {/snippet}
            </Form>
          </div>
        </div>
      </div>
    </div>
  </main>
</LayoutMain>
