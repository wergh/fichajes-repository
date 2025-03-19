<template>
  <div class="bg-gray-50 rounded-md overflow-hidden">
    <!-- Encabezados de la tabla -->
    <div class="grid grid-cols-4 gap-4 p-4 text-sm font-medium text-gray-500">
      <div class="flex items-center gap-1">
        Día
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m6 9 6 6 6-6"/></svg>
      </div>
      <div>Hora entrada</div>
      <div>Hora salida</div>
      <div></div>
    </div>

    <!-- Filas de datos -->
    <div v-if="workEntries.length > 0">
      <div
          v-for="(entry) in formattedWorkEntries"
          :key="entry.id"
      >
        <div v-if="entry.formattedEndTime != null" class="grid grid-cols-4 gap-4 p-4 text-sm border-t border-gray-200" >
          <work-entry-row
              :entry="entry"
              :selectedUser="selectedUser"
              @reload-user="$emit('reload-user')"
              @delete-entry="$emit('delete-entry', entry)"
            ></work-entry-row>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { WorkEntry } from '@/interfaces/WorkEntry';
import WorkEntryRow from "@/components/WorkEntryRow.vue";
import {formatDateForHumans, formatHourForHumans} from "@/utils/time-format";
import type {User} from "@/interfaces/User";

const props = defineProps<{
  workEntries: WorkEntry[];
  selectedUser: User;
}>();

defineEmits(['reload-user', 'delete-entry']);

const formattedWorkEntries = computed(() => {
  return props.workEntries.map(entry => {
    const startDate = new Date(entry.startDate);
    const endDate = new Date(entry.endDate);


    const formattedDate = formatDateForHumans(startDate);

    const formattedStartTime = formatHourForHumans(startDate)

    const formattedEndTime = formatHourForHumans(endDate)

    return {
      ...entry,
      formattedDate,
      formattedStartTime,
      formattedEndTime
    };
  });
});



</script>
