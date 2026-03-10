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
    processing: boolean;
  }

  let { allowRegister }: Props = $props();
</script>

<svelte:head>
  <title>Blog Settings</title>
</svelte:head>

<LayoutMain>
  <main class="flex min-h-[calc(100vh-4rem)] bg-white py-10">
    <div class="w-full px-4 sm:px-6 lg:px-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-lg font-semibold text-gray-900">Blog Settings</h1>
          <p class="mt-2 text-gray-700">
            Manage your blog configuration and preferences.
          </p>
        </div>
      </div>

      <Form method="PUT" action={SettingController.update()}>
        {#snippet children({ errors, processing }: SlotFormProps)}
          <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div
                class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
              >
                <table class="min-w-full divide-y divide-gray-300">
                  <thead>
                    <tr>
                      <th
                        scope="col"
                        class="py-3.5 pr-3 pl-4 text-left font-semibold text-gray-900 sm:pl-0"
                      >
                        Setting
                      </th>
                      <th
                        scope="col"
                        class="hidden px-3 py-3.5 text-left font-semibold text-gray-900 sm:table-cell"
                      >
                        Description
                      </th>
                      <th
                        scope="col"
                        class="px-3 py-3.5 text-left font-semibold text-gray-900"
                      >
                        Value
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr>
                      <td
                        class="py-4 pr-3 pl-4 font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                      >
                        Allow Registration
                      </td>
                      <td
                        class="hidden px-3 py-4 text-gray-500 sm:table-cell"
                      >
                        Allow guests to register an account on the blog.
                      </td>
                      <td class="px-3 py-4 whitespace-nowrap">
                        <input
                          type="hidden"
                          name="allow_register"
                          value="0"
                        />
                        <CheckboxWithLabel
                          label="Enabled"
                          name="allow_register"
                          id="allow_register"
                          value="1"
                          checked={allowRegister}
                        />
                        {#if errors.allow_register}
                          <div class="mt-1 text-sm text-red-500">
                            {errors.allow_register}
                          </div>
                        {/if}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="mt-6 flex justify-end">
            <SubmitButton label="Save" {processing} />
          </div>
        {/snippet}
      </Form>
    </div>
  </main>
</LayoutMain>
