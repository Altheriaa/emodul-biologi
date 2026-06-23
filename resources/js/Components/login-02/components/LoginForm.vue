<script setup>
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import {
  Field,
  FieldDescription,
  FieldGroup,
  FieldLabel,
  FieldSeparator,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { useForm, usePage, Link } from '@inertiajs/vue3';

const props = defineProps({
  class: {
    type: [Boolean, null, String, Object, Array],
    required: false,
    skipCheck: true,
  },
});

const form = useForm({
    email : '',
    password : ''
})

function store() {
    form.post('/login')
}

const page = usePage();
</script>

<template>
  <form @submit.prevent="store" :class="cn('flex flex-col gap-6', props.class)">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">Masuk ke Akun Anda</h1>
        <p class="text-muted-foreground text-sm text-balance">
          Masukkan Email Anda untuk Masuk ke Akun Anda
        </p>
      </div>
      <Field>
        <FieldLabel for="email"> Email </FieldLabel>
        <Input id="email" type="email" placeholder="m@example.com" required v-model="form.email"/>
        <small v-if="form.errors.email" class="text-red-500 text-xs">{{ form.errors.email }}</small>
      </Field>
      <Field>
        <div class="flex items-center">
          <FieldLabel for="password"> Password </FieldLabel>
          <Link 
            href="/forgot-password"
            class="ml-auto text-sm underline-offset-4 hover:underline"
          >
            Lupa Password Anda?
          </Link>
        </div>
        <Input id="password" type="password" required v-model="form.password"/>
        <small v-if="form.errors.password" class="text-red-500 text-xs">{{ form.errors.password }}</small>
      </Field>
      <Field>
        <Button type="submit"> Login </Button>
      </Field>
      <FieldSeparator></FieldSeparator>
      <Field>
        <FieldDescription class="text-center">
          Belum Punya Akun?
          <Link href="/register">Daftar</Link>
        </FieldDescription>
      </Field>
    </FieldGroup>
  </form>
</template>
