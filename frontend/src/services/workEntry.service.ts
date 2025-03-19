// workEntry.service.ts
import { apiService } from './api.service';
import { endpoints } from '@/config/api';
import type { CreateWorkEntryForm, UpdateWorkEntryForm } from "@/utils/WorkEntryFormData";

export const workEntryService = {
    async beginWorkEntry(formData: CreateWorkEntryForm) {
        try {
            return await apiService.post(endpoints.workEntries.create, formData);
        } catch (error) {
            throw error;
        }
    },
    async closeWorkEntry(userId: string) {
        try {
            return await apiService.get(endpoints.workEntries.close(userId));
        } catch (error) {
            throw error;
        }
    },

    async deleteWorkEntry(userId: string, workEntryId: string) {
        try {
            return await apiService.delete(endpoints.workEntries.delete(userId, workEntryId));
            } catch (error) {
            throw error;
        }
    },

    async getWorkEntriesByUser(userId: string) {
        try {
            return await apiService.get(endpoints.workEntries.getByUser(userId));
        } catch (error) {
            throw error;
        }
    },

    async getWorkEntryById(userId: string, workEntryId: string) {
        try {
            return await apiService.get(endpoints.workEntries.getById(userId, workEntryId));
        } catch (error) {
            throw error;
        }
    },

    async updateWorkEntry(workEntryId: string, formData: UpdateWorkEntryForm) {
        try {
            return await apiService.patch(endpoints.workEntries.update(workEntryId), formData);
            } catch (error) {
            throw error;
        }
    }

}
