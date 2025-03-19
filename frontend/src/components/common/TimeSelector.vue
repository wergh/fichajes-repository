<template>
  <div class="flex flex-col">
    <label v-if="label" :for="id" class="text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    <div class="flex items-center rounded-md overflow-hidden">
      <!-- Selector de horas -->
      <select
          :id="`${id}-hour`"
          :name="`${name}-hour`"
          v-model="selectedHour"
          class="py-2 pl-3 pr-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
          @change="emitChange"
      >
        <option v-for="hour in hours" :key="`hour-${hour}`" :value="hour">
          {{ hour.toString().padStart(2, '0') }}
        </option>
      </select>

      <span class="px-1 text-gray-500">:</span>

      <!-- Selector de minutos -->
      <select
          :id="`${id}-minute`"
          :name="`${name}-minute`"
          v-model="selectedMinute"
          class="py-2 pl-2 pr-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
          @change="emitChange"
      >
        <option v-for="minute in minutes" :key="`minute-${minute}`" :value="minute">
          {{ minute.toString().padStart(2, '0') }}
        </option>
      </select>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';

interface Props {
  id?: string;
  name?: string;
  label?: string;
  modelValue?: string;
  disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  id: 'time-selector',
  name: 'time',
  label: '',
  modelValue: '',
  disabled: false
});

const emit = defineEmits(['update:modelValue', 'change']);

const hours = Array.from({ length: 24 }, (_, i) => i);
const minutes = Array.from({ length: 60 }, (_, i) => i);

const selectedHour = ref(0);
const selectedMinute = ref(0);

const formattedTime = computed(() => {
  return `${selectedHour.value.toString().padStart(2, '0')}:${selectedMinute.value.toString().padStart(2, '0')}`;
});

const emitChange = () => {
  emit('update:modelValue', formattedTime.value);
  emit('change', formattedTime.value);
};

onMounted(() => {
  if (props.modelValue) {
    const [hour, minute] = props.modelValue.split(':').map(Number);
    selectedHour.value = hour || 0;
    selectedMinute.value = minute || 0;
  }
});

watch(() => props.modelValue, (newValue) => {
  if (newValue) {
    const [hour, minute] = newValue.split(':').map(Number);
    selectedHour.value = hour || 0;
    selectedMinute.value = minute || 0;
  }
});
</script>
