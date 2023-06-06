<template>
  <div class="min-h-100 min-h-screen">
    <TheHeader v-if="userProfile.isLogin" />
    <main>
      <div class="mx-auto max-w-7xl md:mt-20 sm:px-6 lg:px-8">
        <router-view />
      </div>
    </main>
  </div>

  <div class="response-message-wrapper">
    <transition-group name="response">
      <ResponseMessage v-for="toast in toastList" :key="toast.uniqId" :toastData="toast" :length="toastList.length" />
    </transition-group>
  </div>
</template>

<script setup>
import TheHeader from "@/components/shared/TheHeader.vue"
import ResponseMessage from "@/components/shared/ResponseMessage.vue";
import { toastList } from "@/store/widget/toast.js";
import { userProfile } from '@/store/isLogin.js'
</script>