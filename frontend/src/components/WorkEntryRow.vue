<template>
  <template v-if="!editMode">
    <div>{{ entry.formattedDate }}</div>
    <div>{{ entry.formattedStartTime }}</div>
    <div>{{ entry.formattedEndTime }}</div>
    <div class="flex gap-2">
      <button @click="enableEditMode()" class="text-gray-500 hover:text-gray-700 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
      </button>
      <button @click="confirmationDelete(entry)" class="text-gray-500 hover:text-gray-700 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>
  </template>
  <template v-else>
    <div>{{ entry.formattedDate }}</div>
    <div><TimeSelector
        :id="entry.id"
        :name="'startTime'+entry.id"
        v-model="horaEntrada"
    >
    </TimeSelector></div>
    <div><TimeSelector
        :id="entry.id"
        :name="'endTime'+entry.id"
        v-model="horaSalida"
    >
    </TimeSelector></div>
    <div class="flex gap-2">
      <button @click="confirmationUpdate(entry)" class="text-gray-500 hover:text-gray-700 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
      </button>
      <button @click="disableEditMode()" class="text-gray-500 hover:text-gray-700 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>
    </div>
  </template>

</template>
<script setup lang="ts">

import { ref } from 'vue';
import Swal from 'sweetalert2';
import type {WorkEntry} from "@/interfaces/WorkEntry.js";
import TimeSelector from "@/components/common/TimeSelector.vue";
import type {APIResponses} from "@/config/api";
import {workEntryService} from "@/services/workEntry.service";
import {getAtomDates} from "@/utils/time-format";
import {useToast} from 'vue-toastification'
import type {User} from "@/interfaces/User";
import type {AxiosError} from "axios";

const props = defineProps<{
  entry: WorkEntry;
  selectedUser: User;
}>();

const emits = defineEmits<{
  (e: 'reload-user'): void;
  (e: 'delete-entry', entry: WorkEntry): void;
}>()

const toast = useToast();
const horaEntrada:string = ref<string>(props.entry.formattedStartTime)
const horaSalida:string = ref<string>(props.entry.formattedEndTime)
let editMode = ref(false);

const enableEditMode = () => {
  editMode.value = true;
}

const disableEditMode = () => {
  editMode.value = false;
  horaEntrada.value = props.entry.formattedStartTime
  horaSalida.value = props.entry.formattedEndTime
}

const confirmationDelete = (entry: WorkEntry) => {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "No podrás revertir esta acción",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      emits('delete-entry', entry);
    }
  });
}

const confirmationUpdate = (entry: WorkEntry) => {

  entry.formattedStartTime = horaEntrada
  entry.formattedEndTime = horaSalida

  Swal.fire({
    title: '¿Estás seguro?',
    text: "Toda actualización de un Fichaje de entrada queda registrado en el sistema",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, actualizar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      handleEditEntry(entry)
      editMode.value = false;
    }
  });
}

async function handleEditEntry(entry: WorkEntry) {

  const {startDateAtom, endDateAtom} = getAtomDates(entry);

  try{
    const response: APIResponses['workEntries']['update'] = await workEntryService.updateWorkEntry(entry.id, {"userId": props.selectedUser.id, "startDate": startDateAtom, "endDate": endDateAtom})
    if (response) {
      toast.success("Fichaje actualizado correctamente");
      emits('reload-user');
    }
  } catch (error: any) {
    toast.error(error.response.data.message)
    emits('reload-user');
  }


}
</script>
