<script lang="ts">
  import LayoutMain from "@/components/layouts/main/LayoutMain.svelte";
  import { Form } from "@inertiajs/svelte";
  import SettingController from "@/actions/App/Http/Controllers/Blog/SettingController";
  import CheckboxWithLabel from "@/components/forms/CheckboxWithLabel.svelte";
  import SubmitButton from "@/components/forms/SubmitButton.svelte";

  interface Props {
    allowRegister: boolean;
  }

  interface SlotFormProps {
    errors: Record<string, string>;
  }

  let { allowRegister }: Props = $props();
</script>

<svelte:head>
  <title>Blog Settings</title>
</svelte:head>

<LayoutMain>
  <main class="grow py-10">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-4 gap-6">
        <div class="col-span-4">
          <div class="sm:w-full sm:max-w-sm">
            <Form
              class="space-y-6"
              method="PUT"
              action={SettingController.update()}
            >
              {#snippet children({ errors }: SlotFormProps)}
                <!-- Hidden input ensures false is sent when checkbox is unchecked -->
                <input type="hidden" name="allow_register" value="0" />
                <CheckboxWithLabel
                  label="Allow guest registration"
                  name="allow_register"
                  id="allow_register"
                  value="1"
                  checked={allowRegister}
                />

                {#if errors.allow_register}
                  <div class="text-red-500">{errors.allow_register}</div>
                {/if}

                <div>
                  <SubmitButton label="Save Checkbox" />
                </div>
              {/snippet}
            </Form>
          </div>
        </div>
      </div>
    </div>
  </main>
</LayoutMain>
