<template>
  <div class="relative">
    <div
        class="flex items-center gap-2 cursor-pointer"
        @click="isOpen = !isOpen"
    >
      <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white">
        {{ userInitials }}
      </div>
      <span class="font-medium">{{ selectedUser?.name || 'Seleccionar usuario' }}</span>
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m6 9 6 6 6-6"/></svg>
    </div>

    <div
        v-if="isOpen"
        class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-10"
    >
      <ul class="py-1">
        <li
            v-for="user in users"
            :key="user.id"
            class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center gap-2"
            @click="selectUser(user)"
        >
          <div class="w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs">
            {{ getInitials(user.name) }}
          </div>
          <span>{{ user.name }}</span>
        </li>
        <li
            class="px-4 py-2 hover:bg-gray-100 cursor-pointer border-t text-indigo-600 font-medium"
            @click="openCreateModal"
        >
          Crear nuevo usuario
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  users: {
    type: Array,
    default: () => []
  },
  selectedUserId: {
    type: [String, Number],
    default: null
  }
});

const emit = defineEmits(['select-user', 'create-user']);

const isOpen = ref(false);

const selectedUser = computed(() => {
  return props.users.find(user => user.id === props.selectedUserId);
});

const userInitials = computed(() => {
  return selectedUser.value ? getInitials(selectedUser.value.name) : '';
});

function getInitials(name) {
  if (!name) return '';
  return name
      .split(' ')
      .map(part => part[0])
      .join('')
      .toUpperCase()
      .substring(0, 2);
}

function selectUser(user) {
  emit('select-user', user);
  isOpen.value = false;
}

const openCreateModal = () => {
  emit('create-user');
  isOpen.value = false;
}
</script>
