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
import { reactive } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';

const props = defineProps({
  class: {
    type: [Boolean, null, String, Object, Array],
    required: false,
    skipCheck: true,
  },
});

const post = reactive({
    name : '',
    email : '',
    password : '',
    nim : '',
    angkatan : '',
    
})

function store() {
    router.post('/register', post)
}
</script>

<template>
  <form @submit.prevent="store" :class="cn('flex flex-col gap-6', props.class)">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">Create your account</h1>
        <p class="text-muted-foreground text-sm text-balance">
          Fill in the form below to create your account
        </p>
      </div>
      <Field>
        <FieldLabel for="name"> Nama Lengkap </FieldLabel>
        <Input id="name" type="text" placeholder="John Doe" v-model="post.name" required />
      </Field>
      <Field>
        <FieldLabel for="email"> Email </FieldLabel>
        <Input id="email" type="email" placeholder="m@example.com" v-model="post.email" required />
      </Field>
      <Field>
        <FieldLabel for="password"> Password </FieldLabel>
        <Input id="password" type="password" required v-model="post.password" />
        <FieldDescription>
          Password Minimal 8 Karakter.
        </FieldDescription>
      </Field>
      <Field>
        <FieldLabel for="nim"> NIM </FieldLabel>
        <Input id="nim" type="number" v-model="post.nim" required />
        <FieldDescription>
          ex. 22999999
        </FieldDescription>
      </Field>
      <Field>
        <FieldLabel for="angkatan"> Angkatan </FieldLabel>
        <Input id="angkatan" type="string" placeholder="2024" v-model="post.angkatan" required />
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
          Sudah Punya Akun? <Link href="/login">Login</Link>
        </FieldDescription>
      </Field>
    </FieldGroup>
  </form>
</template>
