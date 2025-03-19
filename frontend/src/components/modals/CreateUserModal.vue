<template>
  <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/30" @click="close"></div>

    <!-- Modal -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4 z-10">
      <div class="p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-lg font-medium">Crear usuario</h2>
          <button @click="close" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          </button>
        </div>

        <div class="mb-4">
          <label class="block text-sm mb-1">Nombre usuario</label>
          <input
              v-model="userName"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
              @keyup.enter="createUser"
          />
        </div>

        <div class="flex justify-end gap-2">
          <button
              @click="close"
              class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md"
          >
            Cancelar
          </button>
          <button
              @click="createUser"
              class="px-4 py-2 text-sm font-medium text-white bg-indigo-900 hover:bg-indigo-800 rounded-md"
              :disabled="!userName.trim()"
          >
            Crear
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'create']);

const userName = ref('');

function close() {
  emit('close');
  userName.value = '';
}

function createUser() {
  if (userName.value.trim()) {
    emit('create', userName.value);
    userName.value = '';
  }
}
</script>
