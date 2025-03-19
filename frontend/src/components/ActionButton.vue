<template>
  <button
      :disabled="loading"
      :class="[
      'px-6 py-2 rounded-md text-white font-medium transition-colors',
      buttonClass
    ]"
      @click="handleClick"
  >
    <span v-if="loading" class="spinner-border mr-2"></span>&nbsp;
    {{ buttonText }}
  </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  action: {
    type: String,
    required: true,
    validator: (value) => ['enter', 'exit'].includes(value)
  },
  loading: Boolean,
});

const emit = defineEmits(['action']);

const buttonClass = computed(() => {
  switch (props.action) {
    case 'enter':
      return 'bg-green-500 hover:bg-green-600';
    case 'exit':
      return 'bg-red-400 hover:bg-red-500';
    default:
      return 'bg-gray-500 hover:bg-gray-600';
  }
});

const buttonText = computed(() => {
  switch (props.action) {
    case 'enter':
      return 'Entrar';
    case 'exit':
      return 'Salir';
    default:
      return 'Acción';
  }
});

const handleClick = () => {
  emit('action', props.action);
};
</script>

<style scoped>
.spinner-border {
  border: 2px solid transparent;
  border-top: 2px solid #ffffff;
  display: inline-block;
  border-radius: 50%;
  width: 16px;
  height: 16px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
