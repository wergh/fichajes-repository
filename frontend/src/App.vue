<template>
  <div class="min-h-screen bg-gray-100 p-4">
    <div class="mx-auto bg-white rounded-lg shadow-sm">
      <!-- Header con cronómetro y controles -->
      <div v-if="users.length" class="p-4 flex items-center justify-end">
        <div class="flex items-center gap-2">
          <TimeDisplay
              :currentTime="timerSeconds"
              :targetTime="targetTime"
          />

          <ActionButton
              :action="currentAction"
              :loading="loading"
              @action="handleAction"
          />

          <UserSelector
              :users="users"
              :selectedUserId="selectedUserId"
              @select-user="handleSelectUser"
              @create-user="isCreateModalOpen = true"
          />
        </div>
      </div>

      <!-- Contenido principal -->
      <div class="p-4">

        <div v-if="!users.length && !mainLoading">
          <EmptyUsers
          @create-user="isCreateModalOpen = true"
          />
        </div>
        <div v-else-if="!mainLoading">
          <EmptyState v-if="!workEntries.length" />

          <WorkEntryList
              v-else
              :workEntries="workEntries"
              :selectedUser="selectedUser"
              @reload-user="loadUserData(selectedUser.id)"
              @delete-entry="handleDeleteEntry"
          />
        </div>
        <div v-else>
          <div class="flex flex-col items-center justify-center py-16 text-gray-500">
            <p><span class="spinner-border mr-2"></span>&nbsp;</p>
            <p class="text-lg font-medium">Cargando</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para crear usuario -->
    <CreateUserModal
        :isOpen="isCreateModalOpen"
        @close="isCreateModalOpen = false"
        @create="handleCreateUser"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import TimeDisplay from './components/TimeDisplay.vue';
import ActionButton from './components/ActionButton.vue';
import UserSelector from './components/UserSelector.vue';
import WorkEntryList from './components/WorkEntryList.vue';
import EmptyState from './components/EmptyState.vue';
import EmptyUsers from './components/EmptyUsers.vue';
import CreateUserModal from './components/modals/CreateUserModal.vue';
import { useTimer } from './composables/useTimer';
import type {User, UserList} from "@/interfaces/User.js";
import type {WorkEntry} from "@/interfaces/WorkEntry.js";
import { userService } from '@/services/user.service';
import { workEntryService } from "@/services/workEntry.service";
import type {APIResponses} from '@/config/api';
import {useToast} from 'vue-toastification'



// Estado
const users = ref<UserList[]>([])
const selectedUserId = ref(null);
const selectedUser = ref<User>()
const workEntries = ref<WorkEntry[]>([]);
const isCreateModalOpen = ref(false);
const isWorking = ref(false);
const targetTime = ref(8 * 60 * 60); // 8 horas en segundos
const loading = ref(false);
const mainLoading = ref(true);
const toast = useToast();


// Timer
const { seconds: timerSeconds, start: startTimer, stop: stopTimer } = useTimer();

// Acción actual (entrar/salir/pausa)
const currentAction = computed(() => {
  if (!selectedUserId.value) return 'enter';
  return isWorking.value ? 'exit' : 'enter';
});

// Cargar datos del usuario seleccionado
async function loadUserData(userId: string) {

  mainLoading.value=true
  try {

    const response: APIResponses['users']['getById'] = await userService.getUserById(userId)
    selectedUser.value = response.data;
    workEntries.value = selectedUser.value.workEntries;
    timerSeconds.value = selectedUser.value.totalTime;

    const openEntry = workEntries.value.find(entry => !entry.endDate);
    isWorking.value = !!openEntry;
    if (isWorking.value) {
      startTimer(timerSeconds.value);
    } else {
      stopTimer();
    }
  } catch (error) {
    console.error('Error al cargar datos del usuario:', error);
  }
  mainLoading.value=false
}

function handleSelectUser(user: SelectedUser) {
  selectedUserId.value = user.id;
  loadUserData(user.id);
}

async function handleAction(action) {
  if (!selectedUserId.value) return;

  loading.value = true;
  if (action === 'enter') {

    try {
      const response: APIResponses['workEntries']['create'] = await workEntryService.beginWorkEntry({"userId": selectedUser.value.id})
      if (response) {
        isWorking.value = true;
        startTimer(timerSeconds.value);
        toast.success("Fichaje iniciado correctamente");
      } else {
        console.error('Error: Respuesta vacía o no válida');
      }
    } catch (error) {
      console.error('Error al iniciar el Work Entry:', error);
    }

  } else if (action === 'exit') {

    try {
      const response: APIResponses['workEntries']['close'] = await workEntryService.closeWorkEntry(selectedUser.value.id)
      if (response) {
        isWorking.value = false
        stopTimer()
        workEntries.value.unshift(response.data)
        toast.success("Fichaje finalizado correctamente");
      }
    } catch (error) {
      console.error('Error al detener el Work Entry:', error);
    }

  }
  loading.value = false;
}

async function handleCreateUser(name: string) {

  try {
    const response: APIResponses['users']['create'] = await userService.createUser({"name": name });

    if (response) {
      users.value.push(response.data);
      selectedUserId.value = response.data.id;
      isCreateModalOpen.value = false;
      loadUserData(response.data.id);
      toast.success("Usuario creado correctamente");
    }
  } catch (error) {
    console.error('Error al detener el Work Entry:', error);
    toast.success("Error al crear el usuario");
  }

}

async function handleDeleteEntry(entry: WorkEntry) {
  try {
  const response: APIResponses['workEntries']['delete'] = await workEntryService.deleteWorkEntry(selectedUser.value.id, entry.id)
    if (response) {
      workEntries.value = workEntries.value.filter(e => e.id !== entry.id);
      toast.success("Fichaje eliminado correctamente");
    }
  } catch (error) {
    console.error('Error al borrar el Work Entry:', error);
  }
  workEntries.value = workEntries.value.filter(e => e.id !== entry.id);
}

const getUsers = async () => {
  const response: APIResponses['users']['getAll'] = await userService.getUsers();
  users.value = response.data;
  mainLoading.value=false;
}

onMounted(async () => {
  await getUsers()
})
</script>

<style scoped>
.spinner-border {
  border: 4px solid transparent;
  border-top: 4px solid #000000;
  display: block;
  border-radius: 50%;
  width: 60px;
  height: 60px;
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
