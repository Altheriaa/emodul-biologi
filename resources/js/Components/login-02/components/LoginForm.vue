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
    email : '',
    password : ''
})

function store() {
    router.post('/login', post)
}

const page = usePage();
</script>

<template>
  <form @submit.prevent="store" :class="cn('flex flex-col gap-6', props.class)">
    <FieldGroup>
      <div class="flex flex-col items-center gap-1 text-center">
        <h1 class="text-2xl font-bold">Login to your account</h1>
        <p class="text-muted-foreground text-sm text-balance">
          Enter your email below to login to your account
        </p>
      </div>
      <Field>
        <FieldLabel for="email"> Email </FieldLabel>
        <Input id="email" type="email" placeholder="m@example.com" required v-model="post.email"/>
      </Field>
      <Field>
        <div class="flex items-center">
          <FieldLabel for="password"> Password </FieldLabel>
          <Link 
            href="/forgot-password"
            class="ml-auto text-sm underline-offset-4 hover:underline"
          >
            Forgot your password?
          </Link>
        </div>
        <Input id="password" type="password" required v-model="post.password"/>
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
