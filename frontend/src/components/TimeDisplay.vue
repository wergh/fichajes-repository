<template>
  <div class="flex items-center text-sm font-medium">
    <span class="text-gray-700">{{ formattedCurrentTime }} /</span>
    <span class="text-gray-500 ml-1">{{ formattedTargetTime }}</span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
  currentTime: {
    type: Number,
    required: true
  },
  targetTime: {
    type: Number,
    default: 8 * 60 * 60 // 8 horas en segundos
  }
});

const formattedCurrentTime = computed(() => {
  const hours = Math.floor(props.currentTime / 3600);
  const minutes = Math.floor((props.currentTime % 3600) / 60);
  const seconds = props.currentTime % 60;

  if (hours > 0) {
    return `${hours}h ${minutes.toString().padStart(2, '0')}min ${seconds.toString().padStart(2, '0')}s`;
  }
  return `${minutes.toString().padStart(2, '0')}min ${seconds.toString().padStart(2, '0')}s`;
});

const formattedTargetTime = computed(() => {
  const hours = Math.floor(props.targetTime / 3600);
  const minutes = Math.floor((props.targetTime % 3600) / 60);
  const seconds = props.targetTime % 60;

  return `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
});
</script>
