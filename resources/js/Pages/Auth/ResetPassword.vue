<script setup>
import { GalleryVerticalEnd } from "lucide-vue-next"
import { useForm } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field'
import { Input } from '@/components/ui/input'

const props = defineProps({
    email: String,
    token: String,
})

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post('/reset-password', {
        onFinish: () => {
            form.reset('password', 'password_confirmation')
        }
    })
}
</script>

<template>
  <div class="grid min-h-svh lg:grid-cols-2">
    <div class="flex flex-col gap-4 p-6 md:p-10">
      <div class="flex justify-center gap-2 md:justify-start">
        <a href="#" class="flex items-center gap-2 font-medium">
          <div class="flex size-6 items-center justify-center rounded-md">
            <img src="/asset/Logo Pals.png" alt="Logo">
          </div>
          E-Plans.
        </a>
      </div>
      <div class="flex flex-1 items-center justify-center">
        <div class="w-full max-w-xs">
          <form @submit.prevent="submit" class="flex flex-col gap-6">
            <FieldGroup>
              <div class="flex flex-col items-center gap-1 text-center">
                <h1 class="text-2xl font-bold">Reset Password</h1>
                <p class="text-muted-foreground text-sm text-balance">
                  Masukkan password baru Anda di bawah ini
                </p>
              </div>

              <Field>
                <FieldLabel for="email">Email</FieldLabel>
                <Input
                  id="email"
                  type="email"
                  v-model="form.email"
                  required
                  readonly
                />
                <small v-if="form.errors.email" class="text-red-500 text-xs">
                  {{ form.errors.email }}
                </small>
              </Field>

              <Field>
                <FieldLabel for="password">Password Baru</FieldLabel>
                <Input
                  id="password"
                  type="password"
                  v-model="form.password"
                  placeholder="Minimal 8 karakter"
                  required
                />
                <small v-if="form.errors.password" class="text-red-500 text-xs">
                  {{ form.errors.password }}
                </small>
              </Field>

              <Field>
                <FieldLabel for="password_confirmation">Konfirmasi Password</FieldLabel>
                <Input
                  id="password_confirmation"
                  type="password"
                  v-model="form.password_confirmation"
                  placeholder="Ulangi password baru"
                  required
                />
              </Field>

              <Field>
                <Button type="submit" :disabled="form.processing" class="w-full">
                  {{ form.processing ? 'Memproses...' : 'Reset Password' }}
                </Button>
              </Field>
            </FieldGroup>
          </form>
        </div>
      </div>
    </div>
    <div class="bg-muted relative hidden lg:block">
      <img
        src="/asset/img-login.jpg"
        alt="Image"
        class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
      >
    </div>
  </div>
</template>
