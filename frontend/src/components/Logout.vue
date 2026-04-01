<template>
    <button @click="handleLogout">Logout</button>
</template>

<script setup>
  import api from '@/api';
  import { useRouter } from 'vue-router';

  const router = useRouter();

  async function handleLogout() {
    await api.post('/logout');
    router.push('/login');
  }
</script>