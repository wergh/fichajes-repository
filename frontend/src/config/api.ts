// config/api.ts
import axios from 'axios';
import type { User,UserList } from "@/interfaces/User";
import type { WorkEntry } from "@/interfaces/WorkEntry";


const API_URL = import.meta.env.VITE_BACKEND_URL || 'http://localhost:3000/api';


const apiClient = axios.create({
    baseURL: API_URL,
    timeout: 30000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

apiClient.interceptors.request.use(
    (config) => {
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response) {
            const status = error.response.status;
            const errorData = error.response.data;
            switch (status) {
                case 400: // HTTP_BAD_REQUEST
                    // - ValidationFailedException
                    // - WorkEntryIsAlreadyOpenException
                    // - NotOverlapException
                    // - EndDateInTheFutureNotAllowed
                    console.error('Request erronea:', errorData.message);
                    break;

                case 403: // HTTP_FORBIDDEN
                    // UnauthorizedAccessToWorkEntry
                    console.error('Acceso no autorizado:', errorData.message);
                    break;

                case 404: // HTTP_NOT_FOUND
                    // EntityNotFoundException
                    console.error('Entidad no encontrada:', errorData.message);
                    break;

                default:
                    // Otros errores
                    console.error(`Error ${status}:`, errorData);
            }
        } else if (error.request) {
            console.error('API No Response:', error.request);
        } else {
            console.error('API Request Error:', error.message);
        }
        return Promise.reject(error);
    }
);

const endpoints = {
    users: {
        getAll: '/users',
        getById: (userId: string) => `/user/${userId}`,
        create: '/users',
    },
    workEntries: {
        create: '/work-entry',
        close: (userId: string) => `/work-entry/close/${userId}`,
        delete: (userId: string, workEntryId: string) => `/work-entry/delete/${userId}/${workEntryId}`,
        getByUser: (userId: string) => `/work-entries/${userId}`,
        getById: (userId: string, workEntryId: string) => `/work-entry/${userId}/${workEntryId}`,
        update: (workEntryId: string) => `/work-entry/${workEntryId}`,
    }
};

export type APIResponses = {
    users: {
        getAll: {
            message: string
            data: UserList[]
        };
        getById: {
            message: string
            data: User | null
        };
        create: {
            message: string
            data: User | null
        };
    };
    workEntries: {
        create: {
            message: string
            data: WorkEntry | null
        };
        close: {
            message: string
            data: WorkEntry | null
        };
        delete: {
            message: string
        };
        getByUser: {
            message: string
            data: WorkEntry[]
        };
        getById: {
            message: string
            data: WorkEntry | null
        };
        update: {
            message: string
            data: WorkEntry | null
        };
    };
};

export { apiClient, endpoints };
