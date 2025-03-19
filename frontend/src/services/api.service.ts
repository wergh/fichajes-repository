// services/api.service.ts
import { apiClient } from '@/config/api';

export const apiService = {
    async get<T>(url: string, params?: object): Promise<T> {
        const response = await apiClient.get<T>(url, { params });
        return response.data;
    },

    async post<T>(url: string, data?: any): Promise<T> {
        const response = await apiClient.post<T>(url, data);
        return response.data;
    },

    async patch<T>(url: string, data?: any): Promise<T> {
        const response = await apiClient.patch<T>(url, data);
        return response.data;
    },

    async delete<T>(url: string): Promise<T> {
        const response = await apiClient.delete<T>(url);
        return response.data;
    }
};
