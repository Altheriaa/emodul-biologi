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
    name : '',
    email : '',
    password : '',
    password_confirmation: '',
    nim : '',
    angkatan : '',
})

function store() {
    form.post('/register')
}
</script>

<template>
  <form @submit.prevent="store" :class="cn('flex flex-col gap-6', props.class)">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">Buat Akun</h1>
        <p class="text-muted-foreground text-sm text-balance">
          Isi form dibawah untuk membuat akun
        </p>
      </div>
      <Field>
        <FieldLabel for="name"> Nama Lengkap </FieldLabel>
        <Input id="name" type="text" placeholder="John Doe" v-model="form.name" required />
        <small v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</small>
      </Field>
      <Field>
        <FieldLabel for="email"> Email </FieldLabel>
        <Input id="email" type="email" placeholder="m@example.com" v-model="form.email" required />
        <small v-if="form.errors.email" class="text-red-500 text-xs">{{ form.errors.email }}</small>
      </Field>
      <Field>
        <FieldLabel for="password"> Password </FieldLabel>
        <Input id="password" type="password" required v-model="form.password" />
        <small v-if="form.errors.password" class="text-red-500 text-xs">{{ form.errors.password }}</small>
        <FieldDescription>
          Password Minimal 8 Karakter.
        </FieldDescription>
      </Field>
      <Field>
        <FieldLabel for="password_confirmation"> Konfirmasi Password </FieldLabel>
        <Input id="password_confirmation" type="password" required v-model="form.password_confirmation" />
        <small v-if="form.errors.password_confirmation" class="text-red-500 text-xs">{{ form.errors.password_confirmation }}</small>
      </Field>
      <Field>
        <FieldLabel for="nim"> NIM </FieldLabel>
        <Input id="nim" type="number" v-model="form.nim" required />
        <small v-if="form.errors.nim" class="text-red-500 text-xs">{{ form.errors.nim }}</small>
        <FieldDescription>
          ex. 22999999
        </FieldDescription>
      </Field>
      <Field>
        <FieldLabel for="angkatan"> Angkatan </FieldLabel>
        <Input id="angkatan" type="string" placeholder="2024" v-model="form.angkatan" required />
        <small v-if="form.errors.angkatan" class="text-red-500 text-xs">{{ form.errors.angkatan }}</small>
        <FieldDescription>
          ex. 2024
        </FieldDescription>
      </Field>
      <Field>
        <Button type="submit"> Daftar </Button>
      </Field>
      <FieldSeparator></FieldSeparator>
      <Field>
        <FieldDescription class="px-6 text-center">
          Sudah Punya Akun? <Link href="/">Login</Link>
        </FieldDescription>
      </Field>
    </FieldGroup>
  </form>
</template>
