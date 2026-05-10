<script setup>
import { cn } from '@/lib/utils';
import { useForm, usePage, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Field, FieldDescription, FieldGroup, FieldLabel, FieldSeparator } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { computed } from 'vue';

const props = defineProps({
  class: {
    type: [Boolean, null, String, Object, Array],
    required: false,
    skipCheck: true,
  },
});

const form = useForm({
    email: ''
});

const submit = () => {
    form.post('/forgot-password');
};

const page = usePage();
const successMessage = computed(() => page.props.flash?.success);
</script>

<template>
  <form @submit.prevent="submit" :class="cn('flex flex-col gap-6', props.class)">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">Lupa Password</h1>
        <p class="text-muted-foreground text-sm text-balance">
          Masukkan email Anda untuk menerima link reset password
        </p>
      </div>

      <!-- Success message -->
      <div v-if="successMessage" class="rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-green-700 text-center">
        {{ successMessage }}
      </div>

      <Field>
        <FieldLabel for="email">Email</FieldLabel>
        <Input
          id="email"
          type="email"
          placeholder="nama@email.com"
          required
          v-model="form.email"
        />
        <small v-if="form.errors.email" class="text-red-500 text-xs">
          {{ form.errors.email }}
        </small>
      </Field>

      <Field>
        <Button type="submit" :disabled="form.processing" class="w-full">
          {{ form.processing ? 'Mengirim...' : 'Kirim Link Reset' }}
        </Button>
      </Field>

      <FieldSeparator></FieldSeparator>

      <Field>
        <FieldDescription class="text-center">
          Ingat password Anda?
          <Link href="/" class="underline underline-offset-4 hover:text-primary">Login</Link>
        </FieldDescription>
      </Field>
    </FieldGroup>
  </form>
</template>
