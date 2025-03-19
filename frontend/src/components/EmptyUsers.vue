<template>
  <div class="flex flex-col items-center justify-center py-16 text-gray-500">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12 mb-4 text-gray-300"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
    <p class="text-lg font-medium">{{ message }}</p>

    <p class="mt-3">
    <button class="px-6 py-2 rounded-md text-white font-medium transition-colors  bg-blue-950" @click="handleClick">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white inline"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
       Crear nuevo
    </button>
    </p>
  </div>


</template>

<script setup lang="ts">
const props = defineProps({
  message: {
    type: String,
    default: 'Aún no tienes usuarios creados'
  }
});

const emits = defineEmits<{
(e: 'create-user'): void;
}>();

const handleClick = () => {
  emits('create-user');
}
</script>
