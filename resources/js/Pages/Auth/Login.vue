<script setup>
import { GalleryVerticalEnd } from "lucide-vue-next"
import LoginForm from "@/components/login-02/components/LoginForm.vue"
</script>

<template>
  <div class="grid min-h-svh lg:grid-cols-2">
    <div class="flex flex-col gap-4 p-6 md:p-10">
      <div class="flex justify-center gap-2 md:justify-start">
        <a href="#" class="flex items-center gap-2 font-medium">
          <div class="flex size-6 items-center justify-center rounded-md">
            <img src="/asset/Logo Pals.png" alt="Logo">
          </div>
          E-Pals.
        </a>
      </div>
      <div class="flex flex-1 items-center justify-center">
        <div class="w-full max-w-xs">
          <LoginForm />
        </div>
      </div>
      <div class="text-center text-sm text-gray-500 mt-auto">
        &copy; 2026 Nakhlatul Faradisa
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
